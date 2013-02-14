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
<div class='fss_kb_prodlist'>
	<?php echo FSS_Helper::PageSubTitle("PLEASE_CHOOSE_A_PRODUCT"); ?>
	
	<?php if ($this->main_prod_search): ?>
		<form id="searchProd" action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=kb' );?>" method="post" name="adminForm">
	<input type=hidden name='searchtype' value='products' />
	<div class='kb_product' >
	<div class='kb_product_image'>
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/prodsearch.png' width='64' height='64'>
				</div>
				<div class='kb_product_head' style="padding-top:6px;padding-bottom:6px;">
					<?php echo JText::_("SEARCH_FOR_A_PRODUCT"); ?>
				</div>
				<input type="hidden" name="limitstart" id='limitstart' value="0">
				<input type="hidden" name="limit" id='limit' value="<?php echo $this->limit; ?>">
<div class='faq_category_desc'>
					<input id='prodsearch' name='prodsearch' value="<?php echo JViewLegacy::escape($this->search); ?>">
					<input id='prod_submit' class='button' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
					<input id='prod_reset'  class='button' type='submit' value='<?php echo JText::_("RESET"); ?>' />
				</div>
			</div>
		</form>		
		<div style="padding-bottom:6px;"></div>
	<?php endif; ?>
	
	<div id='prod_search_res'>
		<?php if ($this->main_prod_colums > 1): ?>
			<?php $colwidth = floor(100 / $this->main_prod_colums) . "%"; ?>
		
			<table width='100%' cellspacing="0" cellpadding="0">
			<?php $column = 1; ?>
			
			<?php foreach ($this->products as &$product) : ?>
			
				<?php if ($column == 1) : ?>
	        		<tr><td width='<?php echo $colwidth; ?>' class='fss_faq_cat_col_first' valign='top'>
				<?php else: ?>
	        		<td width='<?php echo $colwidth; ?>' class='fss_faq_cat_col' valign='top'>
				<?php endif; ?>

				<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod.php';
				//include "components/com_fss/views/kb/snippet/_prod.php" ?>
				
				<?php if ($column == $this->main_prod_colums): ?>
						</td></tr>
				<?php else: ?>
		        		</td>
				<?php endif; ?>
			     
				<?php        
					$column++;
					if ($column > $this->main_prod_colums)
						$column = 1;
				?>
			<?php endforeach; ?>
		
		<?php	
			if ($column > 1)
			{ 
				while ($column <= $this->main_prod_colums)
				{
					echo "<td class='fss_faq_cat_col' valign='top'><div class='faq_category'></div></td>";	
					$column++;
				}
				echo "</tr>"; 
				$column = 1;
			}
		?>

			</table> 	
			
		<?php else: ?>
		
			<?php foreach ($this->products as &$product): ?>
				<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod.php';
				//include "components/com_fss/views/kb/snippet/_prod.php" ?>
			<?php endforeach; ?>
			
		<?php endif; ?>
		<div class="fss_clear"></div>
		<?php if ($this->main_prod_pages) echo $this->pagination->getListFooter(); ?>
		
	</div>
</div>

<script>

<?php if ($this->main_prod_search): ?>
window.addEvent('domready', function(){
	SetupProdSearch();
});

function SetupProdSearch()
{
	$('prod_submit').addEvent( 'click', function(evt){
		// Stops the submission of the form.
		new Event(evt).stop();
		
		var value = $('prodsearch').value;
		var limit = $('limit').value;
		if (value == '') value = '__all__';
		var url = '<?php echo str_replace("&amp;","&",FSSRoute::x( '&tmpl=component' )); ?>&prodsearch=' + value + '&limit=' + limit ;
		
<?php if (FSS_Helper::Is16()): ?>
		$('prod_search_res').load(url);
<?php else: ?>
		var mySearch = new Ajax(url, {method: 'get', update: 'prod_search_res'}).request();
<?php endif; ?>
	});
	$('prod_reset').addEvent( 'click', function(evt){
		// Stops the submission of the form.
		new Event(evt).stop();
		$('prodsearch').value = '';
		var url = '<?php echo str_replace("&amp;","&",FSSRoute::x( '&tmpl=component' )); ?>&prodsearch=__all__';
		
<?php if (FSS_Helper::Is16()): ?>
		$('prod_search_res').load(url);
<?php else: ?>
		var mySearch = new Ajax(url, {method: 'get', update: 'prod_search_res'}).request();
<?php endif; ?>
	});				
}
<?php endif; ?>

function ChangePage(newpage)
{
	var limitstart = document.getElementById('limitstart');
	if (!newpage)
		newpage = 0;
	limitstart.value = newpage;
	
	{
		var value = $('prodsearch').value;
		var limit = $('limit').value;
		var limitstart = $('limitstart').value;
		if (value == '') value = '__all__';
		var url = '<?php echo str_replace("&amp;","&",FSSRoute::x( '&tmpl=component' )); ?>&prodsearch=' + value + '&limit=' + limit + '&limitstart=' + limitstart ;
		
<?php if (FSS_Helper::Is16()): ?>
		var myRequest = new Request({method: 'get', url:  url,
			onSuccess: function(responseText, responseXML){
				$('prod_search_res').innerHTML = responseText;
			}
		});
		myRequest.send();
<?php else: ?>
		var mySearch = new Ajax(url, {method: 'get', update: 'prod_search_res'}).request();
<?php endif; ?>
	}
}
function ChangePageCount(newcount)
{
	var limit = document.getElementById('limit');
	if (!newcount)
		newcount = 10;
	limit.value = newcount;
		
	var limitstart = document.getElementById('limitstart');
	limitstart.value = 0;
	
	{
		var value = $('prodsearch').value;
		var limit = $('limit').value;
		var limitstart = $('limitstart').value;
		if (value == '') value = '__all__';
		var url = '<?php echo str_replace("&amp;","&",FSSRoute::x( '&tmpl=component' )); ?>&prodsearch=' + value + '&limit=' + limit + '&limitstart=' + limitstart ;
		
<?php if (FSS_Helper::Is16()): ?>
		var myRequest = new Request({method: 'get', url:  url,
			onSuccess: function(responseText, responseXML){
				$('prod_search_res').innerHTML = responseText;
			}
		});
		myRequest.send();
<?php else: ?>
		var mySearch = new Ajax(url, {method: 'get', update: 'prod_search_res'}).request();
<?php endif; ?>
	}

}

</script>

