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



class FsssControllerTicketdept extends FsssController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 'unpublish' );
		$this->registerTask( 'publish', 'publish' );
		$this->registerTask( 'orderup', 'orderup' );
		$this->registerTask( 'orderdown', 'orderdown' );
		$this->registerTask( 'saveorder', 'saveorder' );
	}


	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}

	function edit()
	{
		JRequest::setVar( 'view', 'ticketdept' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model =& $this->getModel('ticketdept');

        $post = JRequest::get('post');
        $post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if ($model->store($post)) {
			$msg = JText::_("TICKET_DEPARTMENT_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_TICKET_DEPARTMENT");
		}

		$link = 'index.php?option=com_fss&view=ticketdepts';
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('ticketdept');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_TICKET_DEPARTMENTS_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("TICKET_DEPARTMENT_S_DELETED");
		}

		$this->setRedirect( 'index.php?option=com_fss&view=ticketdepts', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=ticketdepts', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('ticketdept');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_A_TICKET_DEPARTMENT");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketdepts', $msg );
	}

	function publish()
	{
		$model = $this->getModel('ticketdept');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_A_TICKET_DEPARTMENT");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketdepts', $msg );
	}

	function orderup()
	{
		$model = $this->getModel('ticketdept');
		if (!$model->changeorder(-1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketdepts', $msg );
	}

	function orderdown()
	{
		$model = $this->getModel('ticketdept');
		if (!$model->changeorder(1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketdepts', $msg );
	}

	function saveorder()
	{
		$model = $this->getModel('ticketdept');
		if (!$model->saveorder())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketdepts', $msg );
	}

	function prods()
	{
		JRequest::setVar( 'view', 'ticketdept' );
		JRequest::setVar( 'layout', 'prods'  );
		
		parent::display();
	}
}



