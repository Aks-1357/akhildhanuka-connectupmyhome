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
<?php echo FSS_Helper::PageTitle("KNOWLEDGE_BASE","SEARCH_RESULTS"); ?>

<script>
function ChangePage(newpage)
{
	var limitstart = document.getElementById('limitstart');
	if (!newpage)
		newpage = 0;
	limitstart.value = newpage;
	
	document.forms.searchProd.submit();
}

function ChangePageCount(newcount)
{
	var limit = document.getElementById('limit');
	if (!newcount)
		newcount = 10;
	limit.value = newcount;
		
	var limitstart = document.getElementById('limitstart');
	limitstart.value = 0;
	
	document.forms.searchProd.submit();
}


</script>

<?php $product = $this->product; ?>
<?php $cat = $this->cat; ?>

<div class="fss_kb_search">
		<form id="searchProd" action="<?php echo FSSRoute::x( '&view=kb' );?>" method="post" name="adminForm">
		<?php if ($product['id'] > 0): ?>
			<input type="hidden" name='prodid' value='<?php echo $product['id']; ?>'>
		<?php endif; ?>
		<?php if ($cat['id'] > 0): ?>
			<input type="hidden" name='catid' value='<?php echo $cat['id']; ?>'>
		<?php endif; ?>
		<div class='kb_product' >
			<div class='kb_product_image'>
			    <img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/kbsearch.png' width='64' height='64'>
			</div>
			<div class='kb_product_head' style="padding-top:6px;padding-bottom:6px;">
			<?php if ($product && $cat): ?>
				<?php echo JText::sprintf('SEARCH_KNOWLEDGE_BASE_ARTICLES_FOR_IN',$product['title'],$cat['title']); ?> 
			<?php elseif ($product) : ?>
				<?php echo JText::sprintf('SEARCH_KNOWLEDGE_BASE_ARTICLES_FOR',$product['title']); ?> 
			<?php elseif ($cat) : ?>
				<?php echo JText::sprintf('SEARCH_KNOWLEDGE_BASE_ARTICLES_IN',$cat['title']); ?> 
			<?php else: ?>
				<?php echo JText::_("SEARCH_KNOWLEDGE_BASE_ARTICLES"); ?>
			<?php endif; ?>
			</div>
<input type="hidden" name="limitstart" id='limitstart' value="0">
<input type="hidden" name="limit" id='limit' value="<?php echo $this->limit; ?>">
			<div class='faq_category_desc'>
			<input id='kb_search' name='kbsearch' value="<?php echo JViewLegacy::escape($this->search); ?>">
			<input id='kb_submit' class='button' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
			<input id='art_reset' class='button' type='submit' value='<?php echo JText::_("RESET"); ?>' /></div>
		</div>
		</form>
		<div class='fss_clear'></div>
</div>
<div class='kb_category_artlist'>
<?php foreach ($this->results as $art): ?>
	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_art.php';
	//include "components/com_fss/views/kb/snippet/_art.php" ?>
<?php endforeach; ?>
<?php if (count($this->results) == 0): ?>
<div class="fss_no_results"><?php echo JText::_("NO_ARTICLES_MATCH_YOUR_SEARCH_CRITERIA"); ?></div>
<?php endif; ?>
</div>

<?php if ($this->cat_art_pages) echo $this->pagination->getListFooter(); ?>


<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>


<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js';
//include 'components/com_fss/assets/js/content_edit.js'; ?>

jQuery(document).ready(function () {
	jQuery('#art_reset').click(function () {
		jQuery('#kb_search').val("");
	});
});
</script>
