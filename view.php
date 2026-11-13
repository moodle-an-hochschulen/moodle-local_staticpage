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
global $CFG, $PAGE, $USER, $FULLME;

// Include lib.php.
require_once($CFG->dirroot . '/local/staticpage/lib.php');

// Include locallib.php.
require_once($CFG->dirroot . '/local/staticpage/locallib.php');

// Get plugin config.
$localstaticpageconfig = get_config('local_staticpage');

// Require login if the plugin or Moodle is configured to force login.
if (
    $localstaticpageconfig->forcelogin == STATICPAGE_FORCELOGIN_YES ||
        ($localstaticpageconfig->forcelogin == STATICPAGE_FORCELOGIN_GLOBAL && $CFG->forcelogin)
) {
    require_login();
}

// Get requested page's name.
$page = required_param('page', PARAM_ALPHANUMEXT);

// View only with the canonical /static/<page>.html URL.
if ($localstaticpageconfig->apacherewrite == true) {
    // Compare only the path component of the requested URL with the expected path, respecting Moodle subdirectory
    // installations. This way, the check is neither fooled by query parameters nor by a mismatching page parameter.
    $requestpath = parse_url($FULLME, PHP_URL_PATH) ?? '';
    $wwwrootpath = rtrim(parse_url($CFG->wwwroot, PHP_URL_PATH) ?? '', '/');
    $canonicalpath = '/static/' . $page . '.html';
    if ($requestpath !== $wwwrootpath . $canonicalpath) {
        redirect(new \moodle_url($canonicalpath));
    }
}

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

// Remove the UTF-8 byte order mark (if present).
// This is an invisible marker (the bytes EF BB BF) which some editors put at the beginning of UTF-8 files.
// Otherwise, it would end up behind the charset meta tag which is prepended below.
// There, libxml would treat it as text content and would move all following head elements into the body.
$filecontents = preg_replace('/^\xEF\xBB\xBF/', '', $filecontents);

// If the document is UTF-8 encoded, prepend a charset meta tag.
// Without this, libxml would parse the document as ISO-8859-1 if the document does not declare its charset,
// declares it too late or declares an unsupported charset, and would garble all non-ASCII characters.
// Documents which are not UTF-8 encoded are left untouched to let libxml detect their encoding itself.
if (mb_check_encoding($filecontents, 'UTF-8')) {
    $filecontents = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $filecontents;
}

// Import the document, load DOM.
// The libxml warnings are suppressed as libxml's HTML4 parser complains about HTML5 tags and minor markup issues
// and these warnings would otherwise be printed before the page output.
$staticdoc = new DOMDocument();
$libxmlinternalerrors = libxml_use_internal_errors(true);
$staticdoc->loadHTML($filecontents);
libxml_clear_errors();
libxml_use_internal_errors($libxmlinternalerrors);

// Set page url.
if ($localstaticpageconfig->apacherewrite == true) {
    $PAGE->set_url('/static/' . $page . '.html');
} else {
    $PAGE->set_url('/local/staticpage/view.php?page=' . $page);
}

// Set page context.
$PAGE->set_context(context_system::instance());

// Set page layout.
$PAGE->set_pagelayout('standard');

// Add page name as body class.
$PAGE->add_body_class('local-staticpage-' . $page);

// Extract page's first h1 (if present).
if (!empty($staticdoc->getElementsByTagName('h1')->item(0)->nodeValue)) {
    $firsth1 = format_string($staticdoc->getElementsByTagName('h1')->item(0)->nodeValue);
} else {
    $firsth1 = $page;
}

// Get head tag.
$head = $staticdoc->getElementsByTagName('head')->item(0);

// Extract page title in head (if present).
if ($head !== null && !empty($head->getElementsByTagName('title')->item(0)->nodeValue)) {
    $title = format_string($head->getElementsByTagName('title')->item(0)->nodeValue);
} else {
    $title = $page;
}

// Extract style tag in head (if present) and insert into HTML head if this is enabled.
// Any unexpected setting value falls back to not using the style tag.
if (
    $head !== null && $localstaticpageconfig->styletag == STATICPAGE_STYLETAG_YES &&
    !empty($head->getElementsByTagName('style')->item(0)->nodeValue)
) {
    $style = $head->getElementsByTagName('style')->item(0)->nodeValue;
    $CFG->additionalhtmlhead .= '<style>' . $style . '</style>';
}

// Extract link tags in head (if present) and insert into HTML head if this is enabled.
if ($head !== null && $localstaticpageconfig->linktags != STATICPAGE_LINKTAGS_NO) {
    foreach ($head->getElementsByTagName('link') as $linknode) {
        if ($localstaticpageconfig->linktags == STATICPAGE_LINKTAGS_ASIS) {
            // The admin trusts the link tags in the static page documents.
            // Thus, the link tag is passed on as it is.
            $CFG->additionalhtmlhead .= $staticdoc->saveHTML($linknode);
        } else {
            // The link tags should be cleaned.
            // Any unexpected setting value falls back to this mode as well.
            // Thus, the link tag is rebuilt with a limited set of safe attributes.
            $CFG->additionalhtmlhead .= local_staticpage_clean_link_tag($linknode);
        }
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
} else if ($localstaticpageconfig->documentheadingsource == STATICPAGE_TITLE_HEAD) {
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
// Any unexpected setting value falls back to the safe default (clean HTML code and process filters).
$cleanhtml = ($localstaticpageconfig->cleanhtml != STATICPAGE_CLEANHTML_NO);
$processfilters = ($localstaticpageconfig->processfilters != STATICPAGE_PROCESSFILTERS_NO);
echo format_text($pagecontent, FORMAT_HTML, [
    'trusted' => !$cleanhtml,
    'noclean' => !$cleanhtml,
    'filter' => $processfilters,
]);

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
