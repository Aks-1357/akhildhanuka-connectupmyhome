<?php
$app = JFactory::getApplication();
?>

<div id="details_div" style="display: none;">
	<div class="installation_div_arrow">
		<div class="nextbutton" onclick="javascript:controllerObj.ValidateAndForword('#thank_div');">
			Next
		</div>
		<div class="backbutton" onclick="javascript:controllerObj.switchInnerPrevDivs('details_div','installation_div');">
			Back
		</div>
	</div>

	<form id="your_detail_form_div" action ="index.php?option=com_homeconnect&task=homeconnect.thankYou" method = "post">
		<div><h2>Your Details</h2></div>

        <div id="errordiv" style="color:red"></div>

		<div class="title">Name:</div>
		<input type="text" name="name" id="d_name" class="inputbox background">

		<div class="title">Phone:</div>
		<input type="text" name="phone" id="d_phone" class="inputbox background">

		<div class="title">What is your email address:</div>
		<input type="text" name="d_email" id="d_email" class="inputbox background">

		<div class="title" style="display:none">What is your home address:</div>
		<input type="text" style="display:none" name="homeaddress" id="d_haddress" class="inputbox background">
		<div class="title"><strong>Address:</strong></div>
		<div class="title" style="display:none">Street No. :</div>
		<input type="text" style="display:none" name="homeaddress_streetno" id="d_address_streetno" class="inputbox background">
		<div class="title" style="display:none" >Street Address :</div>
		<input type="text" style="display:none" name="homeaddress_street" id="d_address_streetadd" class="inputbox background">
		<div class="title" style="display: none;">Suburb:</div>
		<input type="text" style="display: none;" name="homeaddress_suburb" id="d_address_suburb" class="inputbox background">
		<div class="title">Home Address:</div>
		<input type="text" name="homeaddress" id="d_addressCity" class="inputbox background">
		<div class="title">State:</div>
		<input type="text" name="homeaddress" id="d_addressState" class="inputbox background">
		<div class="title">Country:</div>
		<input type="text" name="homeaddress" id="d_addressCountry" class="inputbox background">
		<div class="title" style="display:none">Postcode:</div>
		<input type="text" style="display:none" name="homeaddress" id="d_addressPincode" class="inputbox background">

		<div class="title">What is your installation address:(&nbsp;<input type="checkbox" id="autofillcheck" name="autofillcheck" onclick="javascript:controllerObj.autofillText('d_haddress', 'd_install_address', this.id);"/> same as above) </div>
		<input type="textarea" name="install_address" id="d_install_address" class="textarea background" style="display:none;" rows="5" cols="25"><br>
		<div class="title"><strong>Address:</strong></div>
		<div class="title" style="display:none" >Street No. :</div>
		<input type="text" style="display:none" name="homeaddress_streetno" id="d_address_streetnoC" class="inputbox background">
		<div class="title" style="display:none">Street Address :</div>
		<input type="text" style="display:none" name="homeaddress_street" id="d_address_streetaddC" class="inputbox background">
		<div class="title" style="display: none;">Suburb:</div>
		<input type="text" style="display: none;" name="homeaddress_suburb" id="d_address_suburbC" class="inputbox background">
		<div class="title">Installation Address:</div>
		<input type="text" name="homeaddress" id="d_addressCityC" class="inputbox background">
		<div class="title">State:</div>
		<input type="text" name="homeaddress" id="d_addressStateC" class="inputbox background">
		<div class="title">Country:</div>
		<input type="text" name="homeaddress" id="d_addressCountryC" class="inputbox background">
		<div class="title" style="display:none">Postcode:</div>
		<input type="text" style="display:none" name="homeaddress" id="d_addressPincodeC" class="inputbox background">
		<br><br>
        <input type="checkbox" id="d_accept_terms" name="accept_terms" value="1"> I have read and accept the <div class="moreread"><a style="cursor:pointer;" href="<?php echo JURI::base().'images/ConnectUpMyHome.pdf' ?>" target="_blank">terms and conditions</a></div>
        <div id="agreement" style="display:none; position:absolute;border: solid 1px #000;">
		<img src="" float="right" style="cursor:pointer" alt="close" onclick="javascript:controllerObj.hideDiv('#agreement')"><br>
		</div>
		<input type="hidden" name="ip" id="ip" value="">

		<input type="hidden" name="log" id="log" value="">

		<input type="hidden" name="data" id="data" value="">

		<input type="hidden" name="emailid" id="emailid" value="">

		<input type="hidden" name="subject" id="subject" value="">

		<input type="hidden" name="suppliers" id="suppliers" value="">

		<input type="hidden" name="final_prices" id="final_prices" value="">

		<input type="hidden" name="customer_address" id="customer_address" value="">

		<input type="hidden" name="customer_number" id="customer_number" value="">

		<input type="hidden" name="customer_installation_address" id="customer_installation_address" value="">

		<input type="hidden" name="cumh_installer_help" id="cumh_installer_help" value="">
	</form>

</div>