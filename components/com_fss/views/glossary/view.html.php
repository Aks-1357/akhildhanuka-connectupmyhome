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

class FssViewGlossary extends JViewLegacy
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
        
		JHTML::_('behavior.modal', 'a.modal');
		//JHTML::_('behavior.mootools');
        $db =& JFactory::getDBO();

        $aparams = FSS_Settings::GetViewSettingsObj('glossary');
		$this->use_letter_bar = $aparams->get('use_letter_bar',0);
		
		if ($this->use_letter_bar)
		{
			$qry = "SELECT UPPER(SUBSTR(word,1,1)) as letter FROM #__fss_glossary";
			$where = array();
		
			if (FSS_Helper::Is16())
			{
				$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
				$user = JFactory::getUser();
				$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
			}	
			if (count($where) > 0)
				$qry .= " WHERE " . implode(" AND ",$where);
		
			$qry .= "GROUP BY letter ORDER BY letter";
			$db->setQuery($qry);
			$this->letters = $db->loadObjectList();
			
			if (count($this->letters) == 0)
			{
				return parent::display("empty");	
			}
		}
				
		$this->curletter = "";
		
		// if we are showing on a per letter basis only
		if ($this->use_letter_bar == 2)
		{
			$this->curletter = JRequest::getVar('letter',$this->letters[0]->letter);	
		}
		
		$where = array();
		$where[] = "published = 1";
        $query = "SELECT * FROM #__fss_glossary";
		if ($this->curletter)
		{
			$where[] = "SUBSTR(word,1,1) = '".FSSJ3Helper::getEscaped($db, $this->curletter)."'";
		}
		
		if (FSS_Helper::Is16())
		{
			$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	

		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);
	
		$query .= " ORDER BY word";
        $db->setQuery($query);
        $this->rows = $db->loadObjectList();
  
        $pathway =& $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'glossary' )))
			$pathway->addItem("Glossary");

		if (FSS_Settings::get('glossary_use_content_plugins'))
		{
			// apply plugins to article body
			$dispatcher	=& JDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$art = new stdClass;
			if (FSS_Helper::Is16())
			{
				//$aparams = new JParameter(null);
			} else {
				$aparams = new stdClass();	
			}
			foreach ($this->rows as &$row)
			{
				if ($row->description)
				{
					$art->text = $row->description;
					$art->noglossary = 1;
					if (FSS_Helper::Is16())
					{
						$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.glossary', & $art, &$this->params, 0));
					} else {
						$results = $dispatcher->trigger('onPrepareContent', array (& $art, & $aparams, 0));
					} 
					$row->description = $art->text;
				}
				if ($row->longdesc)
				{
					$art->text = $row->longdesc;
					$art->noglossary = 1;
					if (FSS_Helper::Is16())
					{
						$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.glossary.long', & $art, &$this->params, 0));
					} else {
						$results = $dispatcher->trigger('onPrepareContent', array (& $art, & $aparams, 0));
					} 
					$row->longdesc = $art->text;
				}
			}
		}     
		   	
  		parent::display($tpl);
    }
}

