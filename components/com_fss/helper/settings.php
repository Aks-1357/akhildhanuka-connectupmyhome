<?php
/**
* @Copyright Freestyle Joomla (C) 2010
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*     
* This file is part of Freestyle Support Portal
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/
?>
<?php
/*
			<param name="kb_rate" default="1" />
			<param name="kb_comments" default="1" />
			<param name="kb_comments_captcha" default="0" />
			<param name="kb_comments_moderate" default="0" />
			<param name="test_moderate" default="0" />
			<param name="test_moderate_captcha" default="0" />
*/

define("FSS_IT_KB",1);
define("FSS_IT_FAQ",2);
define("FSS_IT_TEST",3);
define("FSS_IT_NEWTICKET",4);
define("FSS_IT_VIEWTICKETS",5);
define("FSS_IT_ANNOUNCE",6);
define("FSS_IT_LINK",7);
define("FSS_IT_GLOSSARY",8);
define("FSS_IT_ADMIN",9);
define("FSS_IT_GROUPS",10);

jimport( 'joomla.version' );
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );

class FSS_Settings 
{
	static $fss_view_settings;
	
	function _GetSettings()
	{
		global $fss_settings;
		
		if (empty($fss_settings))
		{
			FSS_Settings::_GetDefaults();
			
			$db =& JFactory::getDBO();
			$query = 'SELECT * FROM #__fss_settings';
			$db->setQuery($query);
			$row = $db->loadAssocList();
			
			if (count($row) > 0)
			{
				foreach ($row as $data)
				{
					$fss_settings[$data['setting']] = $data['value'];
				}
			}

			$query = 'SELECT * FROM #__fss_settings_big';
			$db->setQuery($query);
			$row = $db->loadAssocList();
			
			if (count($row) > 0)
			{
				foreach ($row as $data)
				{
					$fss_settings[$data['setting']] = $data['value'];
				}
			}
		}	
	}
	
	function _Get_View_Settings()
	{
		if (empty(FSS_Settings::$fss_view_settings))
		{
			FSS_Settings::_View_Defaults();
			
			$db =& JFactory::getDBO();
			$query = 'SELECT * FROM #__fss_settings_view';
			$db->setQuery($query);
			$row = $db->loadAssocList();
			
			if (count($row) > 0)
			{
				foreach ($row as $data)
				{
					FSS_Settings::$fss_view_settings[$data['setting']] = $data['value'];
				}
			}
		}	
	}
	
