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
 * Local plugin "staticpage" - Library
 *
 * @package     local
 * @subpackage  local_staticpage
 * @copyright   2013 Alexander Bias, University of Ulm <alexander.bias@uni-ulm.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// defined('MOODLE_INTERNAL') || die(); - Must not be called because this script is called from outside moodle

define('STATICPAGE_TITLE_H1', 1);
define('STATICPAGE_TITLE_HEAD', 2);


/**
 * Check if static page is available and downloadable on given URL
 *
 * @param string $url Static page URL
 * @return bool
 */
function local_staticpage_check_availability($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    if (!ini_get('open_basedir') && !ini_get('safe_mode')) {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // We need that to prevent false errors with self-signed certificates on webserver.
    $ret = curl_exec($ch);

    $ret = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($ret == 200) {
        return true;
    } else {
        return false;
    }
}
