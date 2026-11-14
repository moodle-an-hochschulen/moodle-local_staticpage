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

// Include config.php.
require(__DIR__ . '/../../config.php');

// Globals.
global $CFG, $PAGE, $OUTPUT;

// Include adminlib.php.
require_once($CFG->libdir . '/adminlib.php');

// Include plugin libraries.
require_once($CFG->dirroot . '/local/staticpage/lib.php');
require_once($CFG->dirroot . '/local/staticpage/locallib.php');

// Set up external admin page.
admin_externalpage_setup('local_staticpage_pagelist');


// Prepare page.
$title = get_string('settingspagelist', 'local_staticpage');
$PAGE->set_title($title);
$PAGE->set_heading($title);

echo $OUTPUT->header();


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
        get_string(
            'settingspagelistnofiles',
            'local_staticpage',
            rtrim($CFG->wwwroot, '/') . '/admin/settings.php?section=local_staticpage_documents'
        ),
        'alert alert-warning'
    );
    // Otherwise start page list.
} else {
    // Output instruction.
    $html .= $OUTPUT->box(
        get_string(
            'settingspagelistinstruction',
            'local_staticpage',
            rtrim($CFG->wwwroot, '/') . '/admin/settings.php?section=local_staticpage_documents'
        ),
        'alert alert-info'
    );

    // Fetch all files.
    $pages = $fs->get_area_files($context->id, 'local_staticpage', 'documents', false, 'filename', false);

    // Get plugin config.
    $localstaticpageconfig = get_config('local_staticpage');

    // Remember if an availability check failed with a connection error or a timeout.
    // In this case, the site most probably can't reach its own URL at all (e.g. because of a firewall, DNS or
    // hairpin NAT issue) and each further check would just wait for the timeout again.
    // Thus, the remaining checks are skipped to prevent this list from loading for a long time.
    $checkavailabilityconnectionerror = false;

    // Map the availability check results to badge classes.
    // A non-working clean URL is only shown as a warning as the static page can still be delivered at the standard URL
    // if Apache mod_rewrite isn't forced.
    $badgeclasses = [
        'standard' => [
            STATICPAGE_CHECKAVAILABILITY_RESPONSE_SUCCESS => 'text-bg-success',
            STATICPAGE_CHECKAVAILABILITY_RESPONSE_FAIL => 'text-bg-danger',
            STATICPAGE_CHECKAVAILABILITY_RESPONSE_ERROR => 'text-bg-danger',
            STATICPAGE_CHECKAVAILABILITY_RESPONSE_DISABLED => 'text-bg-info',
            STATICPAGE_CHECKAVAILABILITY_RESPONSE_SKIPPED => 'text-bg-secondary',
        ],
        'rewrite' => [
            STATICPAGE_CHECKAVAILABILITY_RESPONSE_SUCCESS => 'text-bg-success',
            STATICPAGE_CHECKAVAILABILITY_RESPONSE_FAIL => 'text-bg-warning',
            STATICPAGE_CHECKAVAILABILITY_RESPONSE_ERROR => 'text-bg-warning',
            STATICPAGE_CHECKAVAILABILITY_RESPONSE_DISABLED => 'text-bg-info',
            STATICPAGE_CHECKAVAILABILITY_RESPONSE_SKIPPED => 'text-bg-secondary',
        ],
    ];

    // Collect the page list entries.
    $templatepages = [];
    foreach ($pages as $page) {
        // Collect information about the page.
        $pagefilename = $page->get_filename();
        $pageextension = pathinfo($pagefilename, PATHINFO_EXTENSION);
        $pagepagename = pathinfo($pagefilename, PATHINFO_FILENAME);

        // If this is not .html file, skip it.
        if ($pageextension != 'html') {
            continue;
        }

        // Compose the page list entry.
        // The filename and the pagename are escaped by the template even though the file storage
        // cleans all filenames with PARAM_FILE already.
        $templatepage = [
            'filename' => $pagefilename,
            'pagename' => $pagepagename,
            'invalid' => false,
            'urls' => [],
        ];

        // If the pagename would be rejected by view.php, show a warning instead of URLs which would not work anyway.
        if (clean_param($pagepagename, PARAM_ALPHANUMEXT) !== $pagepagename) {
            $templatepage['invalid'] = true;
            $templatepages[] = $templatepage;
            continue;
        }

        // Compose the URLs of the page.
        $pageurls = [];
        // The standard URL is only used if Apache mod_rewrite isn't forced.
        if (!$localstaticpageconfig->apacherewrite) {
            $pageurls['standard'] = rtrim($CFG->wwwroot, '/') . '/local/staticpage/view.php?page=' . $pagepagename;
        }
        $pageurls['rewrite'] = rtrim($CFG->wwwroot, '/') . '/static/' . $pagepagename . '.html';

        // Check the availability of each URL.
        foreach ($pageurls as $urltype => $pageurl) {
            if ($checkavailabilityconnectionerror) {
                $pageurlavailable = STATICPAGE_CHECKAVAILABILITY_RESPONSE_SKIPPED;
            } else {
                $pageurlavailable = local_staticpage_check_availability($pageurl);
                if ($pageurlavailable == STATICPAGE_CHECKAVAILABILITY_RESPONSE_ERROR) {
                    $checkavailabilityconnectionerror = true;
                }
            }

            $templatepage['urls'][] = [
                'label' => get_string('settingspagelisturl' . $urltype, 'local_staticpage'),
                'url' => $pageurl,
                'status' => get_string('checkavailabilityresponse' . $pageurlavailable, 'local_staticpage'),
                'badgeclass' => $badgeclasses[$urltype][$pageurlavailable],
                'description' => get_string(
                    'settingspagelistcheck' . $pageurlavailable,
                    'local_staticpage',
                    get_string('settingspagelistcheckconfig' . $urltype, 'local_staticpage')
                ),
            ];
        }

        $templatepages[] = $templatepage;
    }

    // Output page list.
    $html .= $OUTPUT->render_from_template('local_staticpage/pagelist', ['pages' => $templatepages]);
}

// Output HTML.
echo $html;

// Finish page.
echo $OUTPUT->footer();
