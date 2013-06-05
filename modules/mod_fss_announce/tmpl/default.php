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
<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if ($maxheight > 0): ?>
<script>

jQuery(document).ready(function () {
	setTimeout("announce_scrollDown()",3000);
});

function announce_scrollDown()
{
	var settings = { 
		direction: "down", 
		step: 40, 
		scroll: true, 
		onEdge: function (edge) { 
			if (edge.y == "bottom")
			{
				setTimeout("announce_scrollUp()",3000);
			}
		} 
	};
	jQuery(".fss_announce_scroll").autoscroll(settings);
}

function announce_scrollUp()
{
	var settings = { 
		direction: "up", 
		step: 40, 
		scroll: true,    
		onEdge: function (edge) { 
			if (edge.y == "top")
			{
				setTimeout("announce_scrollDown()",3000);
			}
		} 
	};
	jQuery(".fss_announce_scroll").autoscroll(settings);
}
</script>

<style>
#fss_announce_scroll {
	max-height: <?php echo $maxheight; ?>px;
	overflow: hidden;
}
</style>
<?php endif; ?>

<div id="fss_announce_scroll" class="fss_announce_scroll">

<?php 
foreach($rows as $announce)
{
	$parser->SetVar('title', $announce['title']);
	$parser->SetVar('subtitle', $announce['subtitle']);
	$parser->SetVar('date', FSS_Helper::Date($announce['added'], FSS_DATETIME_MID));
	$parser->SetVar('body', $announce['body']);
	$parser->SetVar('link', FSSRoute::_( 'index.php?option=com_fss&view=announce&announceid=' . $announce['id'] ));
	
	$authid = $announce['author'];
	$user = JFactory::getUser($authid);
	if ($user->id > 0)
	{
		$parser->setVar('author', $user->name);	
		$parser->setVar('author_username', $user->username);	
	} else {
		$parser->setVar('author', JText::_('UNKNOWN'));	
		$parser->setVar('author_username', JText::_('UNKNOWN'));	
	}

	echo $parser->Parse();
}
?>

</div>

<?php if ($params->get('show_more')) : ?>
<div class='fss_mod_announce_all'><a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=announce&announceid=' ); ?>'><?php echo JText::_("SHOW_ALL_ANNOUNCEMENTS"); ?></a></div>
<?php endif; ?>
