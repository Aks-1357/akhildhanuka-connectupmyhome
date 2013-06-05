<?php
$app = JFactory::getApplication();
$count = count($this->data);
$subcategories = array();
?>
<script type="text/javascript">

function ShowOptionsForTV(){
	if(document.getElementById("rdoMultiRoomYes").checked == true){
	var drdl_TV_Connect_Rooms = document.getElementById("drdl_TV_Connect_Rooms");
	var divMultiRoomSelection = document.getElementById("divMultiRoomSelection");
	if(parseInt(drdl_TV_Connect_Rooms.value) > 0){
		var html="";
		
		for(var i=1;i<= parseInt(drdl_TV_Connect_Rooms.value); i++){ 
				html+="<br /> Room "+i+": "+"<select id='room"+i+"' onchange='controllerObj.setRoom("+drdl_TV_Connect_Rooms.value+",this.id,this.value)' style='width:120px;margin-bottom:10px'><option value='HQBox'>HQ Box</option><option value='HQ Box in HD'>HQ Box in HD</option><option value='Xbox'>Xbox</option></select>"; 
			}
		divMultiRoomSelection.innerHTML = html;
		controllerObj.setRoom(drdl_TV_Connect_Rooms.value);
	}
	else{
		divMultiRoomSelection.innerHTML = "";
		}
	}
	else{
		document.getElementById("divMultiRoomSelection").innerHTML = "";
		}
}
function HideOptionsForTV(){
	document.getElementById("divMultiRoomSelection").innerHTML = "";
	
}
</script>
<div id="installation_div">

 <?php $nextAccordion = "javascript:controllerObj.nextAccordion('".JFilterOutput::stringURLSafe('category_accordion_div')."',".($count-1).",".$count.");";?>
		<div style="float: right;">
			
			<div class="nextbutton" onclick="javascript:controllerObj.switchInnerDivs('installation_div','details_div','<?php echo JURI::base().'images/main_accordian/';?>','your_selection');">
				Next
			</div>
			<?php if (JRequest::getVar('bundle_type') == 'recommend')
			{?>
			<div class="backbutton" onclick="javascript:controllerObj.switchInnerPrevDivs('installation_div','recommended_div');">
			<?php }else {?>
            <div class="backbutton" onclick="javascript:controllerObj.switchInnerPrevDivs('installation_div','confirmation_div');">
            <?php }?>
				Back
			</div>
		</div>
	<div id="category_product_instalation_div">
	  
		<h3 style="margin-top:0px;color:#0076A2;">Connect Up My Home</h3>
		<div class="wrapper" style="border:none;padding-left:0px;box-shadow:none;margin-top:-15px">Based on your product selections we can help you install and connect up devices and products in your home to make the most of your TV content, music, and gaming experience.  You're on your way to enjoying a connected home.</div>
		
		<div class="wrapper">
			<div style="float:left;text-align:center;width:197px;">
				<div style="width:177px; margin:10px">
                <input type="checkbox" name="tv" id="category_check_tv-content" class="checkbox" style="display:none;"/>
				<img src="<?php echo JURI::base().'templates/'.$app->getTemplate().'/images/tv_big.png';?>"/>
				<span class="title" style="font-weight:bold">TV</span>
                </div>
				<div id="id_new_selected_product_div_tv" class="selected_product_div tile" style="padding-left: 0px; width: 190px;">
				   <div class="tile_inner_installation">
	    			<div class="miniwrap"><div id="id_new_selected_product_div_tv-content" class="product"><div class="carousel"><div class="bx-wrapper" style="max-width: 100%;"><div class="bx-viewport" style="width: 100%;  position: inherit; min-height: 92px;"><ul id="id_new_selected_ul_tv-content" class="columns" style="position: inherit; width: auto;"></ul></div></div></div></div></div>
	    			</div>
	    		</div>
			</div>
			<div class="title1">
				 
				 	<p class="ptag">How many devices do you want your TV Content played through?<br />
					<select id="drdl_TV_Connect_Devices" style="width:120px;margin-top:10px">
							<option value="0">How Many</option>
							<option value="1" >1</option>
							<option value="2" >2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
						</select>
					</p>
						<br />
						<div id="div_ShowsSomeTime" style="display:none;">
							<p class="ptag">Would you like Multi-room?<br><span>Yes &nbsp;</span><input type="radio" value="Yes" name="foxtel" id="rdoMultiRoomYes" onclick="ShowOptionsForTV();" />
								<span style="margin-left:10px">No &nbsp;</span><input type="radio" value="No" name="foxtel" id="rdoMultiRoomNo" checked="checked" onclick="HideOptionsForTV();"/></p> 
							<p class="ptag">How many rooms?</span><br /><select id="drdl_TV_Connect_Rooms" style="width:120px;margin-top:10px" onchange="ShowOptionsForTV();">
								<option value="0">How Many</option>
								<option value="1" >1</option>
								<option value="2" >2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
							</p>
							<div id="divMultiRoomSelection" style="width:100%;">
							</div>
					</div>
			</div>
		</div>
		<div class="wrapper">
			<div style="float:left;text-align:center;width:197px;">
            <div style="width:177px; margin:10px">
				<input type="checkbox" name="tv" id="category_check_music-content" class="checkbox" style="display:none;"/>
                <span style="margin:0px;">
				<img src="<?php echo JURI::base().'templates/'.$app->getTemplate().'/images/music_big.png';?>"/></span>
				<span class="title" style="font-weight:bold">Music</span>
                </div>
				<div id="id_new_selected_product_div_music" class="selected_product_div tile" style="padding-left:0px; width:190px;">
				   <div class="tile_inner_installation">
	    			<div class="miniwrap"><div id="id_new_selected_product_div_music-content" class="product"><div class="carousel"><div class="bx-wrapper" style="max-width: 100%;"><div class="bx-viewport" style="width: 100%;  position: inherit; min-height: 92px;"><ul id="id_new_selected_ul_music-content" class="columns" style="position: inherit; width: auto;"></ul></div></div></div></div></div>
	    			</div>
	    		</div>
			</div>
			<div class="title1" style="float:left;">
				<p class="ptag">Would you like help connecting your music to play on multiple devices throughout multiple rooms? <br>
				<span>Yes &nbsp;</span><input type="radio" value="Yes" name="music" id="rdoMultiDeviceYes" />
				<span style="margin-left:10px">No &nbsp;</span><input type="radio" value="No" name="music" id="rdoMultiDeviceNo" />
				</p>
				<p class="ptag" >How many devices? <br/><select id="drdl_Music_Devices" style="width:120px;margin-top:10px">
					<option value="0">How Many</option>
					<option value="1" >1</option>
					<option value="2" >2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
				</p>
				<p class="ptag" >How many rooms?  <br/><select id="drdl_Music_Rooms" style="width:120px;">
					<option value="0">How Many</option>
					<option value="1" >1</option>
					<option value="2" >2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				</select>
				</p>
			</div>
		</div>
		
		<div class="wrapper">
			<div style="float:left;text-align:center;width:197px;">
            <div style="width:177px; margin:10px">
				<input type="checkbox" name="tv" id="category_check_tv-content" class="checkbox" style="display:none;" />
				<img src="<?php echo JURI::base().'templates/'.$app->getTemplate().'/images/game_big.png';?>"/>
				<span class="title" style="font-weight:bold">Gaming</span>
                </div>
				<div id="id_new_selected_product_div_gaming" class="selected_product_div tile" style="padding-left:0px; width:190px;">
				<div class="tile_inner_installation">
	    			<div class="miniwrap"><div id="id_new_selected_product_div_gaming-content" class="product"><div class="carousel"><div class="bx-wrapper" style="max-width: 100%;"><div class="bx-viewport" style="width: 100%;  position: inherit; min-height: 92px;"><ul id="id_new_selected_ul_gaming-content" class="columns" style="position: inherit; width: auto;"></ul></div></div></div></div></div>
	    			</div>
	    		</div>
			</div>
			<div class="title1" style="float:left;">
				<p class="ptag">Would you like help setting up your gaming through the internet? <br/>
				<span>Yes &nbsp;</span><input type="radio" value="Yes" name="gaming" id="rdoInternetYes" />
				<span style="margin-left:10px">No &nbsp;</span><input type="radio" name="gaming" value="No" id="rdoInternetNo" /></p>
			</div>
		</div>
		<div class="wrapper">
			<div style="float:left;text-align:center;width:197px;">
            <div style="width:177px; margin:10px">
				<input type="checkbox" name="tv" id="category_check_tv-content" class="checkbox" style="display:none;" />
				<img src="<?php echo JURI::base().'templates/'.$app->getTemplate().'/images/web_big.png';?>"/>
				<span class="title" style="font-weight:bold">Broadband</span>
                </div>
				<div id="id_new_selected_product_div_broadband" class="selected_product_div tile" style="padding-left:0px; width:190px;">
				<div class="tile_inner_installation">
	    			<div class="miniwrap"><div id="id_new_selected_product_div_broadband-content" class="product"><div class="carousel"><div class="bx-wrapper" style="max-width: 100%;"><div class="bx-viewport" style="width: 100%;  position: inherit; min-height: 92px;"><ul id="id_new_selected_ul_broadband-content" class="columns" style="position: inherit; width: auto;"></ul></div></div></div></div></div>
	    			</div>
	    		</div>
			</div>
			<div class="title1" style="float:left;">
				<p class="ptag">Would you like help setting up your broadband service and connecting your devices to your wi-fi?<br>
				<span>Yes &nbsp;</span><input type="radio" name="broadband" value="Yes" id="rdoConnectToWiFiYes" />
				<span style="margin-left:10px">No &nbsp;</span><input type="radio" name="broadband" value="No" id="rdoConnectToWiFiNo" />
				</p>
			</div>
		</div><br/>
		<div class="wrapper" style="border-bottom:none;">Would you like any other assistance in connecting up your home? <br/>
		<div class="TextAreaTitle">
			<textarea name="tellusrequirement" id="tellusrequirement" cols="19" rows="5"  class="textarea" style="margin-left:0px"></textarea>
		</div>
		</div>
		
		<div class="wrapper" style="font-weight:bold;border-bottom:none;">Our partners will be in contact to discuss your requirements and schedule a time to connect up your home. Note that prices quoted for your selections may change with your installation requirements and needs.</div><br/>	
	</div>
	<div style="clear:left;clear:right;"></div>

</div>