<?php
	$app = JFactory::getApplication();
	$monthly_price = 0;
	
	$one_off_price = 0;

	$minPriceFlag	= 0;
	$closestFlag	= 0;
	$popularFlag	= 0;
	
	
	function imageHtmlString($productname)
{
	
 	$htmlstring ="";
	if (strrchr($productname, '+'))
		{    
								 $imag = explode("+", $productname);
								 $img1=$imag[0];
								 $img2=$imag[1];
								 
								 $imagefile1="";
								 $imagefile2="";
								$imagefile1= JURI::base().'images/small_logo/'.JFilterOutput::stringURLSafe($img1).'.jpg';

								if(!file_exists(JPATH_SITE.DS.'images'.DS.'small_logo'.DS.JFilterOutput::stringURLSafe($img1).'.jpg'))
							    {
									$imagefile1= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
							    }
							    if($img2)
							    {
								    $imagefile2= JURI::base().'images/small_logo/'.JFilterOutput::stringURLSafe($img2).'.jpg';
	
									if(!file_exists(JPATH_SITE.DS.'images'.DS.'small_logo'.DS.JFilterOutput::stringURLSafe($img2).'.jpg'))
								    {
										$imagefile2= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
								    }
							    
							   
							
							    
									
						$htmlstring ='<img src='.$imagefile1.' alt='.$img1.'class="two_imageSize"/>'.
									 '<img src='.$imagefile2.' alt='.$img2.'class="two_imageSize"/>';
								
							    }
							    return $htmlstring;
		}
							    
		else
		{ 
			$imagefile1= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($productname).'.jpg';

			if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($productname).'.jpg'))
				{
					$imagefile1= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
				}
			$htmlstring='<img src='.$imagefile1.' alt='.$productname.' class="imageSize"/>';
							    
			 return $htmlstring;				    
		}
							    
}
	
?>

<style>
.subinfodiv
{
position:relative;
top:15px;
}
.hovertitle {
    color: #010101;
    font-size: 11px;
    font-weight: bold !important;
    margin-bottom: 5px;
    margin-top:20px;
    width: 100px;
    font-family: Arial,Helvetica,sans-serif !important;
    text-align: center;
     line-height: 1.2em;
}

ul.columns li .info {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #CBCBCB;
    border-radius: 3px 3px 3px 3px;
    display: none;
    font-size: 9px;
    left: 0px;
    padding: 0 0 10px 0px;
    position: absolute;
    top: 60px;
    width: 180px;
}
.hovercategory {
    color: #010101;
    font-size: 12px;
    min-width: 50px;
}
 .hoverdescription {
    color: #010101;
    font-size: 12px;
   min-width: 100%;
   text-align:center;
    line-height: 1.1em;
}
.hoverquota {
    color: #FF6600;
    font-size: 12px;
    font-weight: bold;
    min-width: 100%;
   text-align:center;
    line-height: 1.1em;
}
.hoverquota span
{
    color: #010101;
    font-size: 12px;
    min-width: 100%;
   text-align:center;
line-height: 1.1em;
}
.hoveramount 
{
  color: #010101;
  font-size: 12px;
  min-width: 100%;
   text-align:center;
  line-height: 1.1em;
 }
 .hoveramount span
{
    color: #010101;
    font-size: 12px;
   min-width: 100%;
   text-align:center;
    line-height: 1.1em;

}
#new_selected_product_div .product {
    background-color: #FFFFFF;
    border: 1px solid #FFFFFF;
    border-radius: 3px 3px 3px 3px;
    box-shadow: 0 0 0 5px rgba(150, 150, 150, 0.04), 0 1px 3px rgba(25, 25, 25, 0.3), 0 10px 15px rgba(200, 200, 200, 0.04) inset;
    cursor: pointer;
    min-height: 240px;
    margin: 0 7px;
    padding: 0px;
    width: 180px;
}
#new_selected_product_div .product img {
    max-height: 70px !important;
    max-width: 140px !important;
}
#new_selected_product_div .no_product {
    background-color: #FFFFFF;
    border: 1px solid #FFFFFF;
    border-radius: 3px 3px 3px 3px;
    margin: 0 7px;
    min-height: 70px;
    padding: 5px 0;
    width: 140px;
}
#new_selected_product_div .miniwrap {
    float: left;
    min-height: 50px;
    min-width: 71px;
}
</style>

