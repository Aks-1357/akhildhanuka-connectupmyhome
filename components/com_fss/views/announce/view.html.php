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

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport('joomla.utilities.date');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'glossary.php');

class FssViewAnnounce extends JViewLegacy
{
    function display($tpl = null)
    {
		JHTML::_('behavior.modal', 'a.modal');
        $mainframe = JFactory::getApplication();
        $aparams = $mainframe->getPageParameters('com_fss');
        
        $announceid = JRequest::getVar('announceid', 0, '', 'int'); 
		
 		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content'.DS.'announce.php');
		$this->content = new FSS_ContentEdit_Announce();
		$this->content->Init();
 		
		$model =& $this->getModel();
		$model->content = $this->content;
      
        if ($announceid)
        {
            $tmpl = JRequest::getVar('tmpl'); 
            $this->assignRef( 'tmpl', $tmpl );
            $this->setLayout("announce");
            $this->assignRef("announce",$this->get("Announce"));
            
            $pathway =& $mainframe->getPathway();
			if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'announce' )))	
				$pathway->addItem(JText::_("ANNOUNCEMENTS"), FSSRoute::x( '&limitstart=&announceid=' ) );
            $pathway->addItem($this->announce['title']);
 
  			$this->comments = new FSS_Comments('announce',$announceid);
			$this->comments->PerPage(FSS_Settings::Get('announce_comments_per_page'));
			if ($this->comments->Process())
				return;			

			if (FSS_Settings::get('announce_use_content_plugins'))
			{
				// apply plugins to article body
				$dispatcher	=& JDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$art = new stdClass;
				$art->text = $this->announce['body'];
				$art->noglossary = 1;
				if (FSS_Helper::Is16())
				{
					//$aparams = new JParameter(null);
					$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.announce', &$art, &$this->params, 0));
				} else {
					$aparams = new stdClass();
					$results = $dispatcher->trigger('onPrepareContent', array (&$art, $aparams, 0));
				} 
				$this->announce['body'] = $art->text;
				if ($this->announce['fulltext'])
				{
					$art->text = $this->announce['fulltext'];
					$art->noglossary = 1;
					if (FSS_Helper::Is16())
					{
						$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.announce.fulltext', & $art, &$this->params, 0));
					} else {
						$results = $dispatcher->trigger('onPrepareContent', array (& $art, $aparams, 0));
					} 
					$this->announce['fulltext'] = $art->text;
				}
			}
			
			$this->parser = new FSSParser();
			$type = FSS_Settings::Get('announcesingle_use_custom') ? 2 : 3;
			$this->parser->Load("announcesingle", $type); 

        	parent::display($tpl);
        	return;
		}
	
        $pathway =& $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'announce' )))
			$pathway->addItem(JText::_("ANNOUNCEMENTS"));

        $this->assignRef("announces",$this->get('Announces'));
        $this->assignRef('pagination', $this->get('Pagination'));
  
		if (FSS_Settings::get('announce_use_content_plugins_list'))
		{
			// apply plugins to article body
			$dispatcher	=& JDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$art = new stdClass;
			
			foreach ($this->announces as &$item)
			{
				$art->text = $item['body'];
				$art->noglossary = 1;
				if (FSS_Helper::Is16())
				{
					//$aparams = new JParameter(null);
					$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.announce', & $art, &$this->params, 0));
				} else {
					$aparams = new stdClass();
					$results = $dispatcher->trigger('onPrepareContent', array (& $art, & $aparams, 0));
				} 
				$item['body'] = $art->text;

			}
		}     
		
		$this->comments = new FSS_Comments('announce',null,$this->announces);

		$this->parser = new FSSParser();
		$type = FSS_Settings::Get('announce_use_custom') ? 2 : 3;
		$this->parser->Load("announce", $type); 
		
        parent::display($tpl);
    }
}

