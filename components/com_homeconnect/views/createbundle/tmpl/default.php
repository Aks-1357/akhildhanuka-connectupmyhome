<?php
defined('_JEXEC') or die;
?>

<?php
$jsPath =  JURI::base()."js/";

$document =& JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'includes/assets/css/jquery/ui-lightness/jquery-ui.css');
$document->addStyleSheet(JURI::base() . 'includes/assets/css/jquery/ui-lightness/jquery.ui.theme.css');
?>

<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<script src="<?php echo $jsPath; ?>jquery/jquery-1.8.3.js"></script>
<script src="<?php echo $jsPath; ?>jquery/jquery-ui-1.9.2.custom.min.js"></script>

<script src="<?php echo $jsPath; ?>jquery/jquery.geocomplete.js"></script>
<script src="<?php echo $jsPath; ?>logger.js"></script>

<script src="<?php echo $jsPath; ?>connect_controller.js"></script>
<script src="<?php echo $jsPath; ?>connect_model.js"></script>
<script src="<?php echo $jsPath; ?>connect_view.js"></script>

<!-- landing div -->
<?php
echo $this->loadTemplate('landing');
?>
<!-- landing div -->


<!-- main accordian page -->

<div id="main_accordian_div" style="width: 95%;height:100%; display: none; float: left; border: 1px solid #ccc; padding: 5px 20px 20px;">
	<h3 style="font-weight: normal;">
		<label id="main_category_div_address">1, Smith Manly, NSW 2101</label>
		&nbsp;&nbsp;&nbsp;
		<strong><span>Avaiable in Your Area</span></strong>
	</h3>

	<div style="width: 90%; margin: 3px 10px 10px 4px">
		<div id="left" style="float: left; width: 67%; border:1px solid #ccc; margin:3px 0px 10px 4px;">
			<!-- main category div -->
			<?php
				echo $this->loadTemplate('main_category');
			?>
			<!-- main category div -->

			<!-- installation div -->
			<?php
				echo $this->loadTemplate('installation');
			?>
			<!-- installation div -->

			<!-- confirmation div -->
			<?php
				echo $this->loadTemplate('confirmation');
			?>
			<!-- confirmation div -->

			<!-- details div -->
			<?php
				echo $this->loadTemplate('details');
			?>
			<!-- details div -->

			<!-- recommendation div -->
			<?php
				echo $this->loadTemplate('recommendation');
			?>
			<!-- recommendation div -->
		</div>
	</div>

	<!-- right_sidebar div -->
	<?php
		echo $this->loadTemplate('right_sidebar');
	?>
	<!-- right_sidebar div -->

	</div>
</div>
















<!-- thank u page -->
<div id="thank_div" style="display:none; float: left; border: 1px solid #ccc; margin: 3px 0px 10px 4px;">
	<div style="width: 100%; margin: 3px 10px 10px 4px">
		<div id="left" style="float: left; width: 67%;  margin:3px 0px 10px 4px;">
			<div id="thank_you_inner_selected_brand_div" style="">
				Thank u for using connectupmyhome
				<br>
				Our Partner below will be in touch  with you soon.
				<br>
				<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_1', 'main_category_inner_product_div_1', 1);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
					B1
				</div>

				<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_1', 'main_category_inner_product_div_1', 2);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
				B 2
				</div>

				<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_1', 'main_category_inner_product_div_1', 3);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
				 B3
				</div>

				<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_1', 'main_category_inner_product_div_1', 4);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
				 B4
				</div>

				<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_1', 'main_category_inner_product_div_1', 4);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
				B5
				</div>
			</div>
		</div>
	</div>
</div>


<script>
var controllerObj = new Controller();
controllerObj.init();
</script>