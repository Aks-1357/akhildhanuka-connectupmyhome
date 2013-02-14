<?php
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'cron'.DS.'cron.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');

class FSSCronAutoClose extends FSSCron
{
	function Execute($aparams)
	{
		$this->Log("Auto closing tickets");

		$db =& JFactory::getDBO();

		$can_close = FSS_Ticket_Helper::GetStatusIDs('can_autoclose');
		$def_close = FSS_Ticket_Helper::GetStatusID('def_closed');
		
		//echo "Can Close : " . implode(", ", $can_close) . "<br>";
		//echo "Close To : " . $def_close . "<br>";
		
		$now = FSS_Helper::CurDate();
		// if no audit log to be created, then can just close all tickets in a single query, this is quicker!
		if (!$aparams['addaudit'] && !$aparams['emailuser'])
		{
			
			$qry = "UPDATE #__fss_ticket_ticket SET closed = '{$now}', ticket_status_id = $def_close WHERE DATE_ADD(`lastupdate` ,INTERVAL " . FSSJ3Helper::getEscaped($db, $aparams['closeinterval']) . " DAY) < '{$now}' AND ticket_status_id IN (" . implode(", ", $can_close) . ")";
			$db->setQuery($qry);
			$db->Query(); // UNCOMMENT

			$rows = $db->getAffectedRows();
			$this->Log($qry); // COMMENT
			$this->Log("Auto closed $rows tickets");
			return;
		}

		//echo "Not Yet!";
		//exit;
		$qry = "SELECT * FROM #__fss_ticket_ticket WHERE DATE_ADD(`lastupdate` ,INTERVAL " . FSSJ3Helper::getEscaped($db, $aparams['closeinterval']) . " DAY) < '{$now}' AND ticket_status_id IN (" . implode(", ", $can_close) . ")";
		$db->setQuery($qry);
		$rows = $db->loadAssocList();
		/*echo "<pre style='background-color:white;'>";
		echo $qry;
		print_r($rows);
		echo "</pre>";*/
		$this->Log("Found ".count($rows)." tickets to close");

		if (count($rows) == 0)
			return;

		$ids = array();

		$auditrows = array();

		foreach($rows as $row)
		{
			$ids[] = FSSJ3Helper::getEscaped($db, $row['id']);

			if ($aparams['addaudit'])
			{
				// add audit log to the ticket	
				$auditqry[] = "(".FSSJ3Helper::getEscaped($db, $row['id']).", 'Audit Message', 'Ticket auto-closed after ".FSSJ3Helper::getEscaped($db, $aparams['closeinterval'])." days of inactivity', 0, 3, '{$now}')";
			}
			
			if ($aparams['emailuser'])
			{
				//print_p($row);	
				FSS_EMail::Admin_AutoClose($row);
				//exit;
			}
		}
			
		if ($aparams['addaudit'])
		{
			$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted) VALUES \n";
			$qry .= implode(",\n ",$auditqry);
			$db->setQuery($qry);
			/*echo "<pre style='background-color:white;'>";
			echo $qry;
			echo "</pre>";*/
			$db->Query();
		}
		
		$qry = "UPDATE #__fss_ticket_ticket SET closed = '{$now}', ticket_status_id = $def_close WHERE id IN (" . implode(", ",$ids) . ")";
		$db->setQuery($qry);
		$db->Query();
		
		$this->Log("Closed ".count($rows)." tickets");
		/*echo "<pre style='background-color:white;'>";
		echo $qry;
		echo "</pre>";*/
	}
}