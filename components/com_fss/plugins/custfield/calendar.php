<?php

class CalendarPlugin extends FSSCustFieldPlugin
{
	var $name = "Date Popup";
	
	function DisplaySettings($params) // passed object with settings in
	{
		$values = unserialize($params);
		if (!is_array($values))
		{
			$values = array();	
			$values['format'] = '';
			$values['use_time'] = 0;
			$values['today_default'] = 0;
		}
		
		$output = "Use Time: <input type='checkbox' name='cal_use_time' value='1' ";
		if ($values['use_time'] == 1)
			$output .= " checked";
		$output .= "><br/>";
		$output .= "Today as default: <input type='checkbox' name='cal_today_default' value='1' ";
		if ($values['today_default'] == 1)
			$output .= " checked";
		$output .= "><br/>";
		$output .= "Date Format: <input name='cal_format' value='{$values['format']}'><br/>";
		$output .= "Leave blank for default format.<br>
					%d - day as number ( with leading zero )<br>
					%j - day as number<br>
					%D - abbreviated name of the day<br>
					%l - full name of the day<br>
					<br>
					%m - month as number ( with leading zero )<br>
					%n - month as number<br>
					%M - abbreviated name of the month<br>
					%F - full name of the month<br>
					<br>
					%y - year as number (2 digits)<br>
					%Y - year as number (4 digits)<br>
					<br>
					%h - hours (12)<br>
					%H - hours (24)<br>
					%i - minutes<br>
					%s - seconds<br>
					%a - am or pm<br>
					%A - AM or PM<br>";

		
		return $output;
	}
	
	function SaveSettings() // return object with settings in
	{
		$values = array();	
		$values['format'] = JRequest::getVar('cal_format');
		$values['use_time'] = JRequest::getVar('cal_use_time');
		$values['today_default'] = JRequest::getVar('cal_today_default');
		
		return serialize($values);
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		$params = unserialize($params);
		
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'components/com_fss/assets/css/calendar.css'); 
		$document->addStyleSheet(JURI::root().'components/com_fss/assets/css/calendar_omega.css'); 
		$document->addScript(JURI::root().'components/com_fss/assets/js/calendar.js'); 
		
		//echo "Current Value : $current<br>";
			
		$display = $current;
				
		if ($params['today_default'] && $current == "")
		{
			if ($params['use_time'])
			{
				$current = date("Y-m-d H:i:s");	
			} else {
				$current = date("Y-m-d");	
			}
			
			// need to convert the date into cal format specified
			if ($params['format'])
			{
				$display = date($this->DXtoPhpFormat($params['format']),strtotime($current));
			} else {
				$display = $current;	
			}
		} else if ($current != "")
		{
			$display = date($this->DXtoPhpFormat($params['format']),strtotime($current));
		}
		
		$output = "<input name='custom_$id' id='custom_$id' value='$display' readonly='readonly'>";
		$output .= "<input type='hidden' name='custom_{$id}_raw' id='custom_{$id}_raw' value='$current'>";
		$output .= "<script>";
		$output .= "
		jQuery(document).ready(function () {
			myCalendar = new dhtmlXCalendarObject('custom_$id','omega');
			myCalendar.attachEvent('onClick',function(date){
				\n";
				
		if ($params['use_time'])
		{
			$output .= " var raw = this.getFormatedDate('%Y-%m-%d %H:%i:%s');\n";
		} else {
			$output .= " var raw = this.getFormatedDate('%Y-%m-%d');\n";
		}
		$output .= "
				jQuery('#custom_{$id}_raw').val(raw);
			})
			";
			
		if ($params['format'])
		{
			$output .= "myCalendar.setDateFormat('{$params['format']}');\n";
		}
		
		if (!$params['use_time'])
		{
			$output .= "myCalendar.hideTime();\n";
		}
		$output .= "});";
		$output .= "</script>";
		return $output;
	}
	
	function DXtoPhpFormat($format)
	{
		return str_replace("%","",$format);	
	}
	
	function Display($value, $params, $context, $id) // output the field for display
	{
		$params = unserialize($params);
		
		if ($params['format'])
		{
			$time = strtotime($value);
			$value = date($this->DXtoPhpFormat($params['format']),$time);
		}
		
		return $value;
	}
	
	function Save($id, $params)
	{
		$value = JRequest::getVar("custom_{$id}_raw");
	
		return $value;
	}

	function CanEdit()
	{
		return true;	
	}
}