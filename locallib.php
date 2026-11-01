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

/**
 * Rebuild a stylesheet link tag from a static page document with a limited set of safe attributes only.
 *
 * This is used if the static page's HTML code should be cleaned. The link tags are inserted into Moodle's HTML head
 * and do not pass format_text() there, so they have to be cleaned separately.
 *
 * @param DOMElement $linknode The link node from the static page document.
 * @return string The rebuilt link tag or an empty string if the link tag is not allowed.
 */
function local_staticpage_clean_link_tag(DOMElement $linknode): string {
    // Only accept stylesheets and alternate stylesheets as this is what is needed to style a static page.
    $reltokens = preg_split('/\s+/', core_text::strtolower(trim($linknode->getAttribute('rel'))), -1, PREG_SPLIT_NO_EMPTY);
    sort($reltokens);
    if ($reltokens !== ['stylesheet'] && $reltokens !== ['alternate', 'stylesheet']) {
        return '';
    }

    // Only accept properly formatted relative URLs and absolute http(s) URLs.
    $href = clean_param(trim($linknode->getAttribute('href')), PARAM_URL);
    if ($href === '') {
        return '';
    }

    // Copy the remaining harmless attributes, but nothing else (especially no event handler attributes).
    $attributes = ['rel' => implode(' ', $reltokens), 'href' => $href];
    foreach (['type', 'media', 'crossorigin', 'integrity', 'title'] as $attributename) {
        if ($linknode->hasAttribute($attributename)) {
            $attributes[$attributename] = $linknode->getAttribute($attributename);
        }
    }

    return html_writer::empty_tag('link', $attributes);
}
