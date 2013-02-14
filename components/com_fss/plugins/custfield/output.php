<?php

class OutputPlugin extends FSSCustFieldPlugin
{
	var $name = "HTML Output";
	
	function DisplaySettings($params) // passed object with settings in
	{
		$output = "<textarea name='plugin_html_output'>$params</textarea>";
		
		return $output;
	}
	
	function SaveSettings() // return object with settings in
	{
		return JRequest::getVar('plugin_html_output', '', 'post', 'string', JREQUEST_ALLOWRAW);
	}
	
	function Input($current, $params, $context, $id) // output the field for editing
	{
		return $params;
	}
	
	function Save()
	{
		return "";
	}
	
	function Display($value, $params, $context) // output the field for display
	{
		return $params;
	}
		
	function CanEdit()
	{
		return false;	
	}
}