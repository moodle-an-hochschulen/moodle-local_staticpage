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
 * Local plugin "staticpage" - Local Library
 *
 * @package    local_staticpage
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Check if static page is available and downloadable on given URL
 *
 * @param string $url Static page URL
 * @return bool
 */
function local_staticpage_check_availability($url) {
    // If the availability check is disabled, we are already done.
    if (STATICPAGE_CHECKAVAILABILITY_NO == get_config('local_staticpage', 'checkavailability')) {
        return STATICPAGE_CHECKAVAILABILITY_RESPONSE_DISABLED;
    }

    // Prepare cURL request.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    if (!ini_get('open_basedir')) {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    }
    // We need that to prevent false errors with self-signed certificates on webserver.
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Add the configured timeouts to the cURL request.
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, get_config('local_staticpage', 'checkavailabilityconnecttimeout'));
    curl_setopt($ch, CURLOPT_TIMEOUT, get_config('local_staticpage', 'checkavailabilitytimeout'));

    // Run cURL request.
    curl_exec($ch);

    // Check cURL result and return if there was a general problem.
    if (curl_errno($ch) !== CURLE_OK) {
        return STATICPAGE_CHECKAVAILABILITY_RESPONSE_ERROR;
    }

    // Get cURL return code and close the connetion.
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Return a success if the static page was available (HTTP 200).
    if ($code >= 200 && $code < 300) {
        return STATICPAGE_CHECKAVAILABILITY_RESPONSE_SUCCESS;

        // Otherwise return a failure.
    } else {
        return STATICPAGE_CHECKAVAILABILITY_RESPONSE_FAIL;
    }
}
