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
 * Local plugin "staticpage" - Event definition.
 *
 * @package    local_staticpage
 * @copyright  2023 Bright Alley
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_staticpage\event;

/**
 * The local_staticpage staticpage viewed event class.
 *
 * @package    local_staticpage
 * @copyright  2023 Bright Alley
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class staticpage_viewed extends \core\event\base {

    /**
     * Init method.
     */
    protected function init() {
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Returns localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventstaticpageviewed', 'local_staticpage');
    }

    /**
     * Returns non-localised event description with id's for admin use only.
     *
     * @return string
     */
    public function get_description() {
        return "The user with id '$this->userid' has viewed the static page with title '" . $this->other['title'] . "'.";
    }

    /**
     * Get url related to the action.
     *
     * @return \core\url
     */
    public function get_url() {
        return new \core\url('/local/staticpage/view.php', ['page' => $this->other['page']]);
    }
}
