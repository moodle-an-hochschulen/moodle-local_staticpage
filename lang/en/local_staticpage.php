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

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Static Pages';
$string['apacherewrite'] = 'Force Apache mod_rewrite';
$string['apacherewrite_desc'] = 'Serve static pages only with a clean URL, rewritten by Apache mod_rewrite. See README file for details.';
$string['documentdirectory'] = 'Document directory';
$string['documentdirectory_desc'] = 'The document directory which contains .html files with the static pages\' HTML code. See README file for details.';
$string['documentheadingsource'] = 'Data source of document heading';
$string['documentheadingsource_desc'] = 'The data source of the static page\'s document heading';
$string['documentlist'] = 'Document list';
$string['documentlistdirectoryempty'] = 'There are no .html files in the document directory, therefore there are no static pages to be delivered. See README file for details.';
$string['documentlistdirectorynotreadable'] = 'The document directory is not readable, therefore there are no static pages to be delivered.';
$string['documentlistentryfilename'] = 'The following document file was found:<br /><strong>{$a}</strong>';
$string['documentlistentrylanguage'] = 'The document will be available for the following language:<br /><strong>{$a}</strong>';
$string['documentlistentryreachablefail'] = 'The document should be available at the following URL, but actually a browser won\'t be able to download and view it (perhaps there is something wrong with your webserver configuration - see README file for details):<br /><strong>{$a}</strong>';
$string['documentlistentryreachablesuccess'] = 'The document is available and can be linked to at the following URL:<br /><strong>{$a}</strong>';
$string['documentlistentryrewritefail'] = 'The document should be available to at the following clean URL, but actually a browser won\'t be able to download and view it (perhaps there is something wrong with your webserver or mod_rewrite configuration - see README file for details):<br /><strong>{$a}</strong>';
$string['documentlistentryrewritesuccess'] = 'The document is available and can be linked to at the following clean URL:<br /><strong>{$a}</strong>';
$string['documentlistentrypagename'] = 'From the document file\'s filename, Moodle derived the following pagename:<br /><strong>{$a}</strong>';
$string['documentlistentryunsupported'] = 'The filename suffix refers to an unsupported language pack and therefore, the document will <strong>not be available</strong>. Please change the document filename to a supported language pack.';
$string['documentlistinstruction'] = 'This list shows all files which have been found in the document directory and their URLs';
$string['documentlistnodirectory'] = 'The document directory doesn\'t exist, therefore there are no static pages to be delivered.';
$string['documentnavbarsource'] = 'Data source of breadcrumb item title';
$string['documentnavbarsource_desc'] = 'The data source of the static page\'s breadcrumb item title (used in the Moodle "Navbar")';
$string['documenttitlesource'] = 'Data source of document title';
$string['documenttitlesource_desc'] = 'The data source of the static page\'s document title (used in the browser titlebar)';
$string['documenttitlesourceh1'] = 'First h1 tag in HTML code (usually located shortly after opening the body tag)';
$string['documenttitlesourcehead'] = 'First title tag in HTML code (usually located within the head tag)';
$string['international'] = 'All languages';
$string['pagenotfound'] = 'Page not found';
