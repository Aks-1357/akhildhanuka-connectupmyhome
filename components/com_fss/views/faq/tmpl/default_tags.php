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

defined('_JEXEC') or die('Restricted access'); ?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("FREQUENTLY_ASKED_QUESTIONS","TAGS"); ?>

	<div class="fss_spacer"></div>
	<?php echo FSS_Helper::PageSubTitle("PLEASE_SELECT_A_TAG"); ?>

	<div class='faq_category'>
	    <div class='faq_category_image'>
			<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tags-64x64.png' width='64' height='64'>
	    </div>
	    <div class='fss_spacer contentheading' style="padding-top:6px;padding-bottom:6px;">
			<?php echo JText::_("FAQS"); ?> <?php echo JText::_('TAGS'); ?>
		</div>
	</div>
	<div class='fss_clear'></div>
	
	<div class='fss_faqs' id='fss_faqs'>
	<?php if (count($this->tags)) foreach ($this->tags as $tag) : ?>
		<div class='fss_faq fss_faq_tag'>
			<div class="fss_faq_question">
				<a class='fss_highlight' href='<?php echo FSSRoute::_('index.php?option=com_fss&view=faq&tag=' . urlencode($tag->tag) . '&Itemid=' . JRequest::getVar('Itemid')); ?>'>
					<?php echo $tag->tag; ?>
				</a>
			</div>
		</div>	
	<?php endforeach; ?>
	<?php if (count($this->tags) == 0): ?>
	<div class="fss_no_results"><?php echo JText::_("NO_TAGS_FOUND");?></div>
	<?php endif; ?>
	</div>
	
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>
