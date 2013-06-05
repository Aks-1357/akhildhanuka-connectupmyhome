<?php
$app = JFactory::getApplication();
$i = 0;
$count = count($this->data);
$app = JFactory::getApplication();
$templateDir = JURI::base() . 'templates/' . $app->getTemplate();
?>
<style>
 a.brandtip {outline:none; float:left; } 
 a.brandtip strong {line-height:30px;}
 a.brandtip:hover {text-decoration:none;}
 a.brandtip span 
 	{ 				
 		z-index:10;
 		display:none; 
 		padding:14px 20px;
		margin-top:-30px;
		margin-left:28px;
		width:240px;
		line-height:16px;
	 }
a.brandtip:hover span
{
	 display:inline;
	 position:absolute;
	 color:#000;
	 border:1px solid #D3D2D2;
	 background:#fff;
	 margin-left:-100px;
} 
.callout
 {
 	z-index:20;
 	position:absolute;
 	top:30px;border:0;left:-12px;
 }
  /*CSS3 extras*/ 
a.brandtip span
{ 

		border-radius:4px;
	 	-moz-border-radius: 4px;
	   -webkit-border-radius: 4px;	
	    -moz-box-shadow: 5px 5px 8px #CCC;
	    -webkit-box-shadow: 5px 5px 8px #CCC; 
	    box-shadow: 5px 5px 8px #CCC;
 }

#page-wrap {
	margin: 0 auto;
	position: relative;
	width: 980px;
}
#gallery {
	display: table;
	margin: 0 auto;
}
#gallery li {
	float: left;
	margin:0;
	position: relative;
	list-style:none;
}
#gallery span {
	background: rgba(0, 0, 0, 0.7);
	opacity: 10;
	padding: 10% 0;
	width: 100%;
	text-align: center;
	position: absolute;
	left: 0;
	bottom: 0;
	-moz-transition: opacity 0.6s ease-in;
	-o-transition: opacity 0.6s ease-in;
	-webkit-transition: opacity 0.6s ease-in;
}
#gallery li:hover span {
	opacity: 1;
}
#gallery a {
	display: block;
	position: relative;
	-moz-transition: border 0.6s ease-out;
	-o-transition: border 0.6s ease-out;
	-webkit-transition: border 0.6s ease-out;
}
#gallery a:hover {
}
#gallery img {
	float: left;
}
#lightbox table{
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	border:1px solid #D3D2D2;
}
#lightbox tr, td
{ padding:5px;
border:1px solid #D3D2D2 !important;
}
#lightbox #img2 {
	left: 115%;
}
#lightbox .headspan_a{
	background: none repeat scroll 0 0 #01A4D4;
    border-radius: 5px 5px 0 0;
    color: #fff;
    float: left;
    font-size: 12px;
    font-weight: bold;
    height: 22px;
    padding: 8px 0 0 10px;
    width: 630px;
	margin-bottom:20px;
}
#lightbox .headspan{
	background: none repeat scroll 0 0 #D3D2D2;
    color: #4D4C4C;
    float: left;
    font-size: 12px;
    font-weight: bold;
    height: 22px;
    padding: 8px 0 0 10px;
    width: 550px;
}
#lightbox a {
	color: #000000;
    cursor: pointer;
    float: right;
    margin: 4px;
    width: auto;
}
#lightbox{
	background: none repeat scroll 0 0 #FFFFFF;
    border: 2px solid #DBDBDB;
    border-radius: 5px 5px 5px 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
    display: block;
    left: 30px;
    padding: 0 0 40px 30px;
    position: absolute;
    top: 850%;
    width: 70%;
    height:400px;
    z-index: 10000;}

</style>

