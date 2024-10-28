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
 * Local plugin "staticpage" - Settings: List of static pages
 *
 * @package    local_staticpage
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\output\html_writer;

// Include config.php.
require(__DIR__ . '/../../config.php');

// Globals.
global $CFG, $PAGE, $OUTPUT;

// Include adminlib.php.
require_once($CFG->libdir.'/adminlib.php');

// Include plugin libraries.
require_once($CFG->dirroot.'/local/staticpage/lib.php');
require_once($CFG->dirroot.'/local/staticpage/locallib.php');

// Set up external admin page.
admin_externalpage_setup('local_staticpage_pagelist');


// Prepare page.
$title = get_string('settingspagelist', 'local_staticpage');
$PAGE->set_title($title);
$PAGE->set_heading($title);

echo $OUTPUT->header();
echo $OUTPUT->heading($title);


// Initialize HTML output.
$html = '';

// Fetch context.
$context = \context_system::instance();

// Get filearea.
$fs = get_file_storage();

// If no file is found, quit with notification.
if ($fs->is_area_empty($context->id, 'local_staticpage', 'documents')) {
    // Output notification.
    $html .= $OUTPUT->box(
            get_string('settingspagelistnofiles', 'local_staticpage',
                    rtrim($CFG->wwwroot, '/').'/admin/settings.php?section=local_staticpage_documents'),
            'alert alert-warning');
    // Otherwise start page list.
} else {
    // Output instruction.
    $html .= $OUTPUT->box(
            get_string('settingspagelistinstruction', 'local_staticpage',
                    rtrim($CFG->wwwroot, '/').'/admin/settings.php?section=local_staticpage_documents'),
            'alert alert-info');

    // Start page list.
    $html .= html_writer::start_tag('ul');

    // Fetch all files.
    $pages = $fs->get_area_files($context->id, 'local_staticpage', 'documents', false, 'filename', false);

    // Get plugin config.
    $localstaticpageconfig = get_config('local_staticpage');

    // Output each page as a page list entry.
    foreach ($pages as $page) {

        // Collect information about the page.
        $pagefilename = $page->get_filename();
        $pageextension = pathinfo($pagefilename, PATHINFO_EXTENSION);
        $pagepagename = pathinfo($pagefilename, PATHINFO_FILENAME);

        // If this is not .html file, skip it.
        if ($pageextension != 'html') {
            continue;
        }

        // Start page list entry.
        $html .= html_writer::start_tag('li');

        // Print basic information about the page.
        $html .= html_writer::tag('p', get_string('settingspagelistentryfilename', 'local_staticpage', $pagefilename));
        $html .= html_writer::tag('p', get_string('settingspagelistentrypagename', 'local_staticpage', $pagepagename));

        // Print normal static page URL - Do only if apache rewrite isn't forced.
        if (!$localstaticpageconfig->apacherewrite) {
            // Check availability.
            $pageurlstandard = rtrim($CFG->wwwroot, '/').'/local/staticpage/view.php?page='.$pagepagename;
            $pageurlstandardavailable = local_staticpage_check_availability($pageurlstandard);

            // Show if document is available.
            $statusbadgevariant = strtr(
                $pageurlstandardavailable,
                [
                    STATICPAGE_CHECKAVAILABILITY_RESPONSE_SUCCESS => 'bg-success text-light',
                    STATICPAGE_CHECKAVAILABILITY_RESPONSE_FAIL => 'bg-danger text-light',
                    STATICPAGE_CHECKAVAILABILITY_RESPONSE_ERROR => 'bg-danger text-light',
                    STATICPAGE_CHECKAVAILABILITY_RESPONSE_DISABLED => 'bg-info text-dark',
                ]
            );
            $html .= html_writer::tag('p',
                get_string('settingspagelistentrystandard'.$pageurlstandardavailable, 'local_staticpage',
                    html_writer::tag('span',
                        get_string('checkavailabilityresponse'.$pageurlstandardavailable, 'local_staticpage'),
                            ['class' => 'badge me-2 '.$statusbadgevariant]).
                            html_writer::link($pageurlstandard, $pageurlstandard)
                )
            );
        }

        // Print rewritten static page URL.
        // Check availability.
        $pageurlrewrite = rtrim($CFG->wwwroot, '/').'/static/'.$pagepagename.'.html';
        $pageurlrewriteavailable = local_staticpage_check_availability($pageurlrewrite);

        // Show if document is available.
        $statusbadgevariant = strtr(
            $pageurlrewriteavailable,
            [
                STATICPAGE_CHECKAVAILABILITY_RESPONSE_SUCCESS => 'bg-success text-light',
                STATICPAGE_CHECKAVAILABILITY_RESPONSE_FAIL => 'bg-warning text-dark',
                STATICPAGE_CHECKAVAILABILITY_RESPONSE_ERROR => 'bg-warning text-dark',
                STATICPAGE_CHECKAVAILABILITY_RESPONSE_DISABLED => 'bg-info text-dark',
            ]
        );
        $html .= html_writer::tag('p',
            get_string('settingspagelistentryrewrite'.$pageurlrewriteavailable, 'local_staticpage',
                html_writer::tag('span',
                    get_string('checkavailabilityresponse'.$pageurlrewriteavailable, 'local_staticpage'),
                        ['class' => 'badge me-2 '.$statusbadgevariant]).
                        html_writer::link($pageurlrewrite, $pageurlrewrite)
            )
        );

        // Finish page list entry.
        $html .= html_writer::end_tag('li');

    }

    // Finish page list.
    $html .= html_writer::end_tag('ul');
}

// Output HTML.
echo $html;

// Finish page.
echo $OUTPUT->footer();
