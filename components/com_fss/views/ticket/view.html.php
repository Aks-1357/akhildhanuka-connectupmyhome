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
jimport('joomla.filesystem.file');
jimport('joomla.utilities.date');
//JHTML::_('behavior.mootools');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'paginationex.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php');

class FssViewTicket extends JViewLegacy
{
    function display($tpl = null)
    {
		JHTML::_('behavior.modal', 'a.modal');

		$user =& JFactory::getUser();
		$this->userid = $user->get('id');
		
		$this->ticket = null;
		$this->assign('tmpl','');
		
		$what = JRequest::getVar('what','');
		$layout = JRequest::getVar('layout','');
		
		// reset the login and password
		if ($what == "reset")
		{
			$_SESSION['ticket_email'] = "";
			$_SESSION['ticket_name'] = "";
			$_SESSION['ticket_pass'] = "";
		}

		if ($what == "addccuser")
			return $this->AddCCUser();
		if ($what == "removeccuser")
			return $this->RemoveCCUser();

		if ($what == "pickccuser")
			return $this->PickCCUser();

		// should we display the edit field screen 
		if ($what == 'editfield')
			return $this->EditField();	
		
		// save an edited field and continue what we were doing afterwards
		if ($what == 'savefield')
			if ($this->SaveField())
				return;
		
		// check for product search ajax display
		$search = JRequest::getVar('prodsearch', '', '', 'string');     
		if ($search != "")
			return $this->searchProducts();	
    	
		// page to hunt for unregistered ticket
		if ($what == "find")
			return $this->findTicket();

		// save status changes
		if ($what == "statuschange")
			return $this->saveStatusChanges();
		
		// save any replys
		if ($what == 'savereply')
			return $this->saveReply();
			
		// save any replys
		if ($what == 'messages')
			return $this->showMessages();

		// process any file downloads
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
		        
		$this->assignRef('count',$this->get('TicketCount'));

		// handle opening ticket
    	if ($layout == "open")
    		return $this->doOpenTicket();
    
		// handel ticket reply
    	if ($layout == "reply")
    		return $this->doUserReply();

		// display ticket list / ticket
    	return $this->doDisplayTicket();
    }
	
	function searchProducts()
	{
		$mainframe = JFactory::getApplication();
		$aparams = $mainframe->getPageParameters('com_fss');
		
		$this->assignRef('support_advanced',FSS_Settings::get('support_advanced'));
				
		$pagination =& $this->get('ProdPagination');
		$this->assignRef('pagination', $pagination);

		$search = JRequest::getVar('prodsearch', '', '', 'string');  
		
		$this->assignRef('prodsearch',$search);
		$this->assignRef( 'results', $this->get("Products") );
		
		parent::display("search"); 
		exit;
	} 
	
	// display the reply form
	function doUserReply()
	{
		$db =& JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		
		$errors['subject'] = '';
		$errors['body'] = '';
		$this->assignRef( 'errors', $errors );
		

	    if (!$this->GetTicket())
			return;

 		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'components/com_fss/assets/css/popup.css'); 
		$document->addScript(JURI::root().'components/com_fss/assets/js/popup.js'); 

		$this->getCCInfo();
		
		
		$pathway =& $mainframe->getPathway();
		$pathway->addItem(JText::_("TICKET") . " : " . $this->ticket['reference'] . " - " . $this->ticket['title'],FSSRoute::x( 'index.php?option=com_fss&view=ticket&ticketid=' . $this->ticket['id'] ));
		$pathway->addItem(JText::_("POST_REPLY"));
		
