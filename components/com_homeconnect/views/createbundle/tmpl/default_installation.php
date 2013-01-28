<?php
?>

<div id="installation_div" style="display: none;">
	<div id="category_product_instalation_div" style="padding: 20px;">
		<div style="margin: 10px;">
			<input type="checkbox" name="broadband_connection" id="category_check_4"> Broadband Connection <br>
		</div>

		<div style="margin: 10px;">
			<input type="checkbox" name="tv" id="category_check_1"> TV 
			<select id="nof_installation_cat_1">
				<option value="0">How Many</option>
				<option value="1" selected>1</option>
				<option value="2" >2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</div>

		<div style="margin: 10px;">
			<input type="checkbox" name="gaming_consoles" id="category_check_2"> Gaming Consoles
			<select id="nof_installation_cat_2">
			
				<option value="0">How Many</option>
				<option value="1" selected>1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</div>

		<div style="margin: 10px;">
			<input type="checkbox" name="music_systems" id="category_check_3"> Music Systems
			<select id="nof_installation_cat_3">
				<option value="0">How Many</option>
				<option value="1" selected>1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</div>
	</div>

	<div style="margin: 10px; padding: 5px;">
		Tell us about your requirements:<br>
		<input style="height: 50px;" type="textarea" name="tellusrequirement" rows="5" cols="25"/>
	</div>


    <div style="cursor: pointer; float: left;" onclick="javascript:controllerObj.switchInnerDivs('installation_div','main_category_div',0,0);">
		&lt;=
	</div>

	<div style="cursor: pointer; float: right;" onclick="javascript:controllerObj.switchInnerDivs('installation_div','confirmation_div',0,0);">
		=&gt;
	</div>
	
</div>