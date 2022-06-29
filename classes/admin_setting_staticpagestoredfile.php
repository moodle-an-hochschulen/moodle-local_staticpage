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
 * Local plugin "staticpage" - Settings class file
 *
 * @package    local_staticpage
 * @copyright  2017 Andrew Hancox, Synergy Learning UK on behalf of Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_staticpage;

/**
 * Class used for uploading the staticpage files into file storage, inherits quite everything from \admin_setting_configstoredfile.
 *
 * @package    local_staticpage
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_setting_staticpagestoredfile extends \admin_setting_configstoredfile {

    /**
     * This function renames .htm files to .html (if any files with this extension exist) when the filepicker is saved
     * and calls the parent function to retain its functionality
     * @param array $data - The data submitted from the form or null to set the default value for new installs.
     * @return bool - true if successful.
     */
    public function write_setting($data) {
        $now = time() - 1;

        $fs = get_file_storage();
        $options = $this->get_options();
        $component = is_null($this->plugin) ? 'core' : $this->plugin;

        $response = parent::write_setting($data);

        if (!empty($response)) { // This means file was not successfully stored.
            return $response;
        }

        $files = $fs->get_area_files($options['context']->id,
                $component,
                $this->filearea,
                $this->itemid,
                'sortorder,filepath,filename',
                false,
                $now);

        foreach ($files as $file) {
            $existingname = $file->get_filename();
            $newname = preg_replace('/.htm$/', '.html', $existingname);

            if ($newname != $existingname) {
                try {
                    $file->rename($file->get_filepath(), $newname);
                } catch (\file_exception $ex) {
                    $file->delete();
                    return get_string('fileexists', 'moodle', $newname);
                }
            }
        }

        return '';
    }
}
