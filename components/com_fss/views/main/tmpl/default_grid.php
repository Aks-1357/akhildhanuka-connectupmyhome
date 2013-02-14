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
 
// No direct access
 
defined('_JEXEC') or die('Restricted access'); ?>

<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("SUPPORT","MAIN_MENU"); ?>

<?php if ($this->showadmin): ?>
	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'main'.DS.'snippet'.DS.'supportinfo.php';
	//include "components/com_fss/views/main/snippet/supportinfo.php" ?>
<?php endif; ?>

<table class="fss_support_main" cellspacing="12" width="<?php echo $this->mainwidth; ?>">
<?php $col = 0; ?>
<?php $colcount = $this->maincolums; ?>
<?php $width = 100 / $colcount; ?>

	<?php foreach ($this->menus as $menu): ?>
		<?php if ($menu['published'] == 0) continue; ?>
		<?php if ($col == 0) : ?>
		<tr>
		<?php endif; ?>	

			<td width="<?php echo $width; ?>%" valign="top" style="text-align:center">
			
			<?php 
				$link = $menu['link'];				
				if ($menu['itemid'] > 0)
					$link .= '&Itemid=' . $menu['itemid'];
			$link = JRoute::_( $link ); ?>
				
				<?php if ($menu['icon'] && $this->hideicons == 0): ?>
					<div class="fss_support_image" style="text-align:center">
						<a href='<?php echo $link ?>'>
						<?php if (file_exists(JPATH_SITE.DS.'images'.DS.'fss'.DS.'menu'.DS.$menu['icon'])) : ?>
							<img src='<?php echo JURI::base(); ?>images/fss/menu/<?php echo $menu['icon']; ?>' width="<?php echo $this->imagewidth; ?>" height="<?php echo $this->imageheight; ?>" />
						<?php else: ?>
							<img src='<?php echo JURI::base(); ?>components/com_fss/assets/mainicons/<?php echo $menu['icon']; ?>' width="<?php echo $this->imagewidth; ?>" height="<?php echo $this->imageheight; ?>" />
						<?php endif; ?>
						</a>
					</div>
				<?php endif; ?>
				<div class="fss_support_title" style="text-align:center">
					<a href='<?php echo $link ?>'>
						<?php echo JText::_($menu['title']); ?>
					</a>
				</div>
				<?php if ($menu['description'] && $this->show_desc): ?>
					<div class="fss_support_desc" style="text-align:center">
						<?php echo JText::_($menu['description']); ?>
					</div>
				<?php endif; ?>
				
			</td>
			
		<?php $col++; ?>
		
		<?php if ($col == $colcount): ?>
			</tr>
		<?php $col = 0; ?>
		<?php endif; ?>
		
	<?php endforeach; ?>
<?php if ($col > 0) echo "</tr>"; ?>	
</table>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>