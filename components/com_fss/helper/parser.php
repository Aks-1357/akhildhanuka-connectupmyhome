<?php

class FSSParser
{
	var $template = "";
	var $vars = array();

	var $loadedtmpl;
	var $loadedtype;

	function Load($template, $tpltype)
	{
		//echo "Loading $template => $tpltype<br>";
		if ($this->loadedtmpl == $template && $this->loadedtype == $tpltype)
			return;
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__fss_templates WHERE template = '" . FSSJ3Helper::getEscaped($db, $template) . "' AND tpltype = " . FSSJ3Helper::getEscaped($db, $tpltype);
		$db->SetQuery($query);
		$tmpl = $db->LoadObject();
		$this->template = $tmpl->value;
		$this->ProcessLanguage();
		
		$this->loadedtmpl = $template;
		$this->loadedtype = $tpltype;
	}	
	
	function ProcessLanguage()
	{
		$text = $this->template;
		if (preg_match_all("/\%([A-Za-z_]+)\%/", $text, $matches))
		{
			foreach($matches[1] as $match)
			{
				$find = "%" . $match . "%";
				$replace = JText::_($match);

				$text = str_replace($find, $replace, $text);
			}
			
			$this->template = $text;
		}
	}
	
	function Clear()
	{
		$this->vars = array();	
	}

	function SetVar($var, $value)
	{
		$this->vars[$var] = $value;
	}

	function AddVars(&$vars)
	{
		foreach($vars as $var => $value)
			$this->vars[$var] = $value;
	}

	function Parse()
	{
		//echo "<pre>";
		//print_r($this->vars);
		//echo "</pre>";
		$t = $this->template;
		//echo "Parsing : <pre>" . htmlentities($t) . "</pre><br>";
		$o = $this->ParseInt($t);

		//echo "Result : <pre>" . htmlentities($o) . "</pre><br>";
		//exit;
		return $o;
	}

	function ParseInt($t)
	{
		$max = 0;
		$o = "";
		$toffset = 0;
		
		while (strpos($t,"{",$toffset) !== false && $max < 1000)
		{
			$start = strpos($t,"{",$toffset)+1;	
			$end = strpos($t,"}",$start);
			$tag = substr($t,$start,$end-$start);
			$max++;

			$bits = explode(",",$tag);
			//echo "Tag : " . $bits[0] . "<br>";

			$o .= substr($t,$toffset,$start-$toffset-1);
			$toffset = $end + 1;

			if ($bits[0] == "if" || $bits[0] == "endif")
			{
				//echo "Processing IF <br>";

				// find the endif. Allows nested if statements
				$open = 1;
				$ifstart = $toffset;
				while (strpos($t,"{",$toffset !== false) && $open > 0)
				{
					$start = strpos($t,"{",$toffset)+1;	
					$end = strpos($t,"}",$start);
					$tag = substr($t,$start,$end-$start);

					$bits2 = explode(",",$tag);
					if ($bits2[0] == "if")
					{
						$open ++;	
					} else if ($bits2[0] == "endif")
					{
						$open--;	
					}
					$toffset = $end + 1;
					//echo "If tag $tag, depth = $open<br>";
				}
				$ifend = $toffset;
				$ifcode = substr($t,$ifstart,$ifend-$ifstart-7);

				//echo "IF Code : <pre>" . htmlentities($ifcode) . "</pre><br>";

				// match the if
				$matched = false;
				//echo "If: " . print_r($bits, true) . " - ";
				//echo $this->vars[$bits[1]] . " - ";
				if (count($bits) == 2)
				{
					$var = $bits[1];

					if (array_key_exists($var,$this->vars))
					{
						$value = $this->vars[$var];
						if ($value)
							$matched = true;
					}
				} else if (count($bits) == 3)
				{
					$var = $bits[1];
					$value = trim($bits[2],"\"'");	
					
					if (array_key_exists($var,$this->vars))
					{
						$varvalue = $this->vars[$var];
						if ($varvalue == $value)
							$matched = true;
					}
				} else if (count($bits) == 4)
				{
					$var = $bits[1];
					$value = trim($bits[2],"\"'");	
					$op = $bits[3];
					if (array_key_exists($var,$this->vars))
					{
						$varvalue = $this->vars[$var];
						if ($op == "not")
						{
							if ($varvalue != $value)
								$matched = true;
						} else {
							if ($varvalue == $value)
								$matched = true;
						}
					}
				}

				/*if ($matched)
					echo "TRUE";
				else 
					echo "FALSE";

				echo "<br>";*/
				// if IF statement is matched, parse the insides of it
				if ($matched)
					$o .= $this->ParseInt($ifcode);
			} else if ($bits[0] == "set")
			{
				if (count($bits) == 3)
				{
					$var = $bits[1];
					$value = $bits[2];
					if (is_numeric($value))
					{
						$this->vars[$var] = $value;	
					} else if ( 
							(substr($value,0,1) == "\"" || substr($value,0,1) == "'") &&
							(substr($value,strlen($value)-1,1) == "\"" || substr($value,strlen($value)-1,1) == "'"))
					{
						$this->vars[$var] = trim($value,"\"'");	
					} else if (array_key_exists($value,$this->vars))
					{
						$this->vars[$var] = $this->vars[$value];
					} else {
						$this->vars[$var] = $value;	
					}
					//echo "Setting $var to {$this->vars[$var]}<br>";
				}
			} else {
				if (array_key_exists($bits[0],$this->vars))
				{
					$o .= $this->vars[$bits[0]];
				}	
			}
		}

		$o .= substr($t,$toffset);

		if ($max == 1000)
			exit;
		
		return $o;
	}

