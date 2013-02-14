<?php
/**
 * Joomla! 2.5.* module mod_homeconnect
 * @author Akshay
 * @package Joomla
 * @subpackage Homeconnect
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
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
<script src="<?php echo $jsPath; ?>jquery/jquery.cluetip.js"></script>
<script src="<?php echo $jsPath; ?>logger.js"></script>

<script src="<?php echo $jsPath; ?>connect_module.js"></script>

<?php
// echo $hello;
?>

<div id="tool_tip_1" class="tooltip1">
	<?php echo $params->get('tooltip1'); ?>
	<br />
	<a href="<?php echo $params->get('tooltip1_link'); ?>">
		<?php echo $params->get('tooltip1_link_text'); ?>
	</a>
</div>

<div id="tool_tip_2" class="tooltip1">
	<?php echo $params->get('tooltip2'); ?>
	<br />
	<a href="<?php echo $params->get('tooltip2_link'); ?>">
		<?php echo $params->get('tooltip2_link_text'); ?>
	</a>
</div>

<form action="index.php?option=com_homeconnect&view=createbundle" method="post" name="bundle" id="bundle">

	<?php
	$module		= JModuleHelper::getModule( 'homeconnect' );

	$tagLine	= ($module->position == 'homeleftmodule') ? "tagLine"	: "InnertagLine";
	$tagLine1	= ($module->position == 'homeleftmodule') ? "tagLine1"	: "InnertagLine1";
	$HomeForm	= ($module->position == 'homeleftmodule') ? "HomeForm"	: "InnerHomeForm";
	?>

	<div id="landing_div">
	    <div>
	    	<span id="<?php echo $tagLine; ?>"><?php echo $params->get('line1'); ?></span>
			<span id="<?php echo $tagLine1; ?>"><?php echo $params->get('line2'); ?></span>
        </div>

		<div>
			<input class="<?php echo $HomeForm; ?>" id="geocomplete" name="geocomplete" type="text" placeholder="Type in an address / postcode" />
            <a class="tool_tip_1" rel="#tool_tip_1" href="javascript:void(0);">
				<img id="tool_tip_1_inner" alt="More Info" src="<?php echo JURI::base().'images/Information.png'; ?>">
			</a>
            <label for="geocomplete" style="font-size:10px;">
            	<strong>Example : </strong>
				<span onClick="javascript:moduleObj.selectAddress(this.innerHTML);"><?php echo trim(strip_tags($params->get('example_address'))); ?></span>
			</label>

			<div id="address_error_div" style="color: red;"> </div>
			<!-- Aks : Not Required
			<input id="find" type="button" value="find" />
			-->
		</div>

		<div style="margin-top: 10px;">
			<input class="<?php echo $HomeForm; ?>" id="email" name="email" type="text" placeholder="Type in  your email address" />
			<a class="tool_tip_2" rel="#tool_tip_2" href="javascript:void(0);">
				<img id="tool_tip_2_inner" alt="More Info" src="<?php echo JURI::base().'images/Information.png'; ?>">
			</a>
			<div id="email_error_div" style="color: red;"> </div>
		</div>

		<?php
		if($module->position == 'homeleftmodule')
		{ ?>
			<div style="width: 100%; margin-top: 15px; text-align: center;">
				<div id="recommended_bundle" onClick="javascript:moduleObj.showBundle('recommend');" class="create" >Recommended Bundle</div>
				<div id="or"> - OR - </div>
				<div id="create_bundle" onClick="javascript:moduleObj.showBundle('create');" class="recomended">Create your Own Bundle</div>
			</div>
		<?php }
		else
		{ ?>
			<div>
				<center>
					<div id="recommended_bundle" onClick="javascript:moduleObj.showBundle('recommend');" class="Inner_create" >Recommended Bundle</div>
					<div id="Inneror"> - OR - </div>
					<div id="create_bundle" onClick="javascript:moduleObj.showBundle('create');" class="Inner_recomended">Create your Own Bundle</div>
				</center>
			</div>
		<?php }?>

		<input type="hidden" id="bundle_type" name="bundle_type">
		<!-- Aks : No need
		<pre id="logger">Log:</pre>
		-->
	</div>
</form>

<?php
	$app = JFactory::getApplication();
	$closeImg = JURI::base() . 'templates/' . $app->getTemplate() . '/images/close.png';
?>

<script>
	var moduleObj = new Module();
	moduleObj.init('<?php echo $closeImg; ?>');
</script>