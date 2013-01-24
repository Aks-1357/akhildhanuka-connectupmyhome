<?php
?>

<div id="installation_div" style="display: none;">
	<div id="category_product_instalation_div" style="padding: 20px;">
		<div style="margin: 10px;">
			<input type="checkbox" name="broadband_connection"> Broadband Connection <br>
		</div>

		<div style="margin: 10px;">
			<input type="checkbox" name="tv" checked> TV 
			<select>
				<option value="0">How Many</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</div>

		<div style="margin: 10px;">
			<input type="checkbox" name="gaming_consoles"> Gaming Consoles
			<select>
				<option value="0">How Many</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</div>

		<div style="margin: 10px;">
			<input type="checkbox" name="music_systems"> Music Systems
			<select>
				<option value="0">How Many</option>
				<option value="1">1</option>
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