	function _GetDefaults()
	{
		global $fss_settings;
		
		if (empty($fss_settings))
		{
			$fss_settings = array();
			
			$fss_settings['version'] = 0;
			$fss_settings['fsj_username'] = '';
			$fss_settings['fsj_apikey'] = '';
	
			$fss_settings['perm_mod_joomla'] = 0;
			$fss_settings['perm_article_joomla'] = 0;
			
			$fss_settings['captcha_type'] = 'none';

			$fss_settings['recaptcha_public'] = '';
			$fss_settings['recaptcha_private'] = '';
			$fss_settings['recaptcha_theme'] = 'red';
			$fss_settings['comments_moderate'] = 'none';
			$fss_settings['comments_hide_add'] = 1;
			$fss_settings['email_on_comment'] = '';
			$fss_settings['comments_who_can_add'] = 'anyone';
			
			$fss_settings['test_use_email'] = 1;
			$fss_settings['test_use_website'] = 1;
			$fss_settings['commnents_use_email'] = 1;
			$fss_settings['commnents_use_website'] = 1;
		
			$fss_settings['hide_powered'] = 0;
			$fss_settings['announce_use_content_plugins'] = 0;
			$fss_settings['announce_use_content_plugins_list'] = 0;
			$fss_settings['announce_comments_allow'] = 1;
			$fss_settings['announce_comments_per_page'] = 0;
			$fss_settings['announce_per_page'] = 10;
			
			$fss_settings['kb_rate'] = 1;
			$fss_settings['kb_comments'] = 1;
			$fss_settings['kb_view_top'] = 0;
			
			$fss_settings['kb_show_views'] = 1;
			$fss_settings['kb_show_recent'] = 1;
			$fss_settings['kb_show_recent_stats'] = 1;
			$fss_settings['kb_show_viewed'] = 1;
			$fss_settings['kb_show_viewed_stats'] = 1;
			$fss_settings['kb_show_rated'] = 1;
			$fss_settings['kb_show_rated_stats'] = 1;
			$fss_settings['kb_show_dates'] = 1;
			$fss_settings['kb_use_content_plugins'] = 0;
			$fss_settings['kb_show_art_related'] = 1;
			$fss_settings['kb_show_art_products'] = 1;
			$fss_settings['kb_show_art_attach'] = 1;
			$fss_settings['kb_contents'] = 1;
			$fss_settings['kb_smaller_subcat_images'] = 0;
			$fss_settings['kb_comments_per_page'] = 0;
			$fss_settings['kb_prod_per_page'] = 5;
			$fss_settings['kb_art_per_page'] = 10;
			
			
			$fss_settings['test_moderate'] = 'none';
			$fss_settings['test_email_on_submit'] = '';
			$fss_settings['test_allow_no_product'] = 1;
			$fss_settings['test_who_can_add'] = 'anyone';
			$fss_settings['test_hide_empty_prod'] = 1;
			$fss_settings['test_comments_per_page'] = 0;

			$fss_settings['skin_style'] = 0;
			$fss_settings['support_entire_row'] = 0;
			$fss_settings['support_autoassign'] = 0;
			$fss_settings['support_assign_open'] = 0;
			$fss_settings['support_assign_reply'] = 0;
			$fss_settings['support_user_attach'] = 1;
			$fss_settings['support_lock_time'] = 30;
			$fss_settings['support_show_msg_counts'] = 1;
			$fss_settings['support_reference'] = "4L-4L-4L";
			$fss_settings['support_list_template'] = "classic";
			$fss_settings['support_custom_register'] = "";
			$fss_settings['support_no_logon'] = 0;
			$fss_settings['support_no_register'] = 0;
			$fss_settings['support_info_cols'] = 1;
			$fss_settings['support_actions_as_buttons'] = 0;
			$fss_settings['support_choose_handler'] = 'none';
			$fss_settings['support_dont_check_dupe'] = 1;
			
			$fss_settings['support_user_reply_width'] = 56;
			$fss_settings['support_user_reply_height'] = 10;
			$fss_settings['support_admin_reply_width'] = 56;
			$fss_settings['support_admin_reply_height'] = 10;
			$fss_settings['support_next_prod_click'] = 1;			
			$fss_settings['support_subject_size'] = 35;			
			$fss_settings['support_subject_message_hide'] = '';			
			$fss_settings['support_filename'] = 0;			
			
			$fss_settings['support_tabs_allopen'] = 0;	
			$fss_settings['support_tabs_allclosed'] = 0;
			$fss_settings['support_tabs_all'] = 0;			
			$fss_settings['ticket_prod_per_page'] = 5;
			$fss_settings['ticket_per_page'] = 10;
			
			$fss_settings['support_restrict_prod'] = 0;
			
			$fss_settings['css_hl'] = '#f0f0f0';
			$fss_settings['css_tb'] = '#ffffff';
			$fss_settings['css_bo'] = '#e0e0e0';
			
			$fss_settings['display_head'] = '';
			$fss_settings['display_foot'] = '';
			$fss_settings['use_joomla_page_title_setting'] = 0;
			
			$fss_settings['content_unpublished_color'] = '#FFF0F0';

			if (FSS_Helper::Is16())
			{
				$fss_settings['display_h1'] = '<h1>$1</h1>';
				$fss_settings['display_h2'] = '<h2>$1</h2>';
				$fss_settings['display_h3'] = '<h3>$1</h3>';
				$fss_settings['display_popup'] = '<h2>$1</h2>';
				$fss_settings['display_style'] = '.fss_main tr, td 
{
	border: none;
	padding: 1px;
}';
				$fss_settings['display_popup_style'] = '.fss_popup tr, td 
{
	border: none;
	padding: 1px;
}';
			} else {
				$fss_settings['display_h1'] = '<div class="component-header"><div class="componentheading">$1</div></div>';
				$fss_settings['display_h2'] = '<div class="fss_spacer contentheading">$1</div>';
				$fss_settings['display_h3'] = '<div class="fss_admin_create">$1</div>';
				$fss_settings['display_popup'] = '<div class="component-header"><div class="componentheading">$1</div></div>';
				$fss_settings['display_style'] = '';
				$fss_settings['display_popup_style'] = '';
			}

			$fss_settings['support_email_on_create'] = 0;
			$fss_settings['support_email_handler_on_create'] = 0;
			$fss_settings['support_email_on_reply'] = 0;
			$fss_settings['support_email_handler_on_reply'] = 0;
			$fss_settings['support_email_handler_on_forward'] = 0;
			$fss_settings['support_email_on_close'] = 0;
			$fss_settings['support_user_can_close'] = 1;
			$fss_settings['support_user_can_reopen'] = 1;
			$fss_settings['support_advanced'] = 1;
			$fss_settings['support_allow_unreg'] = 0;
			$fss_settings['support_delete'] = 1;
			$fss_settings['support_advanced_default'] = 0;

			$fss_settings['support_cronlog_keep'] = 5;

			$fss_settings['support_hide_priority'] = 0;
			$fss_settings['support_hide_handler'] = 0;
			$fss_settings['support_hide_users_tickets'] = 0;
			$fss_settings['support_hide_tags'] = 0;
			$fss_settings['support_email_unassigned'] = '';

			$fss_settings['support_email_from_name'] = '';
			$fss_settings['support_email_from_address'] = '';
			$fss_settings['support_email_site_name'] = '';
			
			$fss_settings['support_ea_check'] = 0;
			$fss_settings['support_ea_all'] = 0;
			$fss_settings['support_ea_reply'] = 0;
			$fss_settings['support_ea_type'] = 0;
			$fss_settings['support_ea_host'] = '';
			$fss_settings['support_ea_port'] = '';
			$fss_settings['support_ea_username'] = '';
			$fss_settings['support_ea_password'] = '';
			$fss_settings['support_ea_mailbox'] = '';

			$fss_settings['support_user_message'] = '#c0c0ff';
			$fss_settings['support_admin_message'] = '#c0ffc0';
			$fss_settings['support_private_message'] = '#ffc0c0';
			
			$fss_settings['support_basic_name'] = '';
			$fss_settings['support_basic_username'] = '';
			$fss_settings['support_basic_email'] = '';
			$fss_settings['support_basic_messages'] = '';

			$fss_settings['glossary_faqs'] = 1;
			$fss_settings['glossary_kb'] = 1;
			$fss_settings['glossary_announce'] = 1;
			$fss_settings['glossary_link'] = 1;
			$fss_settings['glossary_title'] = 0;
			$fss_settings['glossary_use_content_plugins'] = 0;
			$fss_settings['glossary_ignore'] = '';

			$fss_settings['faq_popup_width'] = 650;
			$fss_settings['faq_popup_height'] = 375;
			$fss_settings['faq_popup_inner_width'] = 0;
			$fss_settings['faq_use_content_plugins'] = 0;
			$fss_settings['faq_use_content_plugins_list'] = 0;
			$fss_settings['faq_per_page'] = 10;
			
			// 1.9 comments stuff
			$fss_settings['comments_announce_use_custom'] = 0;
			$fss_settings['comments_kb_use_custom'] = 0;
			$fss_settings['comments_test_use_custom'] = 0;	
			$fss_settings['comments_general_use_custom'] = 0;		
			$fss_settings['comments_testmod_use_custom'] = 0;	
				
			$fss_settings['announce_use_custom'] = 0;		
			$fss_settings['announcemod_use_custom'] = 0;		
			$fss_settings['announcesingle_use_custom'] = 0;		
			
			// date format stuff
			$fss_settings['date_dt_short'] = '';
			$fss_settings['date_dt_long'] = '';
			$fss_settings['date_d_short'] = '';
			$fss_settings['date_d_long'] = '';
			$fss_settings['timezone_offset'] = 0;
		}	
	}

