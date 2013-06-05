<?php
	$brands = array();
	$brands_wrk = array();
	$suppliers = $_SESSION['suppliers']; // JRequest::getVar('suppliers');

	$brands_wrk =	json_decode($suppliers);
  if(!empty($brands_wrk))
  {
	foreach ($brands_wrk as $b)
	{
		$temp = explode("::", $b);
		if(!in_array($temp[1], $brands))
		{
			array_push($brands, $temp[1]);
		}
	}
  }
?>

<div id="thank_div">
	<div id="left" class="ThankYou">
		<div id="thank_you_inner_selected_brand_div">
			<div class="title"><span> Thank You for using Connect Up My Home </span> </div>
			<br>
			Our partners below will contact you soon.
			<br>
			<?php
			foreach($brands as $suppliername)
			{
				$sup = JFilterOutput::stringURLSafe($suppliername);
				?>
				<div class="thankyoutile">
				 <?php 
					if (strrchr($suppliername, '+'))
					{
						$imag = explode("+", $suppliername);
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
						$imagefile1= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($suppliername).'.jpg';

						if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($suppliername).'.jpg'))
						{
							$imagefile1= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
						}
						?>
						<img src="<?php echo $imagefile1; ?>" alt="<?php echo $suppliername ?>" class="imageSize"/>
						<?php
					}
					?>
					<!--<img src="<?php// echo "images/main_accordian/".$sup.".jpg"; ?>" class="imageSize">-->
				</div>
				<?php
			}
			if(JRequest::getVar('cumh_installer_help') == true)
			{ ?>
			<a class="brandtip" href="javascript:void(0);">
				<div class="thankyoutile">
					<img src="<?php echo "images/logo.png"; ?>" class="imageSize">
				</div>
				<span class="hovertitle">Thank You, our representative will be in touch with you soon</span>
			</a>
			<?php
			}
			?>
		</div>
	</div>
</div>

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
/*#gallery a:focus {
	-moz-transform: rotate(-2deg);
	-o-transform: rotate(-2deg);
	-webkit-transform: rotate(-2deg);
}*/
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
border:1px solid #D3D2D2 !important;}

#lightbox img {
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