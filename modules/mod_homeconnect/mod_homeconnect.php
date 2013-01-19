<?php
/**
 * Joomla! 2.5.* module mod_homeconnect
 * @author Akshay
 * @package Joomla
 * @subpackage Homeconnect
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );

$hello = modHomeconnectHelper::getHello( $params );
require( JModuleHelper::getLayoutPath( 'mod_homeconnect' ) );
?>