	// return a list of settings that are used on the templates section
	function GetTemplateList()
	{
		$template = array();
		$template[] = "display_style";
		$template[] = "display_popup_style";
		$template[] = "display_h1";
		$template[] = "display_h2";
		$template[] = "display_h3";
		$template[] = "display_head";
		$template[] = "display_foot";
		$template[] = "display_popup";
		$template[] = "support_list_template";
		
		$template[] = "comments_announce_use_custom";
		$template[] = "comments_test_use_custom";
		$template[] = "comments_kb_use_custom";
		$template[] = "comments_general_use_custom";
		$template[] = "comments_testmod_use_custom";
		$template[] = "announce_use_custom";
		$template[] = "announcemod_use_custom";
		$template[] = "announcesingle_use_custom";
		
		$res = array();
		foreach($template as $setting)
		{
			$res[$setting] = $setting;
		}
		return $res;	
	}
	
	function StoreInTemplateTable()
	{
		$intpl = array();
		$intpl[] = "comments_general";	
		$intpl[] = "comments_announce";	
		$intpl[] = "comments_kb";	
		$intpl[] = "comments_test";	
		$intpl[] = "comments_testmod";	
		$intpl[] = "announce";	
		$intpl[] = "announcemod";	
		$intpl[] = "announcesingle";	
		
		$res = array();
		foreach($intpl as $setting)
		{
			$res[$setting] = $setting;
		}
		return $res;	
	}
		
