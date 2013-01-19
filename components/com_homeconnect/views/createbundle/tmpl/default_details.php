<?php
?>

<div id="details_div" style="display: none;">
	<div id="your_detail_form_div">
		<table>
			<tr style="border: none;">
				<td style="border: none;" rowspan="3">Your Details</td>
				<td style="border: none;" align="right" >Name:</td>
				<td style="border: none;"><input type="text" name="name" id="name" ></td>
			</tr>

			<tr style="border: none;">
				<td style="border: none;" align="right" >Phone:</td>
				<td style="border: none;"><input type="text" name="phone" id="phone" ></td>
			</tr>

			<tr style="border: none;">
				<td style="border: none;">What is Your email Address</td>
				<td style="border: none;"><input type="text" name="email" id="email" ></td>
			</tr>

			<tr style="border: none;">
				<td style="border: none;" colspan="3">What is your Home Adress</td>
			</tr>

			<tr style="border: none;">
				<td style="border: none;" colspan="3"><input type="text" name="homeaddress" id="haddress" size="50"></td>
			</tr>

			<tr style="border: none;">
				<td style="border: none;" colspan="3">What is your installation Adress</td>
			</tr>

			<tr style="border: none;">
				<td style="border: none;" colspan="3"><input type="text" name="install_address" id="install_address"size="50"></td>
			</tr>
		</table>

		<div style="cursor: pointer; float: left;" onclick="javascript:controllerObj.switchInnerPrevDivs('details_div');">
			&lt;=
		</div>

		<div style="cursor: pointer; float: right;" onclick="javascript:controllerObj.switchDivs('#thank_div');">
			=&gt;
		</div>
	</div>
</div>