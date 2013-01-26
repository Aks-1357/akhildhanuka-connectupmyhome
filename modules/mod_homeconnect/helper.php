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

class modHomeconnectHelper
{
	/* Aks : Example
	function getHello( $params )
	{
		return 'Your helper text';
	}
	*/

	public function getHomePageText()
	{
		$db = JFactory::getDbo();

		$query = " SELECT `home_text` FROM `#__homeconnect` LIMIT 0,1 ";

		$db->setQuery($query);

		return $db->loadResult();
	}
}
?>