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
 * @package     local
 * @subpackage  local_staticpage
 * @copyright   2013 Alexander Bias, University of Ulm <alexander.bias@uni-ulm.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// defined('MOODLE_INTERNAL') || die(); - Must not be called because this script is called from outside moodle

// Include config.php
require_once('../../config.php');

// Globals
global $PAGE;

// Get plugin config
$config = get_config('local_staticpage');


// View only with /static/ URL
if ($config->apacherewrite == true) {
    if (strpos($_SERVER['REQUEST_URI'], '/static/') > 0 || strpos($_SERVER['REQUEST_URI'], '/static/') === false) {
        die;
    }
}


// Get requested page's name
$page = required_param('page', PARAM_ALPHA);

// Put together absolute document paths based on requested page and current language
$lang = current_language();
$path_language = rtrim($config->documentdirectory, '/').'/'.$page.'.'.$lang.'.html';
$path_international = rtrim($config->documentdirectory, '/').'/'.$page.'.html';


// Does language based document file exist?
if (file_exists($path_language) == true) {
    // Remember document path
    $path = $path_language;
}
// Otherwise, does international document file exist?
else if (file_exists($path_international) == true) {
    // Remember document path
    $path = $path_international;
}
// If not, quit with error message
else {
    print_error('pagenotfound', 'local_staticpage');
}


// Import the document, load DOM
$staticdoc = new DOMDocument();
$staticdoc->loadHTMLFile($path);

// Extract page title (if present)
if (!empty($staticdoc->getElementsByTagName('h1')->item(0)->nodeValue)) {
    $title = $staticdoc->getElementsByTagName('h1')->item(0)->nodeValue;
}
else {
    $title = $page;
}

// Set page url
if ($config->apacherewrite == true) {
    $PAGE->set_url('/static/'.$page);
}
else {
    $PAGE->set_url('/local/staticpage/view.php?'.$page);
}

// Prepare moodle page
$PAGE->set_context(context_system::instance());
if (array_key_exists('staticpage', $PAGE->theme->layouts)) {
    $PAGE->set_pagelayout('staticpage');
}
else {
    $PAGE->set_pagelayout('standard');
}
$PAGE->navbar->add($title);
$PAGE->set_heading($title);
$PAGE->set_title($title);
echo $OUTPUT->header();

// Get html code
$html = $staticdoc->saveHTML();

// Remove everything except body tag content from html
$startcut = strpos($html, '<body>') + 6;
$stopcut = strpos($html, '</body>') - $startcut;
$html = substr($html, $startcut, $stopcut);

// Print html code
echo $html;

echo $OUTPUT->footer();
