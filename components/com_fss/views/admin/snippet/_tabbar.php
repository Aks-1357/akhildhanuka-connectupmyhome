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
<?php ('_JEXEC') or die('Restricted access'); ?>
<div class='ffs_tabs'>

<!--<a class='ffs_tab <?php if ($this->layout == "" || $this->layout == "default") echo "fss_tab_selected";?>' href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=default' );?>'><?php echo JText::_("OVERVIEW"); ?></a>-->

<?php if ($this->permission['support']): ?>
<a class='ffs_tab <?php if ($this->layout == "support") echo "fss_tab_selected";?>' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin&layout=support' );?>'>
<img  class="fss_tab_image" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/support_16.png'>
<?php echo JText::_("SA_SUPPORT"); ?>
</a> 
<?php endif; ?>

<?php if ($this->permission['mod_kb']): ?>
<a class='ffs_tab <?php if ($this->layout == "moderate") echo "fss_tab_selected";?>' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin&layout=moderate' );?>'>
<img  class="fss_tab_image" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/moderate_16.png'>
<?php echo JText::sprintf("SA_MODERATE",$this->moderatecount); ?>
</a>
<?php endif; ?>

<?php if ($this->permission['artperm'] > 0): ?>
<a class='ffs_tab <?php if ($this->layout == "content") echo "fss_tab_selected";?>' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin&layout=content' );?>'>
<img  class="fss_tab_image" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/content_16.png'>
<?php echo JText::sprintf("SA_CONTENT"/*,$this->contentmod*/); ?>
</a>
<?php endif; ?>

<?php if ($this->permission['groups'] > 0): ?>
<?php if (empty($this->view)) $this->view = ""; ?>
<a class='ffs_tab <?php if ($this->view == "groups") echo "fss_tab_selected";?>' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=groups' );?>'>
<img  class="fss_tab_image" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/groups_16.png'>
<?php echo JText::_("GROUPS"); ?>
</a>
<?php endif; ?>

</div>
