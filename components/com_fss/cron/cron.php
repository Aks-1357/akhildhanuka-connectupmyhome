<?php
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'settings.php' );

class FSSCron
{
	var $_log;

	function Log($message)
	{
		$this->_log .= $message."<br>";
	}

	function SaveLog()
	{
		$db =& JFactory::getDBO();
		$class = get_class($this);
		$class = str_ireplace("FSSCron","",$class);
		$now = FSS_Helper::CurDate();
		
		$qry = "INSERT INTO #__fss_cron_log (cron, `when`, log) VALUES ('".FSSJ3Helper::getEscaped($db, $class)."', '{$now}', '" . FSSJ3Helper::getEscaped($db, $this->_log) . "')";
		$db->SetQuery($qry);
		$db->Query();
		//echo $qry."<br>";
		
		$qry = "DELETE FROM #__fss_cron_log WHERE `when` < DATE_SUB('{$now}', INTERVAL ".(int)FSS_Settings::get('support_cronlog_keep')." DAY)";
		$db->SetQuery($qry);
		$db->Query();		
	}

}