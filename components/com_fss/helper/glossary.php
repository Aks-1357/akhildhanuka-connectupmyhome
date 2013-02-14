<?php

class FSS_Glossary
{
	function GetGlossary()
	{
		global $fss_glossary;
	
		if (empty($fss_glossary))
		{
			$db =& JFactory::getDBO();
			$query = 'SELECT * FROM #__fss_glossary';
			
			$where = array();
			$where[] = " published = 1 ";
			if (FSS_Helper::Is16())
			{
				$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
				$user = JFactory::getUser();
				$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
			}	
			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);

			$query .= ' ORDER BY LENGTH(word) DESC';
			
			//echo $query. "<br>";
			$db->setQuery($query);
			$rows = $db->loadAssocList();
			
			$fss_glossary = array();
			
			foreach ($rows as $data)
			{
				$fss_glossary[$data['word']] = $data['description'];
			}
		}
	}

	function ReplaceGlossary($text)
	{
		global $fss_glossary;
		global $fss_glossary_offset;
		if (empty($fss_glossary_offset)) $fss_glossary_offset = 0;
		FSS_Glossary::GetGlossary();
		
		if (count($fss_glossary) == 0)
			return $text;
				
		$replacewords = array();
		
		foreach($fss_glossary as $word => $tip)
		{
			$link = "#";
			if (FSS_Settings::get('glossary_link'))
				$link = FSSRoute::_( 'index.php?option=com_fss&view=glossary&letter='.strtolower(substr($word,0,1)).'#' . $word );
			$title = '';
			if (FSS_Settings::get('glossary_title'))
				$title = '$1';
			
			$key = "XXX_".$fss_glossary_offset."_XXX";
			$fss_glossary_offset++;
			$replacewords[$key] = array();
			// need to dissalow anything before or after this is a-z, 0-9, - _
			
			$rword = str_replace("/","\/",$word);
			preg_match_all("/(?!(?:[^<]+>))\b($rword)\b/uis",$text,$matches);
			//print_r($matches);
			if (count($matches) > 0)
			{
				foreach($matches[0] as $origword)
					$replacewords[$key][] = $origword;
				$replace = "<a href='$link' class='fsj_tip fss_glossary_word'>$key</a>";
				$text = preg_replace("/(?!(?:[^<]+>))\b($rword)\b/uis","$replace",$text);
			}
		}
		
		foreach ($replacewords as $key => $words)
		{
			foreach ($words as $word)
			{
				$text = FSS_Glossary::str_replace_first($key, $word, $text);
			}	
		}
		return $text;
	}
	
	function str_replace_first($search, $replace, $subject) {
		$pos = strpos($subject, $search);
		if ($pos !== false) {
			
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}
		return $subject;
	}
	
	function Footer()
	{
		global $fss_glossary;
		FSS_Glossary::GetGlossary();
		
		if (count($fss_glossary) == 0)
			return "";
	
		$tail = "<div id='glossary_words' style='display:none;'>";
		
		foreach($fss_glossary as $word => $tip)
		{
			$wordl = strtolower($word);
			$wordl = preg_replace("/[^a-z0-9]/", "", $wordl);
			if (FSS_Settings::get('glossary_title'))
			{
				$tail .= "<div id='glossary_$wordl'><h4>$word</h4><div class='fsj_gt_inner'>$tip</div></div>";
			} else {
				$tail .= "<div id='glossary_$wordl'><div class='fsj_gt_inner'>$tip</div></div>";
			}
		}
		
		$tail .= "</div>";
		
		return $tail;
	}
}
