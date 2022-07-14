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
 * @package    local_staticpage
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('STATICPAGE_TITLE_H1', 1);
define('STATICPAGE_TITLE_HEAD', 2);

define('STATICPAGE_FORCELOGIN_YES', 1);
define('STATICPAGE_FORCELOGIN_NO', 2);
define('STATICPAGE_FORCELOGIN_GLOBAL', 3);

define('STATICPAGE_PROCESSFILTERS_YES', 1);
define('STATICPAGE_PROCESSFILTERS_NO', 2);

define('STATICPAGE_CLEANHTML_YES', 1);
define('STATICPAGE_CLEANHTML_NO', 2);

define('STATICPAGE_CHECKAVAILABILITY_YES', 1);
define('STATICPAGE_CHECKAVAILABILITY_NO', 2);

define('STATICPAGE_CHECKAVAILABILITY_RESPONSE_SUCCESS', 'success');
define('STATICPAGE_CHECKAVAILABILITY_RESPONSE_FAIL', 'fail');
define('STATICPAGE_CHECKAVAILABILITY_RESPONSE_ERROR', 'error');
define('STATICPAGE_CHECKAVAILABILITY_RESPONSE_DISABLED', 'disabled');
