<?php

// No direct access.
defined('_JEXEC') or die;
if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php'))
{
	
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );
	require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'glossary.php');
	require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'settings.php');
	require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php');

	class plgContentfss_glossary extends JPlugin
	{
		public function onContentPrepare($context, &$row, &$params, $page = 0)
		{
			if (is_object($row))
			{
				if (property_exists($row, "id"))
					$context .= "." . $row->id;	
			} else if (is_array($row))
			{
				if (array_key_exists("id", $row))
					$context .= "." . $row['id'];
			}
			
			$ignore = FSS_Settings::Get('glossary_ignore');
			if (trim($ignore) != "")
			{
				$ignore = explode("\n", $ignore);
			
				foreach ($ignore as $ign)
				{
					$ign = trim($ign);
					if ($ign == "") continue;
					
					if (stripos(" ".$context, $ign) > 0)
					{
						return true;	
					}
				}				
			}

			// skip plugin on freestyle components
			if (strpos($context, "_fss") > 0)
			{
				return true;
			}

			// Don't run this plugin when the content is being indexed
			if (strpos($context, 'finder.indexer') > 0) {
				return true;
			}

			if (is_object($row)) {
				if (!empty($row->noglossary)) // skip glossary plugin on fss content
					return true;
				
				$row->text .= "\n\n\n<div style='display:none;' id='fss_glossary_context'>$context</div>\n\n\n";
				
				return $this->_glossary($row->text, $params);
				
			} else if (is_array($row)) {	
				$row['text'] .= "\n\n\n<div style='display:none;' id='fss_glossary_context'>$context</div>\n\n\n";
				return $this->_glossary($row['text'], $params);
			}
			
			$row .= "<div style='/*display:none;*/' id='fss_glossary_context'>$context</div>";
			return $this->_glossary($row, $params);
		}

		protected function _glossary(&$text, &$params)
		{
			$text = FSS_Glossary::ReplaceGlossary($text);
			$text .= FSS_Glossary::Footer();
			
			$document =& JFactory::getDocument();
			$css = FSSRoute::x( "index.php?option=com_fss&view=css&layout=default" );
			$document->addStyleSheet($css); 
			$document->addScript( JURI::root().'components/com_fss/assets/js/jquery.1.8.3.min.js' );
			$document->addScript( JURI::base().'components/com_fss/assets/js/main.js' );

			return true;
		}
	}


}