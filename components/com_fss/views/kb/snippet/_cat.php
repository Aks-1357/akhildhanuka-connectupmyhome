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

if (empty($depth)) $depth = 1;if (empty($catdepth)) $catdepth = 1;
?>
<?php ('_JEXEC') or die('Restricted access'); ?>
<?php if (  (array_key_exists("subcats",$cat) && count($cat['subcats']) > 0) || (array_key_exists("arts",$cat) && count($cat['arts'])) || $this->view_mode_cat == "normal" || JRequest::getVar('catid',0) == $cat['id'] ): ?>
<div class='kb_category accordion_toggler_<?php echo $depth; ?>' <?php if ($this->view_mode_cat == "accordian") echo " style='cursor: pointer;' "; ?> >
	<?php if ($cat['image']) : ?>
	<div class='kb_category_image'>
		<?php if ($catdepth > 1 && FSS_Settings::get('kb_smaller_subcat_images')): ?>
			<img src='<?php echo JURI::root( true ); ?>/images/fss/kbcats/<?php echo $cat['image']; ?>' width='32' height='32'>
		<?php else : ?>
			<img src='<?php echo JURI::root( true ); ?>/images/fss/kbcats/<?php echo $cat['image']; ?>' width='64' height='64'>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<div class='kb_category_head' <?php if ($this->view_mode_cat == "accordian") echo " style='padding-top:6px;padding-bottom:6px;' "; ?>>
		<?php if ($cat['id'] == $this->curcatid) : ?><b><?php endif; ?>
					
		<?php if ($this->view_mode_cat == "popup") : ?>
	
			<a class="modal fss_highlight" href='<?php echo FSSRoute::x( '&what=&tmpl=component&limitstart=&catid=' . $cat['id'] . '&view_mode=' . $this->view_mode_incat ); ?>' rel="{handler: 'iframe', size: {x: 650, y: 375}}">
				<?php echo $cat['title'] ?>
			</a>

		<?php elseif ($this->view_mode_cat == "accordian"): ?>
			<A class="fss_highlight" href="javascript:function Z(){Z=''}Z()"><?php echo $cat['title'] ?></a> 			
		<?php else: ?>
			
			<?php if ($this->view_mode_cat == "normal"): ?>
				<A class="fss_highlight" href='<?php echo FSSRoute::x( '&what=&limitstart=&catid=' . $cat['id'] );?>'><?php echo $cat['title'] ?></a>
			<?php else: ?>
				<?php echo $cat['title'] ?>
			<?php endif; ?>

		<?php endif; ?>
					
		<?php if ($cat['id'] == $this->curcatid) : ?></b><?php endif; ?>
	</div>
	<div class='kb_category_desc'><?php echo $cat['description']; ?></div>
</div>

<?php if ($this->view_mode_cat == "links" || $this->view_mode_cat == "accordian") : ?>
	<div class="fss_clear"></div>
	<div class='kb_category_artlist accordion_content_<?php echo $depth; ?>'>
	<!-- Category contents -->
	<?php if (empty($catold)) $catold = array(); ?>
	<!-- Sub categories -->
	<?php $catdepth++; ?>
	<?php if (array_key_exists("subcats",$cat) && count($cat['subcats']) > 0) : ?>
		<?php array_push($catold, $cat); $depth++;?>
			<?php foreach ($cat['subcats'] as &$cat): ?>
				<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat.php';
				//include "components/com_fss/views/kb/snippet/_cat.php" ?>
			<?php endforeach; ?>
		<?php $cat = array_pop($catold); $depth--; ?>
	<?php endif; ?>
	<?php $catdepth--; ?>
	<!-- Articles -->
	<?php if (array_key_exists("arts",$cat) && count($cat['arts'])): ?>
		<?php foreach ($cat['arts'] as &$art) : ?>
			<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_art.php';
			//include "components/com_fss/views/kb/snippet/_art.php" ?>
			<?php endforeach; ?>
	<?php endif; ?>	
	</div>
<?php endif; ?>				

<?php endif; ?>