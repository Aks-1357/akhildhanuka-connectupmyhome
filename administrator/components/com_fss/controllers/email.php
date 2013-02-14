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

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



class FsssControllerEmail extends FsssController
{

	function __construct()
	{
		parent::__construct();
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}

	function edit()
	{
		JRequest::setVar( 'view', 'email' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model =& $this->getModel('email');

        $post = JRequest::get('post');
        
		if ($post['ishtml'])
		{
			$post['body'] = JRequest::getVar('body_html', '', 'post', 'string', JREQUEST_ALLOWRAW);	
			unset($post['body_html']);	
		}
		
		if ($model->store($post)) {
			$msg = JText::_("EMAIL_TEMPLATE_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_EMAIL_TEMPLATE");
		}

		$link = 'index.php?option=com_fss&view=emails';
		$this->setRedirect($link, $msg);
	}

	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=emails', $msg );
	}

}



