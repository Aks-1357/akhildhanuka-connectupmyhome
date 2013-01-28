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

<div id="tool_tip_1">It would be great if you would provide your full address to enable us
				to make the best possible recommendation. However, if you choose to
				just pass on your postcode we can still provide you with a recommendation.
				< link to privacy policy here ></div>

<div id="tool_tip_2">It would be great if you would provide your email address to enable us
				to send you the recommendation that we give you on connecting up your
				home < link to privacy policy here ></div>

<form action="index.php?option=com_homeconnect&view=createbundle" method="post" name="bundle" id="bundle">
	<div id="landing_div" style="width: 94%; display: block; float: left; border: 1px solid #ccc; padding: 5px 20px 20px;">
		<center><h3 style="margin: 0;"><?php echo $homeText; ?></h3></center>

		<div style="margin-top: 10px;">
			<label for="geocomplete" style="display: block; font-weight: bold;">What is your address/postal code?<sup>*</sup></label>
			<input id="geocomplete" name="geocomplete" type="text" placeholder="Type in an address" size="35" />
			<a class="tool_tip_1" rel="#tool_tip_1" href="javascript:void(0);">
				<img id="tool_tip_1_inner" alt="More Info" src="<?php echo JURI::base().'images/Information.png'; ?>">
			</a>
			<div id="address_error_div" style="color:red;"> </div>
			<!-- Aks : Not Required
			<input id="find" type="button" value="find" />
			-->
		</div>

		<div style="margin-top: 10px;">
			<label for="email" style="display: block; font-weight: bold;">What is your email address?</label>
			<input id="email" name="email" type="text" placeholder="Type in an email address" size="35" />
			<a class="tool_tip_2" rel="#tool_tip_2" href="javascript:void(0);">
				<img id="tool_tip_2_inner" alt="More Info" src="<?php echo JURI::base().'images/Information.png'; ?>">
			</a>
			<div id="email_error_div" style="color:red;"> </div>
		</div>

		<div style="width: 100%; margin-top: 15px; text-align: center;">
			<div id="create_bundle" onClick="javascript:moduleObj.showBundle('create');" style="cursor: pointer; border: 1px solid #ccc; padding: 15px 0; width: 40%; float: left;">Create your Own Bundle</div>
			<div style="margin: 0 8px; padding: 15px 0; width: 10%; float: left;"> - OR - </div>
			<div id="recommended_bundle" onClick="javascript:moduleObj.showBundle('recommend');" style="cursor: pointer; border: 1px solid #ccc; padding: 15px 0; width: 40%; float: left;">Recommended Bundle</div>
		</div>

		<input type="hidden" id="bundle_type" name="bundle_type">
		<!-- Aks : No need
		<pre id="logger">Log:</pre>
		-->
		<p style="float: left;">* Minimum of Postcode Required</p>
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