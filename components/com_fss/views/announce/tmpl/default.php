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
<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("ANNOUNCEMENTS"); ?>

<?php if (count($this->announces) > 0) : ?>
<form method="post" action="<?php echo FSSRoute::x(''); ?>" name="form">

<?php 
	
foreach($this->announces as $announce)
{
	$unpubclass = ""; 
	if ($announce['published'] == 0) 
		$unpubclass = "content_edit_unpublished";

	$this->parser->SetVar('class', $unpubclass);
	$this->parser->SetVar('editpanel', $this->content->EditPanel($announce));
	$this->parser->SetVar('date', FSS_Helper::Date($announce['added'],FSS_DATE_MID));
	$this->parser->SetVar('link', FSSRoute::x( '&announceid=' . $announce['id'] ));
	$this->parser->setVar('title', FSS_Helper::PageSubTitle($announce['title']));
	$this->parser->setVar('subtitle', $announce['subtitle']);

	$authid = $announce['author'];
	$user = JFactory::getUser($authid);
	if ($user->id > 0)
	{
		$this->parser->setVar('author', $user->name);	
		$this->parser->setVar('author_username', $user->username);	
	} else {
		$this->parser->setVar('author', JText::_('UNKNOWN'));	
		$this->parser->setVar('author_username', JText::_('UNKNOWN'));	
	}
	
	if (FSS_Settings::get( 'glossary_announce' )) {
		$this->parser->setVar('body', FSS_Glossary::ReplaceGlossary($announce['body'])); 
	} else {
		$this->parser->setVar('body', $announce['body']); 
	}
	
	$this->parser->SetVar('needsreadmore', $announce['fulltext'] || FSS_Settings::get('announce_comments_allow') ? '1' : '');
	
	if (FSS_Settings::get( 'glossary_announce' )) {
		$this->parser->setVar('fulltext', FSS_Glossary::ReplaceGlossary($announce['fulltext'])); 
	} else {
		$this->parser->setVar('fulltext', $announce['fulltext']); 
	}

	if ($announce['fulltext']) {
		$this->parser->SetVar('readmore', JText::_("READ_MORE"));
	} else {
		$this->parser->SetVar('readmore', JText::_("COMMENTS"));
	}

	if (FSS_Settings::get('announce_comments_allow') && $this->comments->GetCount($announce['id']) > 0)
	{
		$this->parser->SetVar('commentcount', $this->comments->GetCount($announce['id']) . "<img class='fss_comment_count_img fsj_tip' src='" . JURI::root( true ) . "/components/com_fss/assets/images/comments.png' title='". $this->comments->GetCount($announce['id'])." Comments'>");
	} else {
		$this->parser->SetVar('commentcount', "");
	}

	echo $this->parser->Parse();
} 

?>

<div class='fss_pagewrapper'>
<?php echo $this->pagination->getListFooter(); ?>
</div>
</form>
<?php else: ?>
<?php echo FSS_Helper::PageSubTitle("THERE_ARE_CURRENTLY_NO_ANNOUNCEMENTS"); ?>
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php if (FSS_Settings::get( 'glossary_announce' )) echo FSS_Glossary::Footer(); ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>


<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js';
//include 'components/com_fss/assets/js/content_edit.js'; ?>
</script>
