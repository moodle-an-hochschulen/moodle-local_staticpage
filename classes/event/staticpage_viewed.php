<?php
/**
 * Staticpage viewed event class.
 *
 * @package    local_staticpage
 * @since      Moodle 4.1
 * @copyright  2023 Bright Alley
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_staticpage\event;
defined('MOODLE_INTERNAL') || die();

class staticpage_viewed extends \core\event\base {

    /**
     * Initialise event parameters.
     */
    protected function init() {
        $this->data['objecttable'] = 'staticpage';
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
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
        return "The user with id '$this->userid' has viewed the static page with title '" .  $this->other['title'] . "'.";
    }

    /**
     * Return the legacy event name.
     *
     * @return string
     */
    public static function get_legacy_eventname() {
        return 'local_staticpage_viewed';
    }
}
