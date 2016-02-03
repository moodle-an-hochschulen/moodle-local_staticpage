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
 * @package     local
 * @subpackage  local_staticpage
 * @copyright   2013 Alexander Bias, University of Ulm <alexander.bias@uni-ulm.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_local_staticpage_upgrade($oldversion) {

    if ($oldversion < 2016020309) {
        $context = \context_system::instance();
        $fs = get_file_storage();

        $filerecord = array('component' => 'local_staticpage', 'filearea' => 'documents',
                            'contextid' => $context->id, 'itemid' => 0, 'filepath' => '/',
                            'filename' => '');

        $documentsdirectory = get_config('local_staticpage', 'documentdirectory');
        $handle = @opendir($documentsdirectory);

        if ($handle) {
            $todelete = array();
            while (false !== ($file = readdir($handle))) {
                $ishtml = strpos($file, '.html');

                if (!$ishtml) {
                    continue;
                }

                $filerecord['filename'] = $file;
                $fullpath = $documentsdirectory . '/' . $file;

                $fs->create_file_from_pathname($filerecord, $fullpath);
                $todelete[] = $fullpath;
            }

            if ($handle) {
                closedir($handle);
            }

            foreach ($todelete as $file) {
                $result = @unlink($file);

                if ($result == false) {
                    $message = get_string('upgrade_notice_2016020307', 'local_staticpage', $file);
                    echo html_writer::tag('div', $message, array('class' => 'alert alert-info'));
                }
            }
        }

        upgrade_plugin_savepoint(true, 2016020309, 'local', 'sandbox');
    }

    return true;
}
