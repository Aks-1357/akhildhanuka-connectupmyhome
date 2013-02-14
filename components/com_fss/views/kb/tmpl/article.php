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

<?php echo FSS_Helper::PageStyle(); ?>
<?php if (JRequest::getVar('tmpl') != "component"): ?>
<?php echo FSS_Helper::PageTitle("KNOWLEDGE_BASE",$this->art['title']); ?>
	<div class="fss_spacer"></div>
<?php endif; ?>
<?php $unpubclass = ""; if ($this->art['published'] == 0) $unpubclass = "content_edit_unpublished"; ?>
<div class="<?php echo $unpubclass; ?>">
	<?php echo $this->content->EditPanel($this->art); ?>
<?php if (FSS_Settings::get('kb_contents')) : ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_contents.php';
//include "components/com_fss/views/kb/snippet/_contents.php" ?>
<?php endif; ?>
<?php if ($this->kb_rate): ?>
	<div class='fss_kb_rate' id='fss_kb_rate'>
		<div class='fss_kb_rate_head'><?php echo JText::_("RATE_THIS_ARTICLE"); ?></div>
		<div class='fss_kb_rate_line fade fss_kb_rate_top' id='rate_up'><A href='#' class='fss_highlight'><img src='<?php echo JURI::base(); ?>/components/com_fss/assets/images/rate_up.png'><span class='fss_kb_rate_text'><?php echo JText::_("VERY_HELPFULL"); ?></span></a></div>
		<div class='fss_kb_rate_line fade' id='rate_same'><A href='#' class='fss_highlight'><img src='<?php echo JURI::base(); ?>/components/com_fss/assets/images/rate_same.png'><span class='fss_kb_rate_text'><?php echo JText::_("COULD_BE_BETTER"); ?></span></a></div>
		<div class='fss_kb_rate_line fade' id='rate_down'><A href='#' class='fss_highlight'><img src='<?php echo JURI::base(); ?>/components/com_fss/assets/images/rate_down.png'><span class='fss_kb_rate_text'><?php echo JText::_("NOT_HELPFULL"); ?></span></a></div>
	</div>
<?php endif; ?>

	<?php echo FSS_Helper::PageSubTitle($this->art['title']); ?>
	
	<div id="kb_art_body">
		<?php 
		if (FSS_Settings::get( 'glossary_kb' )) {
			echo FSS_Glossary::ReplaceGlossary($this->art['body']); 
		} else {
			echo $this->art['body']; 
		}		
		?>
	</div>
</div>

<?php if ($this->kb_rate): ?>
	<script>
	jQuery(document).ready( function () {
	jQuery('#rate_up a').click( function (ev) {
	ev.preventDefault();
		jQuery.get('<?php echo FSSRoute::x( '&tmpl=component&rate=up&kbartid=' . $this->art['id'], false); ?>');
		jQuery('#fss_kb_rate').html('<div class="fss_kb_rate_head"><?php echo JText::_("THANK_YOU_FOR_YOUR_FEEDBACK"); ?></div>');
	});	

	jQuery('#rate_same a').click( function (ev) {
		ev.preventDefault();
		jQuery.get('<?php echo FSSRoute::x( '&tmpl=component&rate=same&kbartid=' . $this->art['id'] , false); ?>');
		jQuery('#fss_kb_rate').html('<div class="fss_kb_rate_head"><?php echo JText::_("THANK_YOU_FOR_YOUR_FEEDBACK"); ?></div>');
	});

	jQuery('#rate_down a').click( function (ev) {
		ev.preventDefault();
		jQuery.get('<?php echo FSSRoute::x( '&tmpl=component&rate=down&kbartid=' . $this->art['id'], false); ?>');
		jQuery('#fss_kb_rate').html('<div class="fss_kb_rate_head"><?php echo JText::_("THANK_YOU_FOR_YOUR_FEEDBACK"); ?></div>');
	});
});

</script>
<?php endif; ?>

<div class='fss_clear'></div>   


<?php if (FSS_Settings::get('kb_show_art_products')): ?>
<?php $applies = array(); ?>
<?php if (count($this->products) > 1) :?>
	<?php foreach ($this->applies as $app) { $applies[] = $app['title']; } ?>
	<?php if (count($applies) > 0) { ?>
		<div class="fss_spacer"></div>
		<?php echo FSS_Helper::PageSubTitle("APPLIES_TO"); ?>
		<div class='fss_kb_applies'><?php echo implode(", ",$applies); ?></div> 
	<?php } ?>
<?php endif; ?>
<?php endif; ?>

<?php if (FSS_Settings::get('kb_show_art_related')): ?>
<?php if (count($this->related) > 0) :?>
	<div class="fss_spacer"></div>
	<?php echo FSS_Helper::PageSubTitle("RELATED_ARTICLES"); ?>
	<div class="fss_kb_related_div">
	<?php foreach ($this->related as $relart) : ?>
		<div class='fss_kb_related'><a href='<?php echo FSSRoute::x('&kbartid=' . $relart['id']); ?>'><?php echo $relart['title']; ?></a></div> 
	<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php endif; ?>

<?php if (FSS_Settings::get('kb_show_dates') && ($this->art['created'] != "0000-00-00 00:00:00" || $this->art['modified'] != "0000-00-00 00:00:00")) : ?>
<div class="fss_spacer"></div>
<?php echo FSS_Helper::PageSubTitle("DETAILS"); ?>

<div class='fss_kb_applies'>
<?php $dates = array();
if ($this->art['created'] != "0000-00-00 00:00:00") $dates[] = JText::_("CREATED"). " : " . $this->art['created'];
if ($this->art['modified'] != "0000-00-00 00:00:00") $dates[] = JText::_("MODIFIED"). " : " . $this->art['modified'];
?>
<?php echo implode(", ",$dates); ?>
</div> 
<?php endif; ?>


<?php if (count($this->artattach) > 0 && FSS_Settings::get('kb_show_art_attach')) :?>
	<div class="fss_spacer"></div>
	<?php echo FSS_Helper::PageSubTitle("ATTACHED_FILES"); ?>

<div  class="fss_kb_files_div">

<?php foreach ($this->artattach as $file) : ?>
<?php $filelink = FSSRoute::x( '&fileid=' . $file['id'] ); ?>
	<div style='float: left;'>
	<table class="fss_kb_files" width='350'> 
	<tr>
	<td width='42' nowrap rowspan='3' valign='top'>
            <a href='<?php echo $filelink; ?>' alt='Download'><img border='0' src='<?php echo JURI::base(); ?>/components/com_fss/assets/images/diskbig.png' width='64' height='64' /></a>
        </td>
        <td nowrap class='fss_kb_files_title'><a href='<?php echo $filelink; ?>' alt='Download'><?php echo $file['title']; ?></a></td>
    </tr>
    <tr>
        <td nowrap class='fss_kb_files_size'><?php echo $file['filename']; ?>, <strong><?php echo round($file['size'] / 1000,0); ?>Kb</strong></td>
</tr>
<tr>
<td class='fss_kb_files_description'>
        	<div style='float:right'><A href='<?php echo $filelink; ?>' alt='Download'><?php echo JText::_("DOWNLOAD"); ?></a></div>
        	<?php echo $file['description']; ?>
        </td>
    </tr>
</table>
</div>
<?php endforeach; ?>

</div>

<div class='fss_clear'></div>
<?php endif; ?>

<?php $this->comments->DisplayComments(); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>

<?php if (FSS_Settings::get( 'glossary_kb' )) echo FSS_Glossary::Footer(); ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>


<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js';
//include 'components/com_fss/assets/js/content_edit.js'; ?>
</script>
