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
 * Local plugin "staticpage" - Language pack
 *
 * @package    local_staticpage
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['apacherewrite'] = 'Force Apache mod_rewrite';
$string['apacherewrite_desc'] = 'Serve static pages only with a clean URL, rewritten by Apache mod_rewrite. See the <a href="https://github.com/moodle-an-hochschulen/moodle-local_staticpage/blob/main/README.md#apache-mod_rewrite">plugin documentation on Github</a> for details.';
$string['checkavailability'] = 'Check availability';
$string['checkavailability_desc'] = 'Configure if Moodle should check for static file availability on the list of static pages or not.';
$string['checkavailabilityconnecttimeout'] = 'Connect timeout';
$string['checkavailabilityconnecttimeout_desc'] = 'Configure the maximum number of seconds to wait while trying to connect during the availability check. Use 0 to wait indefinitely.';
$string['checkavailabilityno'] = 'No, don\'t check availability';
$string['checkavailabilityresponsedisabled'] = 'Not checked';
$string['checkavailabilityresponseerror'] = 'Not available - Error';
$string['checkavailabilityresponsefail'] = 'Not available - Non-2xx';
$string['checkavailabilityresponsesuccess'] = 'Available';
$string['checkavailabilitytimeout'] = 'Timeout';
$string['checkavailabilitytimeout_desc'] = 'Configure the maximum number of seconds to allow cURL functions to execute during the availability check. Use 0 to allow indefinite execution time.';
$string['checkavailabilityyes'] = 'Yes, check availability';
$string['cleanhtml'] = 'Clean HTML code';
$string['cleanhtml_desc'] = 'Configure if the static page\'s HTML code should be cleaned (and thereby special tags like &lt;iframe&gt; being removed). See the <a href="https://github.com/moodle-an-hochschulen/moodle-local_staticpage/blob/main/README.md#24-process-content">plugin documentation on Github</a> for details.';
$string['cleanhtmlno'] = 'No, don\'t clean HTML code';
$string['cleanhtmlyes'] = 'Yes, clean HTML code';
$string['documentheadingsource'] = 'Data source of document heading';
$string['documentheadingsource_desc'] = 'The data source of the static page\'s document heading';
$string['documents'] = 'Documents';
$string['documents_desc'] = 'The .html files with the static pages\' HTML code. See the <a href="https://github.com/moodle-an-hochschulen/moodle-local_staticpage/blob/main/README.md#1-documents">plugin documentation on Github</a> for details.';
$string['documenttitlesource'] = 'Data source of document title';
$string['documenttitlesource_desc'] = 'The data source of the static page\'s document title (used in the browser titlebar)';
$string['documenttitlesourceh1'] = 'First h1 tag in HTML code (usually located shortly after opening the body tag)';
$string['documenttitlesourcehead'] = 'First title tag in HTML code (usually located within the head tag)';
$string['eventstaticpageviewed'] = 'Static page viewed';
$string['forcelogin'] = 'Force login';
$string['forcelogin_desc'] = 'Serve static pages only to logged in users or also to non-logged in visitors. This behaviour can be set specifically for static pages or can be set to respect Moodle\'s global forcelogin setting. See the <a href="https://github.com/moodle-an-hochschulen/moodle-local_staticpage/blob/main/README.md#23-force-login">plugin documentation on Github</a> for details.';
$string['forceloginglobal'] = 'Respect the global setting $CFG->forcelogin';
$string['pagenotfound'] = 'Page not found';
$string['pluginname'] = 'Static Pages';
$string['privacy:metadata'] = 'The static pages plugin provides extended functionality to Moodle admins, but does not store any personal data.';
$string['processcontent'] = 'Process content';
$string['processfilters'] = 'Process filters';
$string['processfilters_desc'] = 'Configure if Moodle filters (especially the multilanguage filter) should be processed when serving the static page\'s content. See the <a href="https://github.com/moodle-an-hochschulen/moodle-local_staticpage/blob/main/README.md#24-process-content">plugin documentation on Github</a> for details.';
$string['processfiltersno'] = 'No, don\'t process filters';
$string['processfiltersyes'] = 'Yes, process filters';
$string['settingspagelist'] = 'List of static pages';
$string['settingspagelistentryfilename'] = 'The following document file was found:<br /><strong>{$a}</strong>';
$string['settingspagelistentrypagename'] = 'From the document file\'s filename, Moodle derived the following pagename:<br /><strong>{$a}</strong>';
$string['settingspagelistentryrewritedisabled'] = 'The static page should be available at the following clean URL, but is not verified because checking availability is disabled:<br /><strong>{$a}</strong>';
$string['settingspagelistentryrewriteerror'] = 'The static page should be available at the following clean URL, but actually a browser won\'t be able to download and view it either because of connection error or responding slower than checkavailabilitytimeout config (perhaps there is something wrong with your webserver or mod_rewrite configuration):<br /><strong>{$a}</strong>';
$string['settingspagelistentryrewritefail'] = 'The static page should be available at the following clean URL, but actually a browser won\'t be able to download and view it due to a non-2xx HTTP status code (perhaps there is something wrong with your webserver or mod_rewrite configuration):<br /><strong>{$a}</strong>';
$string['settingspagelistentryrewritesuccess'] = 'The static page is available and can be linked to at the following clean URL:<br /><strong>{$a}</strong>';
$string['settingspagelistentrystandarddisabled'] = 'The static page should be available at the following standard URL, but is not checked because checking availability is disabled:<br /><strong>{$a}</strong>';
$string['settingspagelistentrystandarderror'] = 'The static page should be available at the following standard URL, but actually a browser won\'t be able to download and view it either because of connection error or responding slower than checkavailabilitytimeout config (perhaps there is something wrong with your webserver configuration):<br /><strong>{$a}</strong>';
$string['settingspagelistentrystandardfail'] = 'The static page should be available at the following standard URL, but actually a browser won\'t be able to download and view it due to a non-2xx HTTP status code (perhaps there is something wrong with your webserver configuration):<br /><strong>{$a}</strong>';
$string['settingspagelistentrystandardsuccess'] = 'The static page is available and can be linked to at the following standard URL:<br /><strong>{$a}</strong>';
$string['settingspagelistinstruction'] = 'This list shows all static pages which have been uploaded into the <a href="{$a}">static pages document area</a> and their URLs';
$string['settingspagelistnofiles'] = 'There are no .html files in the <a href="{$a}">static pages document area</a>, therefore there are no static pages to be delivered. See the <a href="https://github.com/moodle-an-hochschulen/moodle-local_staticpage/blob/main/README.md#1-documents">plugin documentation on Github</a> for details.';
$string['staticpage:managedocuments'] = 'Manage static page documents';
$string['upgrade_notice_2016020307'] = '<strong>UPGRADE NOTICE:</strong> The static page document files were moved to the new filearea within Moodle. You can delete the legacy documents directory {$a} now. For more upgrade instructions, especially if you have been using the multilanguage features of this plugin, see the <a href="https://github.com/moodle-an-hochschulen/moodle-local_staticpage/blob/main/README.md#upgrading-from-previous-versions">plugin documentation on Github</a> for details.';
