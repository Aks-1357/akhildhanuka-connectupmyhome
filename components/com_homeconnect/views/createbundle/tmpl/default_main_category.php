<?php
?>

<div id="main_category_div" style="<?php echo $this->mainCategoryDisplay; ?>">
	<div id="category_accordion_div">
		<div class="group">
			<h3 style="margin: 0;">TV</h3>
			<div>
				<div id="main_category_inner_div_1">
					<div style="display: block; float: left;">
						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_1', 'main_category_inner_product_div_1', 1,1);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 1
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_1', 'main_category_inner_product_div_1', 2,1);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 2
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_1', 'main_category_inner_product_div_1', 3,1);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 3
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_1', 'main_category_inner_product_div_1', 4,1);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 4
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_1', 'main_category_inner_product_div_1', 5,1);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 5
						</div>
					</div>

					<div onClick="javascript:controllerObj.nextAccordion('category_accordion_div',1);" style="cursor: pointer; float: right;">
					=&gt;
					</div>
					<div  style="cursor: pointer; float: left;">
					<INPUT onClick="javascript:controllerObj.TrayedOutCategory(this.id)" id="cat_1" NAME="cat_1" TYPE="CHECKBOX" VALUE="1">&nbsp I Do not Want Any TV product
					</div>
				</div>

				<div id="main_category_inner_product_div_1" style="display: none;">
				</div>
			</div>
		</div>

		<div class="group">
			<h3 style="margin: 0;">Gaming</h3>
			<div>
			   <div id="main_category_inner_div_2">
					<div style="display: block; float: left;">
						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_2', 'main_category_inner_product_div_2', 1,2);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 1
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_2', 'main_category_inner_product_div_2', 2,2);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 2
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_2', 'main_category_inner_product_div_2', 3,2);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 3
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_2', 'main_category_inner_product_div_2', 4,2);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 4
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_2', 'main_category_inner_product_div_2', 5,2);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 5
						</div>
					</div>

					<div onClick="javascript:controllerObj.nextAccordion('category_accordion_div',2);" style="cursor: pointer; float: right;">
					=&gt;
					</div>
					<div  style="cursor: pointer; float: left;">
					<INPUT onClick="javascript:controllerObj.TrayedOutCategory(this.id)" id="cat_2" NAME="cat_2" TYPE="CHECKBOX" VALUE="1">&nbsp I Do not Want Any Games product
					</div>
				</div>

				<div id="main_category_inner_product_div_2" style="display: none;">
				</div>
			</div>
		</div>

		<div class="group">
			<h3 style="margin: 0;">Music</h3>
			<div>
			    <div id="main_category_inner_div_3">
					<div style="display: block; float: left;">
						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_3', 'main_category_inner_product_div_3', 1,3);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 1
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_3', 'main_category_inner_product_div_3', 2,3);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 2
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_3', 'main_category_inner_product_div_3', 3,3);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 3
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_3', 'main_category_inner_product_div_3', 4,3);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 4
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_3', 'main_category_inner_product_div_3', 5,3);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 5
						</div>
					</div>

					<div onClick="javascript:controllerObj.nextAccordion('category_accordion_div',3);" style="cursor: pointer; float: right;">
					=&gt;
					</div>
					<div  style="cursor: pointer; float: left;">
					<INPUT onClick="javascript:controllerObj.TrayedOutCategory(this.id)" id="cat_3" NAME="cat_3" TYPE="CHECKBOX" VALUE="1">&nbsp I Do not Want Any Music product
					</div>
				</div>

				<div id="main_category_inner_product_div_3" style="display: none;">
				</div>
			</div>
		</div>

		<div class="group">
			<h3 style="margin: 0;">Broadband</h3>
			<div>
			   <div id="main_category_inner_div_4">
					<div style="display: block; float: left;">
						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_4', 'main_category_inner_product_div_4', 1,4);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 1
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_4', 'main_category_inner_product_div_4', 2,4);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 2
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_4', 'main_category_inner_product_div_4', 3,4);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 3
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_4', 'main_category_inner_product_div_4', 4,4);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 4
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_4', 'main_category_inner_product_div_4', 5,4);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 5
						</div>
					</div>

					<div onClick="javascript:controllerObj.nextAccordion('category_accordion_div',4);" style="cursor: pointer; float: right;">
					=&gt;
					</div>
					<div  style="cursor: pointer; float: left;">
					<INPUT onClick="javascript:controllerObj.TrayedOutCategory(this.id)" id="cat_4" NAME="cat_4" TYPE="CHECKBOX" VALUE="1">&nbsp I Do not Want Any Broadband product
					</div>
				</div>	

				<div id="main_category_inner_product_div_4" style="display: none;">
				</div>
			</div>
		</div>

		<div class="group">
			<h3 style="margin: 0;">ConnectUpMyHome</h3>
			<div>
			    <div id="main_category_inner_div_5">
					<div style="display: block; float: left;">
						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_5', 'main_category_inner_product_div_5', 1,5);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 1
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_5', 'main_category_inner_product_div_5', 2,5);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 2
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_5', 'main_category_inner_product_div_5', 3,5);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 3
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_5', 'main_category_inner_product_div_5', 4,5);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 4
						</div>

						<div onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_5', 'main_category_inner_product_div_5', 4,5);" style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;">
						B 5
						</div>
					</div>

					<div onClick="javascript:controllerObj.switchInnerDivs('main_category_div','installation_div',0,0);" style="cursor: pointer; float: right;">
					=&gt;
					</div>
					<div  style="cursor: pointer; float: left;">
					<INPUT onClick="javascript:controllerObj.TrayedOutCategory(this.id)" id="cat_5" NAME="cat_5" TYPE="CHECKBOX" VALUE="1">&nbsp I Do not Want Any connectUp my Homes product
					</div>
				</div>

				<div id="main_category_inner_product_div_5" style="display: none;">
				</div>
			</div>
		</div>
	</div>
</div>