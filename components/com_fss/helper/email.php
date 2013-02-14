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
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');

class FSS_EMail
{
	function Get_Sender()
	{
		$config =& JFactory::getConfig();
		
		if (FSSJ3Helper::IsJ3())
		{		
			$address = 	$config->get( 'config.mailfrom' );
			$name = $config->get( 'config.fromname' );		
		} else {		
			$address = 	$config->getValue( 'config.mailfrom' );
			$name = $config->getValue( 'config.fromname' );		
		}

		//echo "Name : $name, Address : $address<br>";
		
		if (FSS_Settings::get('support_email_from_name') != "")
			$name = FSS_Settings::get('support_email_from_name');

		if (FSS_Settings::get('support_email_from_address') != "")
			$address = FSS_Settings::get('support_email_from_address');
		return array( $address, $name );
	}

	function Send_Comment($comments)
	{	
		if ($comments->dest_email == "") 
		{
			return;
		}
		
		$mailer =& JFactory::getMailer();
		$config =& JFactory::getConfig();
		$sender = FSS_EMail::Get_Sender();
		$mailer->setSender($sender);
		$recipient = array($comments->dest_email);
		$mailer->addRecipient($recipient);
		
		$tpl = $comments->handler->EMail_GetTemplate($comments->moderate);
		$template = FSS_EMail::Get_Template($tpl);
		
		$data = $comments->comment;
		$data['moderated'] = $comments->moderate;
		if ($data['moderated'] == 0)
			$data['moderated'] = "";
			
		if (!array_key_exists('customfields',$data))
			$data['customfields'] = "";
		if (!array_key_exists('email',$data))
			$data['email'] = "";
		if (!array_key_exists('website',$data))
			$data['website'] = "";
		if (!array_key_exists('linkmod',$data))
			$data['linkmod'] = "";
		if (!array_key_exists('linkart',$data))
			$data['linkart'] = "";
		
		if ($comments->moderate)
		{
			$data['linkmod'] = $comments->GetModLink();
		}
			
		$links = $comments->handler->EMail_AddFields($data);
		$links['linkart'] = 1;
		$links['linkmod'] = 1;
		
		if ($data['moderated'] == 0)
		{
			$data['moderated'] = "";
			$data['linkmod'] = "";
		}
		if ($template['ishtml'])
		{
			$data['article'] = "<a href='{$data['linkart']}'>{$data['article']}</a>";
			FSS_EMail::ProcessLinks($data, $links);
			
			// add custom fields html style
			$customfields = "";
			foreach($comments->customfields as &$field)
				$customfields .= $field['description'] . ": " . $data['custom_' . $field['id']] . "<br />";
			$data['customfields'] = $customfields;
		} else {
			// add custom fields text style
			$customfields = "";
			foreach($comments->customfields as &$field)
				$customfields .= $field['description'] . ": " . $data['custom_' . $field['id']] . "\n";
			$data['customfields'] = $customfields;
		}
		
		//file_put_contents("printr.txt",print_r($data,true));

		$email = FSS_EMail::ParseGeneralTemplate($template, $data);
		
		/*print_p($comments);
		print_p($data);
		print_p($email);*/
		
		$mailer->isHTML($template['ishtml']);
		$mailer->setSubject($email['subject']);
		$mailer->setBody($email['body']);

		//print_p($email);

		//exit;
		$send =& $mailer->Send();	
	}
	
	function ProcessLinks(&$data, $links)
	{
		foreach ($links as $link => $temp)
		{
			if ($data[$link] == "")
				continue;
			$data[$link] = "<a href='{$data[$link]}'>here</a>";	
		}
	}
	
	function ParseGeneralTemplate($template, $data)
	{
		if ($template['ishtml'])
		{
			$data['body'] = str_replace("\n","<br>\n",$data['body']);	
		}
	
		foreach($data as $var => $value)
			$vars[] = FSS_EMail::BuildVar($var,$value);

		$email['subject'] = FSS_EMail::ParseText($template['subject'],$vars);
		$email['body'] = FSS_EMail::ParseText($template['body'],$vars);
	
		return $email;			
	}
	