	function GetLargeList()
	{
		$large = array();
		$large[] = "display_style";
		$large[] = "display_popup_style";
		$large[] = "display_h1";
		$large[] = "display_h2";
		$large[] = "display_h3";
		$large[] = "display_head";
		$large[] = "display_foot";
		$large[] = "display_popup";
		
		$res = array();
		foreach($large as $setting)
		{
			$res[$setting] = $setting;
		}
		return $res;	
	}
	
	function get($setting)
	{
		global $fss_settings;
		FSS_Settings::_GetSettings();
		return $fss_settings[$setting];	
	}
	
	function &GetAllSettings()
	{
		global $fss_settings;
		FSS_Settings::_GetSettings();
		return $fss_settings;	
	}
	
	function &GetAllViewSettings()
	{
		FSS_Settings::_Get_View_Settings();
		return FSS_Settings::$fss_view_settings;	
	}
	
	function _View_Defaults()
	{
		// FAQS
		
		// When Showing list of Categories
		FSS_Settings::$fss_view_settings['faqs_always_show_faqs'] = 0;
		FSS_Settings::$fss_view_settings['faqs_hide_allfaqs'] = 0;
		FSS_Settings::$fss_view_settings['faqs_hide_tags'] = 0;
		FSS_Settings::$fss_view_settings['faqs_hide_search'] = 0;
		FSS_Settings::$fss_view_settings['faqs_show_featured'] = 0;
		FSS_Settings::$fss_view_settings['faqs_num_cat_colums'] = 1;
		FSS_Settings::$fss_view_settings['faqs_view_mode_cat'] = 'accordian';
		FSS_Settings::$fss_view_settings['faqs_view_mode_incat'] = 'accordian';
		
		// When Showing list of FAQs
		FSS_Settings::$fss_view_settings['faqs_always_show_cats'] = 0;
		FSS_Settings::$fss_view_settings['faqs_view_mode'] = 'accordian';
		FSS_Settings::$fss_view_settings['faqs_enable_pages'] = 1;
		
		// Glossary
		FSS_Settings::$fss_view_settings['glossary_use_letter_bar'] = 0;
		
		// Testimonials
		FSS_Settings::$fss_view_settings['test_test_show_prod_mode'] = 'accordian';
		FSS_Settings::$fss_view_settings['test_test_pages'] = 1;
		FSS_Settings::$fss_view_settings['test_test_always_prod_select'] = 0;
		
		
		// KB
		
		// Main Page
		FSS_Settings::$fss_view_settings['kb_main_show_prod'] = 1;
		FSS_Settings::$fss_view_settings['kb_main_show_cat'] = 0;
		FSS_Settings::$fss_view_settings['kb_main_show_sidebyside'] = 0;
		FSS_Settings::$fss_view_settings['kb_main_show_search'] = 0;
		
		// Main Page - Products List Settings		
		FSS_Settings::$fss_view_settings['kb_main_prod_colums'] = 1;
		FSS_Settings::$fss_view_settings['kb_main_prod_search'] = 1;
		FSS_Settings::$fss_view_settings['kb_main_prod_pages'] = 0;
		
		// Main Page - Category List Settings
		FSS_Settings::$fss_view_settings['kb_main_cat_mode'] = 'normal';
		FSS_Settings::$fss_view_settings['kb_main_cat_arts'] = 'normal';
		FSS_Settings::$fss_view_settings['kb_main_cat_colums'] = 1;
		
		// When Product Selected
		FSS_Settings::$fss_view_settings['kb_prod_cat_mode'] = 'accordian';
		FSS_Settings::$fss_view_settings['kb_prod_cat_arts'] = 'normal';
		FSS_Settings::$fss_view_settings['kb_prod_cat_colums'] = 1;
		FSS_Settings::$fss_view_settings['kb_prod_search'] = 1;
		
		// When Product and Category Selected
		FSS_Settings::$fss_view_settings['kb_cat_cat_mode'] = 'accordian';
		FSS_Settings::$fss_view_settings['kb_cat_cat_arts'] = 'normal';
		FSS_Settings::$fss_view_settings['kb_cat_art_pages'] = 0;
		FSS_Settings::$fss_view_settings['kb_cat_search'] = 0;		
	}
	
