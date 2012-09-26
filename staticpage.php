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


// =========================================================================
// View only with /static/ URL
// =========================================================================

if (strpos($_SERVER['REQUEST_URI'], '/static/') > 0 || strpos($_SERVER['REQUEST_URI'], '/static/') === false)
	die;


// =========================================================================
// Config and globals
// =========================================================================

require_once('../../config.php');
global $CFG, $PAGE;



// =========================================================================
// Preprocessing
// =========================================================================

// Check if any staticpages are set in config.php
if (empty($CFG->staticvaliddocuments))
	print_error('pagenotfound', 'local_staticpage');

// Get page name
$page = required_param('page', PARAM_SAFEPATH);

// Throw error if page name is not allowed
if (!array_key_exists($page, $CFG->staticvaliddocuments))
	print_error('pagenotfound', 'local_staticpage');

// Get documents path
if (!empty($CFG->staticdocumentspath))
	$path = $CFG->staticdocumentspath;
else
	$path = $CFG->dataroot.'/staticpages/';

// Put together complete document path based on language
$lang = current_language();
$path .= $CFG->staticvaliddocuments[$page].'.'.$lang.'.html';


// =========================================================================
// Processing
// =========================================================================

// Does document file exist?
if (file_exists($path) == false) {
	// If not, quit with error message
	print_error('pagenotfound', 'local_staticpage');
}
// Import the document
else {
	// Load dom
	$staticdoc = new DOMDocument(); 
	$staticdoc->loadHTMLFile($path); 

	// Extract page title (if present)
	if (!empty($staticdoc->getElementsByTagName('h1')->item(0)->nodeValue))
		$title = $staticdoc->getElementsByTagName('h1')->item(0)->nodeValue;
	else
		$title = $page;

	// Prepare moodle page
	$PAGE->set_context(context_system::instance());
	if (array_key_exists('staticpage', $PAGE->theme->layouts))
		$PAGE->set_pagelayout('staticpage');
	else
		$PAGE->set_pagelayout('standard');
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
}
