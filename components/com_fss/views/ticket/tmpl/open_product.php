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
<?php echo FSS_Helper::PageTitle("SUPPORT","NEW_SUPPORT_TICKET"); ?>
<div class="fss_spacer"></div>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_openheader.php';
//include "components/com_fss/views/ticket/snippet/_openheader.php" ?>

<?php echo FSS_Helper::PageSubTitle("PLEASE_SELECT_A_PRODUCT_FOR_YOUR_SUPPORT_ENQUIRY"); ?>

<?php if ($this->support_advanced): ?>
<form id="searchProd" action="<?php echo FSSRoute::x( 'index.php?limitstart=0&option=com_fss&layout=open&view=ticket' );?>" method="post" name="adminForm">
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
		<div class='faq_category_desc'><input id='prodsearch' name='prodsearch' value="<?php echo JViewLegacy::escape($this->search); ?>">
			<input id='prod_submit' class='button' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
			<input id='prod_reset' class='button' type='submit' value='<?php echo JText::_("RESET"); ?>' />
		</div>
	</div>
</form>		
<div style="padding-bottom:6px;"></div>
<?php endif; ?>
<div class="fss_clear"></div>

<form name='prodselect' action='<?php echo FSSRoute::x('&layout=open');?>' method='post'>
<?php if (!FSS_Settings::get('support_next_prod_click')): ?>
	<input class='button pickproduct' type='submit' value='<?php echo JText::_("NEXT"); ?>'>
<?php endif; ?>
	<div id='prod_search_res' class="prod_search_res">
		<?php $hasprodimages = false; ?>
		<?php $multitype = false; $hast0 = false; $hast1 = false; ?>
		<?php foreach ($this->products as &$product): ?>
			<?php if ($product['image']) { $hasprodimages = true;} ?>
			<?php if ($product['maxtype'] == 0)	$hast0 = true; ?>
			<?php if ($product['maxtype'] == 1)	$hast1 = true; ?>
		<?php endforeach; ?>
		
		<?php if ($hast0 && $hast1) $multitype = true; ?>
		<?php $curtype = -1; ?>
		<?php foreach ($this->products as &$product): ?>
			<?php if ($multitype && $curtype != $product['maxtype']) : ?>
				<?php echo $product['maxtype'] == 0 ? FSS_Helper::PageSubTitle2("OTHER_PRODUCTS") : FSS_Helper::PageSubTitle2("MY_PRODUCTS") ?>
				<?php $curtype = $product['maxtype']; ?>
			<?php endif ;?>
			<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_prod.php';
			//include "components/com_fss/views/ticket/snippet/_prod.php" ?>
		<?php endforeach; ?>
		
		<div class='fss_ticket_prod_foot'></div>
		<?php if ($this->support_advanced) echo $this->pagination->getListFooter(); ?>
		<div class="fss_clear" style="padding-top:4px;"></div>
	</div>
	<div class="fss_clear" style="padding-top:4px;"></div>
	
	
<?php if (!FSS_Settings::get('support_next_prod_click')): ?>
	<input class='button pickproduct' type='submit' value='<?php echo JText::_("NEXT"); ?>'>
<?php endif; ?>
</form>

<script>

var productpicked = false;

function setCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		productpicked = true;
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
			productpicked = true;
		}
	}
<?php if (FSS_Settings::get('support_next_prod_click')): ?>
	document.forms.prodselect.submit();
<?php endif; ?>
}

function setupFormRedirect() {
	
}

<?php if ($this->support_advanced): ?>
window.addEvent('domready', function(){
	$('prod_submit').addEvent( 'click', function(evt){
		// Stops the submission of the form.
		new Event(evt).stop();
		productpicked = false;

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
		productpicked = false;
		var url = '<?php echo str_replace("&amp;","&",FSSRoute::x( '&tmpl=component' )); ?>&prodsearch=__all__';
		
<?php if (FSS_Helper::Is16()): ?>
		$('prod_search_res').load(url);
<?php else: ?>
		var mySearch = new Ajax(url, {method: 'get', update: 'prod_search_res'}).request();
<?php endif; ?>
	});
});
<?php endif;?>

window.addEvent('domready', function(){
	jQuery('.pickproduct').click (function(evt){
		// Stops the submission of the form.
	
		if (!productpicked)
		{
				alert("<?php echo FSS_Helper::escapeJavaScriptTextForAlert(JText::_("YOU_MUST_SELECT_A_PRODUCT")); ?>");
				new Event(evt).stop();
				return;	
			}
		} 
	);
});

function ChangePage(newpage)
{
	var limitstart = document.getElementById('limitstart');
	if (!newpage)
		newpage = 0;
	limitstart.value = newpage;
	
	{
		productpicked = false;
		var value = $('prodsearch').value;
		var limit = $('limit').value;
		var limitstart = $('limitstart').value;
		if (value == '') value = '__all__';
		var url = '<?php echo str_replace("&amp;","&",FSSRoute::x( '&tmpl=component' )); ?>&prodsearch=' + value + '&limit=' + limit + '&limitstart=' + limitstart + '&time=' + new Date().getTime();
		
<?php if (FSS_Helper::Is16()): ?>
		 var req = new Request({
			method: 'get',
			url: url,
			data: { 'do' : '1' },
			onComplete: function(response) { $('prod_search_res').innerHTML = response; }
		}).send();
	    //$('prod_search_res').load(url);
<?php else: ?>
		var mySearch = new Ajax(url, {method: 'get', update: 'prod_search_res'}).request();
<?php endif; ?>
	}
}

function ChangePageCount(newcount)
{
	productpicked = false;
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
		
		//alert(url);
<?php if (FSS_Helper::Is16()): ?>
		 var req = new Request({
			method: 'get',
			url: url,
			data: { 'do' : '1' },
			onComplete: function(response) { $('prod_search_res').innerHTML = response; }
		}).send();

	//alert($('prod_search_res').html();
<?php else: ?>
		var mySearch = new Ajax(url, {method: 'get', update: 'prod_search_res'}).request();
<?php endif; ?>
	}

}

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>