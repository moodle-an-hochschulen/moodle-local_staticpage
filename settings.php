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
 * Local plugin "staticpage" - Settings
 *
 * @package    local_staticpage
 * @copyright  2013 Alexander Bias, University of Ulm <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Include lib.php
require_once(dirname(__FILE__) . '/lib.php');

global $CFG, $PAGE;

if ($hassiteconfig) {
    // New settings page
    $page = new admin_settingpage('staticpage', get_string('pluginname', 'local_staticpage'));


    // Document filearea
    $page->add(new admin_setting_heading('local_staticpage/documentsheading', get_string('documents', 'local_staticpage'), ''));

    // Create document filearea widget
    $page->add(new admin_setting_configstoredfile('local_staticpage/documents', get_string('documents', 'local_staticpage'), get_string('documents_desc', 'local_staticpage'), 'documents', 0, array('maxfiles' => -1, 'accepted_types' => '.html')));


    // Document title source
    $page->add(new admin_setting_heading('local_staticpage/documenttitlesourceheading', get_string('documenttitlesource', 'local_staticpage'), ''));

    // Create document title source widget
    $titlesource[STATICPAGE_TITLE_H1] = get_string('documenttitlesourceh1', 'local_staticpage');
    $titlesource[STATICPAGE_TITLE_HEAD] = get_string('documenttitlesourcehead', 'local_staticpage');
    $page->add(new admin_setting_configselect('local_staticpage/documenttitlesource', get_string('documenttitlesource', 'local_staticpage'), get_string('documenttitlesource_desc', 'local_staticpage'), STATICPAGE_TITLE_H1, $titlesource));
    $page->add(new admin_setting_configselect('local_staticpage/documentheadingsource', get_string('documentheadingsource', 'local_staticpage'), get_string('documentheadingsource_desc', 'local_staticpage'), STATICPAGE_TITLE_H1, $titlesource));
    $page->add(new admin_setting_configselect('local_staticpage/documentnavbarsource', get_string('documentnavbarsource', 'local_staticpage'), get_string('documentnavbarsource_desc', 'local_staticpage'), STATICPAGE_TITLE_H1, $titlesource));


    // Apache rewrite
    $page->add(new admin_setting_heading('local_staticpage/apacherewriteheading', get_string('apacherewrite', 'local_staticpage'), ''));

    // Create apache rewrite control widget
    $page->add(new admin_setting_configcheckbox('local_staticpage/apacherewrite', get_string('apacherewrite', 'local_staticpage'), get_string('apacherewrite_desc', 'local_staticpage'), 0));


    // Force login
    $page->add(new admin_setting_heading('local_staticpage/forceloginheading', get_string('forcelogin', 'local_staticpage'), ''));

    // Create force login widget
    $forceloginmodes[STATICPAGE_FORCELOGIN_YES] = get_string('yes');
    $forceloginmodes[STATICPAGE_FORCELOGIN_NO] = get_string('no');
    $forceloginmodes[STATICPAGE_FORCELOGIN_GLOBAL] = get_string('forceloginglobal', 'local_staticpage');
    $page->add(new admin_setting_configselect('local_staticpage/forcelogin', get_string('forcelogin', 'local_staticpage'), get_string('forcelogin_desc', 'local_staticpage'), $forceloginmodes[STATICPAGE_FORCELOGIN_GLOBAL], $forceloginmodes));


    // Process content
    $page->add(new admin_setting_heading('local_staticpage/processcontentheading', get_string('processcontent', 'local_staticpage'), ''));

    // Create process filters widget
    $processfiltersmodes[STATICPAGE_PROCESSFILTERS_YES] = get_string('processfiltersyes', 'local_staticpage');
    $processfiltersmodes[STATICPAGE_PROCESSFILTERS_NO] = get_string('processfiltersno', 'local_staticpage');
    $page->add(new admin_setting_configselect('local_staticpage/processfilters', get_string('processfilters', 'local_staticpage'), get_string('processfilters_desc', 'local_staticpage'), $processfiltersmodes[STATICPAGE_PROCESSFILTERS_YES], $processfiltersmodes));

    // Create clean HTML widget
    $cleanhtmlmodes[STATICPAGE_CLEANHTML_YES] = get_string('cleanhtmlyes', 'local_staticpage');
    $cleanhtmlmodes[STATICPAGE_CLEANHTML_NO] = get_string('cleanhtmlno', 'local_staticpage');
    $page->add(new admin_setting_configselect('local_staticpage/cleanhtml', get_string('cleanhtml', 'local_staticpage'), get_string('cleanhtml_desc', 'local_staticpage'), $cleanhtmlmodes[STATICPAGE_CLEANHTML_YES], $cleanhtmlmodes));


    // Add settings page to navigation tree
    $ADMIN->add('localplugins', $page);
}
