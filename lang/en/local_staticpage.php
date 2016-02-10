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
 * @package     local
 * @subpackage  local_staticpage
 * @copyright   2013 Alexander Bias, University of Ulm <alexander.bias@uni-ulm.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Static Pages';
$string['apacherewrite'] = 'Force Apache mod_rewrite';
$string['apacherewrite_desc'] = 'Serve static pages only with a clean URL, rewritten by Apache mod_rewrite. See README file for details.';
$string['documents'] = 'Documents';
$string['documents_desc'] = 'The .html files with the static pages\' HTML code. See README file for details.';
$string['documentheadingsource'] = 'Data source of document heading';
$string['documentheadingsource_desc'] = 'The data source of the static page\'s document heading';
$string['documentnavbarsource'] = 'Data source of breadcrumb item title';
$string['documentnavbarsource_desc'] = 'The data source of the static page\'s breadcrumb item title (used in the Moodle "Navbar")';
$string['documenttitlesource'] = 'Data source of document title';
$string['documenttitlesource_desc'] = 'The data source of the static page\'s document title (used in the browser titlebar)';
$string['documenttitlesourceh1'] = 'First h1 tag in HTML code (usually located shortly after opening the body tag)';
$string['documenttitlesourcehead'] = 'First title tag in HTML code (usually located within the head tag)';
$string['pagenotfound'] = 'Page not found';
$string['upgrade_notice_2016020307_deletefile'] = '<strong>UPGRADE WARNING:</strong> Static page file {$a} was moved to the new filearea within Moodle (see README), but couldn\'t be deleted on disk. Please delete it manually.';
$string['upgrade_notice_2016020307_deletedirectory'] = '<strong>UPGRADE NOTICE:</strong> Static page files were moved to the new filearea within Moodle (see README). You can delete the legacy documents directory {$a} now manually.';
