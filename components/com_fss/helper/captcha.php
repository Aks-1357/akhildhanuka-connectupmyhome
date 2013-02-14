<?php

class FSS_Captcha
{
	function GetCaptcha()
	{
		$usecaptcha = FSS_Settings::get( 'captcha_type' );
		
		if ($usecaptcha == "")
			return "";
		if ($usecaptcha == "fsj")
			return "<img src='" . FSSRoute::x("index.php?option=com_fss&task=captcha_image&random=" . rand(0,65535)) . "' /><input id='security_code' name='security_code' type='text' style='position: relative; top: -14px; left: 3px;'/>";
		if ($usecaptcha == "recaptcha")
		{
			require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'recaptcha.php');
			$error = "";
			global $fss_publickey,$fss_privatekey;
			return fss_recaptcha_get_html($fss_publickey, $error);		
		}
		return "";
	}
	
	function ValidateCaptcha()
	{
		$usecaptcha = FSS_Settings::get( 'captcha_type' );
		if ($usecaptcha == "")
			return true;

		if ($usecaptcha == "fsj")
		{
			if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) ) { 
				//unset($_SESSION['security_code']);
				return true;
			}
			return false;
		}
		if ($usecaptcha == "recaptcha")
		{
			require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'recaptcha.php');
			global $fss_publickey,$fss_privatekey;
			if (array_key_exists("recaptcha_challenge_field",$_POST))
			{
				$resp = fss_recaptcha_check_answer ($fss_privatekey,
					$_SERVER["REMOTE_ADDR"],
					$_POST["recaptcha_challenge_field"],
					$_POST["recaptcha_response_field"]);
			} else {
				$resp = null;	
			}
			if ($resp && $resp->is_valid)
			{
				return true;	
			} else {
				return false;	
			}
		}
		return true;
	}
	
	function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}
	
	function GetImage($width='150',$height='40',$characters='6') {
		$this->font = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'fonts'.DS.'captcha.ttf';
		
		$code = $this->generateCode($characters);
		$_SESSION['security_code'] = $code;
		$code2 = "";
		for ($i = 0; $i < strlen($code); $i++)
			$code2 .= substr($code,$i,1) . " ";
		$code = $code2;
		/* font size will be 75% of the image height */
		$font_size = $height * 0.60;
		$image = imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		/* set the colours */
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 10, 20, 50);
		$noise_color = imagecolorallocate($image, 150, 160, 100);
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/300; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code) or die('Error in imagettftext function');
		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
	}
}