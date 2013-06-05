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

JLoader::import('joomla.application.component.model');
JLoader::import( 'createbundle', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_homeconnect' . DS . 'models' );

class HomeconnectController extends JController
{
	public function getProductsDataFromAPI()
	{
		$model = JModel::getInstance( 'createbundle', 'HomeconnectModel' );

		$superCat = JRequest::getVar('sup_cat_name');
		if(!isset($superCat) || empty($superCat))
		{
			$superCat = "";
		}

		$model->getProductsDataFromAPI( $superCat,
										JRequest::getVar('sub_cat_name'),
										JRequest::getVar('brand_name'),
										JRequest::getVar('prev_id'),
										JRequest::getVar('next_id'),
										JRequest::getVar('accordion_no'),
										JRequest::getVar('noc') );
	}
	public function getBrandsDataFromAPI()
	{
		$model = JModel::getInstance( 'createbundle', 'HomeconnectModel' );

		$Category = JRequest::getVar('cat_name');
		if(!isset($Category) || empty($Category))
		{
			$Category = "";
		}

		$model->getBrandsDataFromAPI($Category);
	}

	public function createlogsendemail()
	{
		$this->createcsv();

		echo $this->sendEmail();
	}

	public function createcsv()
	{
		$log = JRequest::getVar("log");
		$csv_fields = $log;

		$csv_folder = JPATH_ROOT.'/csv';

		$filename = JRequest::getVar("ip");

		$email	= explode("@", JRequest::getVar("email"));

		$CSVFileName = $csv_folder.'/'.$filename.'_'.$email[0].'.csv';
		$i = 1;

		while(file_exists($CSVFileName))
		{
			$CSVFileName=$csv_folder.'/'.$filename.'_'.$email[0].'('.$i.').csv';
			$i++;
		}

		// $TextFileName = $csv_folder.'/'.$filename.'_'.$email[0].'.text';
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");

		// $FileHandle = fopen($TextFileName, 'w') or die("can't open file");
		fclose($FileHandle);
        
		$fp = fopen($CSVFileName, 'w');
		// $fp1 = fopen($TextFileName, 'w');
        foreach ($csv_fields as $field)
        {
		 fputcsv($fp, $field);
        }
       

		/* foreach ($csv_fields as $fields)
		{
			fwrite($fp1,$fields);
			fwrite($fp1,PHP_EOL);
		} */
		fclose($fp);
		// fclose($fp1);
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
			$email	= JRequest::getVar("email");
			$subject	= JRequest::getVar("subject");
			$body	= JRequest::getVar("data");

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

			$send = $mail->Send();
			if($send=="true")
			{
				return "success";
			}
			else 
			{
				return $send;
			}
		}
		catch (Exception $e)
		{
			return false;
		}
	}


	public function sendSelectionChanges()
	{
		$model = $this->getModel('Createbundle', 'HomeconnectModel');
		echo $model->sendSelectionChanges(JRequest::getVar("category"), JRequest::getVar("selection"));
	}
}