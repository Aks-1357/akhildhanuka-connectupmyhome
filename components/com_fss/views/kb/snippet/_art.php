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
<?php ('_JEXEC') or die('Restricted access'); ?>
<?php $what = JRequest::GetVar('what', ''); ?>
<?php $unpubclass = ""; if ($art['published'] == 0) $unpubclass = "content_edit_unpublished"; ?>

	<?php if ($this->view_mode != 'popup'): ?>	
	<div class='fss_faq'>
		<div class="fss_faq_question <?php echo $unpubclass; ?>">
			<?php echo $this->content->EditPanel($art); ?>
			<a class='fss_highlight' href='<?php echo FSSRoute::x( '&what=&kbartid=' . $art['id'] ); ?>'>
				<?php if ($what == "recent" && FSS_Settings::get( 'kb_show_recent_stats' ) && $art['modified'] != "0000-00-00 00:00:00"):?>
					<span class="fss_kb_art_extra">
						<?php echo FSS_Helper::Date($art['modified'], FSS_DATE_SHORT); ?>
					</span>
				<?php endif; ?>
				<?php if ($what == "viewed" && FSS_Settings::get( 'kb_show_viewed_stats' )):?>
					<span class="fss_kb_art_extra"><?php echo $art['views']; ?> <?php echo JText::_("VIEW_S") ?></span>
				<?php endif; ?>
				<?php if ($what == "rated" && FSS_Settings::get( 'kb_show_rated_stats' )):?>
					<span class="fss_kb_art_extra_img"><?php echo $art['rating']; ?> <img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/highestrated_small.png" width="16" height="16" class="fss_kb_art_extra_image"></span>
				<?php endif; ?>
			    <?php echo $art['title']; ?>
			</a>
		</div>
	</div>
	<?php elseif ($this->view_mode == 'popup'): ?>	
	<div class='fss_faq'>
		<div class="fss_faq_question <?php echo $unpubclass; ?>">
			<?php echo $this->content->EditPanel($art); ?>
			<a class="modal fss_highlight" href='<?php echo FSSRoute::x( '&what=&tmpl=component&kbartid=' . $art['id'] ); ?>' rel="{handler: 'iframe', size: {x: 650, y: 375}}">
			    <?php echo $art['title']; ?>
			</a>
		</div>		
	</div>	
	<?php endif; ?>

