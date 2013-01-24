<?php
/**
 * @version     1.0.0
 * @package     com_homeconnect
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      vinayak <vinayakbardale@gmail.com> - http://
 */
 
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

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
		try
		{
			$data		= $_POST['data'];
			$sendto		= $_POST['mail'];
			$subject	= $_POST['subject'];
			$mailer		= &JFactory::getMailer();
			$config		= &JFactory::getConfig();
			$sender		= array( $config->getValue( 'config.mailfrom' ),
								$config->getValue( 'config.fromname' ) );

			// echo $mailer;

			$mailer->setSender($sender);
			$mailer->addRecipient($sendto);
			$mailer->setSubject($subject);
			$mailer->setBody("Testing Connect Up My Home");

			$send = &$mailer->Send();

			if ( $send !== true ) 
			{
				echo 'ERROR : Sending Email Failed' ;
			}
			else
			{
				echo 'Message : Mail Sent Successfully !!!';
			}
		}
		catch (Exception $e)
		{
			echo 'ERROR : '.$e;
		}
	}
}