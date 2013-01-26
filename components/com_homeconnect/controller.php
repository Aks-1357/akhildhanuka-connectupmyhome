<?php
/**
 * @version     1.0.0
 * @package     com_homeconnect
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      akshay <akshay1357agarwal@gmail.com> - http://
 */
 
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport( 'joomla.utilities.utility' );

class HomeconnectController extends JController
{
	public function callWebService()
	{
		// create curl resource
		$ch = curl_init();

		// set url
		curl_setopt($ch, CURLOPT_URL, "http://50.18.20.35:15100/cgi-bin/xsearch?query=2085&rpf_navigation:enabled=1");

		// return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// $output contains the output string
		$output = curl_exec($ch);
		$xml = new SimpleXMLElement($output);

		// close curl resource to free up system resources
		curl_close($ch);

		$result = array();

		for ($i=0; $i < $xml->SEGMENT->RESULTPAGE->NAVIGATION['ENTRIES'][0]; $i++)
		{
			$temp = array();
			if($xml->SEGMENT->RESULTPAGE->NAVIGATION->NAVIGATIONENTRY[$i]['NAME'][0] == "rspnav")
			{
				for ($j=0; $j < ($xml->SEGMENT->RESULTPAGE->NAVIGATION->NAVIGATIONENTRY[$i]->NAVIGATIONELEMENTS['COUNT'][0]); $j++)
				{
					$temp[$j]['brand'] = $xml->SEGMENT->RESULTPAGE->NAVIGATION->NAVIGATIONENTRY[$i]->NAVIGATIONELEMENTS->NAVIGATIONELEMENT[$j]['NAME'][0];
				}
			}
			if($xml->SEGMENT->RESULTPAGE->NAVIGATION->NAVIGATIONENTRY[$i]['NAME'][0] == "taxonomynavigator")
			{
				for ($j = 0; $j < ($xml->SEGMENT->RESULTPAGE->NAVIGATION->NAVIGATIONENTRY[$i]->NAVIGATIONELEMENTS['COUNT'][0]); $j++)
				{
					$temp[$j]['category'] = $xml->SEGMENT->RESULTPAGE->NAVIGATION->NAVIGATIONENTRY[$i]->NAVIGATIONELEMENTS->NAVIGATIONELEMENT[$j]['NAME'][0];
				}
			}
			if(!empty($temp))
			{
				array_push($result, $temp);
			}
		}
		echo json_encode($result);
	}

	public function sendEmail()
	{
		
	
			// Aks :
			try 
			{
					$app	= JFactory::getApplication();
					$mailfrom	= $app->getCfg('mailfrom');
					$fromname	= $app->getCfg('fromname');
					$sitename	= $app->getCfg('sitename');
					
					$name	= 'ConnectUpMyHomeAdmin';
					$email	= $_POST['mail'];
					$subject	= $_POST['subject'];
					$body	= $_POST['data'];
					
					// Prepare email body
					//$body	= $name.' <'.$email.'>'."\r\n\r\n".stripslashes($body);
					
					
					$mail = JFactory::getMailer();
					$mail->addRecipient($email);
					$mail->AddCC($mailfrom);
					 $mail->IsHTML(true);
					$mail->addReplyTo(array($email, $name));
					$mail->setSender(array($mailfrom, $fromname));
					$mail->setSubject($sitename.': '.$subject);
					$mail->setBody($body);
					
					$send=$mail->Send();
					if($send=="true")
					 echo "success";
					 else 
					 echo $send;
				}
					catch (Exception $e)
				{
					echo false;
				}
	}		
			
	}