		$this->setLayout("reply");
		parent::display();	
	}
	
	function saveStatusChanges()
	{ 
		$user =& JFactory::getUser();
		$userid = $user->get('id');

		if (!$this->ValidateUser())
			return;
				
		$ticketid = JRequest::getVar('ticketid',0, '', 'int');
		$now = FSS_Helper::CurDate();
		
		// check for any changed status submitted and save em
		$new_status = JRequest::getVar('new_status',-1, '', 'int'); 
		$new_pri = JRequest::getVar('new_pri',-1, '', 'int'); 
		
		$model =& $this->getModel();
		$this->assignRef('ticket',$model->getTicket($ticketid));
		
		$this->GetTicketPerms($this->ticket);
		
		// check for status change
		if ($this->ticket['ticket_status_id'] != $new_status && !$this->ticket['can_close'])
			return $this->noPermission();	
			
		// check for pri change	
		if ($this->ticket['ticket_pri_id'] != $new_pri && !$this->ticket['can_edit'])
			return $this->noPermission();		
		
		$uids = $model->getUIDS($userid);

		if (!array_key_exists($this->ticket['user_id'], $uids))
		{
			$this->getCCInfo();
			// doesnt have permission to view, check cc list
			if (!array_key_exists("cc",$this->ticket))
				return $this->noPermission();
				
			$found = false;
			foreach ($this->ticket['cc'] as &$user)
			{
				if ($user['id'] == $userid)
					$found = true;		
			}
			if (!$found)
				return $this->noPermission();
		}
		
		/*$model =& $this->getModel(); 
		$ticket = $model->getTicket($ticketid);*/
		//print_r($ticket);
		$changed = false;
		$date = false;

		if ($new_status != $this->ticket['ticket_status_id'])
		{
			$oldstatus = $model->GetStatus($this->ticket['ticket_status_id']);
			$newstatus = $model->GetStatus($new_status);
			$this->AddTicketAuditNote($this->ticket['id'],"Status changed from '" . $oldstatus['title'] . "' to '" . $newstatus['title'] . "'");
			
			$st = FSS_Ticket_Helper::GetStatusByID($new_status);
			
			if (!$st->is_closed)
				$date = true;
			$changed = true;
			
		}
		if ($new_pri != $this->ticket['ticket_pri_id'])
		{
			$oldpri = $model->GetPriority($this->ticket['ticket_pri_id']);
			$newpri = $model->GetPriority($new_pri);
			$this->AddTicketAuditNote($this->ticket['id'],"Priority changed from '" . $oldpri['title'] . "' to '" . $newpri['title'] . "'");
			
			$date = true;
			$changed = true;
		}
		
		if ($new_status > 0)
		{
			$db =& JFactory::getDBO();
			$sets = array();
			
			$st = FSS_Ticket_Helper::GetStatusByID($new_status);
			if ($new_status != $this->ticket['ticket_status_id'])
			{
				if ($st->is_closed)
				{
					$sets[] = "closed = '{$now}'";	
				} else {
					$sets[] = "closed = NULL";	
				}	
			}	
			
			$qry = "UPDATE #__fss_ticket_ticket SET ";
			if ($new_pri != $this->ticket['ticket_pri_id'])
				$sets[] = "ticket_pri_id = '".FSSJ3Helper::getEscaped($db, $new_pri)."'";
			if ($new_status != $this->ticket['ticket_status_id'])
				$sets[] = "ticket_status_id = '".FSSJ3Helper::getEscaped($db, $new_status)."'";
				
			if ($date)
				$sets[] = "lastupdate = '{$now}'";

			if (count($sets) > 0)
			{
				$qry .= implode(", ", $sets);
				$qry .= " WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
				$db->setQuery($qry);$db->Query();
			}
			$this->assignRef('ticket',$model->getTicket($ticketid));
		}	
		
		// forward with what=
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::x('&what=&new_status=&new_pri=',false);
		$mainframe->redirect($link);		
	}	
	
    function doOpenTicket()
    {
        $mainframe = JFactory::getApplication();
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$this->assign('userid',$userid);
		$this->assign('email','');
		$this->assign('admin_create',0);
		
		$_SESSION['admin_create'] = JRequest::getVar('admincreate',0,'','int');
		
		if ($_SESSION['admin_create'] == 1 && JRequest::getVar('user_id',0,'','int') > 0)
		{
			$_SESSION['admin_create_user_id'] = JRequest::getVar('user_id',0,'','int');
		} else if ($_SESSION['admin_create'] == 2)
		{
			if (JRequest::getVar('admin_create_email','','','string') != "")
			{
				$_SESSION['ticket_email'] = JRequest::getVar('admin_create_email','','','string');
				$_SESSION['ticket_name'] = JRequest::getVar('admin_create_name','','','string');
			}
		}
		
		if ($_SESSION['admin_create'] == 1)
		{
			$this->assign('admin_create',1);
			$model = $this->getModel();
			$this->assignRef('user',$model->getUser($_SESSION['admin_create_user_id']));
		} else if ($_SESSION['admin_create'] == 2) {
			$this->assignRef('unreg_name',$_SESSION['ticket_name']);
			$this->assignRef('unreg_email',$_SESSION['ticket_email']);
			$this->assign('admin_create',2);
		}
		
		// store in session and data for an unregistered ticket
		$type = JRequest::getVar('type','', '', 'string');
		if ($type == "without")
		{
			$email = JRequest::getVar('email');
			$name = JRequest::getVar('name');
			
			if ($name == "")
				$name = $email;
				
			if ($email != "")
			{
				$_SESSION['ticket_email'] = $email;
				$_SESSION['ticket_name'] = $name;
			}
		}
	
		if (!$this->ValidateUser('open'))
			return;

		// defaults for blank ticket
		$ticket['priid'] = '';
		$ticket['body'] = '';
		$ticket['subject'] = '';
		$ticket['handler'] = 0;
		
		$this->assignRef( 'ticket', $ticket );
		
		
		$errors['subject'] = '';
		$errors['body'] = '';
		
		$this->assignRef( 'errors', $errors );

		$prodid = JRequest::getVar('prodid',0, '', 'int');
		
		// prod id not set, should we display product list???
    	if ($prodid < 1)
    	{
     		$this->assignRef('products',$this->get('Products'));
			if (count($this->products) > 1)
			{
				$this->assign('search','');
				$this->assignRef('support_advanced',FSS_Settings::get('support_advanced'));
					
				$pagination =& $this->get('ProdPagination');
				$this->assignRef('pagination', $pagination);
				$this->assign('limit',$this->get("ProdLimit"));

				parent::display("product");
				return;
			} else if (count($this->products) == 1)
			{
				$prodid = $this->products[0]['id'];
				JRequest::setVar('prodid',$prodid);
				//echo "Setting prodid to $prodid<br>";
			}
		}
		
		$this->assign('prodid',$prodid);
    	
		
		
    	$deptid = JRequest::getVar('deptid',0, '', 'int');
    	
		// dept id not set, should we display department list?
    	if ($deptid < 1)
    	{
    		$this->assignRef('depts',$this->get('Departments'));
			if (count($this->depts) > 1)
			{
				$this->assignRef('product',$this->get('Product'));
				parent::display("department");
				return;
			} else if (count($this->depts) == 1)
			{
				$deptid = $this->depts[0]['id'];
				JRequest::setVar('deptid',$deptid);
				//echo "Setting deptid to $deptid<br>";
			}
		}
 			
   		$what = JRequest::getVar('what','');
    	
		// done with ticket, try and save, if not, display any errors
    	if ($what == "add")
    	{
    		if ($this->saveTicket())
    		{
    			//exit;
    			if ($this->admin_create > 0)
    			{
					$link = 'index.php?option=com_fss&view=admin&layout=support&Itemid=' . JRequest::getVar('Itemid','') . '&ticketid=' . $this->ticketid;
					$mainframe->redirect($link);
				} else {
					$link = 'index.php?option=com_fss&view=ticket&layout=view&Itemid=' . JRequest::getVar('Itemid','') . '&ticketid=' . $this->ticketid;
					$mainframe->redirect($link);
				}		
    			return;
			} else {
				//echo "Error saving ticket<br>";
			}
		}
		
		// load handlers if required. This depends on what product and department have been selected
		if (FSS_Settings::get('support_choose_handler') != "none")
		{
			$handlers = FSS_Ticket_Helper::ListHandlers($prodid, $deptid, 0/*, true*/);
			
			if (count($handlers) == 0)
				$handlers[] = 0;
			
			$qry = "SELECT user_id, id FROM #__fss_user WHERE id IN (" . implode(", ", $handlers) . ")";
			$db = & JFactory::getDBO();
			
			$db->setQuery($qry);
			$handlers = $db->loadAssocList();
			
			$this->handlers = array();
			$h = array();
			$h['user_id'] = 0;
			$h['id'] = 0;
			$h['name'] = JText::_('AUTO_ASSIGN');
			$this->handlers[] = $h;
				
			if (is_array($handlers))
			{
				foreach ($handlers as $handler)
				{
					$user = JFactory::getUser($handler['user_id']);
				
					$id = $handler['id'];
				
					if ($user)
					{
						$h = array();
						$h['user_id'] = $user->id;
						$h['id'] = $id;
						$h['name'] = $user->name;
					
						$this->handlers[] = $h;
					}
				}
			}
		}
		 		
		$this->assign('deptid',$deptid);
		
     	$this->assignRef('product',$this->get('Product'));
     	$this->assignRef('dept',$this->get('Department'));
     	$this->assignRef('cats',$this->get('Cats'));
		$this->assignRef('pris',$this->get('Priorities'));
		$this->assignRef('support_user_attach',FSS_Settings::get('support_user_attach'));
		
		$this->assignRef('fields',FSSCF::GetCustomFields(0,$prodid,$deptid,0,true));
		
		parent::display();
	}
	
	function saveReply()
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$db =& JFactory::getDBO();

		if (!$this->ValidateUser())
			return;
				
		$ticketid = JRequest::getVar('ticketid',0, '', 'int');
		$this->assignRef('ticketid',$ticketid);

		$subject = JRequest::getVar('subject','', '', 'string');
		$body = JRequest::getVar('body','', '', 'string', JREQUEST_ALLOWRAW);
		$replytype = JRequest::getVar('replytype','');

		$model =& $this->getModel();
		$ticket = $model->getTicket($ticketid);
		
		$this->GetTicketPerms($ticket);
		
		if (!$ticket['can_edit'])
			return $this->noPermission();
		$now = FSS_Helper::CurDate();
		
		$messageid = -1;
		if ($body)
		{
			$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, posted) VALUES ('";
			$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."','{$now}')";
			$db->setQuery($qry);$db->Query();
			$messageid = $db->insertid();
			//echo $qry."<br>";
			
			$def_user = FSS_Ticket_Helper::GetStatusID('def_user');
			
			$qry = "UPDATE #__fss_ticket_ticket SET ticket_status_id = '$def_user', closed = NULL WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
			$db->setQuery($qry);$db->Query();
			//echo $qry."<br>";
			
			$oldstatus = $model->GetStatus($ticket['ticket_status_id']);
			$newstatus = $model->GetStatus($def_user);
			$this->AddTicketAuditNote($ticket['id'],"Status changed from '" . $oldstatus['title'] . "' to '" . $newstatus['title'] . "'");
		}
		
		$attach = false;
		
		for ($i = 0; $i < 10; $i++)
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
            		$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id) VALUES ('";
            		$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "',";
            		$qry .= "'" . FSSJ3Helper::getEscaped($db, $file['name']) . "',";
            		$qry .= "'" . FSSJ3Helper::getEscaped($db, $random.'-'.$file['name']) . "',";
            		$qry .= "'" . $file['size'] . "',";
            		$qry .= "'" . FSSJ3Helper::getEscaped($db, $userid) . "',";
            		$qry .= "'{$now}', $messageid )";
            	
            		$db->setQuery($qry);$db->Query(); 
					
					$attach = true;    
				}
			}
		}
        
        $qry = "UPDATE #__fss_ticket_ticket SET lastupdate = '{$now}' WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
        $db->setQuery($qry);$db->Query(); 
		//echo $qry."<br>";
			
		$model =& $this->getModel(); 
		$this->assignRef('ticket',$model->getTicket($this->ticketid));
		
		$subject = JRequest::getVar('subject','','','string');
		$body = JRequest::getVar('body','','','string', JREQUEST_ALLOWRAW);
		FSS_EMail::User_Reply($this->ticket, $subject, $body)	;			
			
		if ($replytype == "full")
		{
			// forward with what=
			$mainframe = JFactory::getApplication();
			$link = FSSRoute::x('&what=',false);
			$mainframe->redirect($link);
			return;
		}
		ob_clean();
		
		// need to display the messages for the ticket
		$this->GetTicket();
		
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages.php';
		//include "components/com_fss/views/ticket/snippet/_messages.php";
		if ($attach)
		{
			echo "<script>\n window.parent.location.reload();\n </script>";
		} else {
			echo "<script>\n window.parent.FromDone();\n </script>";
		}
		exit;
	}
	
	function showMessages()
	{
		$this->GetTicket();	
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages.php';
		//include "components/com_fss/views/ticket/snippet/_messages.php";
		exit;
	}
	
	function saveTicket()
	{
		$subject = JRequest::getVar('subject','', '', 'string');
		$body = JRequest::getVar('body','', '', 'string', JREQUEST_ALLOWRAW);
    	$prodid = JRequest::getVar('prodid',0, '', 'int');
    	$deptid = JRequest::getVar('deptid',0, '', 'int');
    	$catid = JRequest::getVar('catid',0, '', 'int');
    	$priid = JRequest::getVar('priid',0, '', 'int');
    	$handler = JRequest::getVar('handler',0, '', 'int');
    	$user = JFactory::getUser();
		$userid = $user->get('id');
		$name = "";
		
		$this->admin_create = 0;
		if (array_key_exists('admin_create',$_SESSION))
			$this->admin_create = $_SESSION['admin_create'];
		if ($this->admin_create == 1)
		{
			$this->admin_create = 1;
			$userid = $_SESSION['admin_create_user_id'];
		} else if ($this->admin_create == 2)
		{
			$userid = 0;
		}
			
 		$db =& JFactory::getDBO();
		
		/*$query = "SELECT id FROM #__fss_ticket_ticket WHERE reference = '" . FSSJ3Helper::getEscaped($db, $ref) . "'";
		$db->setQuery($query);
        $rows = $db->loadAssoc();
        
        if ($rows)
        {
        	$this->assign("ticketid",$rows['id']);
        	return true;
		}*/


		$ticket['subject'] = $subject;
		$ticket['body'] = $body;
		$ticket['priid'] = $priid;
		$ticket['handler'] = $handler;
		
		$ok = true;
		$errors['subject'] = '';
		$errors['body'] = '';
		
		if (FSS_Settings::get('support_subject_message_hide') == "subject")
		{
			$ticket['subject'] = substr(strip_tags($ticket['body']), 0, 40);
			$subject = $ticket['subject'];
		} else if ($subject == "")
		{
			$errors['subject'] = JText::_("YOU_MUST_ENTER_A_SUBJECT_FOR_YOUR_SUPPORT_TICKET");	
			$ok = false;
		}
		
		if ($body == "" && FSS_Settings::get('support_subject_message_hide') != "message")
		{
			$errors['body'] = JText::_("YOU_MUST_ENTER_A_MESSAGE_FOR_YOUR_SUPPORT_TICKET");	
			$ok = false;
		}
		
		$fields = FSSCF::GetCustomFields(0,$prodid,$deptid,0,true);
		if (!FSSCF::ValidateFields($fields,$errors))
		{
			$ok = false;	
		}
		
		$email = "";
		$password = "";
		$now = FSS_Helper::CurDate();
		
		if ($userid < 1)
		{	
			$email = FSSJ3Helper::getEscaped($db, $_SESSION['ticket_email']);
			$name = FSSJ3Helper::getEscaped($db, $_SESSION['ticket_name']);

			if ($email == "")
			{
				$ok = false;
			} else {
				$password = FSS_Helper::createRandomPassword();	
				$_SESSION['ticket_pass'] = $password;
			}
		}

		if ($ok)
		{		
			$admin_id = $handler;
			if (!$admin_id)
				$admin_id = FSS_Ticket_Helper::AssignHandler($prodid, $deptid, $catid);
			
			$now = FSS_Helper::CurDate();
			
			$def_open = FSS_Ticket_Helper::GetStatusID('def_open');
			
			$qry = "INSERT INTO #__fss_ticket_ticket (reference, ticket_status_id, ticket_pri_id, ticket_cat_id, ticket_dept_id, prod_id, title, opened, lastupdate, user_id, admin_id, email, password, unregname) VALUES ";
			$qry .= "('', $def_open, '".FSSJ3Helper::getEscaped($db, $priid)."', '".FSSJ3Helper::getEscaped($db, $catid)."', '".FSSJ3Helper::getEscaped($db, $deptid)."', '".FSSJ3Helper::getEscaped($db, $prodid)."', '".FSSJ3Helper::getEscaped($db, $subject)."', '{$now}', '{$now}', '".FSSJ3Helper::getEscaped($db, $userid)."', '".FSSJ3Helper::getEscaped($db, $admin_id)."', '{$email}', '".FSSJ3Helper::getEscaped($db, $password)."', '{$name}')";
			

  			$db->setQuery($qry);$db->Query();
			$this->assign("ticketid",$db->insertid());
			$ref = FSS_Ticket_Helper::createRef($this->ticketid);

			$qry = "UPDATE #__fss_ticket_ticket SET reference = '".FSSJ3Helper::getEscaped($db, $ref)."' WHERE id = '" . FSSJ3Helper::getEscaped($db, $this->ticketid) . "'";  
   			$db->setQuery($qry);$db->Query();


			$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, posted) VALUES ('";
			$qry .= FSSJ3Helper::getEscaped($db, $this->ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."','{$now}')";
			
			$db->setQuery($qry);$db->Query();
			$messageid = $db->insertid();
				
			FSSCF::StoreFields($fields,$this->ticketid);
			
			// save any uploaded file
			        
			for ($i = 1; $i < 10; $i++)
			{
				$file = JRequest::getVar('filedata_' . $i, '', 'FILES', 'array');
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
	            		$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id) VALUES ('";
	            		$qry .= FSSJ3Helper::getEscaped($db, $this->ticketid) . "',";
	            		$qry .= "'" . FSSJ3Helper::getEscaped($db, $file['name']) . "',";
	            		$qry .= "'" . FSSJ3Helper::getEscaped($db, $random.'-'.$file['name']) . "',";
	            		$qry .= "'" . $file['size'] . "',";
	            		$qry .= "'" . FSSJ3Helper::getEscaped($db, $userid) . "',";
	            		$qry .= "'{$now}', $messageid )";
	            	
	            		$db->setQuery($qry);$db->Query();     
					} else {
	            		// ERROR : File cannot be uploaded! try permissions	
					}
				}
			}
			
			$model =& $this->getModel();
			$ticket = $model->getTicket($this->ticketid);
			
			$subject = JRequest::getVar('subject','','','string');
			$body = JRequest::getVar('body','','','string', JREQUEST_ALLOWRAW);
			if ($email != "")
			{
				FSS_EMail::User_Create_Unreg($ticket, $subject, $body);
			} else {
				FSS_EMail::User_Create($ticket, $subject, $body);
			}
			FSS_EMail::Admin_Create($ticket, $subject, $body);
		}
			
		$this->assignRef('errors',$errors);
		$this->assignRef('ticket',$ticket);

		return $ok;
	}

	function GetTicket()
	{
		$mainframe = JFactory::getApplication();
        $this->setLayout("view");
		$model =& $this->getModel();
          
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$this->assign('userid',$userid);
	 	$db =& JFactory::getDBO();
	
		if (!$this->ValidateUser())
			return;
				
		// get ticketid if we can	
		if (!$this->ticket)
		{
			$ticketid = JRequest::getVar('ticketid',0, '', 'int');
		} else {
			$ticketid = $this->ticketid;
		}
		
		// no ticket so display a list
		if ($ticketid < 1)
		{
			$tickets = JRequest::getVar('tickets','open');
			$this->assignRef('ticket_view',$tickets);

			$this->listTickets();	
			return false;
		}		
				
		// display ticket
		$this->assignRef('ticket',$model->getTicket($ticketid));
		$this->getCCInfo();
		
		$uids = $model->getUIDS($userid);

		if (!array_key_exists($this->ticket['user_id'], $uids))
		{
			// doesnt have permission to view, check cc list
			if (!array_key_exists("cc",$this->ticket))
				return $this->noPermission();
				
			$found = false;
			foreach ($this->ticket['cc'] as &$user)
			{
				if ($user['id'] == $userid)
					$found = true;		
			}
			if (!$found)
				return $this->noPermission();
				
			$model->multiuser = 1;
		}
				
		$this->assignRef('messages',$model->getMessages($ticketid));		
		$this->assignRef('attach',$model->getAttach($ticketid));		
		

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
			
 		$this->multiuser = $model->multiuser;
		if ($this->multiuser)
			$this->assignRef('user', $model->getUser($this->ticket['user_id']));

        $pathway =& $mainframe->getPathway();
        $pathway->addItem(JText::_("TICKET")." : " . $this->ticket['reference'] . " - " . $this->ticket['title']);
        
		$this->assignRef('fields',FSSCF::GetCustomFields($ticketid,$this->ticket['prod_id'],$this->ticket['ticket_dept_id'],1));
		$this->assignRef('fieldvalues',FSSCF::GetTicketValues($ticketid, $this->ticket));
		
		$this->assignRef('support_user_attach',FSS_Settings::get('support_user_attach'));
		$errors['subject'] = '';
		$errors['body'] = '';

		$this->GetTicketPerms($this->ticket);

		$this->assignRef( 'errors', $errors );
	
		return true;
	}
	
	function doDisplayTicket()
	{
		if (!$this->GetTicket())
			return;

 		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'components/com_fss/assets/css/popup.css'); 
		$document->addScript(JURI::root().'components/com_fss/assets/js/popup.js'); 

		$this->getCCInfo();
		
		parent::display();
	}
	
	function getCCInfo()
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$db =& JFactory::getDBO();

		// find if a user is a group member or not
		$qry = "SELECT g.id, g.ccexclude FROM #__fss_ticket_group_members AS gm LEFT JOIN #__fss_ticket_group AS g ON gm.group_id = g.id WHERE user_id = ".FSSJ3Helper::getEscaped($db, $userid);
		$db->setQuery($qry);
		$rows = $db->loadObjectList();
		
		$this->show_cc = 0;
		
		foreach ($rows as $row)
		{
			if ($row->ccexclude == 0)
				$this->show_cc = 1;
		}
		
		if ($this->show_cc)
		{
			// can we only cc our own ticket? NO!
			
			// get current cc list
			$qry = "SELECT u.name, u.id FROM #__fss_ticket_cc as c LEFT JOIN #__users as u ON c.user_id = u.id WHERE c.ticket_id = {$this->ticket['id']} ORDER BY name";
			$db->setQuery($qry);
			$this->ticket['cc'] = $db->loadAssocList();
		}
	
	}
	
	// validate userid > 0 or valid ticket email and pass. Set ticket id in request to found id
	function ValidateUser($view = 'view')
	{
		//echo "Using $view<br>";
		$this->setLayout($view);
		
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		
		if ($userid > 0)
			return true;
	
		// use email for non registered ticket
		$sessionemail = "";
		if (array_key_exists('ticket_email',$_SESSION))
			$sessionemail = $_SESSION['ticket_email'];
		$email = JRequest::getVar('email',$sessionemail,'','string');
		$_SESSION['ticket_email'] = $email;
		
		if ($email == "")
		{
			//echo "No email set<br>";
			$this->needLogin();
			return false;
		}
		
		$this->assign('email',$email);
	
		if ($this->getLayout() == "open")
		{
			if (!$this->isValidEmail($email))
			{
				$this->needLogin(3);
				return false;	
			}
			
			if ($this->DupeEmail($email))
			{
				$this->needLogin(1);
				return false;
			}
		} else {		
			if ($email != "")
			{
				// validate ticket password and find ticket id!
				$sessionpass = "";
				if (array_key_exists('ticket_pass',$_SESSION))
					$sessionpass = $_SESSION['ticket_pass'];
				$password = JRequest::getVar('password',$sessionpass,'','string');
				$_SESSION['ticket_pass'] = $password;
				
				//echo "ticket pass : $sessionpass<br>";
				
				$db =& JFactory::getDBO();
				
				$qry = "SELECT id FROM #__fss_ticket_ticket WHERE email = '".FSSJ3Helper::getEscaped($db, $email)."' AND password = '".FSSJ3Helper::getEscaped($db, $password)."'";
				//echo $qry."<br>";
				$db->setQuery($qry);
				$row = $db->loadAssoc();
				
				if ($row)
				{
					JRequest::setVar('ticketid',$row['id']);
				} else {
					$this->needLogin(2);
					return false;
				}
			}
		}
		
		return true;	
	}
	
	function isValidEmail($email){
		if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)*.([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/" , $email)) {
			return false;
		}else{
			$record = 'MX';
			list($user,$domain) = explode('@',$email);
			if(!checkdnsrr($domain,$record)){
				return false;
			}else{
				return true;
			}
		}
	}	

	function listTickets()
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		
		// validate that we have a user id before listing tickets
		if (!$this->ValidateUser())
			return;
			
		$this->ClaimTickets();
			
		$stuff = $this->get('Tickets');
		$model = $this->getModel();
		
		$this->assignRef('tickets',$stuff['tickets']);
		$this->assignRef('pagination', $stuff['pagination'] );

		$this->multiuser = $model->multiuser;

		parent::display("list");	
	}
	
	function ClaimTickets()
	{
		$user =& JFactory::getUser();
		//print_p($user);
		
		$db =& JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET user_id = " . $user->get('id') . " WHERE email = '" . FSSJ3Helper::getEscaped($db, $user->email) . "'";
		$db->setQuery($qry);
		$db->Query();
		//echo $qry;
	}	
	
	function downloadFile()
	{
		$fileid = JRequest::getVar('fileid', 0, '', 'int');            
		
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__fss_ticket_attach WHERE id = "' . FSSJ3Helper::getEscaped($db, $fileid) . '"';
		$db->setQuery($query);
		$row = $db->loadAssoc();
		
		
		$filename = basename($row['filename']);
		$file_extension = strtolower(substr(strrchr($filename,"."),1));
		$ctype = FSS_Helper::datei_mime($file_extension);
		ob_end_clean();
		header("Cache-Control: public, must-revalidate");
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header("Pragma: no-cache");
		header("Expires: 0"); 
		header("Content-Description: File Transfer");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		header("Content-Type: " . $ctype);
		header("Content-Length: ".(string)$row['size']);
	    header('Content-Disposition: attachment; filename="'.$filename.'"');
	    header("Content-Transfer-Encoding: binary\n");
	    
	    //echo getcwd(). "<br>";
	    $file = "components/com_fss/files/support/" . $row['diskfile'];
	    //echo $file;
	    @readfile($file);
	    exit;
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

	// type 0 = normal
	// type 1 = dupe email
	// type 2 = no ticket
  	function needLogin($type = 0)
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

		$this->assign('type',$type);		
		$this->assignRef('return',$url);
		parent::display("login");	    
	}
	
	function noPermission()
	{
		$this->setLayout("nopermission");
		//print_r($this->ticket);
		parent::display();	    
	}
	
	function findTicket()
	{
		$this->setLayout("view");
		//echo "findTicket : Current Layout : " . $this->getLayout() . "<br>";
		/*$url = base64_encode($_SERVER['REQUEST_URI']);//JURI::current() . "?" . $_SERVER['QUERY_STRING'];
		$this->assign('type',3);		
		$this->assignRef('return',$url);*/
		$_SESSION['ticket_email'] = "";
		$_SESSION['ticket_name'] = "";
		$_SESSION['ticket_pass'] = "";
		//parent::display("login");	    
		
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::x('index.php?option=com_fss&view=ticket',false);
		$mainframe->redirect($link);
	}
	
	function DupeEmail($email)
	{
		if (FSS_Settings::get('support_dont_check_dupe'))
			return false;
			
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__users WHERE email = "' . FSSJ3Helper::getEscaped($db, $email) . '"';
		$db->setQuery($query);
		$row = $db->loadAssoc();
		
		if ($row)
		{
			if (array_key_exists('block', $row) && $row['block'] > 0)
				return false;
			
			return true;
		}
		
		return false;		
	}
	
	function EditField()
	{
		if (!$this->ValidateUser())
			return;
			
		$ticketid = JRequest::getVar('ticketid',0, '', 'int');
		$model =& $this->getModel();
		$this->assignRef('ticket',$model->getTicket($ticketid));
		
		$this->GetTicketPerms($this->ticket);
		
		if (!$this->ticket['can_edit'])
			return;
		
		
		
		$this->assignRef('fields',FSSCF::GetCustomFields($ticketid,$this->ticket['prod_id'],$this->ticket['ticket_dept_id'],0));
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
		
		if (!$this->CanEditField($this->field))
			return;

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
	
	function SaveField()
	{
		if (JRequest::getVar('store','','','string') == JText::_("SAVE"))
		{
			if (!$this->ValidateUser())
				return $this->noPermission();
			
			$ticketid = JRequest::getVar('ticketid',0, '', 'int');
			$savefield = JRequest::getVar('savefield',0,'','int');
			$model =& $this->getModel();
			$ticket = $model->getTicket($ticketid);	
			$user =& JFactory::getUser();
			$userid = $user->get('id');
			$uids = $model->getUIDS($userid);
			//print_r($uids);
			
			if (!array_key_exists($ticket['user_id'], $uids))
			{
				$this->ticket = &$ticket;
				$this->getCCInfo();
				// doesnt have permission to view, check cc list
				if (!array_key_exists("cc",$ticket))
					return $this->noPermission();
				
				$found = false;
				foreach ($ticket['cc'] as &$user)
				{
					if ($user['id'] == $userid)
						$found = true;		
				}
				if (!$found)
					return $this->noPermission();
				
			}
		
			$this->GetTicketPerms($ticket);
		
			if (!$ticket['can_edit'])
				return $this->noPermission();

			$this->assignRef('fields',FSSCF::GetCustomFields($ticketid,$ticket['prod_id'],$ticket['ticket_dept_id'],0));
			list($old, $new) = FSSCF::StoreField($savefield, $ticketid, $ticket);
			
			if ($old != $new)
			{
				$field = FSSCF::GetField($savefield);
				if ($this->CanEditField($field))
				{
					if ($field->type == 'checkbox')
					{
						if ($old == "") $old = "No";
						if ($old == "on") $old = "Yes";	
						if ($new == "") $new = "No";
						if ($new == "on") $new = "Yes";	
					}
					$this->AddTicketAuditNote($ticketid,"Custom field '" . $field->description . "' changed from '" . $old . "' to '" . $new . "'");
				}
			}
			//FSSCF::StoreFields($this->fields, $ticketid);	
			// forward with what=
			/*$mainframe = JFactory::getApplication();
			$link = FSSRoute::x('&what=&new_status=&new_pri=',false);
			$mainframe->redirect($link);
			*/
			echo "<script>parent.window.location.reload();</script>";
			exit;
			return true;		
		}	
		
		return false;		
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
		$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','Audit Message','".FSSJ3Helper::getEscaped($db, $note)."','".FSSJ3Helper::getEscaped($db, $this->userid)."',3, '{$now}')";
			
  		$db->SetQuery( $qry );
		//echo $qry. "<br>";
		$db->Query();
		//echo "Audit: $ticketid - $note<br>";	
	}
	
	function GetTicketPerms(&$ticket)
	{
	    $db =& JFactory::getDBO();

		$ticketid = $ticket['id'];
		$owner = $ticket['user_id'];
		
		$user = JFactory::getUser();
		$userid = $user->get('id');

		$ticket['can_edit'] = 0;
		$ticket['can_close'] = 0;		

		if ($userid == $owner)
		{		
			$ticket['can_edit'] = 1;
			$ticket['can_close'] = 1;		
			return;
		}

		// not the ticket owner
		
		// check if on cc list, if so then have level 2 permissions
		$qry = "SELECT user_id FROM #__fss_ticket_cc WHERE ticket_id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' AND user_id = '$userid'";
		$db->setQuery($qry);
		$row = $db->loadObjectList();
		if (count($row) > 0)
			$ticket['can_edit'] = 1;
		
		
		// find a list of groups the owner belongs to
		$qry = "SELECT group_id FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $ticket['user_id'])."'";
		$db->setQuery($qry);
		$owner_groups = $db->loadObjectList('group_id');
		
		// find a list of groups the user belongs to
		$qry = "SELECT * FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."'";
		$db->setQuery($qry);
		$user_groups = $db->loadObjectList('group_id');
		
		// find common groups
		$groups = array();
		$gids = array();
		foreach ($user_groups as $group_id => $group)
		{
			if (array_key_exists($group_id, $owner_groups))
			{
				$groups[] = &$group;	
				$gids[$group_id] = $group_id;
			}
		}

		if (count($gids) == 0)
			return;
			
		// for each of the common groups, check if the users permissions for em and elevate if available
		$qry = "SELECT * FROM #__fss_ticket_group WHERE id IN (" . implode(", ", $gids) . ")";
		$db->setQuery($qry);
		
		$groups = $db->loadObjectList('id');
		
		if (count($groups) == 0)
			return; 
			
		foreach($groups as $group_id => $group)
		{
			$perm = $user_groups[$group_id]->allsee;
			if ($perm == 0)
				$perm = $group->allsee;	
				
			if ($perm > 1)
				$ticket['can_edit'] = 1;
			if ($perm > 2)
				$ticket['can_close'] = 1;
		}
		
		return;
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
		
		$peruser = "";
		if (is_array($field))
		{
			$peruser = $field['peruser'];			
		} else {
			$peruser = $field->peruser;
		}
		
		if ($peruser == 1)
		{
			$owner = $this->ticket['user_id'];
		
			$user = JFactory::getUser();
			$userid = $user->get('id');
			if ($owner == $userid)
				return true;
		} else {
			if ($this->ticket['can_edit'])
				return true;	
		}

		return false;
	}
	
	function PickCCUser()
	{
		$db	=& JFactory::getDBO();
		// build query
		
		// get list of possible user ids
		$user = JFactory::getUser();
		$userid = $user->get('id');
	
		$qry = "SELECT g.id, g.ccexclude FROM #__fss_ticket_group_members AS gm LEFT JOIN #__fss_ticket_group AS g ON gm.group_id = g.id WHERE user_id = ".FSSJ3Helper::getEscaped($db, $userid);
		
		//print_p($_POST);
		
		$db->setQuery($qry);
		$gids = array();
		$rows = $db->loadObjectList();
		foreach($rows as $row)
		{
			if ($row->ccexclude == 0)
				$gids[$row->id] = $row->id;		
		}
		
		if (count($gids) == 0)
			return;
	
		$qry = "SELECT user_id FROM #__fss_ticket_group_members WHERE group_id IN (" . implode(", ",$gids) . ")";
		$db->setquery($qry);
		$user_ids = $db->loadObjectList('user_id');
		
		$uids = array();
		foreach($user_ids as $uid => &$group)
			$uids[$uid] = $uid;
		
		unset($uids[$userid]);
		
		$ticketid = JRequest::getVar('ticketid');
		$this->GetTicket();
		$this->getCCInfo();
		
		if (array_key_exists("cc",$this->ticket))
		{
			foreach ($this->ticket['cc'] as $ccuser)
			{
				$userid = $ccuser['id'];
				unset($uids[$userid]);		
			}	
		}
	
		$qry = "SELECT * FROM #__users ";
		$where = array();
		
		$limitstart = JRequest::getInt('limitstart',0);
		$mainframe = JFactory::getApplication();
		$limit = $mainframe->getUserStateFromRequest('users.limit', 'limit', 10, 'int');
		$search = JRequest::getString('search','');
		
		
		if ($search != "")
		{
			$where[] = "(username LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR name LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR email LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%')";
		}
			
		
		if (count($uids) > 0)
		{
			$where[] = "id IN (" . implode(", ", $uids) . ")";
		} else {
			$where[] = "id = 0";		
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
			
		
		//echo $qry . "<br>";
		// select picked items
		$db->setQuery( $qry, $limitstart, $limit );
		$this->assignRef('users',$db->loadObjectList());

		
		// build pagination
		$this->assignRef('pagination',new JPagination($maxitems, $limitstart, $limit ));
		$this->assignRef('search',$search);
		
		parent::display("users");		
	}
	
	function AddCCUser()
	{
		$db	=& JFactory::getDBO();
		$ticketid = JRequest::getVar('ticketid');
		$userid = JRequest::getVar('userid');
		$this->GetTicket();
		
		$this->GetTicketPerms($this->ticket);
		if ($this->ticket['can_edit'])
		{
			$qry = "REPLACE INTO #__fss_ticket_cc (ticket_id, user_id, isadmin) VALUES ('".FSSJ3Helper::getEscaped($db, $ticketid)."','".FSSJ3Helper::getEscaped($db, $userid)."',0)";
			$db->setQuery($qry);
			$db->Query();
		}
		
		$this->getCCInfo();
		
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ccusers.php';
		//include "components/com_fss/views/ticket/snippet/_ccusers.php";
		
		exit;
	}	
	
	function RemoveCCUser()
	{
		$db	=& JFactory::getDBO();
		$ticketid = JRequest::getVar('ticketid');
		$userid = JRequest::getVar('userid');
		
		$this->GetTicket();
		$this->GetTicketPerms($this->ticket);
		
		if ($this->ticket['can_edit'])
		{
			$qry = "DELETE FROM #__fss_ticket_cc WHERE ticket_id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' AND user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND isadmin = 0";
			$db->setQuery($qry);
			$db->Query();
		}
		
		$this->getCCInfo();
		
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ccusers.php';
		//include "components/com_fss/views/ticket/snippet/_ccusers.php";
		
		exit;
	}
}

