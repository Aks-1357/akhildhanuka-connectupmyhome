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

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport('joomla.utilities.date');
jimport('joomla.filesystem.file');
//JHTML::_('behavior.mootools');

//##NOT_TEST_START##
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');
//##NOT_TEST_END##

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');


class FssViewAdmin extends JViewLegacy
{
	var $parser = null;
	var $layoutpreview = 0;

    function display($tpl = null)
    {
		JHTML::_('behavior.modal', 'a.modal');

		$user =& JFactory::getUser();
		$this->userid = $user->get('id');

		// remove any admin open stuff
		$_SESSION['admin_create'] = 0;
		$_SESSION['admin_create_user_id'] = 0;
		$_SESSION['ticket_email'] = "";
		$_SESSION['ticket_name'] = "";

		// set up permissions
        $mainframe = JFactory::getApplication();
        $aparams = $mainframe->getPageParameters('com_fss');
		$this->assignRef('permission',FSS_Ticket_Helper::getAdminPermissions());
		//print_p($this->permission);
		$model = $this->getModel();
		$model->_perm_where = FSS_Ticket_Helper::$_perm_where;
		
		// sort layout
        $layout = JRequest::getVar('layout',  JRequest::getVar('_layout', ''));
    	$this->assignRef('layout',$layout);
		 	
//##NOT_TEST_START##
		if ($layout == "moderate")
//##NOT_TEST_END##
			return $this->displayModerate();
			
//##NOT_TEST_START##
		if ($layout == "content")
			return $this->displayContent();
			
		if ($layout == "support")
			return $this->displaySupport();
		
		return $this->displayMain();
//##NOT_TEST_END##
    }
	
//##NOT_TEST_START##
	function displayMain()
	{
		if (!$this->permission['mod_kb'] && !$this->permission['support'] && !$this->permission['artperm'] > 0 && !$this->permission['groups'] > 0)
			return $this->NoPerm();

		// only permission is groups, so forward to the groups page
		if (!$this->permission['mod_kb'] && !$this->permission['support'] && !$this->permission['artperm'] > 0)
		{
			if ($this->permission['groups'] > 0)
			{
				$mainframe = JFactory::getApplication();
				$link = FSSRoute::x('index.php?option=com_fss&view=groups',false);
				$mainframe->redirect($link);					
			}
		}

		$this->GetCounts();
		
		$this->GetArticleCounts();
		
		parent::display();
	}
//##NOT_TEST_END##

	function displayModerate()
	{
		if (!$this->permission['mod_kb'])
			return $this->NoPerm();
		
		$this->GetCounts();
		
		if ($this->comments->Process())
			return;
			
		parent::display();
	}
	
//##NOT_TEST_START##
	function displaySupport()
	{
		if (!$this->permission['support'])
			return $this->NoPerm();


		// add calendar js and css
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'components/com_fss/assets/css/calendar.css'); 
		$document->addStyleSheet(JURI::root().'components/com_fss/assets/css/calendar_omega.css'); 
		$document->addScript(JURI::root().'components/com_fss/assets/js/calendar.js'); 

		$what = JRequest::getVar('what', '');
		
		$preview = JRequest::getVar('preview',0);
		if ($preview == -1)
		{
			$preview = "";
			unset($_SESSION['preview']);
		}
		if ($preview == 1 || (array_key_exists('preview',$_SESSION) && $_SESSION['preview'] == 1))
			$this->SortPreview();

		$def_open = FSS_Ticket_Helper::GetStatusID('def_open');
		$tickets = JRequest::getVar('tickets',$def_open);
		$this->assignRef('ticket_view',$tickets);
		
		if (JRequest::getVar('archive','') != "")
			$this->DoArchive();
			
		if (JRequest::getVar('delete','') != "")
			$this->DoDelete();

		if ($what == "settings")
			return $this->doSettings();

		// handle reply
		if ($what == "search")
			return $this->doSearch();

		// handle reply
		if ($what == "reply")
			return $this->doReply();
		
		// save any replys
		if ($what == 'savereply')
			return $this->saveReply();
			
		// select a user to forward ticket to
		if ($what == 'pickuser')
			return $this->pickUser();
		
		if ($what == "statuschange")
			return $this->saveStatusChanges();
		
		// admin creating new ticket for reg user
		if ($what == "newregistered")
			return $this->NewRegistered();
		
		// new ticket for unreg user
		if ($what == "newunregistered")
			return $this->NewUnRegistered();
	
		// editing of custom fields
		if ($what == "editfield")
			return $this->EditField();	
			
		// save ticket title changes	
		if ($what == "tickettitle")
			return $this->EditTitle();		
		// save ticket title changes	
		
		if ($what == "ticketemail")
			return $this->EditEMail();		
	
		if ($what == "ticketcat")
			return $this->EditCat();
	
		// save any custom fields, dont return, just display ticket
		if ($what == "savefield")
		{
			if ($this->SaveField())
				return;
		}
	
		// editing of signature
		if ($what == "changesig")
			return $this->ChangeSig();		
		
		// save signature
		if ($what == "savesig")
		{
			if ($this->SaveSig())
				return;
		}

		if ($what == "editcomment")
			return $this->EditComment();

		if ($what == "savecomment")
			return $this->SaveComment();

		if ($what == "getsig")
			return $this->GetSig();
	
		if ($what == "removetag")
			return $this->removeTag();

		if ($what == "addtag")
			return $this->addTag();

		if ($what == "lockticket")
			return $this->doLockTicket();

		if ($what == "print")
			return $this->doPrintTicket();

		// do file download
		$fileid = JRequest::getVar('fileid', 0, '', 'int');            
		if ($fileid > 0)
		{
			if ($what == 'attach_thumb')
			{
       			return $this->downloadThumb();   
			} else {
        		return $this->downloadFile();   
			}
		}     
		
		// display indiviual ticket
		$ticketid = JRequest::getVar('ticketid',0, '', 'int');
		if ($ticketid > 0)
			return $this->showTicket();	
			        
