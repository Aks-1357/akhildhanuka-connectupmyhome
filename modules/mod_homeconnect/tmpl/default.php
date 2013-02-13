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

<div id="tool_tip_1" class="tooltip1">It would be great if you would provide your full address to enable us
				to make the best possible recommendation. However, if you choose to
				just pass on your postcode we can still provide you with a recommendation.
				< link to privacy policy here ></div>

<div id="tool_tip_2" class="tooltip1">It would be great if you would provide your email address to enable us
				to send you the recommendation that we give you on connecting up your
				home < link to privacy policy here ></div>

<form action="index.php?option=com_homeconnect&view=createbundle" method="post" name="bundle" id="bundle">
	<?php  $module = JModuleHelper::getModule( 'homeconnect' );
	if($module->position == 'homeleftmodule')
	{ ?>
		<div id="landing_div">

	    <div>
	    	<span id="tagLine"><?php echo 'Find out' ?></span>
			<span id="tagLine1"><?php echo 'what is available is your area...' ?></span>
	        </div>

			<div>
				<input id="geocomplete" name="geocomplete" type="text" placeholder="Type in an address / postcode" class="HomeForm"/>
	            <a class="tool_tip_1" rel="#tool_tip_1" href="javascript:void(0);">
					<img id="tool_tip_1_inner" alt="More Info" src="<?php echo JURI::base().'images/Information.png'; ?>">
				</a>
	            <label for="geocomplete" style="font-size:10px;"><strong>Examples:</strong> <span onClick="javascript:moduleObj.selectAddress('Sydney, Australia Victoria 3841 Melbourne');"> Sydney, Australia Victoria 3841 Melbourne &nbsp;<sup>*</sup>  </span></label>

				<div id="address_error_div" style="color:red;"> </div>
				<!-- Aks : Not Required
				<input id="find" type="button" value="find" />
				-->
			</div>

			<div style="margin-top: 10px;">
				<input id="email" name="email" type="text" placeholder="Type in  your email address" class="HomeForm" />
				<a class="tool_tip_2" rel="#tool_tip_2" href="javascript:void(0);">
					<img id="tool_tip_2_inner" alt="More Info" src="<?php echo JURI::base().'images/Information.png'; ?>">
				</a>
				<div id="email_error_div" style="color:red;"> </div>
			</div>

			<div style="width: 100%; margin-top: 15px; text-align: center;">
				<div id="recommended_bundle" onClick="javascript:moduleObj.showBundle('recommend');" class="create" >Recommended Bundle</div>
				<div id="or"> - OR - </div>
				<div id="create_bundle" onClick="javascript:moduleObj.showBundle('create');" class="recomended">Create your Own Bundle</div>
			</div>

			<input type="hidden" id="bundle_type" name="bundle_type">
			<!-- Aks : No need
			<pre id="logger">Log:</pre>
			-->
		</div>
	<?php }
	else
	{ ?>
		<div id="landing_div">

	    <div>
	    	<span id="InnertagLine"><?php echo 'Find out' ?></span>
			<span id="InnertagLine1"><?php echo 'what is available is your area...' ?></span>
	        </div>

			<div>
				<input id="geocomplete" name="geocomplete" type="text" placeholder="Type in an address / postcode" class="InnerHomeForm"/>
	            <a class="tool_tip_1" rel="#tool_tip_1" href="javascript:void(0);">
					<img id="tool_tip_1_inner" alt="More Info" src="<?php echo JURI::base().'images/Information.png'; ?>">
				</a>
	            <label for="geocomplete" style="font-size:10px;"><strong>Examples:</strong> Sydney, Australia Victoria 3841 Melbourne &nbsp;<sup>*</sup></label>

				<div id="address_error_div" style="color:red;"> </div>
				<!-- Aks : Not Required
				<input id="find" type="button" value="find" />
				-->
			</div>

			<div style="margin-top: 10px;">
				<input id="email" name="email" type="text" placeholder="Type in  your email address" class="InnerHomeForm" />
				<a class="tool_tip_2" rel="#tool_tip_2" href="javascript:void(0);">
					<img id="tool_tip_2_inner" alt="More Info" src="<?php echo JURI::base().'images/Information.png'; ?>">
				</a>
				<div id="email_error_div" style="color:red;"> </div>
			</div>

			<div>
            <center>
				<div id="recommended_bundle" onClick="javascript:moduleObj.showBundle('recommend');" class="Inner_create" >Recommended Bundle</div>
				<div id="Inneror"> - OR - </div>
				<div id="create_bundle" onClick="javascript:moduleObj.showBundle('create');" class="Inner_recomended">Create your Own Bundle</div>
                </center>
			</div>

			<input type="hidden" id="bundle_type" name="bundle_type">
			<!-- Aks : No need
			<pre id="logger">Log:</pre>
			-->
		</div>
	<?php } ?>
</form>

<?php
	$app = JFactory::getApplication();
	$closeImg = JURI::base() . 'templates/' . $app->getTemplate() . '/images/close.png';
?>

<script>
	var moduleObj = new Module();
	moduleObj.init('<?php echo $closeImg; ?>');
</script>