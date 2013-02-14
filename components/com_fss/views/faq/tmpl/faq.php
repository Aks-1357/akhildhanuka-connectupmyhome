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
<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php echo $this->tmpl ? FSS_Helper::PageStylePopup() : FSS_Helper::PageStyle(); ?>

<?php $width = ""; if (FSS_Settings::get('faq_popup_inner_width') > 0) $width = " style='width:".FSS_Settings::get('faq_popup_inner_width')."px;' "; ?>
<?php if ($this->tmpl) echo "<div $width>"; ?>

<?php echo $this->tmpl ? FSS_Helper::PageTitlePopup("FREQUENTLY_ASKED_QUESTION") : FSS_Helper::PageTitle("FREQUENTLY_ASKED_QUESTION"); ?>

<?php $unpubclass = ""; if ($this->faq['published'] == 0) $unpubclass = "content_edit_unpublished"; ?>
<div class="<?php echo $unpubclass; ?>">
	<?php echo $this->content->EditPanel($this->faq); ?>

<div class="fss_faq_question ">
	<strong><?php echo $this->faq['question']; ?></strong>
</div>

<?php if ($this->faq['featured']): ?>
	<div class="fss_faq_featured"><img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/featuredfaq.png"	width="16" height="16" /><?php echo JText::_('Featured_faq'); ?></div>
<?php endif; ?>

<div class='fss_faq_answer_single'>	
 
<?php 
if (FSS_Settings::get( 'glossary_faqs' )) {
	echo FSS_Glossary::ReplaceGlossary($this->faq['answer']); 
	if ($this->faq['fullanswer'])
	{
		echo FSS_Glossary::ReplaceGlossary($this->faq['fullanswer']); 
	}
} else {
	echo $this->faq['answer']; 
	if ($this->faq['fullanswer'])
	{
		echo $this->faq['fullanswer']; 
	}
}		
?>
	<?php if (count($this->tags) > 0): ?>
	<div class='fss_faq_tags'>
	
		<span><?php echo JText::_('TAGS'); ?>:</span>
		<?php echo implode(", ", $this->tags); ?>
	</div>
	<?php endif; ?>
</div>	

</div>
<?php if ($this->tmpl) echo "</div>"; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php if (FSS_Settings::get( 'glossary_faqs' )) echo FSS_Glossary::Footer(); ?>

<?php if ($this->tmpl) echo "</div>"; ?>

<?php echo $this->tmpl ? FSS_Helper::PageStylePopupEnd() :FSS_Helper::PageStyleEnd(); ?>

<script>
<?php if ($this->tmpl): ?>
jQuery(document).ready( function ()
{
	jQuery('a').click( function (ev) {
		ev.preventDefault();
		var href = jQuery(this).attr('href');
		window.parent.location.href = href;
	});		
});
<?php endif; ?>

/*##NOT_EXT_START##*/
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js';
//include 'components/com_fss/assets/js/content_edit.js'; ?>
/*##NOT_EXT_END##*/
</script>

