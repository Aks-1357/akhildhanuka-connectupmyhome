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



class FsssControllerFaq extends FsssController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 'unpublish' );
		$this->registerTask( 'publish', 'publish' );
		$this->registerTask( 'unfeature', 'unfeature' );
		$this->registerTask( 'feature', 'feature' );
		$this->registerTask( 'orderup', 'orderup' );
		$this->registerTask( 'orderdown', 'orderdown' );
		$this->registerTask( 'saveorder', 'saveorder' );
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}

    function pick()
    {
        JRequest::setVar( 'view', 'faqs' );
        JRequest::setVar( 'layout', 'pick'  );
        
        parent::display();
    }

	function edit()
	{
		JRequest::setVar( 'view', 'faq' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model =& $this->getModel('faq');

        $post = JRequest::get('post');
        $post['answer'] = JRequest::getVar('answer', '', 'post', 'string', JREQUEST_ALLOWRAW);
        
        $post['fullanswer'] = "";
        
        if (strpos($post['answer'],"system-readmore") > 0)
        {
            $pos = strpos($post['answer'],"system-readmore");
            $answer = substr($post['answer'], 0, $pos);
            $answer = substr($answer,0, strrpos($answer,"<"));
            
            $rest = substr($post['answer'], $pos);
            $rest = substr($rest, strpos($rest,">")+1);
            
            $post['answer'] = $answer;
            $post['fullanswer'] = $rest;                           
        }
        
		if ($model->store($post)) {
			$msg = JText::_("FAQ_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_FAQ");
		}

		$link = 'index.php?option=com_fss&view=faqs';
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('faq');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_FAQ_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("FAQ_S_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('faq');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_AN_FAQ");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function publish()
	{
		$model = $this->getModel('faq');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_AN_FAQ");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function unfeature()
	{
		$model = $this->getModel('faq');
		if (!$model->unfeature())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNFEATURING_AN_FAQ");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function feature()
	{
		$model = $this->getModel('faq');
		if (!$model->feature())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_FEATURING_AN_FAQ");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function orderup()
	{
		$model = $this->getModel('faq');
		if (!$model->changeorder(-1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function orderdown()
	{
		$model = $this->getModel('faq');
		if (!$model->changeorder(1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function saveorder()
	{
		$model = $this->getModel('faq');
		if (!$model->saveorder())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}
}



