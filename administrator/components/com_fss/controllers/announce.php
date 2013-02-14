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


class FsssControllerAnnounce extends FsssController
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
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}


	function edit()
	{
		JRequest::setVar( 'view', 'announce' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model =& $this->getModel('announce');

        $post = JRequest::get('post');
        $post['body'] = JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWRAW);
        
        $post['fulltext'] = "";
        
        if (strpos($post['body'],"system-readmore") > 0)
        {
            $pos = strpos($post['body'],"system-readmore");
            $answer = substr($post['body'], 0, $pos);
            $answer = substr($answer,0, strrpos($answer,"<"));
            
            $rest = substr($post['body'], $pos);
            $rest = substr($rest, strpos($rest,">")+1);
            
            $post['body'] = $answer;
            $post['fulltext'] = $rest;                           
        }
   
		if ($model->store($post)) {
			$msg = JText::_("ANNOUNCEMENT_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_ANNOUNCEMENT");
		}

		$link = 'index.php?option=com_fss&view=announces';
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('announce');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_ANNOUNCEMENTS_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("ANNOUNCEMENT_DELETED");
		}

		$this->setRedirect( 'index.php?option=com_fss&view=announces', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=announces', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('announce');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_AN_ANNOUNCEMENT");

		$this->setRedirect( 'index.php?option=com_fss&view=announces', $msg );
	}

	function publish()
	{
		$model = $this->getModel('announce');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_AN_ANNOUNCEMENT");

		$this->setRedirect( 'index.php?option=com_fss&view=announces', $msg );
	}
}



