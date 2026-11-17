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
 * Local plugin "staticpage" - Upgrade plugin tasks
 *
 * @package    local_staticpage
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\output\html_writer;

/**
 * Upgrade steps for this plugin
 * @param int $oldversion the version we are upgrading from
 * @return boolean
 */
function xmldb_local_staticpage_upgrade($oldversion) {
    global $CFG;

    // Require plugin library for the plugin's constants.
    require_once($CFG->dirroot . '/local/staticpage/lib.php');

    // Fetch documents from documents directory and put them into the new documents filearea.
    if ($oldversion < 2016020309) {
        // Prepare filearea.
        $context = \context_system::instance();
        $fs = get_file_storage();
        $filerecord = ['component' => 'local_staticpage', 'filearea' => 'documents',
                            'contextid' => $context->id, 'itemid' => 0, 'filepath' => '/',
                            'filename' => '', ];

        // Prepare documents directory.
        $documentsdirectory = get_config('local_staticpage', 'documentdirectory');
        $handle = @opendir($documentsdirectory);

        if ($handle) {
            // Fetch all files from documents directory.
            while (false !== ($file = readdir($handle))) {
                // Only process .html files.
                $ishtml = strpos($file, '.html');
                if (!$ishtml) {
                    continue;
                }

                // Compose file name and path.
                $filerecord['filename'] = $file;
                $fullpath = $documentsdirectory . '/' . $file;

                // Put file into filearea.
                $fs->create_file_from_pathname($filerecord, $fullpath);
            }

            // Close documents directory.
            closedir($handle);

            // Show an info message that documents directory is no longer needed.
            $message = get_string('upgrade_notice_2016020307', 'local_staticpage', $documentsdirectory);
            echo html_writer::tag('div', $message, ['class' => 'alert alert-info']);
        }

        // Remove documents directory setting because it is not needed anymore.
        set_config('documentdirectory', null, 'local_staticpage');

        // Remember upgrade savepoint.
        upgrade_plugin_savepoint(true, 2016020309, 'local', 'staticpage');
    }

    if ($oldversion < 2021120803) {
        // Remove documentnavbarsource setting because it was removed from the plugin.
        unset_config('documentnavbarsource', 'local_staticpage');

        // Remember upgrade savepoint.
        upgrade_plugin_savepoint(true, 2021120803, 'local', 'staticpage');
    }

    if ($oldversion < 2026100502) {
        // The style tag and the link tags got their own settings which were previously controlled by the cleanhtml setting.
        // Set these settings in a way that the static pages keep their existing behaviour.
        // The style tag was used regardless of the cleanhtml setting.
        set_config('styletag', STATICPAGE_STYLETAG_YES, 'local_staticpage');
        // The link tags were passed on as they are if the HTML code should not be cleaned and cleaned otherwise.
        if (get_config('local_staticpage', 'cleanhtml') == STATICPAGE_CLEANHTML_NO) {
            set_config('linktags', STATICPAGE_LINKTAGS_ASIS, 'local_staticpage');
        } else {
            set_config('linktags', STATICPAGE_LINKTAGS_CLEAN, 'local_staticpage');
        }

        // Remember upgrade savepoint.
        upgrade_plugin_savepoint(true, 2026100502, 'local', 'staticpage');
    }

    if ($oldversion < 2026100503) {
        // The availability check timeouts got non-zero defaults as a timeout of 0 lets the availability check
        // (and thereby the list of static pages) hang for a long time if the site can't reach its own URL.
        // Replace the previous default of 0 with the new defaults.
        // As it is not possible to distinguish the previous default from a value of 0 which was set deliberately,
        // this also affects sites which have set the value to 0 deliberately.
        $connecttimeout = get_config('local_staticpage', 'checkavailabilityconnecttimeout');
        if ($connecttimeout !== false && (int) $connecttimeout === 0) {
            set_config(
                'checkavailabilityconnecttimeout',
                STATICPAGE_CHECKAVAILABILITY_CONNECTTIMEOUT_DEFAULT,
                'local_staticpage'
            );
        }
        $timeout = get_config('local_staticpage', 'checkavailabilitytimeout');
        if ($timeout !== false && (int) $timeout === 0) {
            set_config('checkavailabilitytimeout', STATICPAGE_CHECKAVAILABILITY_TIMEOUT_DEFAULT, 'local_staticpage');
        }

        // Remember upgrade savepoint.
        upgrade_plugin_savepoint(true, 2026100503, 'local', 'staticpage');
    }

    return true;
}
