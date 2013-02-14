<?php

class UserEmailPlugin extends FSSCustFieldPlugin
{
	var $name = "Display Current Users EMail Address";

	function Input($current, $params, $context, $id) // output the field for editing
	{
		return $this->Display($current, $params, $context, $id);
	}
	
	function Display($value, $params, $context, $id) // output the field for display
	{
		$userid = $context['userid'];
		if ($userid < 1)
			return "";
		$user =& JFactory::getUser($userid);
		return $user->email;
	}

	function CanEdit()
	{
		return false;	
	}
}