<div id="error_message" style="position:absolute;z-index:10000;font-color: red;"></div>	
<div id="main-category-div" style="<?php echo $this->mainCategoryDisplay; ?>">
    
	<div id="category-accordion-div">
		<?php
		foreach ($this->data as $d)
		{
			$i++;
			
		?>
		<div class="group">
			<h3 style="margin: 0;" onClick="javascript:controllerObj.detectAccordion(<?php echo $i;?>)"><?php echo $d->name; ?>  </h3>
			<div>
				<div style="padding: 10px 0px 0px 10px; margin-bottom: -13px;"><strong><?php echo $d->description; ?></strong></div>
				<div id= "<?php echo JFilterOutput::stringURLSafe('main_category_inner_div_'.$d->name); ?>">
				    
		<?php
					if($i == $count){
							$nextAccordion = "javascript:controllerObj.switchInnerDivs('main-category-div','confirmation_div',0,0);";
					}
					else {
				    	$nextAccordion = "javascript:controllerObj.nextAccordion('".JFilterOutput::stringURLSafe('category_accordion_div')."',".$i.",".$count.",'".$d->name."')";
					}
					?>
					<div onClick="<?php echo $nextAccordion; ?>" class="nextbutton">
						Next
					</div>

					<div  id="main-category-wrapper-<?php echo JFilterOutput::stringURLSafe($d->name);?>" class="main_category_wrapper">
					<?php
					    $j=0;
					    
						$class = "BrandDisplayRightBorder";
					    foreach ($d->values as $v)
						{
							
							$tip = $v->tips;
							$j++;
							if( $j % 4 == 0)
							{
								$class = "BrandDisplayRightBorder BorderNone";
							}
							if($j > 4)
							{
								$class = "BrandDisplayTopBorder";
								if( $j % 4 == 0)
								{
									$class = "BrandDisplayTopBorder BorderNone";
								}
							}
						?>
						
			         <a href="javascript:void(0);" class="brandtip">
						<div onClick="javascript:controllerObj.initSwitchInnerDivs('<?php echo JFilterOutput::stringURLSafe('main_category_inner_div_'.$d->name); ?>','<?php echo JFilterOutput::stringURLSafe('main_category_inner_product_div_'.$d->name.'-'.$v->name); ?>', <?php echo $i; ?>, <?php echo $j; ?>, '<?php echo $d->name; ?>', '<?php echo $v->name; ?>')" class="<?php echo $class;?>">
							<div class="tile">
							<div class="tile_inner">

						<?php 
						       if (strrchr($v->name, '+'))
						       {    
								 $imag = explode("+", $v->name);
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
							    
							   
							
							    ?>
									
								<img src="<?php echo $imagefile1; ?>" alt="<?php echo $img1; ?>" class="two_imageSize"/>
								<img src="<?php echo $imagefile2; ?>" alt="<?php echo $img2; ?>" class="two_imageSize"/>
								<?php 
								
							    }
						       }
							    
							    else
							    { 
							     $imagefile1= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($v->name).'.jpg';

								if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($v->name).'.jpg'))
							    {
									$imagefile1= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
							    }
							    	
							    	
							    	?>
							    <img src="<?php echo $imagefile1; ?>" alt="<?php echo $v->name ?>" class="imageSize"/>
							    <?php 
							    
							    }
							    ?>
							    
							    </div>
							</div>
						</div>
						<?php if(!empty($tip)&&isset($tip))
						{						
						?>
						  <span class="hovertitle"><?php echo $tip;?></span>
						<?php 
						}
						  ?>
					  </a>  
						
						<?php } ?>
					</div>

					<div class="NoProducts" style="display:none">
						<input onClick="<?php echo $greyedOut; ?>" id="'<?php echo JFilterOutput::stringURLSafe('checkbox-main_category_inner_div_'.$supcat[0].'_'.$supcat[$a]->name); ?>"  type="checkbox" value="1">
						&nbsp; I Do not Want Any <?php echo $supcat[$a]->name; ?> product
						<div onClick="<?php echo $nextAccordion; ?>" class="arrow">
							<img src="<?php echo JURI::base().'templates/'.$app->getTemplate().'/images/next.png';?>"/>
						</div>
					</div>
				</div>
				<?php
				foreach ($d->values as $v)
				{ ?>
					<div id="<?php echo JFilterOutput::stringURLSafe('main_category_inner_product_div_'.$d->name.'-'.$v->name); ?>" style="display: none;">
					</div>
					
				<?php }?>
				
				
			</div>
			
		</div>
		<?php } ?>
	</div>
</div>