	function Admin_Reply(&$ticket, $subject, $body)
	{
		// ticket replyd to
		if (FSS_Settings::get('support_email_on_reply') == 1)
		{		
			$db =& JFactory::getDBO();

			$custid = $ticket['user_id'];
			$qry = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $custid)."'";
			$db->setQuery($qry);
			$custrec = $db->loadAssoc();
		
			$email = $custrec['email'];
		
			$mailer =& JFactory::getMailer();

			$config =& JFactory::getConfig();
			$sender = FSS_EMail::Get_Sender();
		
			$mailer->setSender($sender);
		
			FSS_EMail::AddTicketRecpts($mailer, $ticket, $custrec);
		
			$template = FSS_EMail::Get_Template('email_on_reply');
			$email = FSS_EMail::ParseTemplate($template,$ticket,$subject,$body,$template['ishtml']);
		
			$mailer->isHTML($template['ishtml']);
			$mailer->setSubject($email['subject']);
			$mailer->setBody($email['body']);
		
			/*print_p($mailer);
			exit;*/
			$send =& $mailer->Send();
			/*
			print_p($send);
			print_p($mailer);
			exit;*/
		}
	}
	
	function Admin_Close(&$ticket, $subject, $body)
	{
		// ticket replyd to
		if (FSS_Settings::get('support_email_on_close') == 1)
		{		
			$db =& JFactory::getDBO();

			$custid = $ticket['user_id'];
			$qry = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $custid)."'";
			$db->setQuery($qry);
			$custrec = $db->loadAssoc();
		
			$email = $custrec['email'];
		
			$mailer =& JFactory::getMailer();

			$config =& JFactory::getConfig();
			$sender = FSS_EMail::Get_Sender();
		
			$mailer->setSender($sender);
		
			FSS_EMail::AddTicketRecpts($mailer, $ticket, $custrec);
		
			$template = FSS_EMail::Get_Template('email_on_close');
			$email = FSS_EMail::ParseTemplate($template,$ticket,$subject,$body,$template['ishtml']);
		
			$mailer->isHTML($template['ishtml']);
			$mailer->setSubject($email['subject']);
			$mailer->setBody($email['body']);
		
			/*print_p($mailer);
			exit;*/
			$send =& $mailer->Send();
			
			//print_p($send);
			//print_p($mailer);
			//exit;
		}
	}
	
	function Admin_AutoClose(&$ticket)
	{
		// ticket replyd to
		$db =& JFactory::getDBO();

		$custid = $ticket['user_id'];
		$qry = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $custid)."'";
		$db->setQuery($qry);
		$custrec = $db->loadAssoc();
		
		$email = $custrec['email'];
		
		$mailer =& JFactory::getMailer();

		$config =& JFactory::getConfig();
		$sender = FSS_EMail::Get_Sender();
		
		$mailer->setSender($sender);
		
		FSS_EMail::AddTicketRecpts($mailer, $ticket, $custrec);
		
		$template = FSS_EMail::Get_Template('email_on_autoclose');
		$email = FSS_EMail::ParseTemplate($template,$ticket,$template['subject'],"",$template['ishtml']);
		
		$mailer->isHTML($template['ishtml']);
		$mailer->setSubject($email['subject']);
		$mailer->setBody($email['body']);
		
		$send =& $mailer->Send();

	}
	
	function AddTicketRecpts(&$mailer, &$ticket, &$custrec)
	{
		// add ticket user as recipient
		if ($ticket['user_id'] == 0)
		{
			$recipient = array($ticket['email']/*, $ticket['unregname']*/);
		} else {
			$recipient = array($custrec['email']/*, $custrec['name']*/);
		}
		
		$mailer->addRecipient($recipient);
		
		// check for any ticket cc users
		FSS_EMail::GetTicketCC($ticket);
		
		if (count($ticket['cc'] > 0))
		{
			foreach ($ticket['cc'] as $cc)
			{
				$mailer->addCC(array($cc['email']/*, $cc['name']*/));	
			}
		}		
		
		// if user_id on ticket is set, then check for any group recipients
		if ($ticket['user_id'] > 0)
		{
			$db =& JFactory::getDBO();
			
			// get groups that the user belongs to
			$qry = "SELECT * FROM #__fss_ticket_group WHERE id IN (SELECT group_id FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $ticket['user_id'])."')";
			$db->setQuery($qry);
			//echo $qry."<br>";
			$groups = $db->loadObjectList('id');
			
			if (count($groups) > 0)
			{
				//print_p($groups);
			
				$gids = array();
			
				foreach ($groups as $id => &$group)
				{
					$gids[$id] = $id;	
				}
			
				// get list of users in the groups
				$qry = "SELECT m.*, u.email, u.name FROM #__fss_ticket_group_members as m LEFT JOIN #__users as u ON m.user_id = u.id WHERE group_id IN (" . implode(", ",$gids) . ")";
				$db->setQuery($qry);
				//echo $qry."<br>";
				$users = $db->loadObjectList();
				//print_p($users);
				
				$toemail = array();
				
				// for all users, if group has cc or user has cc then add to cc list			
				foreach($users as &$user)
				{
					if ($user->allemail || $groups[$user->group_id]->allemail)
					{
						$toemail[$user->email] = $user->name;

					}
				}	
				
				foreach ($toemail as $email => $name)
					$mailer->addCC(array($email/*, $name*/));	
			}
			
			
			
		}
	}
	
	function GetTicketCC(&$ticket)
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT u.name, u.id, u.email FROM #__fss_ticket_cc as c LEFT JOIN #__users as u ON c.user_id = u.id WHERE c.ticket_id = {$ticket['id']} ORDER BY name";
		$db->setQuery($qry);
		$ticket['cc'] = $db->loadAssocList();
	}

	function Admin_Forward(&$ticket, $subject, $body)
	{
		// ticket replyd to
		if (FSS_Settings::get('support_email_handler_on_forward') == 1)
		{		
			$db =& JFactory::getDBO();

			$admin_id = $ticket['admin_id'];
			$query = " SELECT a.*, au.name, au.username, au.email FROM #__fss_user as a ";
			$query .= " LEFT JOIN #__users as au ON a.user_id = au.id ";
			$query .= " WHERE a.id = '".FSSJ3Helper::getEscaped($db, $admin_id)."'";
			$db->setQuery($query);
			$admin_rec = $db->loadAssoc();
		
			$email = $admin_rec['email'];
		
			$mailer =& JFactory::getMailer();

			$config =& JFactory::getConfig();
			$sender = FSS_EMail::Get_Sender();
	
			$mailer->setSender($sender);
		
			$recipient = array($admin_rec['email']);
			//print_r($recipient);
			$mailer->addRecipient($recipient);
		
			$template = FSS_EMail::Get_Template('email_handler_on_forward');
			$email = FSS_EMail::ParseTemplate($template,$ticket,$subject,$body,$template['ishtml']);

			$mailer->isHTML($template['ishtml']);
			$mailer->setSubject($email['subject']);
			$mailer->setBody($email['body']);
		
			$send =& $mailer->Send();
		}
	}

	function User_Create(&$ticket, $subject, $body)
	{
		// ticket replyd to
		if (FSS_Settings::get('support_email_on_create') == 1)
		{		
			$db =& JFactory::getDBO();

			$custid = $ticket['user_id'];
			$qry = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $custid)."'";
			$db->setQuery($qry);
			$custrec = $db->loadAssoc();
		
			$email = $custrec['email'];
		
			$mailer =& JFactory::getMailer();

			$config =& JFactory::getConfig();
			$sender = FSS_EMail::Get_Sender();
		
			$mailer->setSender($sender);
		
			$recipient = array($custrec['email']);
		
			$mailer->addRecipient($recipient);
		
			$template = FSS_EMail::Get_Template('email_on_create');
			$email = FSS_EMail::ParseTemplate($template,$ticket,$subject,$body,$template['ishtml']);

			$mailer->isHTML($template['ishtml']);
			$mailer->setSubject($email['subject']);
			$mailer->setBody($email['body']);
		
			$send =& $mailer->Send();
		}
	}

	function User_Create_Unreg(&$ticket, $subject, $body)
	{
		// ticket replyd to
		if (FSS_Settings::get('support_email_on_create') == 1)
		{		
			$db =& JFactory::getDBO();

			$custid = $ticket['user_id'];
			$qry = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $custid)."'";
			$db->setQuery($qry);
			$custrec = $db->loadAssoc();
		
			$email = $custrec['email'];
		
			$mailer =& JFactory::getMailer();

			$config =& JFactory::getConfig();
			$sender = FSS_EMail::Get_Sender();
		
			$mailer->setSender($sender);
		
			$recipient = array($ticket['email']);
		
			$mailer->addRecipient($recipient);
		
			$template = FSS_EMail::Get_Template('email_on_create_unreg');
			$email = FSS_EMail::ParseTemplate($template,$ticket,$subject,$body,$template['ishtml']);

			$mailer->isHTML($template['ishtml']);
			$mailer->setSubject($email['subject']);
			$mailer->setBody($email['body']);
		
			$send =& $mailer->Send();
		}
	}

	function Admin_Create(&$ticket, $subject, $body)
	{
		// ticket replyd to
		if (FSS_Settings::get('support_email_handler_on_create') == 1)
		{		
			$db =& JFactory::getDBO();

			$admin_id = $ticket['admin_id'];
			if ($admin_id < 1)
			{
				$admin_rec['email'] = trim(FSS_Settings::get('support_email_unassigned'));
				
				if ($admin_rec['email'] == "")
					return;
			} else {
		
				$query = " SELECT a.*, au.name, au.username, au.email FROM #__fss_user as a ";
				$query .= " LEFT JOIN #__users as au ON a.user_id = au.id ";
				$query .= " WHERE a.id = '".FSSJ3Helper::getEscaped($db, $admin_id)."'";
				$db->setQuery($query);
				$admin_rec = $db->loadAssoc();
			} 
		
			$custid = $ticket['user_id'];
			$qry = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $custid)."'";
			$db->setQuery($qry);
			$custrec = $db->loadAssoc();

			$email = $custrec['email'];
		
			$mailer =& JFactory::getMailer();

			$config =& JFactory::getConfig();
			$sender = FSS_EMail::Get_Sender();
		
			$mailer->setSender($sender);
		
			$recipient = array($admin_rec['email']);
			//print_r($recipient);
			$mailer->addRecipient($recipient);
		
			$template = FSS_EMail::Get_Template('email_handler_on_create');
			$email = FSS_EMail::ParseTemplate($template,$ticket,$subject,$body,$template['ishtml']);

			$mailer->isHTML($template['ishtml']);
			$mailer->setSubject($email['subject']);
			$mailer->setBody($email['body']);

			$send =& $mailer->Send();
		}
	}

	function User_Reply(&$ticket, $subject, $body)
	{
		if (FSS_Settings::get('support_email_handler_on_reply') == 1)
		{		
			$db =& JFactory::getDBO();

			$admin_id = $ticket['admin_id'];
			if ($admin_id < 1)
				return;
		
			$query = " SELECT a.*, au.name, au.username, au.email FROM #__fss_user as a ";
			$query .= " LEFT JOIN #__users as au ON a.user_id = au.id ";
			$query .= " WHERE a.id = '".FSSJ3Helper::getEscaped($db, $admin_id)."'";
			$db->setQuery($query);
			$admin_rec = $db->loadAssoc();
		
			//print_r($admin_rec);
		
			$email = $admin_rec['email'];
		
			$mailer =& JFactory::getMailer();

			$config =& JFactory::getConfig();
			$sender = FSS_EMail::Get_Sender();
		
			$mailer->setSender($sender);
		
			$recipient = array($admin_rec['email']);
			//print_r($recipient);
			$mailer->addRecipient($recipient);
		
			$template = FSS_EMail::Get_Template('email_handler_on_reply');
			$email = FSS_EMail::ParseTemplate($template,$ticket,$subject,$body,$template['ishtml']);

			$mailer->isHTML($template['ishtml']);
			$mailer->setSubject($email['subject']);
			$mailer->setBody($email['body']);
		
			$send =& $mailer->Send();
		}
	}

	function &GetHandler($admin_id)
	{
		if ($admin_id == 0)
		{
			$res = array("name" => JText::_("UNASSIGNED"),"username" => JText::_("UNASSIGNED"),"email" => "");
			return $res;	
		}
		$db =& JFactory::getDBO();
		$query = " SELECT a.*, au.name, au.username, au.email FROM #__fss_user as a ";
		$query .= " LEFT JOIN #__users as au ON a.user_id = au.id ";
		$query .= " WHERE a.id = '".FSSJ3Helper::getEscaped($db, $admin_id)."'";
		$db->setQuery($query);
		$handler = $db->loadAssoc();
		return $handler;
	} 

	function &GetUser($user_id)
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row;
	}

	function GetStatus($status_id)
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_ticket_status WHERE id = '".FSSJ3Helper::getEscaped($db, $status_id)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}

	function GetArticle($artid)
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_kb_art WHERE id = '".FSSJ3Helper::getEscaped($db, $artid)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}

	function GetPriority($pri_id)
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_ticket_pri WHERE id = '".FSSJ3Helper::getEscaped($db, $pri_id)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}

	function GetCategory($cat_id)
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_ticket_cat WHERE id = '".FSSJ3Helper::getEscaped($db, $cat_id)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}

	function GetDepartment($dept_id)
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_ticket_dept WHERE id = '".FSSJ3Helper::getEscaped($db, $dept_id)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}

	function GetProduct($prod_id)
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_prod WHERE id = '".FSSJ3Helper::getEscaped($db, $prod_id)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}
	
	function GetMessageHist($ticket_id)
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT m.*, u.name, u.username, u.email FROM #__fss_ticket_messages as m";
		$qry .= " LEFT JOIN #__users as u ON m.user_id = u.id";
		$qry .= " WHERE ticket_ticket_id = '".FSSJ3Helper::getEscaped($db, $ticket_id)."'";	
		$qry .= " AND admin IN (0, 1) ORDER BY posted DESC";
		
		//echo $qry."<br>";
		$db->setQuery($qry);
		$rows = $db->loadAssocList();
		
		return $rows;
	}

	function &ParseTemplate($template,&$ticket,$subject,$body,$ishtml)
	{
		$handler = FSS_EMail::GetHandler($ticket['admin_id']);
		$custrec = FSS_EMail::GetUser($ticket['user_id']);
	
		$subject = trim(str_ireplace("re:","",$subject));
		$vars[] = FSS_EMail::BuildVar('subject',$subject);
		if ($ishtml)
		{
			$body = str_replace("\n","<br>\n",$body);	
		}
		$vars[] = FSS_EMail::BuildVar('body',$body);
		$vars[] = FSS_EMail::BuildVar('reference',$ticket['reference']);
		$vars[] = FSS_EMail::BuildVar('password',$ticket['password']);
		
		if ($ticket['user_id'] == 0)
		{
			$vars[] = FSS_EMail::BuildVar('user_name',$ticket['unregname']);
			$vars[] = FSS_EMail::BuildVar('user_username',JText::_("UNREGISTERED"));
			$vars[] = FSS_EMail::BuildVar('user_email',$ticket['email']);
		} else {
			$vars[] = FSS_EMail::BuildVar('user_name',$custrec['name']);
			$vars[] = FSS_EMail::BuildVar('user_username',$custrec['username']);
			$vars[] = FSS_EMail::BuildVar('user_email',$custrec['email']);
		}
		$vars[] = FSS_EMail::BuildVar('handler_name',$handler['name']);
		$vars[] = FSS_EMail::BuildVar('handler_username',$handler['username']);
		$vars[] = FSS_EMail::BuildVar('handler_email',$handler['email']);
		
		$vars[] = FSS_EMail::BuildVar('ticket_id',$ticket['id']);
		$vars[] = FSS_EMail::BuildVar('status',FSS_EMail::GetStatus($ticket['ticket_status_id']));
		$vars[] = FSS_EMail::BuildVar('priority',FSS_EMail::GetPriority($ticket['ticket_pri_id']));
		$vars[] = FSS_EMail::BuildVar('category',FSS_EMail::GetCategory($ticket['ticket_cat_id']));
		$vars[] = FSS_EMail::BuildVar('department',FSS_EMail::GetDepartment($ticket['ticket_dept_id']));
		$vars[] = FSS_EMail::BuildVar('product',FSS_EMail::GetProduct($ticket['prod_id']));
		
		if (strpos($template['body'],"{messagehistory}") > 0)
		{
			//echo "Get message history<br>";	
			$messages = FSS_EMail::GetMessageHist($ticket['id']);
			
			// need to load in the messagerow template and parse it
			$text = FSS_EMail::ParseMessageRows($messages, $ishtml);
			
			$vars[] = FSS_EMail::BuildVar('messagehistory',$text);
			//print_p($messages);
		}
		
		$uri =& JURI::getInstance();
		$baseUrl = $uri->toString( array('scheme', 'host', 'port'));
		
		$vars[] = FSS_EMail::BuildVar('ticket_link',$baseUrl . FSSRoute::_('index.php?option=com_fss&view=ticket&ticketid=' . $ticket['id']));
		$vars[] = FSS_EMail::BuildVar('admin_link',$baseUrl . FSSRoute::_('index.php?option=com_fss&view=admin&layout=support&ticketid=' . $ticket['id']));

		$config = JFactory::getConfig();
		if (FSSJ3Helper::IsJ3())
		{
			$sitename = $config->get('sitename');
		} else {
			$sitename = $config->getValue('sitename');	
		}
		
		if (FSS_Settings::get('support_email_site_name') != "")
			$sitename = FSS_Settings::get('support_email_site_name');

		$vars[] = FSS_EMail::BuildVar('websitetitle',$sitename);
	
		// need to add the tickets custom fields to the output here
		
		$fields = FSSCF::GetAllCustomFields(true);
		$values = FSSCF::GetTicketValues($ticket['id'],$ticket);
		
		foreach ($fields as $fid => &$field)
		{
			$name = "custom_" . $fid;
			$value = "";
			if (array_key_exists($fid, $values))
				$value = $values[$fid]['value'];
			//echo "$name -> $value<br>";
			
			$fieldvalues = array();
			$fieldvalues[0]['field_id'] = $fid;
			$fieldvalues[0]['value'] = $value;
			
			// only do area output processing if we are in html mode
			if ($field['type'] != "area" || $ishtml)
				$value = FSSCF::FieldOutput($field, $fieldvalues, '');
			
			$vars[] = FSS_EMail::BuildVar($name, $value);
		}
		
		$email['subject'] = FSS_EMail::ParseText($template['subject'],$vars);
		$email['body'] = FSS_EMail::ParseText($template['body'],$vars);
	
		if ($template['ishtml'])
			$email['subject'] = str_replace("\n","<br \>\n",$email['subject']);
		
		return $email;	
	}
	
	function ParseMessageRows(&$messages, $ishtml)
	{
		$template = FSS_EMail::Get_Template('messagerow');
		$result = "";
		
		foreach ($messages as &$message)
		{
			$vars = array();
			//print_p($message);
			if ($message['name'])
			{
				$vars[] = FSS_EMail::BuildVar('name',$message['name']);
				$vars[] = FSS_EMail::BuildVar('email',$message['email']);
				$vars[] = FSS_EMail::BuildVar('username',$message['username']);
			} else {
				$vars[] = FSS_EMail::BuildVar('name','Unknown');
				$vars[] = FSS_EMail::BuildVar('email','Unknown');
				$vars[] = FSS_EMail::BuildVar('username','Unknown');
			}
			$vars[] = FSS_EMail::BuildVar('subject',$message['subject']);
			$vars[] = FSS_EMail::BuildVar('posted',FSS_Helper::Date($message['posted']));
			
			if ($ishtml)
				$message['body'] = str_replace("\n","<br>\n",$message['body']);	
			$vars[] = FSS_EMail::BuildVar('body',$message['body']);	
			
			$result .= FSS_EMail::ParseText($template['body'],$vars);
		}
		
		return $result;
	}

	function BuildVar($name,$value)
	{
		$data['name'] = $name;
		$data['value'] = $value;
		return $data;
	}

	function ParseText($text,&$vars)
	{
		foreach ($vars as $var)
		{
			//echo "Proc : {$var['name']}<br>";
			$value = $var['value'];
			$block = "{".$var['name']."}";
			$start = "{".$var['name']."_start}";
			$end = "{".$var['name']."_end}";
		
			if ($value != "")
			{
				$text = str_replace($block, $value, $text);	
				$text = str_replace($start, "", $text);	
				$text = str_replace($end, "", $text);	
			} else {
				$text = str_replace($block, "", $text);	
				$pos_end = strpos($text, $end);
				$pos_beg = strpos($text, $start);
				//echo "$start = $pos_beg, $end = $pos_end<br>";
				if ($pos_end && $pos_beg){
					$text = substr_replace($text, '', $pos_beg, ($pos_end - $pos_beg) + strlen($end));
				}
			}
		}
		return $text;
	}

	function Get_Template($tmpl)
	{
		$db =& JFactory::getDBO();
		$qry = 	"SELECT body, subject, ishtml FROM #__fss_emails WHERE tmpl = '".FSSJ3Helper::getEscaped($db, $tmpl)."'";
		$db->setQuery($qry);
		return $db->loadAssoc();
	}
}
