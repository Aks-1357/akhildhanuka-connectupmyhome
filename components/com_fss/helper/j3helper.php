<?php

if (!class_exists("JControllerLegacy"))
{
	jimport( 'joomla.application.component.view');
	jimport( 'joomla.application.component.model');
	jimport( 'joomla.application.component.controller');
	class JControllerLegacy extends JController {}
	class JModelLegacy extends JModel {}
	class JViewLegacy extends JView {}
}

class FSSJ3Helper
{
	static function IsJ3()
	{
		$version = new JVersion();
		if ($version->RELEASE >= 3)
		{
			return true;
		} else {
			return false;
		}
	}
	
	function getEscaped(&$db, $string)
	{
		if (!$db) $db =& JFactory::getDBO();
		if (FSSJ3Helper::IsJ3())
		{
			return $db->escape($string);
		} else {
			return $db->getEscaped($string);
		}	
	}
	
	function loadResultArray(&$db)
	{
		if (FSSJ3Helper::IsJ3())
		{
			$rows = $db->loadAssocList();
			$result = array();
			foreach ($rows as &$row)
			{
				foreach ($row as $value)
				{
					$result[] = $value;	
				}			
			}
			return $result;
		} else {
			return $db->loadResultArray();
		}		
	}
}