<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php'))
{
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );

	if (!FSS_Helper::Is16())
	{
		$mainframe->registerEvent( 'onSearch', 'plgSearchFSSFAQs' );
		$mainframe->registerEvent( 'onSearchAreas', 'plgSearchFSSFAQsAreas' );
		
		JPlugin::loadLanguage( 'plg_search_FAQs' );
	} else {
		jimport('joomla.plugin.plugin');	
	}

	function &plgSearchFSSFAQsAreas()
	{
		static $areas = array(
			'FAQs' => 'FAQs'
			);
		return $areas;
	}
	
	function plgSearchFSSFAQs( $text, $phrase='', $ordering='', $areas=null )
	{
		$db            =& JFactory::getDBO();
		$user  =& JFactory::getUser(); 
		
		if (is_array( $areas )) {
			if (!array_intersect( $areas, array_keys( plgSearchFSSFAQsAreas() ) )) {
				return array();
			}
		}
		
		$plugin =& JPluginHelper::getPlugin('search', 'fss_faqs');
		if ($plugin)
		{
			$pluginParams = new JParameter( $plugin->params );
		} else {
			$pluginParams = new JParameter( null );
		}
		$limit 			= $pluginParams->def( 'search_limit', 		50 );
		
		$text = trim( $text );

		if ($text == '') {
			return array();
		}

		$wheres = array();
		switch ($phrase) {
			case 'exact':
				$text          = $db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $text, true ).'%', false );
				$wheres2       = array();
				$wheres2[]   = 'LOWER(a.question) LIKE '.$text;
				$wheres2[]   = 'LOWER(a.answer) LIKE '.$text;
				$where                 = '(' . implode( ') OR (', $wheres2 ) . ')';
				break;
			
			case 'all':
			case 'any':
			default:
				$words         = explode( ' ', $text );
				$wheres = array();
				foreach ($words as $word)
				{
					$word          = $db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $word, true ).'%', false );
					$wheres2       = array();
					$wheres2[]   = 'LOWER(a.question) LIKE '.$word;
					$wheres2[]   = 'LOWER(a.answer) LIKE '.$word;
					$wheres[]    = implode( ' OR ', $wheres2 );
				}
				$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
				break;
		}
		
		switch ( $ordering ) {
			case 'alpha':
				$order = 'a.question ASC';
				break;
			
			case 'oldest':
			case 'popular':
			case 'newest':
			default:
				$order = 'a.question ASC';
		}
		
		$searchFAQs = JText::_("FAQS");
		
		$query = 'SELECT a.question AS title,'
			. ' a.answer as text, c.title as section, '
			. ' a.faq_cat_id, a.id, 0 as created, 2 as browsernav'
			. ' FROM #__fss_faq_faq AS a'
			. ' LEFT JOIN #__fss_faq_cat as c ON a.faq_cat_id = c.id';
		
		$ow = "( ". $where . ")";
		$where = array();
		$where[] = $ow;
		$where[] = "a.published = 1";
		$where[] = "c.published = 1";
		if (FSS_Helper::Is16())
		{
			$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	

		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

		$query .= ' ORDER BY '. $order;
		
		$db->setQuery( $query, 0, $limit );
		$rows = $db->loadObjectList();
		
		foreach($rows as $key => $row) {
			$rows[$key]->href = 'index.php?option=com_fss&view=faq&catid='.$row->faq_cat_id.'&faqid='.$row->id;
		}
		
		return $rows;
	}

	class plgSearchFSS_Faqs extends JPlugin
	{
		/**
			* Constructor
			*
			* @access      protected
			* @param       object  $subject The object to observe
			* @param       array   $config  An array that holds the plugin configuration
			* @since       1.5
			*/
		public function __construct(& $subject, $config)
		{
			parent::__construct($subject, $config);
			$this->loadLanguage();
		}

		/**
			* @return array An array of search areas
			*/
		function onContentSearchAreas() {
			static $areas = array(
				'FAQs' => 'FAQs'
				);
			return $areas;
		}

		/**
			* Weblink Search method
			*
			* The sql must return the following fields that are used in a common display
			* routine: href, title, section, created, text, browsernav
			* @param string Target search string
			* @param string mathcing option, exact|any|all
			* @param string ordering option, newest|oldest|popular|alpha|category
			* @param mixed An array if the search it to be restricted to areas, null if search all
			*/
		function onContentSearch($text, $phrase='', $ordering='', $areas=null)
		{
			$db            =& JFactory::getDBO();
			$user  =& JFactory::getUser(); 
			
			if (is_array( $areas )) {
				if (!array_intersect( $areas, array_keys( plgSearchFSSFAQsAreas() ) )) {
					return array();
				}
			}
			
			$limit			= $this->params->def('search_limit',		50);

			$text = trim( $text );

			if ($text == '') {
				return array();
			}

			$wheres = array();
			switch ($phrase) {
				case 'exact':
					$text          = $db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $text, true ).'%', false );
					$wheres2       = array();
					$wheres2[]   = 'LOWER(a.question) LIKE '.$text;
					$wheres2[]   = 'LOWER(a.answer) LIKE '.$text;
					$where                 = '(' . implode( ') OR (', $wheres2 ) . ')';
					break;
				
				case 'all':
				case 'any':
				default:
					$words         = explode( ' ', $text );
					$wheres = array();
					foreach ($words as $word)
					{
						$word          = $db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $word, true ).'%', false );
						$wheres2       = array();
						$wheres2[]   = 'LOWER(a.question) LIKE '.$word;
						$wheres2[]   = 'LOWER(a.answer) LIKE '.$word;
						$wheres[]    = implode( ' OR ', $wheres2 );
					}
					$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
					break;
			}
			
			switch ( $ordering ) {
				case 'alpha':
					$order = 'a.question ASC';
					break;
				
				case 'oldest':
				case 'popular':
				case 'newest':
				default:
					$order = 'a.question ASC';
			}
			
			$searchFAQs = JText::_("FAQS");
			
			$query = 'SELECT a.question AS title,'
				. ' a.answer as text, c.title as section, '
				. ' a.faq_cat_id, a.id, 0 as created, 2 as browsernav'
				. ' FROM #__fss_faq_faq AS a'
				. ' LEFT JOIN #__fss_faq_cat as c ON a.faq_cat_id = c.id';
			
			
			$ow = "( ". $where . ")";
			$where = array();
			$where[] = $ow;
			$where[] = "a.published = 1";
			$where[] = "c.published = 1";
			if (FSS_Helper::Is16())
			{
				$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
				$user = JFactory::getUser();
				$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
			}	

			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);
			
			$query .= ' ORDER BY '. $order;

			
			$db->setQuery( $query, 0, $limit );
			$rows = $db->loadObjectList();
			
			foreach($rows as $key => $row) {
				$rows[$key]->href = 'index.php?option=com_fss&view=faq&catid='.$row->faq_cat_id.'&faqid='.$row->id;
			}
			
			return $rows;
		}
	}

}