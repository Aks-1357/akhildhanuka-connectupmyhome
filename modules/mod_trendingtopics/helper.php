<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:modules/
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class modTrendingTopicHelper
{
    /**
     * Retrieves the hello message
     *
     * @param array $params An object containing the module parameters
     * @access public
     */    
    function getHello( $params )
    {
    	
        return 'Hello, World!';
    }
    function AddModuleScript()
    {
    	$doc= JFactory::getDocument();
    	$jsPath =  JURI::base()."js/";

		$doc->addScript($jsPath.'jquery/jquery-1.8.3.js');
		$doc->addScript($jsPath.'jquery/jquery.carouFredSel-6.0.4-packed.js');
    }
    //function to get the trending topics
    function getTopics()
    {
    	$query="SELECT * FROM #__trendingtopics where state='1' order by #__trendingtopics.id desc limit 0,6 ";
    	$db=JFactory::getDbo();
    	$db->setQuery($query);
    	$topics=$db->loadObjectList();
    	return $topics;
    }
}
?>