		return $this->viewTickets();			
	}

	function downloadThumb()
	{
		$fileid = JRequest::getVar('fileid', 0, '', 'int');            
		
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__fss_ticket_attach WHERE id = "' . FSSJ3Helper::getEscaped($db, $fileid) . '"';
		$db->setQuery($query);
		$row = $db->loadAssoc();
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'simpleimage.php');
		
		$filename = basename($row['filename']);
		$file_extension = strtolower(substr(strrchr($filename,"."),1));
		$ctype = FSS_Helper::datei_mime($file_extension);

		$thumbfile = "components/com_fss/files/support/" . $row['diskfile'] . ".thumb";
		
		// thumb file exists
		if (file_exists($thumbfile))
		{
			ob_end_clean();
			header("Content-Description: File Transfer");
			header("Content-Type: " . $ctype);
			header("Content-Length: ".(string)$row['size']);
			header("Content-Transfer-Encoding: binary\n");
	    
			//echo getcwd(). "<br>";
			$file = "components/com_fss/files/support/" . $row['diskfile'] . ".thumb";
			//echo $file;
			@readfile($file);
			exit;
 			
		}
		
		$im = new SimpleImage();
		$im->load("components/com_fss/files/support/" . $row['diskfile']);
		$im->resize(48, 48);
		$im->save("components/com_fss/files/support/" . $row['diskfile'] . ".thumb");
		
		ob_end_clean();
		header("Content-Description: File Transfer");
		header("Content-Type: " . $ctype);
		header("Content-Length: ".(string)$row['size']);
		header("Content-Transfer-Encoding: binary\n");
	    
		//echo getcwd(). "<br>";
		$file = "components/com_fss/files/support/" . $row['diskfile'] . ".thumb";
		//echo $file;
		@readfile($file);
		exit;
	}
	
	function DoArchive()
	{
		$ticketid = JRequest::getVar('archive',0, '', 'int');
		
		$def_archive = FSS_Ticket_Helper::GetStatusID('def_archive');

		if ($ticketid > 0)
		{
 			$db	=& JFactory::getDBO();
			$qry = "UPDATE #__fss_ticket_ticket SET ticket_status_id = $def_archive WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
		
			$db->setQuery( $qry );
			$db->query();

			$this->AddTicketAuditNote($ticketid,"Archived Ticket");
		} else if (JRequest::getVar('archive','') == "all")
		{
			$is_closed = FSS_Ticket_Helper::GetClosedStatus();

			$db	=& JFactory::getDBO();
			$qry = "SELECT id FROM #__fss_ticket_ticket WHERE ticket_status_id IN ( " . implode(", ", $is_closed) . " )";
			$db->setQuery($qry);
			echo $qry."<br>";
			$ticketlist = $db->loadObjectList();
			foreach($ticketlist as $ticket)
				$this->AddTicketAuditNote($ticket->id,"Archived Ticket");
			
			$qry = "UPDATE #__fss_ticket_ticket SET ticket_status_id = $def_archive WHERE ticket_status_id IN ( " . implode(", ", $is_closed) . " )";
 			echo $qry . "<br>";
			$db->setQuery( $qry );
			$db->query();
		}
	}
	
	function DoDelete()
	{
		$db	=& JFactory::getDBO();
		$ticketid = JRequest::getVar('delete', 0, '', 'int');

		if ($ticketid > 0)
		{
			$this->DeleteTicket($ticketid);
		} else if (JRequest::getVar('delete', '') == 'archived') {
			$def_archive = FSS_Ticket_Helper::GetStatusID('def_archive');
			$qry = "SELECT * FROM #__fss_ticket_ticket WHERE ticket_status_id = $def_archive";
 			$db	=& JFactory::getDBO();
		
			$db->setQuery( $qry );
			$db->query();
			$tickets = $db->loadObjectList();
			
			foreach($tickets as $ticket)
			{
				$this->DeleteTicket($ticket->id);		
			}
			
		} else if (JRequest::getVar('delete', '') == 'closed') {
			$is_closed = FSS_Ticket_Helper::GetClosedStatus();
			$qry = "SELECT * FROM #__fss_ticket_ticket WHERE ticket_status_id IN ( " . implode(", ", $is_closed) . " )";
 			$db	=& JFactory::getDBO();
		
			$db->setQuery( $qry );
			$db->query();
			$tickets = $db->loadObjectList();
				
			foreach($tickets as $ticket)
			{
				$this->DeleteTicket($ticket->id);		
			}
		}
	}
	
	function DeleteTicket($ticketid)
	{
		if ($ticketid < 1)
			return;
			
 		$db	=& JFactory::getDBO();
		
		$ticketid = FSSJ3Helper::getEscaped($db, $ticketid);
		
		$qry = "DELETE FROM #__fss_ticket_ticket WHERE id = '$ticketid'";
 		$db->setQuery( $qry );
		$db->query();
		
		$qry = "DELETE FROM #__fss_ticket_attach WHERE ticket_ticket_id = '$ticketid'";
 		$db->setQuery( $qry );
		$db->query();
		
		$qry = "DELETE FROM #__fss_ticket_messages WHERE ticket_ticket_id = '$ticketid'";
 		$db->setQuery( $qry );
		$db->query();
		
		$qry = "DELETE FROM #__fss_ticket_field WHERE ticket_id = '$ticketid'";
 		$db->setQuery( $qry );
		$db->query();
	}
	
	function pickUser()
	{
		// build query
		$qry = "SELECT * FROM #__users ";
		$where = array();
		
		$limitstart = JRequest::getInt('limitstart',0);
		$mainframe = JFactory::getApplication();
		$limit = $mainframe->getUserStateFromRequest('users.limit', 'limit', 10, 'int');
		$search = JRequest::getString('search','');
		
		$db	=& JFactory::getDBO();
		
		if ($search != "")
		{
			$where[] = "(username LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR name LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR email LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%')";
		}
				
		if (count($where) > 0)
		{
			$qry .= " WHERE " . implode(" AND ", $where);	
		}

		
		// Sort ordering
		$qry .= " ORDER BY name ";
		
		
		// get max items
 		
		$db->setQuery( $qry );
		$db->query();
		$maxitems = $db->getNumRows();
			
		
		// select picked items
		$db->setQuery( $qry, $limitstart, $limit );
		$this->assignRef('users',$db->loadObjectList());

		
		// build pagination
		$this->assignRef('pagination',new JPagination($maxitems, $limitstart, $limit ));
		$this->assignRef('search',$search);
		
		parent::display("users");	
	}
	
	function viewTickets()
	{
		$mainframe = JFactory::getApplication();
		JHTML::_('behavior.tooltip');		
		
		$stuff = $this->get('Tickets');
		$this->assignRef('tickets',$stuff['tickets']);
		$this->assignRef('pagination', $stuff['pagination'] );

		$pathway =& $mainframe->getPathway();
		$pathway->addItem(JText::_("SUPPORT"));
		$showassigned = 1;
		/*if ($this->permission['seeownonly'])
			$showassigned = 0;*/
		
		$this->assign('showassigned',$showassigned);
		$model =& $this->getModel();
		$this->assignRef('alltags',$model->getAllTags());
		$this->getSearchFields();
		$this->GetCounts();
		$model->getTagsPerTicket();
		$model->getAttachPerTicket();
		$model->getGroupsPerTicket();
		
		if (FSS_Settings::get('support_show_msg_counts'))
		{
			$model->getMessageCounts();	
		}
		$this->getLockedUsers();
		$this->getCustomFields();
		$this->getDBTime();
			
		parent::display();	
	}

	function getDBTime()
	{
		/*$query = "SELECT NOW() as DBTime";
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$time = $db->loadObject();*/
		$this->db_time = strtotime(FSS_Helper::CurDate());		
	}
//##NOT_TEST_END##
	
	function GetCounts()
	{
//##NOT_TEST_START##
		$this->assign('ticketopen',$this->get('ticketOpen'));
		$this->assign('ticketfollow',$this->get('ticketFollow'));
		$this->assign('ticketuser',$this->get('ticketUser'));
		$this->assignRef('count',$this->get('TicketCount'));
//##NOT_TEST_END##
			
		$this->contentmod = 0;
		$this->comments = new FSS_Comments(null,null);
		$this->moderatecount = $this->comments->GetModerateTotal();
	}
	
