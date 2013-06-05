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

<?php if ($maxheight > 0): ?>
<script>

jQuery(document).ready(function () {
	setTimeout("scrollDown()",3000);
});

function scrollDown()
{
	var settings = { 
		direction: "down", 
		step: 40, 
		scroll: true, 
		onEdge: function (edge) { 
			if (edge.y == "bottom")
			{
				setTimeout("scrollUp()",3000);
			}
		} 
	};
	jQuery(".fss_comments_scroll").autoscroll(settings);
}

function scrollUp()
{
	var settings = { 
		direction: "up", 
		step: 40, 
		scroll: true,    
		onEdge: function (edge) { 
			if (edge.y == "top")
			{
				setTimeout("scrollDown()",3000);
			}
		} 
	};
	jQuery(".fss_comments_scroll").autoscroll(settings);
}
</script>

<style>
#fss_comments_scroll {
	max-height: <?php echo $maxheight; ?>px;
	overflow: hidden;
}


</style>
<?php endif; ?>
<?php if (1/*count($rows) > 0*/) : ?>
<div id="fss_comments_scroll" class="testi_wrap" style="width:90%">
<div class="title">Testimonials</div>
<?php $comments->DisplayComments($dispcount, $listtype, $maxlength); ?>
</div>
<?php if ($params->get('show_more')) : ?>
	<?php if ($params->get('morelink')): ?>
		<div class='seeall'><a href='<?php echo JRoute::_( $params->get('morelink') ); ?>'>See all ...</a></div>
	<?php elseif ($prodid == -1): ?>
		<div class='seeall'><a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=test' ); ?>'>See all ...</a></div>
	<?php else : ?>
		<div class='seeall'><a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=test&prodid=' . $prodid ); ?>'>See all ...</a></div>
	<?php endif; ?>
<?php endif; ?>
<?php else: ?>
No testimonials found!.
<?php endif; ?>
<?php if (false): ?>
	<?php if ($params->get('addlink')) :?>
		<div class='fss_mod_test_add'><a href='<?php echo JRoute::_( $params->get('addlink') ); ?>'><?php echo JText::_("ADD_A_TESTIMONIAL"); ?></a></div>
	<?php else: ?>
		<div class='fss_mod_test_add'><a class='modal' href='<?php echo FSSRoute::_( 'index.php?tmpl=component&option=com_fss&view=test&layout=create&onlyprodid=' . $prodid ); ?>' rel="{handler: 'iframe', size: {x: 500, y: 500}}"><?php echo JText::_("ADD_A_TESTIMONIAL"); ?></a></div>
	<?php endif; ?>
<?php endif; ?>
