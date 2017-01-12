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

// Include config.php
require_once(dirname(__FILE__).'/../../config.php');

// Include adminlib.php
require_once($CFG->libdir.'/adminlib.php');

// Include lib.php
require_once(dirname(__FILE__) . '/lib.php');

global $CFG, $PAGE, $OUTPUT;

// Set up external admin page
admin_externalpage_setup('local_staticpage_pagelist');


// Prepare page
$title = get_string('settingspagelist', 'local_staticpage');
$PAGE->set_title($title);
$PAGE->set_heading($title);

echo $OUTPUT->header();
echo $OUTPUT->heading($title);


// Initialize HTML output
$html = '';

// Fetch context
$context = \context_system::instance();

// Get filearea
$fs = get_file_storage();

// If no file is found, quit with notification
if ($fs->is_area_empty($context->id, 'local_staticpage', 'documents')) {
    // Output notification
    $html .= $OUTPUT->box(get_string('settingspagelistnofiles', 'local_staticpage', '/admin/settings.php?section=local_staticpage_documents'), 'alert alert-warning');
}

// Otherwise start page list
else {
    // Output instruction
    $html .= $OUTPUT->box(get_string('settingspagelistinstruction', 'local_staticpage', '/admin/settings.php?section=local_staticpage_documents'), 'alert alert-info');

    // Start page list
    $html .= html_writer::start_tag('ul');

    // Fetch all files
    $pages = $fs->get_area_files($context->id, 'local_staticpage', 'documents', false, 'filename', false);

    // Get plugin config
    $local_staticpage_config = get_config('local_staticpage');

    // Output each page as a page list entry
    foreach ($pages as $page) {

        // Collect information about the page
        $page_filename = $page->get_filename();
        $page_extension = pathinfo($page_filename, PATHINFO_EXTENSION);
        $page_pagename = pathinfo($page_filename, PATHINFO_FILENAME);

        // If this is not .html file, skip it
        if ($page_extension != 'html') {
            continue;
        }

        // Start page list entry
        $html .= html_writer::start_tag('li');

            // Print basic information about the page
            $html .= html_writer::tag('p', get_string('settingspagelistentryfilename', 'local_staticpage', $page_filename));
            $html .= html_writer::tag('p', get_string('settingspagelistentrypagename', 'local_staticpage', $page_pagename));

            // Print normal static page URL - Do only if apache rewrite isn't forced
            if (!$local_staticpage_config->apacherewrite) {
                // Check availability
                $page_url_standard = rtrim($CFG->wwwroot, '/').'/local/staticpage/view.php?page='.$page_pagename;
                $page_url_standard_available = local_staticpage_check_availability($page_url_standard);

                // Show if document is available
                if ($page_url_standard_available) {
                    $html .= html_writer::tag('p',
                        get_string('settingspagelistentrystandardsuccess', 'local_staticpage',
                        html_writer::link($page_url_standard, html_writer::tag('span', get_string('available', 'local_staticpage'), array('class' => 'label label-success')).'&nbsp;'.$page_url_standard))
                    );
                }
                // Otherwise
                else {
                    $html .= html_writer::tag('p',
                        get_string('settingspagelistentrystandardfail', 'local_staticpage',
                        html_writer::link($page_url_standard, html_writer::tag('span', get_string('notavailable', 'local_staticpage'), array('class' => 'label label-important')).'&nbsp;'.$page_url_standard))
                    );
                }
            }

            // Print rewritten static page URL
                // Check availability
                $page_url_rewrite = rtrim($CFG->wwwroot, '/').'/static/'.$page_pagename.'.html';
                $page_url_rewrite_available = local_staticpage_check_availability($page_url_rewrite);

                // Show if document is available
                if ($page_url_rewrite_available) {
                    $html .= html_writer::tag('p',
                        get_string('settingspagelistentryrewritesuccess', 'local_staticpage',
                        html_writer::link($page_url_rewrite, html_writer::tag('span', get_string('available', 'local_staticpage'), array('class' => 'label label-success')).'&nbsp;'.$page_url_rewrite))
                    );
                }
                // Otherwise
                else {
                    $html .= html_writer::tag('p',
                        get_string('settingspagelistentryrewritefail', 'local_staticpage',
                        html_writer::link($page_url_rewrite, html_writer::tag('span', get_string('notavailable', 'local_staticpage'), array('class' => 'label label-warning')).'&nbsp;'.$page_url_rewrite))
                    );
                }

        // Finish page list entry
        $html .= html_writer::end_tag('li');

    }

    // Finish page list
    $html .= html_writer::end_tag('ul');
}

// Output HTML
echo $html;

// Finish page
echo $OUTPUT->footer();