<div id="confirmation_div" style="display: none;">
	<div id="your_selected_product_div">
	
		<div class="installation_div_arrow">
			<div style="display:none" class="nextbutton" onclick="javascript:controllerObj.confirmSelection('confirmation_div', 'details_div','<?php echo JURI::base().'images/basketimages/';?>');">
				Next
			</div>
			<div class="backbutton " onclick="javascript:controllerObj.switchInnerDivs('confirmation_div', 'main-category-div', 0, 0);">
				Back
			</div>
		</div>
		<h2 style="padding-bottom:30px; margin-bottom:10px;"> 
    	    <div style="float:left; margin-top:5px;"> Your Selection</div> 
	        <div class="icon"><img src="<?php echo JURI::base().'templates/'.$app->getTemplate().'/images/tv.png';?>"/></div> 
	        <div class="icon"><img src="<?php echo JURI::base().'templates/'.$app->getTemplate().'/images/music.png';?>"/></div>
     		<div class="icon"><img src="<?php echo JURI::base().'templates/'.$app->getTemplate().'/images/game.png';?>"/></div>
	        <div class="icon"><img src="<?php echo JURI::base().'templates/'.$app->getTemplate().'/images/broadband.png';?>"/></div>
        
        </h2>
		
		<div id="selected_product_inner_div_1">
			<div class="mainwrap">
				<div id="c1" class="no_product">
					<div id="c11" class="carousel">
					</div>
				</div>
			</div>

			<div class="mainwrap">
				<div id="c2" class="no_product">
					<div id ="c12" class="carousel">
					</div>
				</div>
			</div>

			<div class="mainwrap">
				<div id="c3" class="no_product">
					<div id ="c13" class="carousel">
					</div>
				</div>
			</div>

			<div class="mainwrap">
				<div id="c4" class="no_product">
					<div id ="c14" class="carousel">
					</div>
				</div>
			</div>

			<div class="mainwrap" style="display:none">
				<div class="Title">ConnectUpMyHome</div>
				<div id="c5" class="product">
					<div id ="c15" class="carousel">
					</div>
				</div>
			</div>
			<div style="float:right;">
			<div style="margin-top:5px; font-size:11px; font-weight:bold;">(&#10003;) to select</div>
			<div style="margin:40px 0 0 5px; float:left"><input type="checkbox" id="confirmyourselection"onclick="javascript:controllerObj.confirmSelection('confirmation_div', 'installation_div',0,0);"/>
			<div style="width:76px; margin-top:27px;">
				<div class="monthly_price">Monthly Price</div>
            	<div class="monthly_price_price" id="monthlyprice_yourselection_row"></div>
				<div class="monthly_price">One-Off Price</div>
            	<div  style="border-bottom:none;" class="monthly_price_price" id="oneoff_yourselection_row"></div>
 			</div>
		   </div>
		   </div>
		</div>
	</div>

	<div class="selected_product_div">
		<h2> New Selection </h2>
	
		<div class="selection_div_wrap">
	
			<div id="new_selected_product_div" class="selected_product_div" style="float: left;">
				<div class="miniwrap"><div id="new_selected_product_div_tv-content" class="product"><div class="carousel"><div class="bx-wrapper" style="max-width: 100%;"><div class="bx-viewport" style="width: 100%;  position: inherit; min-height: 92px;"><ul id="new_selected_ul_tv-content" class="columns" style="position: inherit; width: auto;"></ul></div></div></div></div></div>
				<div class="miniwrap"><div id="new_selected_product_div_music-content" class="product"><div class="bx-wrapper" style="max-width: 100%;"><div class="bx-viewport" style="width: 100%; position: inherit; min-height: 92px"><div class="carousel"><ul id="new_selected_ul_music-content" class="columns" style="position: inherit; width: auto;"></ul></div></div></div></div></div>
				<div class="miniwrap"><div id="new_selected_product_div_games" class="product"><div class="bx-wrapper" style="max-width: 100%;"><div class="bx-viewport" style="width: 100%; position: inherit; min-height: 92px"><div class="carousel"><ul id="new_selected_ul_games" class="columns" style="position: inherit; width: auto;"></ul></div></div></div></div></div>
				<div class="miniwrap"><div id="new_selected_product_div_broadband" class="product"><div class="bx-wrapper" style="max-width: 100%;"><div class="bx-viewport" style="width: 100%;  position: inherit; min-height: 92px"><div class="carousel"><ul id="new_selected_ul_broadband" class="columns" style="position: inherit; width: auto;"></ul></div></div></div></div></div>	
			</div>
		</div>
        <div style="margin-top:-70px; margin-left: -75px; float:right;"><input type="checkbox" id="confirmnewselection" onclick="javascript:controllerObj.confirmSelection('confirmation_div', 'installation_div','<?php echo JURI::base().'images/basketimages/';?>','new_selection');"/>
			<div style="width:76px; margin-top:27px;">
				<div class="monthly_price">Monthly Price</div>
            	<div class="monthly_price_price" id="monthlyprice_newselection_row">$0.00</div>
				<div class="monthly_price">One-Off Price</div>
            	<div  style="border-bottom:none;" class="monthly_price_price" id="oneoff_newselection_row">$0.00</div>
 			</div>
        
			<div style="width:76px; margin-top:52px;">
				<button type="button"  class="nextbutton" style="float:left;" id="clear_selection" onclick ="javascript:controllerObj.clearSelection()">Clear</button>
			</div>
		</div>                        
	</div>

	<div id="our_recommendation_div">
		<h2> Our Recommendations </h2>
        
		<div id="recommended_product" style="display: block;">
		<?php 
				$r=1;
				$b=1;
				$p=1;
				
				foreach($this->recommended as $bundle)
				{	
					
					foreach($bundle as $bundle1)
					{
						
						if(strcasecmp($bundle1->description, "Lowest price bundle") == 0)
						{
				  
						?>	
							<div id="<?php echo 'lower_'.$r;?>">
						<?php
							if($r==1 || $r==4 || $r==7)
							{ 

								?>
									<div class="RecAddrow">
										<div class="RecTitle"><?php echo $bundle1->description;?></div>
										<div id="<?php echo "recpriceadd".$r;?>" class="RecImage" onClick="javascript:controllerObj.showExpansionDivs('lower_<?php echo ($r+1);?>,lower_<?php echo ($r+2);?>',0,'<?php echo "recpriceadd".$r;?>','<?php echo "recpriceclose".$r;?>');" style ="display:block" >
										</div>
										<div id="<?php echo "recpriceclose".$r;?>" class="RecImage" onClick="javascript:controllerObj.showExpansionDivs('lower_<?php echo ($r+1);?>,lower_<?php echo ($r+2);?>',1,'<?php echo "recpriceadd".$r;?>','<?php echo "recpriceclose".$r;?>');" style ="display:none">
											<img id="" src="<?php //echo JURI::base().'templates/'.$app->getTemplate().'/images/minus.png';?>"/>
										</div>
									</div>   
								<?php 
							}
							else 
							{
								echo  '<div class="RecAddrow">';
								echo '</div>';
							}
								?>
								<div id="lower_<?php echo $r;?>_1" class="RecProduct addDraggableProduct">
								<?php   
											$supplier =$bundle1->tV->supplier;
											$title = $bundle1->tV->title;
											$producttype = $bundle1->tV->category;
											$quota = $bundle1->tV->quota;
											$mprice = $bundle1->tV->monthlyPrice;
											$price = $bundle1->tV->price;
											$contract = $bundle1->tV->contractLength;
											$equipmentPrice = $bundle1->tV->equipmentPrice;
											$installationPrice = $bundle1->tV->installationPrice;
											$equipment = $bundle1->tV->equipment;
											$id = $bundle1->tV->id;
								?>
								<div class="lower_<?php echo $r;?>_1" data-product-detail="<?php echo JFilterOutput::stringURLSafe("tv content").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->tV->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice; ?>">
									<ul class="columns" style="position: inherit !important; padding:none !important">
									<?php 
		
											$imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
						
											if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
											{
												$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
											}
										$imagefilehtml = imageHtmlString($supplier);
											
											
									?>
										<li style='padding:none !important'>
											<a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
												<div style="line-height: normal;" >
												<span class="hovertitle"><?php echo $title;?></span>
												</br></br>
											<?php 
												if($mprice != "") {echo 'Monthly Price: <span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  	if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      	if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      	if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      	if($contract != "") echo 'Contract: '.$contract.'</br>';
										      	if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      
										      		</br>
										      		<div class="showdetails">
										      		<a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a>
										      		</div>
										      	</div>
										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										<span class="hovercategory"><?php echo $category?> </span><br>
											<?php 
												if(!empty($quota))
												{ 
										        ?>
													<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      	}
										    	if(!empty($contract))
												{
											 	?>
													<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										     	}
										      	if(!empty($mprice))
												{
												?>
													<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										    	}
										        if(!empty($price))
												{
												?>
													<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
											<?php 
												}
												?>
										</div>
										</div>
									 	</li>
									</ul>
								</div>
								</div>
   								<div id="lower_<?php echo $r;?>_2" class="RecProduct addDraggableProduct">
									<?php   $supplier =$bundle1->music->supplier;
											$title = $bundle1->music->title;
											$producttype = $bundle1->music->category;
											$quota = $bundle1->music->quota;
											$mprice = $bundle1->music->monthlyPrice;
											$price = $bundle1->music->price;
											$contract = $bundle1->music->contractLength;
											$equipmentPrice = $bundle1->music->equipmentPrice;
											$installationPrice = $bundle1->music->installationPrice;
											$equipment = $bundle1->music->equipment;
											$id = $bundle1->music->id;
									?>
									<div class="lower_<?php echo $r;?>_2" data-product-detail="<?php echo JFilterOutput::stringURLSafe("music content").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->music->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice; ?>">
									    <ul class="columns" style="position: inherit !important; padding:none !important">
									    <?php 
									    				
				   							  $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
						
														if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
													    {
															$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
													    }
												$imagefilehtml = imageHtmlString($supplier);
									    ?>
										<li style='padding:none !important'><a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
										<div style="line-height: normal;" ><span class="hovertitle">
										<?php echo $title;?></span></br></br>
										<?php 
										      if($mprice != "") {echo 'Monthly Price: <span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      if($contract != "") echo 'Contract: '.$contract.'</br>';
										      if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      </br><div class="showdetails"><a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a></div></div>
									
										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										 <span class="hovercategory"><?php echo $producttype?> </span><br>
										      <?php if(!empty($quota))
										      { 
										        ?>
												<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      }
										      if(!empty($contract))
										      {
											 	?>
												<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										      }
										       if(!empty($mprice))
										       {
												?>
												<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										       }
										         if(!empty($price))
										         {
												?>
												<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
												<?php 
										         }
												?>
												</div>
											</div>
											</li>
											</ul>
									</div>
									</div>
							<div id="lower_<?php echo $r;?>_3" class="RecProduct addDraggableProduct">
									<?php   $supplier =$bundle1->gaming->supplier;
											$title = $bundle1->gaming->title;
											$producttype = $bundle1->gaming->category;
											$quota = $bundle1->gaming->quota;
											$mprice = $bundle1->gaming->monthlyPrice;
											$price = $bundle1->gaming->price;
											$contract = $bundle1->gaming->contractLength;
											$equipmentPrice = $bundle1->gaming->equipmentPrice;
											$installationPrice = $bundle1->gaming->installationPrice;
											$equipment = $bundle1->gaming->equipment;
											$id = $bundle1->gaming->id;
									?>
									<div class="lower_<?php echo $r;?>_3" data-product-detail="<?php echo JFilterOutput::stringURLSafe("games").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->gaming->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice; ?>">
									     <ul class="columns" style="position: inherit !important; padding:none !important">
									    <?php 
									    				
				   								  $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
						
														if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
													    {
															$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
													    }
													$imagefilehtml = imageHtmlString($supplier);
									    ?>
										<li style='padding:none !important'><a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
										<div style="line-height: normal;" ><span class="hovertitle">
										<?php echo $title;?></span></br></br>
										<?php 
										      if($mprice != "") {echo 'Monthly Price: <span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      if($contract != "") echo 'Contract: '.$contract.'</br>';
										      if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      </br><div class="showdetails"><a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a></div></div>
									

										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										 <span class="hovercategory"><?php echo $producttype?> </span><br>
										      <?php if(!empty($quota))
										      { 
										        ?>
												<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      }
										      if(!empty($contract))
										      {
											 	?>
												<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										      }
										       if(!empty($mprice))
										       {
												?>
												<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										       }
										         if(!empty($price))
										         {
												?>
												<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
												<?php 
										         }
												?>
												</div>
											</div>
											</li>
											</ul>
									</div>
								</div>
							
								
								<div id="lower_<?php echo $r;?>_4" class="RecProduct addDraggableProduct">
									<?php   $supplier =$bundle1->broadband->supplier;
											$title = $bundle1->broadband->title;
											$producttype = $bundle1->broadband->category;
											$quota = $bundle1->broadband->quota;
											$mprice = $bundle1->broadband->monthlyPrice;
											$price = $bundle1->broadband->price;
											$contract = $bundle1->broadband->contractLength;
											$equipmentPrice = $bundle1->broadband->equipmentPrice;
											$installationPrice = $bundle1->broadband->installationPrice;
											$equipment = $bundle1->broadband->equipment;
											$id = $bundle1->broadband->id;
									?>
									<div class="lower_<?php echo $r;?>_4" data-product-detail="<?php echo JFilterOutput::stringURLSafe("broadband").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->broadband->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice; ?>">
									   <ul class="columns" style="position: inherit !important; padding:none !important">
									    <?php 
									    				
				  								 	  $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
						
														if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
													    {
															$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
													    }
													    $imagefilehtml = imageHtmlString($supplier);
									    ?>
										<li style='padding:none !important'><a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
										<div style="line-height: normal;" ><span class="hovertitle">
										<?php echo $title;?></span></br></br>
										<?php 
										      if($mprice != "") {echo 'Monthly Price: <span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      if($contract != "") echo 'Contract: '.$contract.'</br>';
										      if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      </br><div class="showdetails"><a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a></div></div>
									
										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										 <span class="hovercategory"><?php echo $producttype?> </span><br>
										      <?php if(!empty($quota))
										      { 
										        ?>
												<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      }
										      if(!empty($contract))
										      {
											 	?>
												<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										      }
										       if(!empty($mprice))
										       {
												?>
												<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										       }
										         if(!empty($price))
										         {
												?>
												<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
												<?php 
										         }
												?>
											</div>
											</div>
											</li>
											</ul>
									</div>
								</div>
				
				                <div class="RecDevidercheckbox">
								
                                <div> <input type="checkbox" id="<?php echo 'lower_recommendation_bundle'.$r;?>" name="<?php echo 'recommendation_bundle'.$r;?>" value="<?php echo 'recommendation_bundle'.$r;?>" onclick="javascript:controllerObj.AddRecommendedPackage(['lower_<?php echo $r;?>_1','lower_<?php echo $r;?>_2','lower_<?php echo $r;?>_3','lower_<?php echo $r;?>_4'],'lower_<?php echo $r;?>',this.id)">
								
                                </div>
                                
                                <div style="width:76px; margin-top:27px;">
                                
                                <?php
									echo '<div class="monthly_price">Monthly Price</div><div class="monthly_price_price"><strong>$'.$monthly_price.'</strong></div>';
									echo '<div class="monthly_price">One-Off Price</div><div style="border-bottom:none;" class="monthly_price_price"><strong>$'.$one_off_price.'</strong></div>';
									$monthly_price = 0;
									$one_off_price = 0;
								?>
                                
                                </div>
                                
                                
				               </div>
							</div>
									
							<?php 		
							   
							     $r++;
						}
				    if(strcasecmp($bundle1->description, "Best value bundle") == 0)
				   	  {
				  
								?>	
								<div id="<?php echo 'best_'.$b;?>">
								<?php
								if($b==1)
								{ 
								
								?>
								<div class="RecAddrow">
									<div class="RecTitle"><?php echo $bundle1->description;?></div>
									<div id="<?php echo "recbestadd".$b;?>" class="RecImage" onClick="javascript:controllerObj.showExpansionDivs('best_<?php echo ($b+1);?>,best_<?php echo ($b+2);?>',0,'<?php echo "recbestadd".$b;?>','<?php echo "recbestclose".$b;?>');" style ="display:block" >
										
									</div>
									<div id="<?php echo "recbestclose".$b;?>" class="RecImage" onClick="javascript:controllerObj.showExpansionDivs('best_<?php echo ($b+1);?>,best_<?php echo ($b+2);?>',1,'<?php echo "recbestadd".$b;?>','<?php echo "recbestclose".$b;?>');" style ="display:none">
										<img id="" src="<?php //echo JURI::base().'templates/'.$app->getTemplate().'/images/minus.png';?>"/>
									</div>
								</div>
								<?php 
								}
								else 
								{
									
								echo  '<div class="RecAddrow">';
								 echo '</div>';
								}
								?>
								
								<div id="best_<?php echo $b;?>_1" class="RecProduct addDraggableProduct">
									<?php   $supplier =$bundle1->tV->supplier;
											$title = $bundle1->tV->title;
											$producttype = $bundle1->tV->category;
											$quota = $bundle1->tV->quota;
											$mprice = $bundle1->tV->monthlyPrice;
											$price = $bundle1->tV->price;
											$contract = $bundle1->tV->contractLength;
											$equipmentPrice = $bundle1->tV->equipmentPrice;
											$installationPrice = $bundle1->tV->installationPrice;
											$equipment = $bundle1->tV->equipment;
											$id = $bundle1->tV->id;
									?>
									<div class="best_<?php echo $b;?>_1" data-product-detail="<?php echo JFilterOutput::stringURLSafe("tv content").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->tV->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice; ?>">
									    <ul class="columns" style="position: inherit !important; padding:none !important">
									    <?php 
									    				
				   	  									$imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
						
														if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
													    {
															$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
													    }
													    $imagefilehtml = imageHtmlString($supplier);
									    ?>
										<li style='padding:none !important'><a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
										<div style="line-height: normal;" ><span class="hovertitle">
										<?php echo $title;?></span></br></br>
										<?php 
										      if($mprice != "") {echo 'Monthly Price: <span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      if($contract != "") echo 'Contract: '.$contract.'</br>';
										      if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      </br><div class="showdetails"><a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a></div></div>
									
										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										 <span class="hovercategory"><?php echo $producttype?> </span><br>
										      <?php if(!empty($quota))
										      { 
										        ?>
												<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      }
										      if(!empty($contract))
										      {
											 	?>
												<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										      }
										       if(!empty($mprice))
										       {
												?>
												<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										       }
										         if(!empty($price))
										         {
												?>
												<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
												<?php 
										         }
												?>
											</div>
											</div>
											</li>
											</ul>
									</div>
								</div>
								<div id="best_<?php echo $b;?>_2" class="RecProduct addDraggableProduct">
									<?php   $supplier =$bundle1->music->supplier;
											$title = $bundle1->music->title;
											$producttype = $bundle1->music->category;
											$quota = $bundle1->music->quota;
											$mprice = $bundle1->music->monthlyPrice;
											$price = $bundle1->music->price;
											$contract = $bundle1->music->contractLength;
											$equipmentPrice = $bundle1->music->equipmentPrice;
											$installationPrice = $bundle1->music->installationPrice;
											$equipment = $bundle1->music->equipment;
											$id = $bundle1->music->id;
											
									?>
									<div class="best_<?php echo $b;?>_2" data-product-detail="<?php echo JFilterOutput::stringURLSafe("music content").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->music->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice; ?>">
									    <ul class="columns" style="position: inherit !important; padding:none !important">
									    <?php 
									    				
				   									  $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
						
														if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
													    {
															$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
													    }
													    $imagefilehtml = imageHtmlString($supplier);
									    ?>
										<li style='padding:none !important'><a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
										<div style="line-height: normal;" ><span class="hovertitle">
										<?php echo $title;?></span></br></br>
										<?php 
										      if($mprice != "") {echo 'Monthly Price:<span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      if($contract != "") echo 'Contract: '.$contract.'</br>';
										      if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      </br><div class="showdetails"><a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a></div></div>
									
										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										 <span class="hovercategory"><?php echo $producttype?> </span><br>
										      <?php if(!empty($quota))
										      { 
										        ?>
												<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      }
										      if(!empty($contract))
										      {
											 	?>
												<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										      }
										       if(!empty($mprice))
										       {
												?>
												<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										       }
										         if(!empty($price))
										         {
												?>
												<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
												<?php 
										         }
												?>
											</div>
											</div>
											</li>
											</ul>
									</div>
								</div>
				
								<div id="best_<?php echo $b;?>_3" class="RecProduct addDraggableProduct">
									<?php    $supplier =$bundle1->gaming->supplier;
											$title = $bundle1->gaming->title;
											$producttype = $bundle1->gaming->category;
											$quota = $bundle1->gaming->quota;
											$mprice = $bundle1->gaming->monthlyPrice;
											$price = $bundle1->gaming->price;
											$contract = $bundle1->gaming->contractLength;
											$equipmentPrice = $bundle1->gaming->equipmentPrice;
											$installationPrice = $bundle1->gaming->installationPrice;
											$equipment = $bundle1->gaming->equipment;
											$id = $bundle1->gaming->id;
									?>
									<div class="best_<?php echo $b;?>_3" data-product-detail="<?php echo JFilterOutput::stringURLSafe("games").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->gaming->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice; ?>">
									     <ul class="columns" style="position: inherit !important; padding:none !important">
									    <?php 
				   								  $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
						
														if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
													    {
															$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
													    }
													    $imagefilehtml = imageHtmlString($supplier);
									    ?>
										<li style='padding:none !important'><a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
										<div style="line-height: normal;" ><span class="hovertitle">
										<?php echo $title;?></span></br></br>
										<?php 
										      if($mprice != "") {echo 'Monthly Price: <span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      if($contract != "") echo 'Contract: '.$contract.'</br>';
										      if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      </br><div class="showdetails"><a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a></div></div>
									
										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										 <span class="hovercategory"><?php echo $producttype?> </span><br>
										      <?php if(!empty($quota))
										      { 
										        ?>
												<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      }
										      if(!empty($contract))
										      {
											 	?>
												<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										      }
										       if(!empty($mprice))
										       {
												?>
												<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										       }
										         if(!empty($price))
										         {
												?>
												<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
												<?php 
										         }
												?>
											</div>
											</div>
											</li>
											</ul>
									</div>
								</div>

								<div id="best_<?php echo $b;?>_4" class="RecProduct addDraggableProduct">
									<?php   $supplier =$bundle1->broadband->supplier;
											$title = $bundle1->broadband->title;
											$producttype = $bundle1->broadband->category;
											$quota = $bundle1->broadband->quota;
											$mprice = $bundle1->broadband->monthlyPrice;
											$price = $bundle1->broadband->price;
											$contract = $bundle1->broadband->contractLength;
											$equipmentPrice = $bundle1->broadband->equipmentPrice;
											$installationPrice = $bundle1->broadband->installationPrice;
											$equipment = $bundle1->broadband->equipment;
											$id = $bundle1->broadband->id;
									?>
									<div class="best_<?php echo $b;?>_4" data-product-detail="<?php echo JFilterOutput::stringURLSafe("broadband").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->broadband->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice.','.$equipmentPrice.','.$installationPrice; ?>">
									   <ul class="columns" style="position: inherit !important; padding:none !important">
									    <?php 
									    		$imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
														if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
													    {
															$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
													    }
													    $imagefilehtml = imageHtmlString($supplier);
									    ?>
										<li style='padding:none !important'><a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
										<div style="line-height: normal;" ><span class="hovertitle">
										<?php echo $title;?></span></br></br>
										<?php 
										      if($mprice != "") {echo 'Monthly Price: <span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      if($contract != "") echo 'Contract: '.$contract.'</br>';
										      if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      </br><div class="showdetails"><a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a></div></div>
									
										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										 <span class="hovercategory"><?php echo $producttype?> </span><br>
										      <?php if(!empty($quota))
										      { 
										        ?>
												<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      }
										      if(!empty($contract))
										      {
											 	?>
												<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										      }
										       if(!empty($mprice))
										       {
												?>
												<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										       }
										         if(!empty($price))
										         {
												?>
												<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
												<?php 
										         }
												?>
											</div>
											</div>
											</li>
											</ul>
									</div>
								</div>
				
				                <div class="RecDevidercheckbox">
                                <div>
								<input type="checkbox" id="<?php echo 'best_recommendation_bundle'.$b;?>" name="<?php echo 'recommendation_bundle'.$b;?>" value="<?php echo 'recommendation_bundle'.$b;?>" onclick="javascript:controllerObj.AddRecommendedPackage(['best_<?php echo $b;?>_1','best_<?php echo $b;?>_2','best_<?php echo $b;?>_3','best_<?php echo $b;?>_4'],'best_<?php echo $b;?>',this.id)">
				                </div>
                                
                                <div style="width:76px; margin-top:27px;">
				                <?php
									echo '<div class="monthly_price">Monthly Price</div><div class="monthly_price_price"><strong>$'.$monthly_price.'</strong></div>';
									echo '<div class="monthly_price">One Off Price</div><div style="border-bottom:none;" class="monthly_price_price"><strong>$'.$one_off_price.'</strong></div>';
									$monthly_price = 0;
									$one_off_price = 0;
								?>
                                </div>
                                </div>
   							</div>
							<?php 		
							$b++;
						}
				    if(strcasecmp($bundle1->description, "Most popular bundle") == 0)
				   	  {
							?>	
								<div id="<?php echo 'popular_'.$p;?>">
								<?php
								if($p==1)
								{ 
								?>
								<div class="RecAddrow">
									<div class="RecTitle"><?php echo $bundle1->description;?></div>
									<div id="<?php echo "recpopularadd".$p;?>" class="RecImage" onClick="javascript:controllerObj.showExpansionDivs('popular_<?php echo ($p+1);?>,popular_<?php echo ($p+2);?>',0,'<?php echo "recpopularadd".$p;?>','<?php echo "recpopularclose".$p;?>');" style ="display:block" >
										
									</div>
									<div id="<?php echo "recpopularclose".$p;?>" class="RecImage" onClick="javascript:controllerObj.showExpansionDivs('popular_<?php echo ($p+1);?>,popular_<?php echo ($p+2);?>',1,'<?php echo "recpopularadd".$p;?>','<?php echo "recpopularclose".$p;?>');" style ="display:none">
										<img id="" src="<?php //echo JURI::base().'templates/'.$app->getTemplate().'/images/minus.png';?>"/>
									</div>
								</div>
								<?php 
								}
								else 
								{
									
								echo  '<div class="RecAddrow">';
								 echo '</div>';
								}
								?>
								
								<div id="popular_<?php echo $p;?>_1" class="RecProduct addDraggableProduct">
									<?php   $supplier =$bundle1->tV->supplier;
											$title = $bundle1->tV->title;
											$producttype = $bundle1->tV->category;
											$quota = $bundle1->tV->quota;
											$mprice = $bundle1->tV->monthlyPrice;
											$price = $bundle1->tV->price;
											$contract = $bundle1->tV->contractLength;
											$equipmentPrice = $bundle1->tV->equipmentPrice;
											$installationPrice = $bundle1->tV->installationPrice;
											$equipment = $bundle1->tV->equipment;
											
											$id = $bundle1->tV->id;
									?>
									<div class="popular_<?php echo $p;?>_1" data-product-detail="<?php echo JFilterOutput::stringURLSafe("tv content").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->tV->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice; ?>">
									    <ul class="columns" style="position: inherit !important; padding:none !important">
									    <?php 
									    				
				   								  $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
						
														if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
													    {
															$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
													    }
													    $imagefilehtml = imageHtmlString($supplier);
									    ?>
									<li style='padding:none !important'><a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
										<div style="line-height: normal;" ><span class="hovertitle">
										<?php echo $title;?></span></br></br>
										<?php 
										      if($mprice != "") {echo 'Monthly Price: <span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      if($contract != "") echo 'Contract: '.$contract.'</br>';
										      if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      </br><div class="showdetails"><a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a></div></div>
									
										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										 <span class="hovercategory"><?php echo $producttype?> </span><br>
										      <?php if(!empty($quota))
										      { 
										        ?>
												<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      }
										      if(!empty($contract))
										      {
											 	?>
												<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										      }
										       if(!empty($mprice))
										       {
												?>
												<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										       }
										         if(!empty($price))
										         {
												?>
												<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
												<?php 
										         }
												?>
											</div>
											</div>
											</li>
											</ul>
									</div>
								</div>
								
								<div id="popular_<?php echo $p;?>_2" class="RecProduct addDraggableProduct">
									<?php   $supplier =$bundle1->music->supplier;
											$title = $bundle1->music->title;
											$producttype = $bundle1->music->category;
											$quota = $bundle1->music->quota;
											$mprice = $bundle1->music->monthlyPrice;
											$price = $bundle1->music->price;
											$contract = $bundle1->music->contractLength;
											$equipmentPrice = $bundle1->music->equipmentPrice;
											$installationPrice = $bundle1->music->installationPrice;
											$equipment = $bundle1->music->equipment;
											$id = $bundle1->music->id;
									?>
									<div class="popular_<?php echo $p;?>_2" data-product-detail="<?php echo JFilterOutput::stringURLSafe("music content").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->music->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice; ?>">
									    <ul class="columns" style="position: inherit !important; padding:none !important">
									    <?php 
													 $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
														if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
													    {
															$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
													    }
													    $imagefilehtml = imageHtmlString($supplier);
									    ?>
									<li style='padding:none !important'><a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
										<div style="line-height: normal;" ><span class="hovertitle">
										<?php echo $title;?></span></br></br>
										<?php 
										      if($mprice != "") {echo 'Monthly Price: <span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      if($contract != "") echo 'Contract: '.$contract.'</br>';
										      if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      </br><div class="showdetails"><a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a></div></div>
									
										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										 <span class="hovercategory"><?php echo $producttype?> </span><br>
										      <?php if(!empty($quota))
										      { 
										        ?>
												<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      }
										      if(!empty($contract))
										      {
											 	?>
												<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										      }
										       if(!empty($mprice))
										       {
												?>
												<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										       }
										         if(!empty($price))
										         {
												?>
												<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
												<?php 
										         }
												?>
											</div>
											</div>
											</li>
											</ul>
									</div>
								</div>
				
								<div id="popular_<?php echo $p;?>_3" class="RecProduct addDraggableProduct">
									<?php   $supplier =$bundle1->gaming->supplier;
											$title = $bundle1->gaming->title;
											$producttype = $bundle1->gaming->category;
											$quota = $bundle1->gaming->quota;
											$mprice = $bundle1->gaming->monthlyPrice;
											$price = $bundle1->gaming->price;
											$contract = $bundle1->gaming->contractLength;
											$equipmentPrice = $bundle1->gaming->equipmentPrice;
											$installationPrice = $bundle1->gaming->installationPrice;
											$equipment = $bundle1->gaming->equipment;
											$id = $bundle1->gaming->id;
									?>
									<div class="popular_<?php echo $p;?>_3" data-product-detail="<?php echo JFilterOutput::stringURLSafe("games").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->gaming->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice; ?>">
									     <ul class="columns" style="position: inherit !important; padding:none !important">
									    <?php 
									    	 $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
						
														if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
													    {
															$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
													    }
													    $imagefilehtml = imageHtmlString($supplier);
									    ?>
										<li style='padding:none !important'><a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
										<div style="line-height: normal;" ><span class="hovertitle">
										<?php echo $title;?></span></br></br>
										<?php 
										      if($mprice != "") {echo 'Monthly Price: <span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      if($contract != "") echo 'Contract: '.$contract.'</br>';
										      if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      </br><div class="showdetails"><a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a></div></div>
									
										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										 <span class="hovercategory"><?php echo $producttype?> </span><br>
										      <?php if(!empty($quota))
										      { 
										        ?>
												<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      }
										      if(!empty($contract))
										      {
											 	?>
												<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										      }
										       if(!empty($mprice))
										       {
												?>
												<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										       }
										         if(!empty($price))
										         {
												?>
												<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
												<?php 
										         }
												?>
											</div>
											</div>
											</li>
											</ul>
									</div>
								</div>
<!--								<div class="RecDevider">-->
				
								
				
<!--								<div class="RecDevider">-->
								<div id="popular_<?php echo $p;?>_4" class="RecProduct addDraggableProduct">
									<?php   $supplier =$bundle1->broadband->supplier;
											$title = $bundle1->broadband->title;
											$producttype = $bundle1->broadband->category;
											$quota = $bundle1->broadband->quota;
											$mprice = $bundle1->broadband->monthlyPrice;
											$price = $bundle1->broadband->price;
											$contract = $bundle1->broadband->contractLength;
											$equipmentPrice = $bundle1->broadband->equipmentPrice;
											$installationPrice = $bundle1->broadband->installationPrice;
											$equipment = $bundle1->broadband->equipment;
											$id = $bundle1->broadband->id;
									?>
									<div class="popular_<?php echo $p;?>_4" data-product-detail="<?php echo JFilterOutput::stringURLSafe("broadband").','.JFilterOutput::stringURLSafe($supplier).','.JFilterOutput::stringURLSafe($title).','.JFilterOutput::stringURLSafe($bundle1->broadband->id).','.$contract.','.$quota.','.$mprice.','.$price.','.$equipmentPrice.','.$installationPrice; ?>">
									   <ul class="columns" style="position: inherit !important; padding:none !important">
									    <?php 
									    				
				   	  									$imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($supplier).'.jpg';
						
														if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($supplier).'.jpg'))
													    {
															$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
													    }
													    $imagefilehtml = imageHtmlString($supplier);
									    ?>
										<li style='padding:none !important'><a style="display:block;width:180px;height:70px"><?php echo $imagefilehtml;?></a>
										<div style="line-height: normal;" ><span class="hovertitle">
										<?php echo $title;?></span></br></br>
										<?php 
										      if($mprice != "") {echo 'Monthly Price: <span class="ulstrong"> '.$mprice.'</span></br>'; $monthly_price = $monthly_price + ltrim(ucfirst($mprice),'$');}
				   	  						  if($price != ""){ echo 'One-Off Price: <span class="ulstrong"> '.$price.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($price),'$'); }
										      if($installationPrice != "") { echo 'Installation Price: <span class="ulstrong"> '.$installationPrice.'</span></br>'; $one_off_price = $one_off_price + ltrim(ucfirst($installationPrice),'$');} 
										      if($equipmentPrice != ""){ echo 'Equipment Price: <span class="ulstrong"> '.$equipmentPrice.'</span></br>';$one_off_price = $one_off_price + ltrim(ucfirst($equipmentPrice),'$');}
										      if($contract != "") echo 'Contract: '.$contract.'</br>';
										      if($quota != "")echo 'Quota: '.$quota.'</br>'; 
										      ?>
										      </br><div class="showdetails"><a onclick="javascript:controllerObj.detailspopupwindow('<?php echo $id?>','<?php echo $imagefile;?>')">Details</a></div></div>
									
										<div class="info">
										<div class="subinfodiv">
										<span class="hovertitle"><?php echo $title;?></span><br>
										 <span class="hovercategory"><?php echo $producttype?> </span><br>
										      <?php if(!empty($quota))
										      { 
										        ?>
												<span class="hoverquota"><?php echo $quota;?></span><br>
											<?php 
										      }
										      if(!empty($contract))
										      {
											 	?>
												<span class="hoverdescription"><?php echo $contract;?>  contract</span><br>
											<?php 
										      }
										       if(!empty($mprice))
										       {
												?>
												<div class="hoveramount"><span><?php echo $mprice;?></span>  per month</div><br>
											<?php 
										       }
										         if(!empty($price))
										         {
												?>
												<span class="hoveramount span">Minimum total cost <?php echo $price;?></span><br>
												<?php 
										         }
												?>
											</div>
											</div>
											</li>
											</ul>
									</div>
								</div>
	
				                <div class="RecDevidercheckbox">
                                <div>
								<input type="checkbox" id="<?php echo 'popular_recommendation_bundle'.$p;?>" name="<?php echo 'recommendation_bundle'.$p;?>" value="<?php echo 'recommendation_bundle'.$p;?>" onclick="javascript:controllerObj.AddRecommendedPackage(['popular_<?php echo $p;?>_1','popular_<?php echo $p;?>_2','popular_<?php echo $p;?>_3','popular_<?php echo $p;?>_4'],'popular_<?php echo $p;?>',this.id)">
				                </div>
                                
                                <div style="width:76px; margin-top:27px;">
				                <?php
									echo '<div class="monthly_price">Monthly Price</div><div class="monthly_price_price"><strong>$'.$monthly_price.'</strong></div>';
									echo '<div class="monthly_price">One-Off Price</div><div style="border-bottom:none;" class="monthly_price_price"><strong>$'.$one_off_price.'</strong></div>';
									$monthly_price = 0;
									$one_off_price = 0;
								?>
                                </div>
                                </div>
    						</div>
							<?php 		
							     $p++;
						}
				   }
			 }
			?>
		</div>
	</div>
</div>