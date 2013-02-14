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



class FsssControllerFuser extends FsssController
{
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit', 'prods', 'depts', 'cats' );
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}

	function edit()
	{
		JRequest::setVar( 'view', 'fuser' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
		
		$model =& $this->getModel('fuser');
		$users =& $model->getUsers();
		$groups =& $model->getGroups();
		$user =& $model->getData();
		
		if ($user->id < 1 && count($users) == 0 && count($groups) == 0)
		{
			$msg = JText::_("CANNOT_ADD_ANOTHER_USER_ALL_JOOMLA_USERS_ALREADY_HAVE_RECORDS_FOR_FREESTYLE_SUPPORT_PORTAL");
			$link = 'index.php?option=com_fss&view=fusers';
			$this->setRedirect($link, $msg);
			return;			
		} else {
			parent::display();
		}
	}

	function save()
	{
		$model =& $this->getModel('fuser');

		$post = JRequest::get('post');

		if ($model->store($post)) {
			$msg = JText::_("USER_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_USER");
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_fss&view=fusers';
		$this->setRedirect($link, $msg);
	}

	function remove()
	{
		$model = $this->getModel('fuser');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_USERS_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("USER_S_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=fusers', $msg );
	}

	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=fusers', $msg );
	}

	function prods()
	{
		JRequest::setVar( 'view', 'fuser' );
		JRequest::setVar( 'layout', 'prods'  );
		
		parent::display();
	}

	function depts()
	{
		JRequest::setVar( 'view', 'fuser' );
		JRequest::setVar( 'layout', 'depts'  );
		
		parent::display();
	}

	function cats()
	{
		JRequest::setVar( 'view', 'fuser' );
		JRequest::setVar( 'layout', 'cats'  );
		
		parent::display();
	}
}



