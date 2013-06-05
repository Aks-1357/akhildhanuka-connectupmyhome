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
	setTimeout("faqsmod_scrollDown()",3000);
});

function faqsmod_scrollDown()
{
	var settings = { 
		direction: "down", 
		step: 40, 
		scroll: true, 
		onEdge: function (edge) { 
			if (edge.y == "bottom")
			{
				setTimeout("faqsmod_scrollUp()",3000);
			}
		} 
	};
	jQuery(".fss_mod_faqs_scroll").autoscroll(settings);
}

function faqsmod_scrollUp()
{
	var settings = { 
		direction: "up", 
		step: 40, 
		scroll: true,    
		onEdge: function (edge) { 
			if (edge.y == "top")
			{
				setTimeout("faqsmod_scrollDown()",3000);
			}
		} 
	};
	jQuery(".fss_mod_faqs_scroll").autoscroll(settings);
}
</script>

<style>
.fss_mod_faqs_scroll {
	max-height: <?php echo $maxheight; ?>px;
	overflow: hidden;
}
</style>


<?php endif; ?>

<div id="fss_mod_faqs_scroll" class="fss_mod_faqs_scroll">

<?php foreach ($data as $row) :?>
	<div class='fss_mod_faqs_cont'>
	<div class='fss_mod_faqs_title'>
	<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=faq&faqid=' . $row->id); ?>'>
		<?php echo $row->question; ?>
	</a>
</div>
</div>
<?php endforeach;?>

</div>
