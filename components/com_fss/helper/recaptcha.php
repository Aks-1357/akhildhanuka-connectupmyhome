<?php
/**
* @Copyright Freestyle Joomla (C) 2010
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*     
* This file is part of Freestyle Support Portal
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/
?>
<?php

/**
 * The reCAPTCHA server URL's
 */
define("fss_RECAPTCHA_API_SERVER", "http://api.recaptcha.net");
define("fss_RECAPTCHA_API_SECURE_SERVER", "https://api-secure.recaptcha.net");
define("fss_RECAPTCHA_VERIFY_SERVER", "api-verify.recaptcha.net");

// Captcha stuff
global $fss_publickey,$fss_privatekey;
$fss_publickey = FSS_Settings::get('recaptcha_public');
$fss_privatekey = FSS_Settings::get('recaptcha_private');

if (!$fss_publickey) $fss_publickey = "6LcQbAcAAAAAAHuqZjftCSvv67KiptVfDztrZDIL";
if (!$fss_privatekey) $fss_privatekey = "6LcQbAcAAAAAAMBL5-rp10P3UQ31kpRYLhUFTsqK ";


if (!function_exists ("fss__recaptcha_qsencode"))
	require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'recaptcha_api.php');

?>