//##NOT_TEST_START##
	function GetArticleCounts()
	{
		$this->artcounts = $this->get('ArticleCounts');
	}
	
	function saveReply()
	{
		$db =& JFactory::getDBO();
		$ticketid = JRequest::getVar('ticketid', 0, '', 'int');
		$model =& $this->getModel();

		$subject = JRequest::getVar('subject','','','string');
		$body = JRequest::getVar('body','','','string', JREQUEST_ALLOWRAW);
		$body2 = JRequest::getVar('body2','','','string', JREQUEST_ALLOWRAW);
		$reply_status = JRequest::getVar('reply_status', 0, '', 'int');
		$forward = JRequest::getInt('forward', 0); 
		$ticket = $model->getTicket($ticketid);
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$userid = $user->get('id');
		$sig = $this->GetUserSig();
		$hidefromuser = JRequest::getVar('hidefromuser', 0);

		$messageid = 0;
		$now = FSS_Helper::CurDate();
		
		if ($body)
		{
			
			

			if (trim($body))
			{
				if ($sig && JRequest::getVar('append_sig'))
				{
					$body .= "\n\n" . $sig;	
				}
			
				$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted) VALUES ('";
				$qry .= FSSJ3Helper::getEscaped($db, $ticketid)."','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."',1, '{$now}')";
			
				$db->SetQuery( $qry );
				$db->Query();
				$messageid = $db->insertid();
			}
			
			$dontassign = JRequest::getVar('dontassign', 0, '', 'int');
			if ($forward == 0 && FSS_Settings::get('support_assign_reply') == 1 && $dontassign == 0)
			{
				if ($ticket['admin_id'] != $this->permission['id'])
				{
					$qry = "UPDATE #__fss_ticket_ticket	SET admin_id = '" . FSSJ3Helper::getEscaped($db, $this->permission['id']) . "' WHERE id = '" . FSSJ3Helper::getEscaped($db, $ticket['id']) . "'";
					$db->SetQuery($qry);
					$db->Query(); 

					$this->AddTicketAuditNote($ticket['id'],"Handler '" . $model->GetUserNameFromFSSUID($this->permission['id']) . "' took ownership of the ticket");
				}
			}
			
			if ($reply_status > 0)
			{
				$isclosed = "";
				if ($reply_status != $ticket['ticket_status_id'])
				{
					$st = FSS_Ticket_Helper::GetStatusByID($reply_status);
					if ($st->is_closed)
					{
						$isclosed = ", closed = '{$now}'";	
					} else {
						$isclosed = ", closed = NULL";	
					}
				}
				$qry = "UPDATE #__fss_ticket_ticket	SET ticket_status_id = '".FSSJ3Helper::getEscaped($db, $reply_status)."' $isclosed WHERE id = '" . FSSJ3Helper::getEscaped($db, $ticket['id']) . "'";
				$db->SetQuery( $qry );
				$db->Query();

				if ($reply_status != $ticket['ticket_status_id'])
				{
					$oldstatus = $model->GetStatus($ticket['ticket_status_id']);
					$newstatus = $model->GetStatus($reply_status);
					$this->AddTicketAuditNote($ticket['id'],"Status changed from '" . $oldstatus['title'] . "' to '" . $newstatus['title'] . "'");
				}

			} 
		}
		
		if (trim($body2))
		{
			if ($sig && JRequest::getVar('append_sig'))
			{
				$body2 .= "\n\n" . $sig;	
			}

			// insert admin reply if forwarded and exists (only happens on forward 1,2,3)
			if ($body2 && $forward > 0)
			{
				$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted) VALUES ('";
				$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body2)."','".FSSJ3Helper::getEscaped($db, $userid)."',2, '{$now}')";
			
				$db->SetQuery( $qry );
				$db->Query();
				
				if ($messageid == 0)
					$messageid = $db->insertid();
			}
		}

		//echo "Forward: $forward<br>";

		// forward to another person
		if ($forward == 1)
		{
			$user_id = JRequest::getInt('user_id',0); 
			$qry = "UPDATE #__fss_ticket_ticket	SET admin_id = '".FSSJ3Helper::getEscaped($db, $user_id)."' WHERE id = '" . FSSJ3Helper::getEscaped($db, $ticket['id']) . "'";
			$db->SetQuery($qry);
			$db->Query();
			//echo $qry."<br>";
			$ticket['admin_id'] = $user_id;
			
			$this->AddTicketAuditNote($ticket['id'],"Forwarded to handler '" . $model->GetUserNameFromFSSUID($user_id) . "'");
			// forward to a department
		} else if ($forward == 2)
		{
			$ticket_dept_id = JRequest::getInt('ticket_dept_id',0); 
			$prod_id = JRequest::getInt('prod_id',0); 
			if ($ticket_dept_id != $ticket['ticket_dept_id'] || $prod_id != $ticket['prod_id'])
			{
				$adminid = FSS_Ticket_Helper::AssignHandler($prod_id, $ticket_dept_id, $ticket['ticket_cat_id']);
				$qry = "UPDATE #__fss_ticket_ticket	SET ticket_dept_id = '".FSSJ3Helper::getEscaped($db, $ticket_dept_id)."', prod_id = '".FSSJ3Helper::getEscaped($db, $prod_id)."', admin_id = '".FSSJ3Helper::getEscaped($db, $adminid)."' WHERE id = '" . FSSJ3Helper::getEscaped($db, $ticket['id']) . "'";
				//echo $qry."<br>";
				$db->setQuery($qry);
				$db->Query();
			
				$messages = array();
				
				if ($ticket_dept_id != $ticket['ticket_dept_id'])
					$messages[] = "Changed department to '" . $model->GetDepartment($ticket_dept_id) . "'";
				if ($prod_id != $ticket['prod_id'])
					$messages[] = "Changed product to '" . $model->GetProduct($ticket_dept_id) . "'";
				if ($adminid)
					$messages[] = "Assigned to handler '" . $model->GetUserNameFromFSSUID($adminid) . "'";
				
				$this->AddTicketAuditNote($ticket['id'],implode(", ", $messages));
			}
			// forward to a user
		} else if ($forward == 4)
		{
			$user_id = JRequest::getInt('user_id',0);
			$qry = "UPDATE #__fss_ticket_ticket	SET user_id = '".FSSJ3Helper::getEscaped($db, $user_id)."' WHERE id = '" . FSSJ3Helper::getEscaped($db, $ticket['id']) . "'";
			$db->setQuery($qry);$db->Query();
			$this->AddTicketAuditNote($ticket['id'],"Forwarded to user '" . $model->GetUserNameFromFSSUID($user_id) . "'");
		}
		
		
		// save any file attachments
		for ($i = 0; $i < 10; $i ++)
		{
			$file = JRequest::getVar('filedata_'.$i, '', 'FILES', 'array');
			if (array_key_exists('error',$file) && $file['error'] == 0 && $file['name'] != '')
			{
				$random = 0;
				$destname = JPATH_COMPONENT_SITE.DS.'files'.DS.'support'.DS.$random.'-'.$file['name'];    
				while (JFile::exists($destname))
				{
					$random++; 
					$destname = JPATH_COMPONENT_SITE.DS.'files'.DS.'support'.DS.$random.'-'.$file['name'];                
				}
			
				if (JFile::upload($file['tmp_name'], $destname))
				{
					$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id, hidefromuser) VALUES ('";
					$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $file['name']) . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $random.'-'.$file['name']) . "',";
					$qry .= "'" . $file['size'] . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $userid) . "',";
					$qry .= "'{$now}', $messageid, '".FSSJ3Helper::getEscaped($db, $hidefromuser)."' )";
				
					//echo $qry." - $res<br>";
					$db->setQuery($qry);$db->Query();     
				} else {
					//echo "Upload failed - $res??";	
				}
			}
		}
		
		$qry = "UPDATE #__fss_ticket_ticket SET lastupdate = '{$now}' WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
		$db->setQuery($qry);$db->Query(); 
		
		$subject = JRequest::getVar('subject','','','string');
		$body = JRequest::getVar('body','','','string', JREQUEST_ALLOWRAW);
		$body2 = JRequest::getVar('body2','','','string', JREQUEST_ALLOWRAW);
		
		
		$this->assignRef('ticket',$model->getTicket($ticketid));
		
		// send emails out
		
		// no forward, just admin reply
		if ($forward == 0)
		{
			// check if closed or not. If closed do the closed reply
			if ($reply_status == 3)
			{
				FSS_EMail::Admin_Close($ticket, $subject, $body);
			} else {
				FSS_EMail::Admin_Reply($ticket, $subject, $body);
			}
		// forward to handler
		} else if ($forward == 1)
		{
			if ($body2)
				FSS_EMail::Admin_Forward($ticket, $subject, $body2);
			else
				FSS_EMail::Admin_Forward($ticket, $subject, $body);
			
			FSS_EMail::Admin_Reply($ticket, $subject, $body);
		// forward to department
		} else if ($forward == 2)
		{
			// only send a mail if admin has been assigned
			if ($this->ticket['admin_id'] > 0)
			{
				if ($body2)
					FSS_EMail::Admin_Forward($ticket, $subject, $body2);
				else
					FSS_EMail::Admin_Forward($ticket, $subject, $body);
			}			
			
			FSS_EMail::Admin_Reply($ticket, $subject, $body);
		// post admin comment
		} else if ($forward == 3)
		{
			// email only needed if not posting to your own ticket
			if ($userid != $this->ticket['admin_id'])
			{
				FSS_EMail::Admin_Forward($ticket, $subject, $body2);
			} 
			
		// forward to user
		} else if ($forward == 4)
		{
			// should always email the user
			FSS_EMail::User_Create($ticket, $subject, $body);
		}				
	
		$mainframe = JFactory::getApplication();
		
		$link = FSSRoute::x('&what=',false);
		
		if ($reply_status > 0)
		{
			$st = FSS_Ticket_Helper::GetStatusByID($reply_status);
			if (FSS_Helper::getUserSetting("return_on_reply") || ($st->is_closed && FSS_Helper::getUserSetting("return_on_close")))
			{
				$link = FSSRoute::x('index.php?option=com_fss&view=admin&layout=support&tickets=open',false);	
			}
		}	
			
					
		$mainframe->redirect($link);		
	}
	
	function saveStatusChanges()
	{
		$ticketid = JRequest::getVar('ticketid', 0, '', 'int');

		$new_status = JRequest::getVar('new_status', 0, '', 'int'); 
		$new_pri = JRequest::getVar('new_pri', 0, '', 'int'); 
		
		$model =& $this->getModel(); 
		$ticket = $model->getTicket($ticketid);
		//print_r($ticket);

		$changed = false;
		$date = false;
		$now = FSS_Helper::CurDate();
		
		if ($new_status != $ticket['ticket_status_id'])
		{
			$oldstatus = $model->GetStatus($ticket['ticket_status_id']);
			$newstatus = $model->GetStatus($new_status);
			$this->AddTicketAuditNote($ticket['id'],"Status changed from '" . $oldstatus['title'] . "' to '" . $newstatus['title'] . "'");
	
			$st = FSS_Ticket_Helper::GetStatusByID($new_status);
			
			if (!$st->is_closed)
				$date = true;
			$changed = true;
			
			// check if new status is closed
			if ($st->is_closed)
			{
				FSS_EMail::Admin_Close($ticket, $ticket['title'], "Ticket Closed");	
				//exit;
			}
		}
		
		if ($new_pri != $ticket['ticket_pri_id'])
		{
			$oldpri = $model->GetPriority($ticket['ticket_pri_id']);
			$newpri = $model->GetPriority($new_pri);
			$this->AddTicketAuditNote($ticket['id'],"Priority changed from '" . $oldpri['title'] . "' to '" . $newpri['title'] . "'");
			
			$date = true;
			$changed = true;
		}
		
		$isclosed = "";
		
		if ($new_status != $ticket['ticket_status_id'])
		{
			$st = FSS_Ticket_Helper::GetStatusByID($this->ticket['ticket_status_id']);
			
			if ($st->is_closed)
			{
				$isclosed = ", closed = '{$now}'";	
			} else {
				$isclosed = ", closed = NULL";	
			}
		}

		if ($changed)
		{
			$datesql = "";
			if ($date)
				$datesql = ", lastupdate = '{$now}'";
			$db =& JFactory::getDBO();
			$qry = "UPDATE #__fss_ticket_ticket SET ticket_pri_id = '".FSSJ3Helper::getEscaped($db, $new_pri)."', ticket_status_id = '".FSSJ3Helper::getEscaped($db, $new_status)."' $isclosed $datesql WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
			$db->setQuery($qry);$db->Query();
		}	
		
		// forward with what=
		$mainframe = JFactory::getApplication();
		if ($new_status == 3 && FSS_Helper::getUserSetting("return_on_close"))
		{
			$link = FSSRoute::x('index.php?option=com_fss&view=admin&layout=support&tickets=open',false);			
		} else {
			$link = FSSRoute::x('&what=&new_status=&new_pri=',false);
		}	
		$mainframe->redirect($link);
	}

	function GetTicketLocked()
	{
		$this->getDBTime();

		$cotime = $this->db_time - strtotime($this->ticket['checked_out_time']);
		$this->locked = false;
		if ($cotime < FSS_Settings::get('support_lock_time') && $this->ticket['checked_out'] != $this->userid && $this->ticket['checked_out'] != 0)
		{
			$this->locked = true;
		}
	}
	function doPrintTicket()
	{
		$mainframe = JFactory::getApplication();
		JHTML::_('behavior.tooltip');		

		$db =& JFactory::getDBO();
		$ticketid = JRequest::getVar('ticketid', 0, '', 'int');
		
		$user =& JFactory::getUser();
		$userid = $user->get('id');
				
		$model =& $this->getModel();

		$this->assignRef('ticket',$model->getTicket($ticketid));
		$this->GetTicketLocked();

		$this->assignRef('messages',$model->getMessages($ticketid));		
		$this->assignRef('attach',$model->getAttach($ticketid));		

		$this->clean = JRequest::getVar('clean', 0, '', 'int');

		if ($this->ticket['user_id'] > 0)
		{
			$this->groups = $model->getUsersGroups($this->ticket['user_id']);
		} else {
			$this->groups = array();	
		}
	
		foreach($this->attach as &$attach)
		{
			$message_id = $attach['message_id'];
			foreach($this->messages as &$message)
			{
				if ($message['id'] == $message_id)
				{
					if (!array_key_exists('attach', $message))
						$message['attach'] = array();
						
					$message['attach'][] = $attach;		
				}	
			}
		}

		$this->assignRef('pris',$this->get('Priorities'));
		$this->assignRef('statuss',$this->get('Statuss'));
		
		$pathway =& $mainframe->getPathway();
       	$pathway->addItem(JText::_("SUPPORT"),FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=' . $this->ticket_view ));
		$pathway->addItem(JText::_("VIEW_TICKET"). " : " . $this->ticket['reference'] . " - " . $this->ticket['title']);
	
		if (FSS_Settings::get( 'support_assign_open' ) == 1)
		{
			if ($this->ticket['admin_id'] == 0)
			{
				$qry = "UPDATE #__fss_ticket_ticket	SET admin_id = '" . FSSJ3Helper::getEscaped($db, $this->permission['id']) . "' WHERE id = '" . FSSJ3Helper::getEscaped($db, $this->ticket['id']) . "'";
				$db->setQuery($qry);$db->Query(); 
				$this->ticket['admin_id'] = $this->permission['id'];
				$this->AddTicketAuditNote($this->ticket['id'],"Handler '" . $model->GetUserNameFromFSSUID($this->permission['id']) . "' took ownership of the ticket by opening it");
			}
		}

		$this->assignRef('adminuser',$model->getAdminUser($this->ticket['admin_id']));
		
		$this->assignRef('fields',FSSCF::GetCustomFields($ticketid,$this->ticket['prod_id'],$this->ticket['ticket_dept_id'],3));
		$this->assignRef('fieldvalues',FSSCF::GetTicketValues($ticketid, $this->ticket));
		
		$this->GetCounts();

		if (!FSS_Settings::get('support_hide_users_tickets'))
		{
			$this->userticketcount = $this->GetUserTicketCounts($this->ticket['user_id'],$this->ticket['email']);
		}

		$this->assignRef('tags',$model->getTags($ticketid));
		$this->assignRef('alltags',$model->getAllTags());

		// get caregory select dropdown
		$db =& JFactory::getDBO();
        $query = "SELECT * FROM #__fss_ticket_cat ORDER BY section, title";

        $db->setQuery($query);
        $this->cats = $db->loadAssocList();
		
		if ($this->locked)
		{
			$this->co_user = JFactory::getUser($this->ticket['checked_out']);	
		}
		if (!$this->locked && FSS_Settings::get('support_lock_time') > 0)
		{
			$this->UpdateCheckout($this->ticket['id'],$userid);
		}
		parent::display("print");	
	}		
		
	function showTicket()
	{
		$mainframe = JFactory::getApplication();
		JHTML::_('behavior.tooltip');		

		$db =& JFactory::getDBO();
		$ticketid = JRequest::getVar('ticketid', 0, '', 'int');
		
		$user =& JFactory::getUser();
		$userid = $user->get('id');
				
		$model =& $this->getModel();

		$this->assignRef('ticket',$model->getTicket($ticketid));
		if (!$this->ticket)
		{
			return JError::raiseWarning(404, JText::_('Ticket not found'));
		}
		$this->GetTicketLocked();

		$this->assignRef('messages',$model->getMessages($ticketid));		
		$this->assignRef('attach',$model->getAttach($ticketid));		

		if ($this->ticket['user_id'] > 0)
		{
			$this->groups = $model->getUsersGroups($this->ticket['user_id']);
		} else {
			$this->groups = array();	
		}
	
		foreach($this->attach as &$attach)
		{
			$message_id = $attach['message_id'];
			foreach($this->messages as &$message)
			{
				if ($message['id'] == $message_id)
				{
					if (!array_key_exists('attach', $message))
						$message['attach'] = array();
						
					$message['attach'][] = $attach;		
				}	
			}
		}

		$this->assignRef('pris',$this->get('Priorities'));
		$this->assignRef('statuss',$this->get('Statuss'));
		
		$pathway =& $mainframe->getPathway();
       	$pathway->addItem(JText::_("SUPPORT"),FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=' . $this->ticket_view ));
		$pathway->addItem(JText::_("VIEW_TICKET"). " : " . $this->ticket['reference'] . " - " . $this->ticket['title']);
	
		if (FSS_Settings::get( 'support_assign_open' ) == 1)
		{
			if ($this->ticket['admin_id'] == 0)
			{
				$qry = "UPDATE #__fss_ticket_ticket	SET admin_id = '" . FSSJ3Helper::getEscaped($db, $this->permission['id']) . "' WHERE id = '" . FSSJ3Helper::getEscaped($db, $this->ticket['id']) . "'";
				$db->setQuery($qry);$db->Query(); 
				$this->ticket['admin_id'] = $this->permission['id'];
				$this->AddTicketAuditNote($this->ticket['id'],"Handler '" . $model->GetUserNameFromFSSUID($this->permission['id']) . "' took ownership of the ticket by opening it");
			}
		}

		$this->assignRef('adminuser',$model->getAdminUser($this->ticket['admin_id']));
		
		$this->assignRef('fields',FSSCF::GetCustomFields($ticketid,$this->ticket['prod_id'],$this->ticket['ticket_dept_id'],3));
		$this->assignRef('fieldvalues',FSSCF::GetTicketValues($ticketid, $this->ticket));
		
		$this->GetCounts();

		if (!FSS_Settings::get('support_hide_users_tickets'))
		{
			$this->userticketcount = $this->GetUserTicketCounts($this->ticket['user_id'],$this->ticket['email']);
		}

		$this->assignRef('tags',$model->getTags($ticketid));
		$this->assignRef('alltags',$model->getAllTags());

		// get caregory select dropdown
		$db =& JFactory::getDBO();
        $query = "SELECT * FROM #__fss_ticket_cat ORDER BY section, title";

        $db->setQuery($query);
        $this->cats = $db->loadAssocList();
		
		if ($this->locked)
		{
			$this->co_user = JFactory::getUser($this->ticket['checked_out']);	
		}
		if (!$this->locked && FSS_Settings::get('support_lock_time') > 0)
		{
			$this->UpdateCheckout($this->ticket['id'],$userid);
		}
		//echo "OK!";
		parent::display("view");	
	}

	function UpdateCheckout($ticketid,$userid)
	{
		$db =& JFactory::getDBO();
		$now = FSS_Helper::CurDate();
		$query = "UPDATE #__fss_ticket_ticket SET checked_out = '".FSSJ3Helper::getEscaped($db, $userid)."', checked_out_time = '{$now}' where id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
		//echo $query."<br>";
		$db->setQuery($query);
		$db->query();		
	}

	function GetUserTicketCounts($userid,$email)
	{
		$db =& JFactory::getDBO();
		
		if ($userid)
		{
			$qry = "SELECT count(*) as cnt, ticket_status_id FROM #__fss_ticket_ticket WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' GROUP BY ticket_status_id";
		} else {
			$qry = "SELECT count(*) as cnt, ticket_status_id FROM #__fss_ticket_ticket WHERE email = '".FSSJ3Helper::getEscaped($db, $email)."' GROUP BY ticket_status_id";
		}
		
		$db->setQuery($qry);
		$rows = $db->loadObjectList();

		$out = array();
		FSS_Ticket_Helper::GetStatusList();
		$out['total'] = 0;
			
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$out[$row->ticket_status_id] = $row->cnt;
				$out['total'] += $row->cnt;
			}
		}
			
		// work out counts for allopen, closed, all, archived
			
		/*$archived = FSS_Ticket_Helper::GetStatusID("def_archive");
		$out['archived'] = $out[$archived];


		$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed", true);
		$out['allopen'] = 0;
		foreach ($allopen as $id)
			$out['allopen'] += $out[$id];
		
			
		$allclosed = FSS_Ticket_Helper::GetClosedStatus();
		$out['allclosed'] = 0;
		foreach ($allclosed as $id)
			$out['allclosed'] += $out[$id];

			
		$all = FSS_Ticket_Helper::GetStatusIDs("def_archive", true);
		$out['all'] = 0;
		foreach ($all as $id)
			$out['all'] += $out[$id];*/

		/*$result = new stdClass();
		$result->total = 0;
		$result->open = 0;
		$result->followup = 0;
		$result->closed = 0;
		$result->awaitinguser = 0;

		foreach($rows as $row)
		{
			$result->total += $row->cnt;

			if ($row->ticket_status_id == 1)
			{
				$result->open = $row->cnt;
			} else if ($row->ticket_status_id == 2)
			{
				$result->followup = $row->cnt;
			} else if ($row->ticket_status_id == 3)
			{
				$result->closed = $row->cnt;
			} else if ($row->ticket_status_id == 4)
			{
				$result->awaitinguser = $row->cnt;
			}
		}*/

		return $out;	
	}
	
	function doSearch()
	{
		$mainframe = JFactory::getApplication();
		JHTML::_('behavior.tooltip');		
	
		$stuff = $this->get('TicketSearch');
		$this->assignRef('tickets',$stuff['tickets']);
		$this->assignRef('pagination', $stuff['pagination'] );
		$this->assignRef('ticket_count', $stuff['count'] );

		$pathway =& $mainframe->getPathway();
		$pathway->addItem(JText::_("SUPPORT"),FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=' . $this->ticket_view ));
		$pathway->addItem(JText::_("SEARCH_RESULTS"));
		$showassigned = 1;
		/*if ($this->permission['seeownonly'])
			$showassigned = 0;*/
		
		$this->assign('showassigned',$showassigned);
		
		$tags = JRequest::getVar('tags');
	
		$tags = trim($tags,';');
		if ($tags)
		{
			$tags = explode(";",$tags);
			$this->assignRef('tags',$tags);
		}

		$model =& $this->getModel();
		$this->assignRef('alltags',$model->getAllTags());
		$this->getSearchFields();
		$this->GetCounts();
		$model->getTagsPerTicket();
		$model->getAttachPerTicket();
		$model->getGroupsPerTicket();
		$this->getLockedUsers();
		
		if (FSS_Settings::get('support_show_msg_counts'))
		{
			$model->getMessageCounts();	
		}
		$this->getCustomFields();
		$this->getDBTime();
		parent::display();	
	}
	
	function doSettings()
	{
		$action = JRequest::getVar('action');
		
		if ($action == "cancel")
		{
			$mainframe = JFactory::getApplication();
			$link = FSSRoute::x('index.php?option=com_fss&view=admin&layout=support',false);
			$mainframe->redirect($link,JText::_('CANCELLED'));	
			
			return;		
		}
		
		if ($action == "save" || $action == "apply")
		{
			$all = FSS_Helper::getUserDefaults();
			
			$values = array();
			foreach ($all as $setting => $value)
			{
				$new = JRequest::getVar($setting, 0);
				$values[] = $setting."=".$new;
			}
			
			$user = JFactory::getUser();
			$userid = $user->get('id');
			
			$save = implode("|",$values);
			
			$db =& JFactory::getDBO();
			
			$qry = "UPDATE #__fss_user SET settings = '".FSSJ3Helper::getEscaped($db, $save)."' WHERE user_id = '$userid'";
			$db->setQuery($qry);
			$db->Query();
			
			$this->_permissions = null;
			
			if ($action == "save")
			{
				$link = FSSRoute::x('index.php?option=com_fss&view=admin&layout=support',false);
			} else {
				$link = FSSRoute::x('index.php?option=com_fss&view=admin&layout=support&what=settings',false);
			}
			$mainframe = JFactory::getApplication();
			$mainframe->redirect($link,JText::_('SETTINGS_SAVED'));			
			return;
		}
		
		$this->GetCounts();
		parent::display("settings");	
	}
	
	function grouping($type,$name,$ticket)
	{
		if (empty($this->group_nest))
		{
			$this->group_nest = array();
			$this->group_nest['prod'] = 0;
			$this->group_nest['dept'] = 0;
			$this->group_nest['cat'] = 0;
			$this->group_nest['group'] = 0;
			$this->group_nest['pri'] = 0;
			
			$base = 0;
			if (FSS_Helper::getUserSetting("group_products"))
			{
				$this->group_nest['prod'] = $base;
				$base++;
			}	
			if (FSS_Helper::getUserSetting("group_departments"))
			{
				$this->group_nest['dept'] = $base;
				$base++;
			}	
			if (FSS_Helper::getUserSetting("group_cats"))
			{
				$this->group_nest['cat'] = $base;
				$base++;
			}	
			if (FSS_Helper::getUserSetting("group_group"))
			{
				$this->group_nest['group'] = $base;
				$base++;
			}	
			if (FSS_Helper::getUserSetting("group_pri"))
			{
				$this->group_nest['pri'] = $base;
				$base++;
			}	
		}
		
		if ($name == "")
		{
			if ($type == "prod")
				$name = JText::_('NO_PRODUCT');
			if ($type == "dept")
				$name = JText::_('NO_DEPARTMENT');	
			if ($type == "cat")
				$name = JText::_('NO_CATEGORY');	
			if ($type == "group")
				$name = JText::_('NO_GROUP');	
		}
		$style = "style='padding-left: " . (16 * $this->group_nest[$type]) . "px;'";
?>

	<div class="fss_ticket_grouping" <?php echo $style;?>>
		<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/<?php echo $type; ?>.png' width="16" height="16" style='position:relative;top:3px;'>
		<?php echo $name; ?>
	</div>

<?php	
	}

	function getSearchFields()
	{
		$this->departments = $this->get('Departments');
		$this->products = $this->get('Products');
		$this->cats = $this->get('Cats');
		$this->handlers = $this->get('Handlers');
		$this->statuss = $this->get('Statuss');
		$this->priorities = $this->get('Priorities');
		$this->groups = $this->get('Groups');
	}	
	
	function doReply()
	{
		$mainframe = JFactory::getApplication();
		
		$ticketid = JRequest::getVar('ticketid', 0, '', 'int');
		$this->assign('ticketid',$ticketid);
		$model =& $this->getModel();
		$this->assignRef('ticket',$model->getTicket($ticketid));
		if (!$this->ticket)
		{
			return JError::raiseWarning(404, JText::_('Ticket not found'));
		}
		
		$pathway =& $mainframe->getPathway();
       	$pathway->addItem(JText::_("SUPPORT"),FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=' . $this->ticket_view ));
		$pathway->addItem(JText::_("VIEW_TICKET")." : " . $this->ticket['reference'] . " - " . $this->ticket['title'],FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=' . $this->ticket_view . "&ticketid=" . $this->ticket['id']));
		
		$forward = JRequest::getInt('forward',0);
		$this->assign('forward',$forward);
		
		if ($forward == 1)
		{
			$users =& $this->get("AdminUsers");
			$this->assignRef('handlers',JHTML::_('select.genericlist',  $users, 'user_id', 'class="inputbox" size="1" ', 'admin_id', 'name'));
			$pathway->addItem(JText::_("FORWARD_TO_HANDLER"));
		} else if ($forward == 2)
		{
			$departments =& $this->get("Departments");
			FSS_Helper::Tr($departments);
			$this->assignRef('departments',JHTML::_('select.genericlist',  $departments, 'ticket_dept_id', 'class="inputbox" size="1" ', 'id', 'title', $this->ticket['ticket_dept_id']));
			$products =& $this->get("Products");
			FSS_Helper::Tr($products);
			if (count($products) > 0)
			{
				$this->assignRef('products',JHTML::_('select.genericlist',  $products, 'prod_id', 'class="inputbox" size="1" ', 'id', 'title', $this->ticket['prod_id']));
			} else {
				$this->products = false;	
			}
			$pathway->addItem(JText::_("FORWARD_TO_DEPARTMENT"));
		} else if ($forward == 3)
		{
			$pathway->addItem(JText::_("ADD_COMMENT"));
		} else if ($forward == 4)
		{
			$user = $model->getUser($this->ticket['user_id']);
			if (!is_array($user))
			{
				$user['username'] = $this->ticket['email'];	
				$user['name'] = $this->ticket['unregname'];	
			}
			$this->assignRef('user',$user);

			$pathway->addItem(JText::_("FORWARD_TO_USER"));
		} else {
			$pathway->addItem(JText::_("POST_REPLY"));
		}
		
		$this->assignRef('messages',$model->getMessages($ticketid));		
		$this->assign('support_assign_reply',FSS_Settings::get('support_assign_reply'));

		$this->assignRef('tags',$model->getTags($ticketid));
		$this->assignRef('alltags',$model->getAllTags());

		$this->GetCounts();
		parent::display("reply");			
	}

	function EditTitle()
	{
		$ticketid = JRequest::getVar('ticketid',0, '', 'int');
		$title = JRequest::getVar('title','', '', 'string');
		//echo "Edit Title $ticketid, $title<br>";

		$db =& JFactory::getDBO();

		$model =& $this->getModel();
		$ticket = $model->getTicket($ticketid);
		
		$qry = "UPDATE #__fss_ticket_ticket SET title = '" . FSSJ3Helper::getEscaped($db, $title) . "' WHERE id = '" . FSSJ3Helper::getEscaped($db, $ticketid) . "'";
		$db->setQuery($qry);
		$db->Query();
		
		$this->AddTicketAuditNote($ticketid,"Ticket title changed from '" . $ticket['title'] . "' to '" . $title . "'");

		echo $title;
		exit;
	}
	
	function EditEMail()
	{
		$ticketid = JRequest::getVar('ticketid',0, '', 'int');
		$email = JRequest::getVar('email','', '', 'string');
		//echo "Edit Title $ticketid, $title<br>";

		$db =& JFactory::getDBO();

		$qry = "UPDATE #__fss_ticket_ticket SET email = '" . FSSJ3Helper::getEscaped($db, $email) . "' WHERE id = '" . FSSJ3Helper::getEscaped($db, $ticketid) . "'";
		$db->setQuery($qry);
		$db->Query();
		
		echo $email;

		exit;
	}
	
	function CanEditField($field)
	{
		if (is_array($field) && $field['type'] == "plugin")
		{
			$aparams = FSSCF::GetValues($field);
			$plugin = FSSCF::get_plugin($aparams['plugin']);
			if (!$plugin->CanEdit())
				return false;
		}

		return true;
	}
	
	function EditCat()
	{
		$ticketid = JRequest::getVar('ticketid',0, '', 'int');
		$catid = JRequest::getVar('catid','', '', 'string');
		//echo "Edit Title $ticketid, $title<br>";

		$db =& JFactory::getDBO();

		$qry = "UPDATE #__fss_ticket_ticket SET ticket_cat_id = '" . FSSJ3Helper::getEscaped($db, $catid) . "' WHERE id = '" . FSSJ3Helper::getEscaped($db, $ticketid) . "'";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "SELECT title FROM #__fss_ticket_cat WHERE id = '".FSSJ3Helper::getEscaped($db, $catid)."'";
		$db->setQuery($qry);
		$cat = $db->loadObject();
		echo $cat->title;

		exit;
	}
	
	function EditField()
	{
		$ticketid = JRequest::getVar('ticketid',0, '', 'int');
		$model =& $this->getModel();
		$this->assignRef('ticket',$model->getTicket($ticketid));
		
		$this->assignRef('fields',FSSCF::GetCustomFields($ticketid,$this->ticket['prod_id'],$this->ticket['ticket_dept_id'],3));
		$this->assignRef('fieldvalues',FSSCF::GetTicketValues($ticketid, $this->ticket));

		$fieldid = JRequest::getVar('editfield',0,'','int');
		
		$this->assign('field','');
		$this->assign('fieldvalue','');
		$errors = array();
		$this->assignRef('errors',$errors);
		
		foreach($this->fields as &$field)
		{
			if ($field['id'] == $fieldid)
				$this->assignRef('field',$field);
		}

		foreach($this->fieldvalues as &$fieldvalue)
		{
			if ($fieldvalue['field_id'] == $fieldid)
			{
				JRequest::setVar('custom_' . $fieldid,$fieldvalue['value']);
			}
		}

		$this->assign('fieldid',$fieldid);

		parent::display("editfield");	    
	}

	function ChangeSig()
	{
		parent::display("changesig");	    
	}

	function SaveSig()
	{
		$db =& JFactory::getDBO();
  		$sig = JRequest::getVar('signature');
		$user = JFactory::getUser();
		$userid = $user->get('id');

		$qry = "UPDATE #__fss_user SET signature = '".FSSJ3Helper::getEscaped($db, $sig)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."'";
		$db->setQuery($qry);$db->Query();
?>
	<script>
		parent.reloadSig();
	</script>
<?php
		exit;
	}

	function GetSig()
	{
		echo str_replace("\n","<br />", $this->GetUserSig());
		exit;	
	}
	
	function saveField()
	{
		$ticketid = JRequest::getVar('ticketid',0, '', 'int');
		$model =& $this->getModel();
		$ticket = $model->getTicket($ticketid);			
		$this->assignRef('fields',FSSCF::GetCustomFields($ticketid,$ticket['prod_id'],$ticket['ticket_dept_id'],3));
		$savefield = JRequest::getVar('savefield',0,'','int');
		list($old, $new) = FSSCF::StoreField($savefield, $ticketid, $ticket);	
			
		if ($old != $new)
		{
			$field = FSSCF::GetField($savefield);
			if ($field->type == 'checkbox')
			{
				if ($old == "") $old = "No";
				if ($old == "on") $old = "Yes";	
				if ($new == "") $new = "No";
				if ($new == "on") $new = "Yes";	
			}
			$this->AddTicketAuditNote($ticketid,"Custom field '" . $field->description . "' changed from '" . $old . "' to '" . $new . "'");
		}

		echo "<script>parent.window.location.reload();</script>";
		exit;
	}

	function downloadFile()
	{
        $fileid = JRequest::getVar('fileid', 0, '', 'int');            
		
        $db =& JFactory::getDBO();
    	$query = 'SELECT * FROM #__fss_ticket_attach WHERE id = "' . FSSJ3Helper::getEscaped($db, $fileid) . '"';
        $db->setQuery($query);
        $row = $db->loadAssoc();
        
		$user = JFactory::GetUser($row['user_id']);      
        $filename = basename($row['filename']);
		
		$type = FSS_Settings::get('support_filename');
		
		switch ($type)
		{
			case 1:
				$filename = $user->username . "_" . $filename;
				break;
			case 2:
				$filename = $user->username . "_" . date("Y-m-d") . "_" . $filename;
				break;	
			case 3:
				$filename = date("Y-m-d") . "_" . $user->username . "_" . $filename;
				break;	
			case 4:
				$filename = date("Y-m-d") . "_" . $filename;
				break;	
		}
		
	    $file_extension = strtolower(substr(strrchr($filename,"."),1));
	    $ctype = FSS_Helper::datei_mime($file_extension);
	    ob_end_clean();
	    $file = "components/com_fss/files/support/" . $row['diskfile'];
	    header("Cache-Control: public, must-revalidate");
	    header('Cache-Control: pre-check=0, post-check=0, max-age=0');
	    header("Pragma: no-cache");
	    header("Expires: 0"); 
	    header("Content-Description: File Transfer");
	    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	    header("Content-Type: " . $ctype);
	    //header("Content-Length: ".(string)filesize($file));
	    header('Content-Disposition: attachment; filename="'.$filename.'"');
	    header("Content-Transfer-Encoding: binary\n");
	    
	    //echo getcwd(). "<br>";
	    //echo $file;
	    @readfile($file);
	    exit;
  	}
	
	function NewRegistered()
	{
		$this->GetCounts();
		parent::display("newreg");	    
	}
	
	function NewUnRegistered()
	{
		$this->GetCounts();
		parent::display("newunreg");	    
	}

	function GetUserSig()
	{
        $db =& JFactory::getDBO();
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$query = 'SELECT * FROM #__fss_user WHERE user_id = "' . FSSJ3Helper::getEscaped($db, $userid) . '"';
        $db->setQuery($query);
        $row = $db->loadObject();
		return $row->signature;
	}

	function removeTag()
	{
		$tag = JRequest::getVar('tag');
		$ticketid = JRequest::getVar('ticketid','','int');
	    $db =& JFactory::getDBO();
		$qry = "DELETE FROM #__fss_ticket_tags WHERE ticket_id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' AND tag = '".FSSJ3Helper::getEscaped($db, $tag)."'";
		$db->setQuery($qry);$db->Query();
		$model =& $this->getModel();
		$this->assignRef('tags',$model->getTags($ticketid));
		parent::display("tags");
		$this->AddTicketAuditNote($ticketid,"Remove Tag '$tag'");
		exit;	    
	}

	function addTag()
	{
		$tag = JRequest::getVar('tag');
		$ticketid = JRequest::getVar('ticketid','','int');
		$db =& JFactory::getDBO();
		$qry = "REPLACE INTO #__fss_ticket_tags (ticket_id, tag) VALUES ('".FSSJ3Helper::getEscaped($db, $ticketid)."', '".FSSJ3Helper::getEscaped($db, $tag)."')";
		$db->setQuery($qry);$db->Query();
		$model =& $this->getModel();
		$this->assignRef('tags',$model->getTags($ticketid));
		parent::display("tags");	
		$this->AddTicketAuditNote($ticketid,"Add Tag '$tag'");
		exit;    
	}

	function doLockTicket()
	{
		$ticketid = JRequest::getVar('ticketid','','int');
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$this->UpdateCheckout($ticketid, $userid);
		//echo "Locking Ticket : " . date("d-m-Y H:i:s") . "<br>";
		exit;
	}

	
	function getLockedUsers()
	{
		if (empty($this->tickets) || count($this->tickets) == 0)
			return;
	
		if (empty($this->db_time))
			$this->getDBTime();

		foreach($this->tickets as &$ticket)
		{
			$cotime = $this->db_time - strtotime($ticket['checked_out_time']);
			if ($cotime < FSS_Settings::get('support_lock_time') && $ticket['checked_out'] != $this->userid)
			{
				$ticket['co_user'] = JFactory::getUser($ticket['checked_out']);
			}
		}
	}

	function AddTicketAuditNote($ticketid,$note)
	{
		if ($ticketid < 1)
		{
			echo "ERROR: AddTicketAuditNote called with no ticket id ($note)<br>";
			exit;	
		}
	    $db =& JFactory::getDBO();
		$now = FSS_Helper::CurDate();
		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted) VALUES ('";
		$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','Audit Message','".FSSJ3Helper::getEscaped($db, $note)."','{$this->userid}',3, '{$now}')";
			
  		$db->SetQuery( $qry );
		//echo $qry. "<br>";
		$db->Query();
		//echo "Audit: $ticketid - $note<br>";	
		//exit;
	}

	function OutputHeader()
	{
		if (empty($this->parser))
			$this->parser = new FSSParser();

		if (empty($this->db_time))
			$this->getDBTime();

		if ($this->layoutpreview)
		{
			$this->parser->Load('preview',1);
		} else {
			$this->parser->Load(FSS_Settings::get('support_list_template'),1);
		}
		$this->parser->showassigned = $this->showassigned;
		$this->parser->ticket_view = $this->ticket_view;
		$this->parser->customfields = $this->customfields;
		$this->parser->ParserPopulateTicket($this->parser,null);
		echo $this->parser->Parse();
	}

	function OutputRow(&$row)
	{
		if (empty($this->parser))
			$this->parser = new FSSParser();

		if (empty($this->db_time))
			$this->getDBTime();

		$this->parser->userid = $this->userid;
		$this->parser->db_time = $this->db_time;

		if ($this->layoutpreview)
		{
			$this->parser->Load('preview',0);
		} else {
			$this->parser->Load(FSS_Settings::get('support_list_template'),0);
		}
		
		$this->parser->customfields = $this->customfields;
		$this->parser->ParserPopulateTicket($this->parser,$row);
		echo $this->parser->Parse();
	}
	
	function getCustomFields()
	{
		$this->customfields = FSSCF::GetAllCustomFields(true);
		
		if (count($this->tickets) == 0)
			return;

		$db =& JFactory::getDBO();
		
		$ids = array();
		foreach($this->tickets as &$ticket)
		{
			$ids[] = FSSJ3Helper::getEscaped($db, (int)$ticket['id']);
		}
		
		$qry = "SELECT * FROM #__fss_ticket_field WHERE ticket_id IN (" . implode(" , ", $ids) . ")";
		$db->setQuery($qry);
		$rows = $db->loadAssocList();

		foreach ($rows as $row)
		{
			if (array_key_exists($row['ticket_id'],$this->tickets))
			{
				$this->tickets[$row['ticket_id']]['custom'][$row['field_id']] = $row['value'];	
			}
		}
	}

	function EditComment()
	{
		$messageid = JRequest::getInt('messageid');
		
		$model =& $this->getModel();
		$this->assignRef('message',$model->getMessage($messageid));		
		parent::display('editcomment');
	}

	function SaveComment()
	{
		$messageid = JRequest::getInt('messageid'); 
		$subject = JRequest::getstring('subject'); 

		$body = JRequest::getVar('body','','','string', JREQUEST_ALLOWRAW);
		
		$db =& JFactory::getDBO();

		$qry = "SELECT * FROM #__fss_ticket_messages WHERE id = " . FSSJ3Helper::getEscaped($db, $messageid);
		$db->setQuery($qry);
		$row = $db->LoadAssoc();

		if ($row['subject'] != $subject)
		{
			$this->AddTicketAuditNote($row['ticket_ticket_id'],"Message on " . $row['posted'] . ", subject changed from '".$row['subject']."'");
		} 
		if ($row['body'] != $body)
		{
			$this->AddTicketAuditNote($row['ticket_ticket_id'],"Message on " . $row['posted'] . ", body changed from '".$row['body']."'");
		} 

		$qry = "UPDATE #__fss_ticket_messages SET subject = '".FSSJ3Helper::getEscaped($db, $subject)."', body = '".FSSJ3Helper::getEscaped($db, $body)."' WHERE id = " . FSSJ3Helper::getEscaped($db, $messageid);
		$db->setQuery($qry);
		$db->Query($qry);
		//echo $qry."<br>";
		/*$mainframe = JFactory::getApplication();
		$link = FSSRoute::x('&what=&new_status=&new_pri=',false);
		$mainframe->redirect($link);*/
		//echo "<script>parent.window.location.reload();</script>";
		exit;
				
	}

	function SortPreview()
	{
		$_SESSION['preview'] = 1;
		$this->layoutpreview = 1;

		echo "<div class='fss_layout_preview'><a href='" . FSSRoute::x('&preview=-1') . "'>List Preview - Click to close</a></div>";	

		$list_template = JRequest::getVar('list_template');
		$list_head = JRequest::getVar('list_head', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$list_row = JRequest::getVar('list_row', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$db =& JFactory::getDBO();

		if ($list_template)
		{
			if ($list_template == "custom")
			{
				$qry = "REPLACE INTO #__fss_templates (template, tpltype, value) VALUES ('preview',0,'" . FSSJ3Helper::getEscaped($db, $list_row) . "')";
				$db->setQuery($qry);
				$db->Query();
				$qry = "REPLACE INTO #__fss_templates (template, tpltype, value) VALUES ('preview',1,'" . FSSJ3Helper::getEscaped($db, $list_head) . "')";
				$db->setQuery($qry);
				$db->Query();
			} else {
				$qry = "SELECT tpltype, value FROM #__fss_templates WHERE template = '".FSSJ3Helper::getEscaped($db, $list_template)."'";
				$db->setQuery($qry);
				$rows = $db->loadAssocList();
				foreach($rows as $row)
				{
					$qry = "REPLACE INTO #__fss_templates (template, tpltype, value) VALUES ('preview',".FSSJ3Helper::getEscaped($db, $row['tpltype']).",'" . FSSJ3Helper::getEscaped($db, $row['value']) . "')";
					$db->setQuery($qry);
					$db->Query();	
				}
			}
		}
	}
//##NOT_TEST_END##
	
	function NoPerm()
	{
		//echo "needLogin : Current Layout : " . $this->getLayout() . "<br>";
		if (array_key_exists('REQUEST_URI',$_SERVER))
		{
			$url = $_SERVER['REQUEST_URI'];//JURI::current() . "?" . $_SERVER['QUERY_STRING'];
		} else {
			$option = JRequest::getString('option','');
			$view = JRequest::getString('view','');
			$layout = JRequest::getString('layout','');
			$Itemid = JRequest::getInt('Itemid',0);
			$url = FSSRoute::x("index.php?option=" . $option . "&view=" . $view . "&layout=" . $layout . "&Itemid=" . $Itemid); 	
		}

		$url = str_replace("&what=find","",$url);
		$url = base64_encode($url);

		$this->assignRef('return',$url);

		$this->setLayout("noperm");
		parent::display();
	}
	
//##NOT_TEST_START##
	function displayContent()
	{
		if (!$this->permission['artperm'])
			return $this->NoPerm();
			
		$this->GetCounts();
		$this->GetArticleCounts();

		if ($this->permission['artperm'] < 1)
		{
			echo JText::_("YOU_DO_NOT_HAVE_PERMISSION_TO_PERFORM_AND_SUPPORT_ADMINISTRATION_ACTIVITIES");
			return;
		}
		$type = JRequest::getVar('type','');
		$this->type = $type;
		
		$content = null;
		if ($type)
		{
			require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content'.DS.$type.'.php');
			$class = "FSS_ContentEdit_$type";
			$content = new $class();
		}

		// output actuall page
		if ($content)
		{
			$content->ticketopen = $this->ticketopen;
			$content->ticketfollow = $this->ticketfollow;
			$content->ticketuser = $this->ticketuser;
			$content->count = $this->count;
			$content->contentmod = $this->contentmod;
			$content->comments = $this->comments;
			$content->moderatecount = $this->moderatecount;
			$content->layout = $this->layout;
			$content->type = $this->type;
			
			$content->Display();	
		} else {
			//echo "Overview";
			parent::display();
		}
	}
		
//##NOT_TEST_END##

}