	function GetViewSettingsObj($view)
	{
		// return a view setting object that can be used in place of the getPageParameters object
		// needs info about what view we are in, and access to the view settings
		FSS_Settings::_Get_View_Settings();
			
		return new FSS_View_Settings($view, FSS_Settings::$fss_view_settings);
	}
}

class FSS_View_Settings
{
	var $view;
	var $settings;
	var $mainframe;
	
	function __construct($view, $settings)
	{
		$this->view = $view;
		$this->settings = $settings;
		
		$this->mainframe = JFactory::getApplication();
		$this->params = $this->mainframe->getPageParameters('com_fss');
		
		//print_p($this->settings);
		//print_p($this->params);
	}
	
	function get($var, $default)
	{
		$key = $this->view . "_" . $var;
		
		//echo "Get : $key (Def: $default) = ";

		$value = $this->params->get($var,"XXXXXXXX");
		if ($value != "XXXXXXXX")
		{
			if (!array_key_exists($key, $this->settings))
			{
				//echo $value . " (missing)<br>";
				return $value;
			}
		
			if ($value != -1)
			{
				//echo $value . " (set)<br>";
				return $value;
			}
		}
		
		//echo $this->settings[$key] . " (global)<br>";
		return $this->settings[$key];
	}
}

function FSS_GetAllMenus()
{
	static $getmenus;
	
	if (empty($getmenus))
	{
		$where = array();
		$where[] = 'menutype != "main"';
		$where[] = 'type = "component"';
		$where[] = 'link LIKE "%option=com_fss%"';
		$where[] = 'published = 1';
		
		if (FSS_Helper::Is16())
		{
			$query = 'SELECT title, id, link FROM #__menu';
		} else {
			$query = 'SELECT name as title, id, link FROM #__menu';
		}
		$query .= ' WHERE ' . implode(" AND ", $where);
		
		$db    = & JFactory::getDBO();
		$db->setQuery($query);
		$getmenus = $db->loadObjectList();
	}
	//print_p($getmenus);
	
	return $getmenus;
}

function FSS_GetMenus($menutype)
{
	$getmenus = FSS_GetAllMenus();
	
	//echo "<br>Menu Type : $menutype<br>-<br>";
	$have = array();
	$not = array();
	
	switch ($menutype)
	{
	case FSS_IT_KB:
		$have['view'] = "kb";
		$not['layout'] = "";
		break;						
	case FSS_IT_FAQ:
		$have['view'] = "faq";
		$not['layout'] = "";
		break;						
	case FSS_IT_TEST:
		$have['view'] = "test";
		$not['layout'] = "";
		break;						
	case FSS_IT_NEWTICKET:
		$have['view'] = "ticket";
		$have['layout'] = "open";
		break;
	case FSS_IT_VIEWTICKETS:
		$have['view'] = "ticket";
		$not['layout'] = "";
		break;						
	case FSS_IT_ANNOUNCE:
		$have['view'] = "announce";
		$not['layout'] = "";
		break;						
	case FSS_IT_GLOSSARY:
		$have['view'] = "glossary";
		$not['layout'] = "";
		break;						
	case FSS_IT_ADMIN:
		$have['view'] = "admin";
		$not['layout'] = "";
		break;						
	default:
		return array();							
	}
	
	$results = array();
	
	if (count($getmenus) > 0)
	{
		foreach ($getmenus as $object)
		{ 
			$linkok = 1;
		
			$link = strtolower(substr($object->link,strpos($object->link,"?")+1));
			//echo $link."<br>";
			$parts = explode("&",$link);
		
			$inlink = array();
		
			foreach($parts as $part)
			{
				list($key,$value) = explode("=",$part);
				$inlink[$key] = $value;
			
				if (array_key_exists($key,$not))
				{
					//echo "Has ".$key."<br>";
					$linkok = 0;
				}
			}
				
			foreach ($have as $key => $value)
			{		
				if (!array_key_exists($key,$inlink))
				{
					//echo "Doesnt have ".$key."<br>";
					$linkok = 0;	
				} else {
					if ($inlink[$key] != $value)
					{
						//echo "Value mismatch for ".$key." - " . $value . " should be " . $inlink[$key] . "<br>";
						$linkok = 0;
					}
				}				
			}
		
			if ($linkok)
			{
				$results[] = $object;
				//echo "VALID : " . $link . "<br>";	
			}	
		}
	}
	
	return $results;
}
