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
 * Local plugin "staticpage" - Capabilities
 *
 * @package    local_staticpage
 * @copyright  2021 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = [
        // This capability, in conjunction with moodle/site:configview, allows a user to manage static page documents,
        // but not the static page plugin settings.
        // Additionally, users with moodle/site:config are also allowed to manage the static page documents
        // _and_ the static page plugin settings without needing to have local/staticpages:managedocuments themselves.
        'local/staticpage:managedocuments' => [
                'captype' => 'write',
                'contextlevel' => CONTEXT_SYSTEM,
        ],
];
