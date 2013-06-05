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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);
if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php'))
{
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );

	global $posdata, $date_format;

	$css = FSSRoute::x( "index.php?option=com_fss&view=css&layout=default" );
	$document =& JFactory::getDocument();
	$document->addStyleSheet($css); 
	$css = "modules/mod_fss_kbart/tmpl/mod_fss_kbart.css";
	$document->addStyleSheet($css); 
	if (!FSSJ3Helper::IsJ3())
	{
		if (!JFactory::getApplication()->get('jquery')) {
			JFactory::getApplication()->set('jquery', true);
			$document->addScript( JURI::root().'components/com_fss/assets/js/jquery.1.8.3.min.js' );
		}
	}
	$document->addScript( JURI::base().'components/com_fss/assets/js/jquery.autoscroll.js' );

	$which_arts = $params->get('which_arts');
	$prodid = $params->get('prodid');
	$catid = $params->get('catid');
	$dispcount = $params->get('dispcount');
	$maxheight = $params->get('maxheight');
	$date_format = $params->get('date_format');

	$db =& JFactory::getDBO();

	$qry = "SELECT * FROM #__fss_kb_art";

	$where = array();
	$where[] = "published = 1";

	// for cats
	if ($catid > 0)
	{
		$where[] = "kb_cat_id = " .  FSSJ3Helper::getEscaped($db, $catid);
	}

	if ($prodid > 0)
	{
		$where[] = "(allprods = 1 OR id IN (SELECT kb_art_id FROM #__fss_kb_art_prod WHERE prod_id = ".FSSJ3Helper::getEscaped($db, $prodid)."))";
	}

	if (count($where) > 0)
	{
		$qry .= " WHERE " . implode(" AND ",$where);	
	}

	$order = "";
	if ($which_arts == "recent")
	{
		$order = "modified DESC";
	} else if ($which_arts == "recent_added")
	{
		$order = "created DESC";
	} else if ($which_arts == "viewed")
	{
		$order = "views DESC";	
	} else if ($which_arts == "rated")
	{
		$order = "rating DESC";	
	} else {
		$order = "RAND()";	
	}

	$qry .= " ORDER BY $order ";


	if ($dispcount > 0)
		$qry .= " LIMIT $dispcount";

	$db->setQuery($qry);
	$data = $db->loadObjectList();

	$posdata = array();
	// build extra info pos data
	$opts = array('author', 'date', 'added', 'rating', 'views');

	foreach ($opts as $opt)
	{
		$position = $params->get('show_' . $opt);
		$posdata[$position][] = $opt;
	}

	// add a blank center column if none exists and there are bottom left/right
	if (array_key_exists('below_left', $posdata) || array_key_exists('below_right', $posdata))
	{
		if (!array_key_exists('below_center', $posdata))
			$posdata['below_center'][] = 'blank';	
	}

	// function to show extra info pos data
	if (!function_exists("kb_mod_show_extra"))
	{
		function kb_mod_show_extra($row, $pos)
		{
			global $posdata, $date_format;
			
			if (!array_key_exists($pos, $posdata))
				return;
			
			$output = "<div class='fss_mod_kbart_$pos'>";
			
			foreach ($posdata[$pos] as $item)
			{
				$output .= "<div class='fss_mod_kbart_$item'>";
				
				switch ($item)
				{
					case 'date':
						if ($row->modified != "0000-00-00 00:00:00")
							$output .= FSS_Helper::Date($row->modified, $date_format);
						break;
					case 'added':
						if ($row->created != "0000-00-00 00:00:00")
							$output .= FSS_Helper::Date($row->created, $date_format);
						break;
					case 'author':
						$user = JFactory::getUser($row->author);
						if ($user->name)
							$output .= "By <span class='fss_mod_kbart_{$item}_inner'>" . $user->name . "</span>";
						break;
					case 'rating':
						$output .= "Rating: <span class='fss_mod_kbart_{$item}_inner'>" . $row->rating . "</span>";
						break;
					case 'views':
						$output .= "<span class='fss_mod_kbart_{$item}_inner'>" . $row->views . "</span> Views";
						break;
					case 'blank':
						$output .= "&nbsp;";
						break;
				}
				
				$output .= "</div>";	
			}
			
			$output .= "</div>";
			
			return $output;
		}	
	}


	require( JModuleHelper::getLayoutPath( 'mod_fss_kbart' ) );
}