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



class FsssControllerTest extends FsssController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 'unpublish' );
		$this->registerTask( 'publish', 'publish' );
	}
	
	function ident()
	{
		JRequest::setVar( 'view', 'test' );
		JRequest::setVar( 'layout', 'form'  );
		parent::display();
		exit;
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}


	function edit()
	{
		JRequest::setVar( 'view', 'test' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model =& $this->getModel('test');

        $post = JRequest::get('post');
        $post['body'] = JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if ($model->store($post)) {
			$msg = JText::_("TESTIMONIAL_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_TESTIMONIAL");
		}

		$link = 'index.php?option=com_fss&view=tests';
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('test');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_TESTIMONIAL_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("TESTIMONIAL_S_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=tests', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=tests', $msg );
	}

}



