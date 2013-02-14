<?php
?>
<div id="recommended_div" style="<?php echo $this->recommendDisplay; ?> height:100%; width:88%; padding:5% 0 300px 0; position:relative; margin: 0 0 50px 0;">
	our recommendation
	<div id="rec_error_message" style="font-color:red""></div>
	<div id="recommended_product"  style="display: block; height: 100%;">
		<div id="rec_lower_1" style="height:10%">
			<div onClick="javascript:controllerObj.switchInnerDivs('rec_closest_2,rec_closest_3,rec_popular_2,rec_popular_3','rec_lower_2,rec_lower_3',0);" style="cursor: pointer;  padding: 5px; margin: 15px 35px 15px 15px; float: left;">
				+
				</div>
				<div   style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px  15px 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 5
				</div>
				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 15px 15px 10px; float: left;">
				<INPUT onClick="javascript:controllerObj.PickRecommendedBundle(this.id)" id="rec_select_lowerp_1" NAME="p" TYPE="CHECKBOX" VALUE="1">
				</div>
		</div>

		<div id="rec_lower_2" style="display:none;height:10%">
			<div style="cursor: pointer; width:8px;  padding: 5px; margin:15px 35px 15px 15px; float: left;"> 
				</div>

				<div   style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px  15px 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 5
				</div>
				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 15px 15px 10px; float: left;">
				<INPUT onClick="javascript:controllerObj.PickRecommendedBundle(this.id)" id="rec_select_lowerp_2" NAME="p" TYPE="CHECKBOX" VALUE="1">
				</div>
		</div>

		<div id="rec_lower_3" style="display: none; height: 10%;">
			<div  style="cursor: pointer; width:8px; padding: 5px; margin: 15px 35px 15px 15px; float: left;"> 
				</div>

				<div   style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px  15px 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 5
				</div>
				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 15px 15px 10px; float: left;">
				<INPUT onClick="javascript:controllerObj.PickRecommendedBundle(this.id)" id="rec_select_lowerp_3" NAME="p" TYPE="CHECKBOX" VALUE="1">
				</div>
		</div>

		<div id="rec_closest_1" style="display: block; height: 10%;">
			<div onClick="javascript:controllerObj.switchInnerDivs('rec_lower_2,rec_lower_3,rec_popular_2,rec_popular_3','rec_closest_2,rec_closest_3',0);" style="cursor: pointer; padding: 5px; margin: 15px 35px 15px 15px; float: left;">
				+
				</div>
				<div   style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px  15px 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 5
				</div>
				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 15px 15px 10px; float: left;">
				<INPUT onClick="javascript:controllerObj.PickRecommendedBundle(this.id)" id="rec_select_closest_1" NAME="p" TYPE="CHECKBOX" VALUE="1">
				</div>
		</div>

		<div id="rec_closest_2" style="display: none; height: 10%;">
			<div style="cursor: pointer; width:8px;  padding: 5px; margin:15px 35px 15px 15px; float: left;"> 
				</div>
			<div   style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px  15px 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 5
				</div>
				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 15px 15px 10px; float: left;">
				<INPUT onClick="javascript:controllerObj.PickRecommendedBundle(this.id)" id="rec_select_closest_2" NAME="p" TYPE="CHECKBOX" VALUE="1">
				</div>
		</div>

		<div id="rec_closest_3" style="display: none; height: 10%;">
			<div  style="cursor: pointer; width:8px; padding: 5px; margin: 15px 35px 15px 15px; float: left;"> 
				</div>

				<div   style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px  15px 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 5
				</div>
				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 15px 15px 10px; float: left;">
				<INPUT onClick="javascript:controllerObj.PickRecommendedBundle(this.id)" id="rec_select_closest_3" NAME="p" TYPE="CHECKBOX" VALUE="1">
				</div>
		</div>

		<div id="rec_popular_1" style="display: block; height: 10%;">
			<div onClick="javascript:controllerObj.switchInnerDivs('rec_closest_2,rec_closest_3,rec_lower_2,rec_lower_3','rec_popular_2,rec_popular_3',0);" style="cursor: pointer;  padding: 5px; margin: 15px 35px 15px 15px; float: left;">
				+
				</div>

				<div   style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px  15px 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 5
				</div>
				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 15px 15px 10px; float: left;">
				<INPUT onClick="javascript:controllerObj.PickRecommendedBundle(this.id)" id="rec_select_popular_1" NAME="p" TYPE="CHECKBOX" VALUE="1">
				</div>
		</div>

		<div id="rec_popular_2" style="display: none; height: 10%;">
			<div  style="cursor: pointer; width:8px; padding: 5px; margin: 15px 35px 15px 15px; float: left;"> 
				</div>
				<div   style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px  15px 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 5
				</div>
				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 15px 15px 10px; float: left;">
				<INPUT onClick="javascript:controllerObj.PickRecommendedBundle(this.id)" id="rec_select_popular_2" NAME="p" TYPE="CHECKBOX" VALUE="1">
				</div>
		</div>

		<div id="rec_popular_3" style="display: none; height: 10%;">
			<div  style="cursor: pointer; width:8px; padding: 5px; margin: 15px 35px 15px 15px; float: left;"> 
				</div>
				<div   style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px  15px 15px; float: left;">
				p 1
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 2
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 3
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 4
				</div>

				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 25px 15px 15px; float: left;">
				p 5
				</div>
				<div  style="cursor: pointer; border: 1px solid #ccc; padding: 5px; margin: 15px 15px 15px 10px; float: left;">
				<INPUT onClick="javascript:controllerObj.PickRecommendedBundle(this.id)" id="rec_select_popular_3" NAME="p" TYPE="CHECKBOX" VALUE="1">
				</div>
		</div>

		<div style="margin: 60px 0 0 0 ; cursor: pointer; float: right;" onclick="javascript:controllerObj.switchInnerDivs('recommended_div','details_div',0,0);">
			=&gt;
		</div>
	</div>
</div>