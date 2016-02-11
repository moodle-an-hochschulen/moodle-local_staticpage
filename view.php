<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local plugin "staticpage" - View page
 *
 * @package    local_staticpage
 * @copyright  2013 Alexander Bias, University of Ulm <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// defined('MOODLE_INTERNAL') || die(); - Must not be called because this script is called from outside moodle

// Include lib.php
require_once(dirname(__FILE__) . '/lib.php');

// Include config.php
require_once('../../config.php');

// Globals
global $PAGE;

// Require login if Moodle is configured to force login
if ($CFG->forcelogin) {
    require_login();
}

// Get plugin config
$local_staticpage_config = get_config('local_staticpage');


// View only with /static/ URL
if ($local_staticpage_config->apacherewrite == true) {
    if (strpos($_SERVER['REQUEST_URI'], '/static/') > 0 || strpos($_SERVER['REQUEST_URI'], '/static/') === false) {
        die;
    }
}


// Get requested page's name
$page = required_param('page', PARAM_ALPHANUMEXT);

// Put together document file names
$filename = "$page.html";

// Fetch context
$context = \context_system::instance();

// Get filearea
$fs = get_file_storage();

// Get language based document
$file = $fs->get_file($context->id, 'local_staticpage', 'documents', 0, '/', $filename);

// If no file is found, quit with error message
if (!$file) {
    print_error('pagenotfound', 'local_staticpage');
}

// Get file content
$filecontents = $file->get_content();

// Import the document, load DOM
$staticdoc = new DOMDocument();
$staticdoc->loadHTML($filecontents);

// Extract page's first h1 (if present)
if (!empty($staticdoc->getElementsByTagName('h1')->item(0)->nodeValue)) {
    $firsth1 = $staticdoc->getElementsByTagName('h1')->item(0)->nodeValue;
}
else {
    $firsth1 = $page;
}

// Extract page title (if present)
if (!empty($staticdoc->getElementsByTagName('title')->item(0)->nodeValue)) {
    $title = $staticdoc->getElementsByTagName('title')->item(0)->nodeValue;
}
else {
    $title = $page;
}

// Extract style tag in head (if present) and insert into HTML head
if (!empty($staticdoc->getElementsByTagName('style')->item(0)->nodeValue)) {
    $style = $staticdoc->getElementsByTagName('style')->item(0)->nodeValue;
    $CFG->additionalhtmlhead = $CFG->additionalhtmlhead.'<style>'.$style.'</style>';
}

// Set page url
if ($local_staticpage_config->apacherewrite == true) {
    $PAGE->set_url('/static/'.$page.'.html');
}
else {
    $PAGE->set_url('/local/staticpage/view.php?page='.$page);
}

// Set page context
$PAGE->set_context(context_system::instance());


// Set page layout
$PAGE->set_pagelayout('standard');


// Set page title
if ($local_staticpage_config->documenttitlesource == STATICPAGE_TITLE_H1) {
    $PAGE->set_title($firsth1);
}
else if ($local_staticpage_config->documenttitlesource == STATICPAGE_TITLE_HEAD) {
    $PAGE->set_title($title);
}
else {
    $PAGE->set_title($title);
}

// Set page heading
if ($local_staticpage_config->documentheadingsource == STATICPAGE_TITLE_H1) {
    $PAGE->set_heading($firsth1);
}
else if ($local_staticpage_config->documentheadingsource == STATICPAGE_TITLE_H1) {
    $PAGE->set_heading($title);
}
else {
    $PAGE->set_heading($title);
}

// Set page navbar
if ($local_staticpage_config->documentnavbarsource == STATICPAGE_TITLE_H1) {
    $PAGE->navbar->add($firsth1);
}
else if ($local_staticpage_config->documentnavbarsource == STATICPAGE_TITLE_HEAD) {
    $PAGE->navbar->add($title);
}
else {
    $PAGE->navbar->add($title);
}

echo $OUTPUT->header();

// Get html code
$html = $staticdoc->saveHTML();

// Remove everything except body tag content from html
$startcut = strpos($html, '<body>') + 6;
$stopcut = strpos($html, '</body>') - $startcut;
$html = substr($html, $startcut, $stopcut);

// Print html code
echo format_text($html);

echo $OUTPUT->footer();
