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

$string['pluginname'] = 'Static Pages';
$string['apacherewrite'] = 'Force Apache mod_rewrite';
$string['apacherewritedescription'] = 'Serve static pages only with a clean URL, rewritten by Apache mod_rewrite. See README file for details.';
$string['documentdirectory'] = 'Document directory';
$string['documentdirectorydescription'] = 'The document directory which contains .html files with the static pages\' HTML code. See README file for details.';
$string['documentlist'] = 'Document list';
$string['documentlistdirectoryempty'] = 'There are no .html files in the document directory, therefore there are no static pages to be delivered. See README file for details.';
$string['documentlistdirectorynotreadable'] = 'The document directory is not readable, therefore there are no static pages to be delivered.';
$string['documentlistentryfilename'] = 'The following document file was found:<br /><strong>{$a}</strong>';
$string['documentlistentrylanguage'] = 'The document will be available for the following language:<br /><strong>{$a}</strong>';
$string['documentlistentryreachable'] = 'The document is available and can be linked to at the following URL:<br /><strong>{$a}</strong>';
$string['documentlistentryrewrite'] = 'The document is available and can be linked to at the following clean URL (as long as you did enable Apache mod_rewrite rules as described in the README file):<br /><strong>{$a}</strong>';
$string['documentlistentrypagename'] = 'From the document file\'s filename, Moodle derived the following pagename:<br /><strong>{$a}</strong>';
$string['documentlistentryunsupported'] = 'The filename suffix refers to an unsupported language pack and therefore, the document will <strong>not be available</strong>. Please change the document filename to a supported language pack.';
$string['documentlistinstruction'] = 'This list shows all files which have been found in the document directory and their URLs';
$string['documentlistnodirectory'] = 'The document directory doesn\'t exist, therefore there are no static pages to be delivered.';
$string['international'] = 'All languages';
$string['pagenotfound'] = 'Page not found';