	function ParserPopulateTicket(&$parser, $row)
	{
		$parser->Clear();
		
		if ($row)
		{
			$parser->SetVar('ref',$row['reference']);
			$parser->SetVar('subject',"<a href='".FSSRoute::_( 'index.php?option=com_fss&view=admin&layout=support&ticketid=' . $row['id'] )."'>" . $row['title'] . "</a>");
			$parser->SetVar("status","<span style='color:" . $row['color'] . ";'>" . $row['status'] . "</span>");

			if ($row['user_id'] == 0)
			{
				$name = $row['unregname'] . " (" . JText::_("UNREG") . ")";
			} else {
				$name = $row['name'];
			}
			$parser->SetVar("name",$name);
			$parser->SetVar("lastactivity",FSS_Helper::Date($row['lastupdate'],FSS_DATETIME_SHORT));
			$parser->SetVar("opened",FSS_Helper::Date($row['opened'],FSS_DATETIME_SHORT));
			$parser->SetVar("closed",FSS_Helper::Date($row['closed'],FSS_DATETIME_SHORT));
			$parser->SetVar("department",$row['department']);
			$parser->SetVar("category",$row['category']);
			$parser->SetVar("product",$row['product']);
			$parser->SetVar("priority","<span style='color:" . $row['pricolor'] . ";'>" . $row['priority'] . "</span>");

			$groups = implode(", ", $row['groups']);
			$parser->SetVar('groups',$groups);

			$parser->SetVar('deletebutton','deletebutton');
			$parser->SetVar('archivebutton','archivebutton');

			if (array_key_exists("custom",$row))
			{
				$allcustom = array();
				if (count($row['custom']) > 0)
				{
					foreach	($row['custom'] as $id => $value)
					{
						if (array_key_exists($id,$this->customfields))
						{
							$field = $this->customfields[$id];
									
							if ($field['type'] == "plugin")
							{
								$aparams = FSSCF::GetValues($field);
								if (array_key_exists("plugin", $aparams) && array_key_exists("plugindata", $aparams))
								{	
									$plugin = FSSCF::get_plugin($aparams['plugin']);
									$value = $plugin->Display($value, $aparams['plugindata'], array('ticketid' => $row['id'], 'userid' => $row['user_id'], 'ticket' => $row), $field['id']);
								}
							}
		
							$text = "<span class='fss_support_fieldname'>" . $this->customfields[$id]['description'] . "</span>";
							if ($this->customfields[$id]['type'] == "checkbox")
							{
								if ($value == "on")
									$text .= ": " . JText::_("Yes");
								else
									$text .= ": " . JText::_("No");
							} else {
								$text .= ": " . $value;
							}
							$parser->SetVar("custom".$id,$text);	
							$allcustom[] = $text;
						}
					}
				}
				//echo "All custom : " . implode(", ",$allcustom) . "<br>";
				$parser->SetVar("custom",implode(", ",$allcustom));
			}

			if ($row['assigned'] == '')
			{
				$parser->SetVar('handlername',JText::_("UNASSIGNED"));
			} else {
				$parser->SetVar('handlername',$row['assigned']);
			}
			$parser->SetVar('username',$row['username']);
			$parser->SetVar('email',$row['useremail']);
			$parser->SetVar('handlerusername',$row['handlerusername']);
			$parser->SetVar('handleremail',$row['handleremail']);
			
			$icons = "";
			if (FSS_Settings::get('support_show_msg_counts'))
			{
				$icons .= "<span>";
				$icons .= "<span style='font-weight:normal;top:-2px;position:relative;padding-right:2px;'>".$row['msgcount']['total']."</span><span style='font-weight:normal;top:-2px;position:relative;padding-right:2px;'>x</span><img src='".JURI::root( true )."/components/com_fss/assets/images/messages.png'>";
				$icons .= "</span>";
			}

			$cotime = $this->db_time - strtotime($row['checked_out_time']);
			if ($cotime < FSS_Settings::get('support_lock_time') && $row['checked_out'] != $this->userid && $row['checked_out'] > 0)
			{
				$html = "<div class='fss_user_tt'>" . $row['co_user']->name . " (" .  $row['co_user']->email . ")</div>";
				$icons .= "<img class='fsj_tip' src='" . JURI::root( true ) . "/components/com_fss/assets/images/lock.png' title=\"".JText::_('TICKET_LOCKED') ."::" . $html . "\">";
			} else {
				$icons .= "<img src='" . JURI::root( true ) . "/components/com_fss/assets/images/blank_16.png'>";
			}

			if (!FSS_Settings::get('support_hide_tags'))
			{
				if (isset($row['tags']))
				{
					$html = "";
					foreach($row['tags'] as $tag)
					{
						$html .= "<div class='fss_tag_tt'>" . $tag['tag'] . "</div>";
					}
					$icons .= "<img class='fsj_tip' src='". JURI::root( true ) . "/components/com_fss/assets/images/tag.png' title=\"".JText::_('TICKET_TAGS') ."::" . $html ."\">";
				} else {
					$icons .= "<img src='" . JURI::root( true ) . "/components/com_fss/assets/images/blank_16.png'>";
				}
			}

			if (isset($row['attach']))
			{
				$html = "<table class='fss_attach_tt'>";
				foreach($row['attach'] as $attach)
				{
					$html .= "<tr style='border:0px;'><td nowrap style='border:0px;'>" . $attach['filename'] . "&nbsp;</td><td nowrap style='border:0px;'>&nbsp;".FSS_Helper::display_filesize($attach['size']) . "</td></tr>";
				}
				$html .= "<table>";				
				$icons .= "<img class='fsj_tip' src='". JURI::root( true ) . "/components/com_fss/assets/images/attach.png' title=\"".JText::_('TICKET_ATTACHMENTS') ."::" . $html ."\">";
			} else {
				$icons .= "<img src='" . JURI::root( true ) . "/components/com_fss/assets/images/blank_16.png'>";
			}

			$parser->SetVar('icons',$icons);

			$delete = "<a href='" . FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets='.JRequest::getVar('tickets').'&delete=' . $row['id'] ) . "'>";
			$delete .= "<img src='" . JURI::root( true ) . "/components/com_fss/assets/images/delete_ticket.png'>";
			$delete .= JText::_("DELETE") . "</a>";

			$archive = "<a href='" . FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets='.JRequest::getVar('tickets').'&archive=' . $row['id'] ) . "'>";
			$archive .= "<img src='" . JURI::root( true ) . "/components/com_fss/assets/images/archive_ticket.png'>";
			$archive .= JText::_("ARCHIVE") . "</a>";
			
			$parser->SetVar('archivebutton',$archive);
			$parser->SetVar('deletebutton',$delete);
			
			// TODO: trhl
			
			/*id='ticket_<?php echo $ticket['id'];?>'
			onmouseover="$('ticket_<?php echo $ticket['id'];?>').style.background = '<?php echo FSS_Settings::get('css_hl'); ?>';$('ticket_<?php echo $ticket['id'];?>_2').style.background = '<?php echo FSS_Settings::get('css_hl'); ?>';"
			onmouseout="$('ticket_<?php echo $ticket['id'];?>').style.background = '';$('ticket_<?php echo $ticket['id'];?>_2').style.background = '';"*/

			$trhl = " class='ticket_{$row['id']}' onmouseover='highlightticket({$row['id']})' onmouseout='unhighlightticket({$row['id']})' ";
			if (FSS_Settings::get('support_entire_row'))
			{
				$trhl .= " style='cursor: pointer;' onclick='window.location=\"".FSSRoute::x( '&limitstart=&ticketid=' . $row['id'] )."\"' ";
			}
			$parser->SetVar('trhl',$trhl);
		}

		$parser->SetVar("showassigned",$this->showassigned);
		$parser->SetVar("hidehandler",FSS_Settings::get('support_hide_handler'));
		$parser->SetVar("candelete",FSS_Settings::get('support_delete'));
		$parser->SetVar("view",$this->ticket_view);
	}

}