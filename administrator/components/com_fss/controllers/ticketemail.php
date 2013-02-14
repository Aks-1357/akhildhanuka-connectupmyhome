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

class fsssControllerticketemail extends JControllerLegacy
{

	function __construct()
	{
		parent::__construct();
		
		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 'unpublish' );
		$this->registerTask( 'publish', 'publish' );
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss';
		$this->setRedirect($link, $msg);
	}
	
	function edit()
	{
		JRequest::setVar( 'view', 'ticketemail' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu' , 1);

		parent::display();
	}

	function save()
	{
		$model =& $this->getModel('ticketemail');

        $post = JRequest::get('post');

		if (!array_key_exists('allowunknown',$post))
			$post['allowunknown'] = 0;
	
		if (!array_key_exists('usessl',$post))
			$post['usessl'] = 0;
	
		if (!array_key_exists('usetls',$post))
			$post['usetls'] = 0;
	
		if (!array_key_exists('validatecert',$post))
			$post['validatecert'] = 0;
		
		if (!array_key_exists('allow_joomla',$post))
			$post['allow_joomla'] = 0;

		if ($model->store($post)) {
			$msg = JText::_( 'TICKET_EMAIL_ACCOUNT_SAVED' );
		} else {
			$msg = JText::_( 'ERROR_SAVING_TICKET_EMAIL_ACCOUNT' );
		}

		$link = 'index.php?option=com_fss&view=ticketemails';

		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('ticketemail');
		if(!$model->delete()) {
			$msg = JText::_( 'ERROR_ONE_OR_MORE_TICKET_EMAIL_ACCOUNTS_COULD_NOT_BE_DELETED' );
		} else {
			$msg = JText::_( 'TICKET_EMAIL_ACCOUNTS_DELETED' );
		}

		$link = 'index.php?option=com_fss&view=ticketemails';
		//print_r($_POST);
		//echo "<a href='$link'>Redirect</a>";
		$this->setRedirect($link, $msg );
	}


	function cancel()
	{
		$msg = JText::_( 'OPERATION_CANCELLED' );
		$this->setRedirect( 'index.php?option=com_fss&view=ticketemails', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('ticketemail');
		if (!$model->unpublish())
			$msg = JText::_( 'ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_A_TICKET_EMAIL_ACCOUNT' );

		$this->setRedirect( 'index.php?option=com_fss&view=ticketemails', $msg );
	}

	function publish()
	{
		$model = $this->getModel('ticketemail');
		if (!$model->publish())
			$msg = JText::_( 'ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_A_TICKET_EMAIL_ACCOUNT' );

		$this->setRedirect( 'index.php?option=com_fss&view=ticketemails', $msg );
	}
}


