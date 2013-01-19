<?php
$tooltip_1 = "It would be great if you would provide your full address to enable us
				to make the best possible recommendation. However, if you choose to
				just pass on your postcode we can still provide you with a recommendation.
				< link to privacy policy here >";

$tooltip_2 = "It would be great if you would provide your email address to enable us
				to send you the recommendation that we give you on connecting up your
				home < link to privacy policy here >";
?>

<div id="landing_div" style="width: 94%; display: none; float: left; border: 1px solid #ccc; padding: 5px 20px 20px;">
	<center><h3 style="margin: 0;">Find out what is available in your area</h3></center>

	<div style="margin-top: 10px;">
		<label for="geocomplete" style="display: block; font-weight: bold;">What is your address/postal code?<sup>*</sup></label>
		<input id="geocomplete" name="geocomplete" type="text" placeholder="Type in an address" size="40" />
		<a id="tool_tip_1" href="javascript:void(0);" title="<?php echo $tooltip_1; ?>">
			<img id="tool_tip_1_inner" alt="More Info" src="<?php echo JURI::base().'images/Information.png'; ?>">
		</a>
		<div id="address_error_div" style="color:red;"> </div>
		<!-- Aks : Not Required
		<input id="find" type="button" value="find" />
		-->
	</div>

	<div style="margin-top: 10px;">
		<label for="email" style="display: block; font-weight: bold;">What is your email address?</label>
		<input id="email" name="email" type="text" placeholder="Type in an email address" size="45" />
		<a id="tool_tip_2" href="javascript:void(0);" title="<?php echo $tooltip_2; ?>">
			<img id="tool_tip_2_inner" alt="More Info" src="<?php echo JURI::base().'images/Information.png'; ?>">
		</a>
		<div id="email_error_div" style="color:red;"> </div>
	</div>

	<div style="width: 90%; margin-top: 15px; text-align: center;">
		<div id="create_bundle" style="cursor: pointer; border: 1px solid #ccc; padding: 15px 0; width: 40%; float: left;" onClick="javascript:controllerObj.oncreatebundle('main_accordian_div','main_category_div','recommended_div');">Create your Own Bundle</div>
		<div style="margin: 0 20px; padding: 15px 0; width: 10%; float: left;"> - OR - </div>
		<div id="recommended_bundle" style="cursor: pointer; border: 1px solid #ccc; padding: 15px 0; width: 40%; float: left;" onClick="javascript:controllerObj.oncreatebundle('main_accordian_div','recommended_div','main_category_div');">Recommended Bundle</div>
	</div>

	<!-- Aks : No need
	<pre id="logger">Log:</pre>
	-->
	<p style="float: left;">* Minimum of Postcode Required</p>
</div>