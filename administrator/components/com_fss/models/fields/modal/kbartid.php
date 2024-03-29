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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.form.formfield');

class JFormFieldModal_Kbartid extends JFormField
{
    /**
     * Element name
     *
     * @access    protected
     * @var        string
     */
	protected $type = 'Modal_Kbartid';

    function getInput()
    {
        $mainframe = JFactory::getApplication();

        $db            =& JFactory::getDBO();
        $doc         =& JFactory::getDocument();
        $template     = $mainframe->getTemplate();
        
        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fss'.DS.'tables');
        $faq =& JTable::getInstance('kbart','Table');
	
		$value = $this->value;
	
        if ($value) {
            $faq->load($value);
        } else {
            $faq->title = JText::_("SELECT_A_KB_ARTICLE");
        }   
        
        $js = '
        function jSelectArticle(id, title, catid, object) {
				document.id("'.$this->id.'_id").value = id;
				document.id("'.$this->id.'_name").value = title;
				SqueezeBox.close();
			}';
        $doc->addScriptDeclaration($js);
                                     
        $link = 'index.php?option=com_fss&amp;task=pick&amp;tmpl=component&amp;controller=kbart';

        JHTML::_('behavior.modal', 'a.modal');
        $html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$this->id.'_name" value="'.htmlspecialchars($faq->title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_("SELECT_A_KB_ARTICLE").'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 410}}">'.JText::_("SELECT").'</a></div></div>'."\n";
        $html .= "\n".'<input type="hidden" id="'.$this->id.'_id" name="'.$this->name.'" value="'.(int)$value.'" />';

        return $html;
    }
}


