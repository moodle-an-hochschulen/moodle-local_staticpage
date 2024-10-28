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
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Globals.
global $CFG, $PAGE;

// Include lib.php.
require_once($CFG->dirroot.'/local/staticpage/lib.php');

// Check if this user has the right to manage static page documents.
$hasmanagedocuments = has_capability('local/staticpage:managedocuments', context_system::instance());

// The 'Documents' settings page and the 'List of static pages' settings page should be accessible for users
// with either the moodle/site:config or the local/staticpage:managedocuments capability.
// As there is no possibility to attach multiple capabilities with an OR rule to a settings page,
// we pick the capability to use here and pass it to the page later.
if ($hasmanagedocuments) {
    $capabilityrequiredforpage = 'local/staticpage:managedocuments';
} else {
    $capabilityrequiredforpage = 'moodle/site:config';
}

// Require one of these capabilities.
if ($hassiteconfig || $hasmanagedocuments) {
    // Add new category to site admin navigation tree.
    $ADMIN->add('root', new admin_category('local_staticpage',
            get_string('pluginname', 'local_staticpage', null, true)));


    // Create new documents page.
    $page = new admin_settingpage('local_staticpage_documents',
            get_string('documents', 'local_staticpage', null, true),
            $capabilityrequiredforpage);

    if ($ADMIN->fulltree) {
        // Create document filearea widget.
        $page->add(new \local_staticpage\admin_setting_staticpagestoredfile('local_staticpage/documents',
                get_string('documents', 'local_staticpage', null, true),
                get_string('documents_desc', 'local_staticpage', null, true),
                'documents',
                0,
                ['maxfiles' => -1, 'accepted_types' => '.html']));
    }

    // Add documents page to navigation category.
    $ADMIN->add('local_staticpage', $page);


    // Create new settings page.
    $page = new admin_settingpage('local_staticpage_settings',
            get_string('settings', 'core', null, true));

    if ($ADMIN->fulltree) {
        // Document title source.
        $page->add(new admin_setting_heading('local_staticpage/documenttitlesourceheading',
                get_string('documenttitlesource', 'local_staticpage', null, true),
                ''));

        // Create document title source widget.
        $titlesource[STATICPAGE_TITLE_H1] = get_string('documenttitlesourceh1', 'local_staticpage', null, false);
                // Don't use string lazy loading here because the string will be directly used and
                // would produce a PHP warning otherwise.
        $titlesource[STATICPAGE_TITLE_HEAD] = get_string('documenttitlesourcehead', 'local_staticpage', null, true);
        $page->add(new admin_setting_configselect('local_staticpage/documenttitlesource',
                get_string('documenttitlesource', 'local_staticpage', null, true),
                get_string('documenttitlesource_desc', 'local_staticpage', null, true),
                STATICPAGE_TITLE_H1,
                $titlesource));
        $page->add(new admin_setting_configselect('local_staticpage/documentheadingsource',
                get_string('documentheadingsource', 'local_staticpage', null, true),
                get_string('documentheadingsource_desc', 'local_staticpage', null, true),
                STATICPAGE_TITLE_H1,
                $titlesource));


        // Apache rewrite.
        $page->add(new admin_setting_heading('local_staticpage/apacherewriteheading',
                get_string('apacherewrite', 'local_staticpage', null, true),
                ''));

        // Create apache rewrite control widget.
        $page->add(new admin_setting_configcheckbox('local_staticpage/apacherewrite',
                get_string('apacherewrite', 'local_staticpage', null, true),
                get_string('apacherewrite_desc', 'local_staticpage', null, true),
                0));


        // Force login.
        $page->add(new admin_setting_heading('local_staticpage/forceloginheading',
                get_string('forcelogin', 'local_staticpage', null, true),
                ''));

        // Create force login widget.
        $forceloginmodes[STATICPAGE_FORCELOGIN_YES] = get_string('yes', 'core', null, true);
        $forceloginmodes[STATICPAGE_FORCELOGIN_NO] = get_string('no', 'core', null, true);
        $forceloginmodes[STATICPAGE_FORCELOGIN_GLOBAL] = get_string('forceloginglobal', 'local_staticpage', null, false);
                // Don't use string lazy loading here because the string will be directly used and
                // would produce a PHP warning otherwise.
        $page->add(new admin_setting_configselect('local_staticpage/forcelogin',
                get_string('forcelogin', 'local_staticpage', null, true),
                get_string('forcelogin_desc', 'local_staticpage', null, true),
                STATICPAGE_FORCELOGIN_GLOBAL,
                $forceloginmodes));


        // Process content.
        $page->add(new admin_setting_heading('local_staticpage/processcontentheading',
                get_string('processcontent', 'local_staticpage', null, true),
                ''));

        // Create process filters widget.
        $processfiltersmodes[STATICPAGE_PROCESSFILTERS_YES] = get_string('processfiltersyes', 'local_staticpage', null, false);
                // Don't use string lazy loading here because the string will be directly used and
                // would produce a PHP warning otherwise.
        $processfiltersmodes[STATICPAGE_PROCESSFILTERS_NO] = get_string('processfiltersno', 'local_staticpage', null, true);
        $page->add(new admin_setting_configselect('local_staticpage/processfilters',
                get_string('processfilters', 'local_staticpage', null, true),
                get_string('processfilters_desc', 'local_staticpage', null, true),
                STATICPAGE_PROCESSFILTERS_YES,
                $processfiltersmodes));

        // Create clean HTML widget.
        $cleanhtmlmodes[STATICPAGE_CLEANHTML_YES] = get_string('cleanhtmlyes', 'local_staticpage', null, false);
                // Don't use string lazy loading here because the string will be directly used and
                // would produce a PHP warning otherwise.
        $cleanhtmlmodes[STATICPAGE_CLEANHTML_NO] = get_string('cleanhtmlno', 'local_staticpage', null, true);
        $page->add(new admin_setting_configselect('local_staticpage/cleanhtml',
                get_string('cleanhtml', 'local_staticpage', null, true),
                get_string('cleanhtml_desc', 'local_staticpage', null, true),
                STATICPAGE_CLEANHTML_YES,
                $cleanhtmlmodes));

        // Check availability.
        $page->add(new admin_setting_heading('local_staticpage/checkavailabilityheading',
                get_string('checkavailability', 'local_staticpage', null, true),
                ''));

        // Create checkavailability widget.
        $checkavailabilitymodes[STATICPAGE_CHECKAVAILABILITY_YES] =
                get_string('checkavailabilityyes', 'local_staticpage', null, false);
                // Don't use string lazy loading here because the string will be directly used and
                // would produce a PHP warning otherwise.
        $checkavailabilitymodes[STATICPAGE_CHECKAVAILABILITY_NO] =
                get_string('checkavailabilityno', 'local_staticpage', null, true);
        $page->add(new admin_setting_configselect('local_staticpage/checkavailability',
                get_string('checkavailability', 'local_staticpage', null, true),
                get_string('checkavailability_desc', 'local_staticpage', null, true),
                STATICPAGE_CHECKAVAILABILITY_YES,
                $checkavailabilitymodes));

        // Create connect timeout widget.
        $page->add(new admin_setting_configtext('local_staticpage/checkavailabilityconnecttimeout',
                get_string('checkavailabilityconnecttimeout', 'local_staticpage', null, true),
                get_string('checkavailabilityconnecttimeout_desc', 'local_staticpage', null, true),
                0,
                PARAM_INT,
                4));

        // Create timeout widget.
        $page->add(new admin_setting_configtext('local_staticpage/checkavailabilitytimeout',
                get_string('checkavailabilitytimeout', 'local_staticpage', null, true),
                get_string('checkavailabilitytimeout_desc', 'local_staticpage', null, true),
                0,
                PARAM_INT,
                4));
    }

    // Add settings page to navigation category.
    $ADMIN->add('local_staticpage', $page);


    // Create new external pagelist page.
    $page = new admin_externalpage('local_staticpage_pagelist',
            get_string('settingspagelist', 'local_staticpage', null, true),
            new \core\url('/local/staticpage/settings_pagelist.php'),
            $capabilityrequiredforpage);

    // Add pagelist page to navigation category.
    $ADMIN->add('local_staticpage', $page);
}
