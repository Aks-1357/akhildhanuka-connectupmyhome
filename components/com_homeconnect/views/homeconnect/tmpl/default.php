<?php
/**
 * @version     1.0.0
 * @package     com_homeconnect
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      vinayak <vinayakbardale@gmail.com> - http://
 */

// no direct access
defined('_JEXEC') or die;

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_homeconnect', JPATH_ADMINISTRATOR);
?>

<?php if( $this->item ) : ?>

    <div class="item_fields">
        
        <ul class="fields_list">

			<li><?php echo JText::_('COM_HOMECONNECT_FORM_LBL_HOMECONNECT_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_HOMECONNECT_FORM_LBL_HOMECONNECT_ORDERING'); ?>:
			<?php echo $this->item->ordering; ?></li>
			<li><?php echo JText::_('COM_HOMECONNECT_FORM_LBL_HOMECONNECT_STATE'); ?>:
			<?php echo $this->item->state; ?></li>
			<li><?php echo JText::_('COM_HOMECONNECT_FORM_LBL_HOMECONNECT_CHECKED_OUT'); ?>:
			<?php echo $this->item->checked_out; ?></li>
			<li><?php echo JText::_('COM_HOMECONNECT_FORM_LBL_HOMECONNECT_CHECKED_OUT_TIME'); ?>:
			<?php echo $this->item->checked_out_time; ?></li>
			<li><?php echo JText::_('COM_HOMECONNECT_FORM_LBL_HOMECONNECT_CREATED_BY'); ?>:
			<?php echo $this->item->created_by; ?></li>


        </ul>
        
    </div>
    
<?php else: ?>
    Could not load the item
<?php endif; ?>
