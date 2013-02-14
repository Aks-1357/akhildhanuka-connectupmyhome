<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class FsssViewCronLog extends JViewLegacy
{
	function display($tpl = null)
	{
		$document =& JFactory::getDocument();
		if (FSS_Helper::Is16())
			JHtml::_('behavior.framework');

		JHTML::_('behavior.tooltip');

		$task = JRequest::getVar('task');
		JToolBarHelper::title( JText::_("Cron_Log"), 'fss_cronlog' );
		JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();
		
		if ($task == "cancellist")
			return $this->BackToEmails();

		if ($task == "clear")
			return $this->ClearCronLog();

		$this->DisplayList();
	}

	function BackToEmails()
	{
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::x('index.php?option=com_fss&view=fsss',false);
		$mainframe->redirect($link);
	}

	function ClearCronLog()
	{
		$db =& JFactory::getDBO();
		$qry = "TRUNCATE #__fss_cron_log";
		$db->SetQuery($qry);
		$db->Query($qry);
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::x('index.php?option=com_fss&view=cronlog',false);
		$mainframe->redirect($link);
	}

	function DisplayList()
	{
		JHTML::_('behavior.modal', 'a.modal');

		$page = JRequest::getVar('page',0);
		$perpage = 20;

		$date = JRequest::getVar('date');
		$qry = "SELECT DATE(`when`) as `date`, DATE(`when`) as `label` FROM #__fss_cron_log GROUP BY `date` ORDER BY `date` DESC";
		$db =& JFactory::getDBO();
		$db->setQuery($qry);
		$dates = array();
		$dates[] = JHTML::_('select.option', '', JText::_("SELECT_DATE"), 'date', 'label');
		$dates = array_merge($dates, $db->loadObjectList());
		$datelist = JHTML::_('select.genericlist',  $dates, 'date', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'date', 'label', $date);
		$this->assignRef('dates',$datelist);
		
		
		$taskname = JRequest::getVar('taskname');
		$qry = "SELECT cron, cron as label FROM #__fss_cron_log GROUP BY cron ORDER BY cron";
		$db =& JFactory::getDBO();
		$db->setQuery($qry);
		$tasks = array();
		$tasks[] = JHTML::_('select.option', '', JText::_("SELECT_TASK"), 'cron', 'label');
		$tasks = array_merge($tasks, $db->loadObjectList());
		$takslist = JHTML::_('select.genericlist',  $tasks, 'taskname', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'cron', 'label', $taskname);
		$this->assignRef('tasks',$takslist);
		

		$qry = "SELECT * FROM #__fss_cron_log ";
		$wheres = array();
		if ($date)
			$wheres[] = " DATE(`when`) = '".FSSJ3Helper::getEscaped($db, $date)."' ";
		if ($taskname)
			$wheres[] = " cron = '".FSSJ3Helper::getEscaped($db, $taskname)."' ";

		if (count($wheres) > 0)
			$qry .= "WHERE " . implode(" AND " , $wheres);
		$qry .= " ORDER BY `when` desc";
		$db =& JFactory::getDBO();
		$db->setQuery($qry);
		$db->query();
		$rowcount = $db->getNumRows();

		if ($rowcount > $perpage)
		{
			$db->setQuery($qry, $page * $perpage, $perpage);
		}
		$rows = $db->loadObjectList();
		
		$this->assignRef('rows',$rows);
		$pagecount = ceil($rowcount / $perpage);

		$this->assignRef('pagecount',$pagecount);
		$this->assignRef('page',$page);
		parent::display();	
	}
}
