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
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Include config.php.
// phpcs:disable moodle.Files.RequireLogin.Missing
// Let codechecker ignore the next line because otherwise it would complain about a missing login check
// after requiring config.php which is really not needed.
require(__DIR__ . '/../../config.php');

// Globals.
global $CFG, $PAGE, $USER;

// Include lib.php.
require_once($CFG->dirroot.'/local/staticpage/lib.php');

// Get plugin config.
$localstaticpageconfig = get_config('local_staticpage');

// Require login if the plugin or Moodle is configured to force login.
if ($localstaticpageconfig->forcelogin == STATICPAGE_FORCELOGIN_YES ||
        ($localstaticpageconfig->forcelogin == STATICPAGE_FORCELOGIN_GLOBAL && $CFG->forcelogin)) {
    require_login();
}

// View only with /static/ URL.
if ($localstaticpageconfig->apacherewrite == true) {
    if (strpos($_SERVER['REQUEST_URI'], '/static/') === false) { /* We used to check if /static/ is at the beginning of the
                                                                    REQUEST_URI, but this would break on Moodle subdirectory
                                                                    installations. And there is no better check available in
                                                                    core, so we just accept this rather loose check. */
        die;
    }
}


// Get requested page's name.
$page = required_param('page', PARAM_ALPHANUMEXT);

// Put together document file names.
$filename = "$page.html";

// Fetch context.
$context = \context_system::instance();

// Get filearea.
$fs = get_file_storage();

// Get document from filearea.
$file = $fs->get_file($context->id, 'local_staticpage', 'documents', 0, '/', $filename);

// If no file is found, quit with error message.
if (!$file) {
    throw new \moodle_exception('pagenotfound', 'local_staticpage');
}

// Get file content.
$filecontents = $file->get_content();

// Import the document, load DOM.
$staticdoc = new DOMDocument();
$staticdoc->loadHTML($filecontents);


// Set page url.
if ($localstaticpageconfig->apacherewrite == true) {
    $PAGE->set_url('/static/'.$page.'.html');
} else {
    $PAGE->set_url('/local/staticpage/view.php?page='.$page);
}

// Set page context.
$PAGE->set_context(context_system::instance());

// Set page layout.
$PAGE->set_pagelayout('standard');

// Add page name as body class.
$PAGE->add_body_class('local-staticpage-'.$page);

// Extract page's first h1 (if present).
if (!empty($staticdoc->getElementsByTagName('h1')->item(0)->nodeValue)) {
    $firsth1 = format_string($staticdoc->getElementsByTagName('h1')->item(0)->nodeValue);
} else {
    $firsth1 = $page;
}

// Extract page title (if present).
if (!empty($staticdoc->getElementsByTagName('title')->item(0)->nodeValue)) {
    $title = format_string($staticdoc->getElementsByTagName('title')->item(0)->nodeValue);
} else {
    $title = $page;
}

// Extract style tag in head (if present) and insert into HTML head.
if (!empty($staticdoc->getElementsByTagName('style')->item(0)->nodeValue)) {
    $style = $staticdoc->getElementsByTagName('style')->item(0)->nodeValue;
    $CFG->additionalhtmlhead = $CFG->additionalhtmlhead.'<style>'.$style.'</style>';
}

// Extract link tags in head (if present) and insert into HTML head.
if (!empty($staticdoc->getElementsByTagName('link'))) {
    $linknodes = $staticdoc->getElementsByTagName('link');
    foreach ($linknodes as $linknode) {
        $CFG->additionalhtmlhead .= $staticdoc->saveHTML($linknode);
    }
}

// Set page title.
if ($localstaticpageconfig->documenttitlesource == STATICPAGE_TITLE_H1) {
    $PAGE->set_title($firsth1);
} else if ($localstaticpageconfig->documenttitlesource == STATICPAGE_TITLE_HEAD) {
    $PAGE->set_title($title);
} else {
    $PAGE->set_title($title);
}

// Set page heading.
if ($localstaticpageconfig->documentheadingsource == STATICPAGE_TITLE_H1) {
    $PAGE->set_heading($firsth1);
} else if ($localstaticpageconfig->documentheadingsource == STATICPAGE_TITLE_H1) {
    $PAGE->set_heading($title);
} else {
    $PAGE->set_heading($title);
}

echo $OUTPUT->header();

// Get body tag.
$body = $staticdoc->getElementsByTagName('body')->item(0);

// Get page content from body tag.
$pagecontent = $staticdoc->saveHTML($body);

// Print page content.
if ($localstaticpageconfig->processfilters == STATICPAGE_PROCESSFILTERS_YES &&
        $localstaticpageconfig->cleanhtml == STATICPAGE_CLEANHTML_YES) {
    echo format_text($pagecontent, FORMAT_HTML, ['trusted' => false, 'noclean' => false, 'filter' => true]);
} else if ($localstaticpageconfig->processfilters == STATICPAGE_PROCESSFILTERS_YES &&
        $localstaticpageconfig->cleanhtml == STATICPAGE_CLEANHTML_NO) {
    echo format_text($pagecontent, FORMAT_HTML, ['trusted' => true, 'noclean' => true, 'filter' => true]);
} else if ($localstaticpageconfig->processfilters == STATICPAGE_PROCESSFILTERS_NO &&
        $localstaticpageconfig->cleanhtml == STATICPAGE_CLEANHTML_YES) {
    echo format_text($pagecontent, FORMAT_HTML, ['trusted' => false, 'noclean' => false, 'filter' => false]);
} else if ($localstaticpageconfig->processfilters == STATICPAGE_PROCESSFILTERS_NO &&
        $localstaticpageconfig->cleanhtml == STATICPAGE_CLEANHTML_NO) {
    echo format_text($pagecontent, FORMAT_HTML, ['trusted' => true, 'noclean' => true, 'filter' => false]);
} else { // This should not happen.
    echo $pagecontent;
}

// Log this view.
$logevent = \local_staticpage\event\staticpage_viewed::create([
    'userid' => $USER->id,
    'context' => $context,
    'other' => [
        'title' => $title,
        'page' => $page,
    ],
]);
$logevent->trigger();

echo $OUTPUT->footer();
