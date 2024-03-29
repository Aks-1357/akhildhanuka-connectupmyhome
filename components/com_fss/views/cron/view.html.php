<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class FssViewCron extends JViewLegacy
{
	function display($tpl = null)
    {
		$db =& JFactory::getDBO();
		
		$test = JRequest::getVar('test',0);
		
		if ($test > 0)
		{
			$qry = "SELECT * FROM #__fss_cron WHERE id = ".FSSJ3Helper::getEscaped($db, $test);
		} else {		
			$qry = "SELECT * FROM #__fss_cron WHERE published = 1 AND ((UNIX_TIMESTAMP() - lastrun) - (`interval` * 60)) > 0";
		}
		$db->setQuery($qry);
		$rows = $db->loadObjectList();
		
		if (!$rows)
			exit;
		
		foreach ($rows as $row)
		{
			$db->setQuery("UPDATE #__fss_cron SET lastrun=UNIX_TIMESTAMP() WHERE id='{$row->id}' LIMIT 1");
			$db->query();

			$class = "FSSCron" . $row->class;
			$file = strtolower($row->class) . ".php";
			$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'cron'.DS;
			if (file_exists($path.$file))
			{
				require_once($path.$file);
				$inst = new $class();
				$inst->Execute($this->ParseParams($row->params));

				if ($test > 0)
				{
					echo "<pre>".$inst->_log."</pre>";	
				} else {
					$inst->SaveLog();
				}
			}
		}		

		exit;
    }

	function ParseParams(&$aparams)
	{
		if (substr($aparams,0,2) == "a:")
		{
			return unserialize($aparams);	
		}
		$out = array();
		$bits = explode(";",$aparams);
		foreach ($bits as $bit)
		{
			if (trim($bit) == "") continue;
			$res = explode(":",$bit,2);
			if (count($res) == 2)
			{
				$out[$res[0]] = $res[1];	
			}
		}
		return $out;	
	}

}
