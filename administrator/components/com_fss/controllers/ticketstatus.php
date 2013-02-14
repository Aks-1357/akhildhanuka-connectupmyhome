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

class FsssControllerTicketstatus extends FsssController
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
		JRequest::setVar( 'view', 'ticketstatus' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model =& $this->getModel('ticketstatus');

        $post = JRequest::get('post');
        $post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$msg = "";
		if ($model->store($post)) {
			$msg = JText::_("Saved");
		} else {
			$msg = JText::_("Error Saving");
		}

		$link = 'index.php?option=com_fss&view=ticketstatuss';
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('ticketstatus');
		$res = $model->delete();
		$msg = "";

		if (strlen($res) > 3)
		{
			$msg = JText::_($res);
		} else if ($res == false)
		{
			$msg = JText::_("Error Deleting");
		} else {
			$msg = JText::_("Deleted");
		}

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}


	function cancel()
	{
		$msg = JText::_("Cancelled");
		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('ticketstatus');
		$res = $model->unpublish();
		$msg = "";
		if (strlen($res) > 3)
		{
			$msg = JText::_($res);
		} else if ($res == false)
		{
			$msg = JText::_("Error Unpublishing");
		}

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}

	function publish()
	{
		$model = $this->getModel('ticketstatus');
		$res = $model->publish();
		$msg = "";
		if ($res != true)
		{
			if (strlen($res) > 3)
			{
				$msg = JText::_($res);
			} else {
				$msg = JText::_("Error Publishing");
			}
		}

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}

	function orderup()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->changeorder(-1))
			$msg = JText::_("Error changing the order");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}

	function orderdown()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->changeorder(1))
			$msg = JText::_("Error changing the order");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}

	function saveorder()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->saveorder())
			$msg = JText::_("Error changing the order");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
	
	function is_closed()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_closed(1))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
		
	function not_closed()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_closed(0))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
	
	function can_autoclose()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_autoclose(1))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
		
	function not_autoclosed()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_autoclose(0))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
	
	function own_tab()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_tab(1))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
		
	function not_tab()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_tab(0))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
	
	
	function def_open()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_open'))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}	
	
	function def_archive()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_archive'))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}	
	
	function def_user()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_user'))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
		
	function def_admin()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_admin'))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}	
		
	function def_closed()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_closed'))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
}



