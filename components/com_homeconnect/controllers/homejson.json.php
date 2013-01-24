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

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Homeconnect controller class.
 */
class HomeconnectControllerHomejson extends HomeconnectController
{

	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since	1.6
	 */

	/*
	Aks : No need of this file
	function send()
	{
		$sendto=$_POST['mail'];
		$data=$_POST['data'];
		$subject=$_POST['subject'];
		$mailer =& JFactory::getMailer();
		$config =& JFactory::getConfig();
		$sender = array( 
					$config->getValue( 'config.mailfrom' ),
					$config->getValue( 'config.fromname' ) );

		echo $mailer;

		$mailer->setSender($sender);
		$mailer->addRecipient($sendto);
		$mailer->setSubject($subject);
		$mailer->setBody("hello");
		$send =& $mailer->Send();
		if ( $send !== true ) 
		{
			echo 'Error sending email: ';
		}
		else
		{
			echo 'Mail sent';
		}
	}
	*/
}