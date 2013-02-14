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

<?php echo FSS_Helper::PageSubTitle("PLEASE_SELECT_A_DEPARTMENT_FOR_YOUR_SUPPORT_ENQUIRY"); ?>

<?php if ($this->product): ?>
	<div class='fss_ticket_dept_prod'><?php echo JText::_("PRODUCT"); ?> : <?php if ($this->product['image']): ?><img src='<?php echo JURI::root( true ); ?>/images/fss/products/<?php echo $this->product['image']; ?>' width=24 height=24 style='position:relative;top:7px;'>&nbsp;<?php endif; ?><?php echo $this->product['title']; ?></div>
<?php endif; ?>

<form name='deptselect' action='<?php echo FSSRoute::x('&layout=open');?>' method='post'>
<?php if ($this->prodid > 0): ?>
<input class='button backprod' type='submit' value='<?php echo JText::_("BACK"); ?>'>
<?php endif; ?>
<?php if (!FSS_Settings::get('support_next_prod_click')): ?>
<input class='button' type='submit' id='pickdept' value='<?php echo JText::_("NEXT"); ?>'>
<?php endif; ?>


<?php FSS_Helper::Tr($this->depts); ?>
<?php foreach ($this->depts as $dept): ?>
	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_dept.php';
	//include "components/com_fss/views/ticket/snippet/_dept.php" ?>
	<?php endforeach; ?>
<div class='fss_ticket_prod_foot'></div>
<?php if ($this->prodid > 0): ?>
<input class='button backprod' type='submit' value='<?php echo JText::_("BACK"); ?>'>
<?php endif; ?>
<?php if (!FSS_Settings::get('support_next_prod_click')): ?>
<input class='button' type='submit' id='pickdept' value='<?php echo JText::_("NEXT"); ?>'>
<?php endif; ?>
<input type=hidden name='prodid' id='prodid' value='<?php echo $this->prodid; ?>'>
</form>
<script>

var productpicked = false;

function setCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		//alert(radioObj.checked);
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
	document.forms.deptselect.submit();
<?php endif; ?>
}

function setupFormRedirect() {
	
}

window.addEvent('domready', function(){
	jQuery('#pickdept').click (function(evt){
		// Stops the submission of the form.
		
		if (!productpicked)
		{
			alert("<?php echo FSS_Helper::escapeJavaScriptTextForAlert(JText::_("YOU_MUST_SELECT_A_DEPARTMENT")); ?>");
			new Event(evt).stop();
			return;	
		}
	} );
	jQuery('.backprod').click(function(evt){
		// Stops the submission of the form.
		$('prodid').value = '';		
	} );
});

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>