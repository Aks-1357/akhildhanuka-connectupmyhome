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

<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("KNOWLEDGE_BASE",$this->pagetitle); ?>
<div class="fss_spacer"></div>
<?php $product = $this->product; ?>
<?php $cat = $this->cat; ?>
<?php if ($product['id'] > 0): ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod.php';
//include "components/com_fss/views/kb/snippet/_prod.php" ?>	
<?php endif; ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat.php';

//include "components/com_fss/views/kb/snippet/_cat.php" ?>	

<?php if ($this->cat_search) : ?>
	<div class="fss_kb_search">
		<form id="searchProd" action="<?php echo FSSRoute::x( '&view=kb' );?>" method="post" name="adminForm">
		<input type="hidden" name='prodid' value='<?php echo $product['id']; ?>'>
		<input type="hidden" name='catid' value='<?php echo $cat['id']; ?>'>
<div class='kb_product' >
<div class='kb_product_image'>
			    <img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/kbsearch.png' width='64' height='64'>
</div>
<div class='kb_product_head' style="padding-top:6px;padding-bottom:6px;">
<?php if ($product['title']) : ?>
	<?php echo JText::sprintf('SEARCH_KNOWLEDGE_BASE_ARTICLES_FOR_IN',$product['title'],$cat['title']); ?> 
<?php else: ?>
	<?php echo JText::sprintf('SEARCH_KNOWLEDGE_BASE_ARTICLES_IN',$cat['title']); ?> 
<?php endif; ?>
</div>
<div class='faq_category_desc'>
				<input id='kb_search' name='kbsearch' value="<?php echo JViewLegacy::escape($this->search); ?>">
				<input id='kb_submit' class='button' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
				<input id='art_reset' class='button' type='submit' value='<?php echo JText::_("RESET"); ?>' />
			</div>
		</div>
		</form>
		<div class='fss_clear'></div>
</div>
<?php endif; ?>

<?php if (count($this->subcats) > 0): ?>
	<div class="fss_subcat_cont">
		<?php foreach ($this->subcats as $cat) : ?>
			<?php //include "components/com_fss/views/kb/snippet/_cat.php" ?>	
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php if (FSS_Settings::get('kb_view_top')): ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_views.php';
//include "components/com_fss/views/kb/snippet/_views.php" ?>
<?php endif; ?>

<div class="fss_subcat_cont">
	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat_list.php';
	//include "components/com_fss/views/kb/snippet/_cat_list.php" ?>
</div>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_art_list.php';
//include "components/com_fss/views/kb/snippet/_art_list.php" ?>	

<?php if ($this->cat_art_pages) { ?>
<form id="adminForm" action="<?php echo FSSRoute::x('&limitstart=');?>" method="post" name="adminForm">
<?php echo $this->pagination->getListFooter(); ?>
</form>
<?php } ?>

<?php if (!FSS_Settings::get('kb_view_top')): ?>
<div class="fss_spacer"></div>
<div class='kb_product'></div>
<div class="fss_spacer"></div>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_views.php';
//include "components/com_fss/views/kb/snippet/_views.php" ?>
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>


<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js';
//include 'components/com_fss/assets/js/content_edit.js'; ?>
</script>
