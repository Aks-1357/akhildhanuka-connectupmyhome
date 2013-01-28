<?php
?>

<div id="confirmation_div" style="display:none;width:90%">
	<div id="your_selected_product_div" style="height: 30%; padding:0 0 30px 0; margin: 0 0 30px 0;">
		your selection
		<div id="selected_product_inner_div_1" style="display: block;">
		  <div  onClick="javascript:controllerObj.switchInnerDivs('main_category_inner_div_1', 'main_category_inner_product_div_1', 1);" style="cursor: pointer;  padding: 5px; margin: 0px 25px 0px 15px; float: left;">
			
			</div>
			<div id="c1"  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 0px 20px 0px 15px; float: left;">
			p 1
			</div>

			<div  id="c2"  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 0px 20px 0px 15px; float: left;">
			p 2
			</div>

			<div  id="c3"  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 0px 20px 0px 15px; float: left;">
			p 3
			</div>

			<div  id="c4" style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 0px 20px 0px 15px; float: left;">
			p 4
			</div>

			<div  id="c5"  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 0px 20px 0px 15px; float: left;">
			p 5
			</div>
		</div>
	</div>

	<div id="our_recommendation_div" style="height:100% ; padding:0 0 300px 0; margin: 0 0 50px 0;">
		our recommendation
		<div id="recommended_product"  style="display: block; height: 100%;">
			<div id="lower_1" style="height:10%">
				<div onClick="javascript:controllerObj.switchInnerDivs('closest_2,closest_3,popular_2,popular_3','lower_2,lower_3',0);" style="cursor: pointer;  padding: 5px; margin: 15px; float: left;">
				+
				</div>
				<div   style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 5
				</div>
			</div>

			<div id="lower_2" style="display: none; height: 10%;">
				<div style="cursor: pointer;  padding: 5px; margin:30px 18px 15px 15px; float: left;">
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 5
				</div>
			</div>

			<div id="lower_3" style="display: none; height:10%;">
				<div  style="cursor: pointer;  padding: 5px; margin: 30px 18px 15px 15px; float: left;">
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 5
				</div>
			</div>

			<div id="closest_1" style="display: block; height:10%;">
				<div onClick="javascript:controllerObj.switchInnerDivs('lower_2,lower_3,popular_2,popular_3','closest_2,closest_3',0);" style="cursor: pointer; padding: 5px; margin: 15px; float: left;">
				+
				</div>
				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 5
				</div>
			</div>

			<div id="closest_2" style="display: none; height:10%;">
				<div  style="cursor: pointer;  padding: 5px; margin:30px 15px 18px 15px; float: left;">
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 5
				</div>
			</div>

			<div id="closest_3" style="display: none; height:10%;">
				<div  style="cursor: pointer;  padding: 5px; margin: 30px 18px 15px 15px; float: left;">
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 5
				</div>
			</div>

			<div id="popular_1" style="display: block; height: 10%;">
				<div onClick="javascript:controllerObj.switchInnerDivs('closest_2,closest_3,lowprice_2,lowprice_3','popular_2,popular_3',0);" style="cursor: pointer;  padding: 5px; margin: 15px; float: left;">
				+
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 5
				</div>
			</div>

			<div id="popular_2" style="display: none; height: 10%;">
				<div  style="cursor: pointer;  padding: 5px; margin:30px 18px 15px 15px; float: left;">
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 5
				</div>
			</div>

			<div id="popular_3" style="display: none; height: 10%;">
				<div  style="cursor: pointer;  padding: 5px; margin: 30px 18px 15px 15px; float: left;">
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px; float: left;">
				p 5
				</div>
			</div>
            
		</div>
	</div>
	       <div style="cursor: pointer; float: left;" onclick="javascript:controllerObj.switchInnerDivs('confirmation_div','installation_div',0,0);">
				&lt;=
			</div>
			<div style="cursor: pointer; float: right;" onclick="javascript:controllerObj.switchInnerDivs('confirmation_div','details_div',0,0);">
				=&gt;
			</div>
</div>