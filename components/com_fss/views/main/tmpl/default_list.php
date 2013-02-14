<?php
 
// No direct access
 
defined('_JEXEC') or die('Restricted access'); ?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("SUPPORT","MAIN_MENU"); ?>

<?php if ($this->showadmin): ?>
	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'main'.DS.'snippet'.DS.'supportinfo.php';
	//include "components/com_fss/views/main/snippet/supportinfo.php" ?>
	<?php endif; ?>

<table width="<?php echo $this->mainwidth; ?>">
<?php $col = 0; ?>
<?php $colcount = $this->maincolums; ?>
<?php $width = 100 / $colcount; ?>

	<?php foreach ($this->menus as $menu): ?>
		<?php if ($menu['published'] == 0) continue; ?>
	
		<?php if ($col == 0) : ?>
		<tr>
		<?php endif; ?>	
		
			<td class="fss_support_row_list" width="<?php echo $width; ?>%" valign="top">
			
			<?php 
				$link = $menu['link'];
				if ($menu['itemid'] > 0)
					$link .= '&Itemid=' . $menu['itemid'];
			$link = JRoute::_( $link ); ?>
				
				<?php if ($menu['icon'] && $this->hideicons == 0): ?>
					<div class="fss_support_image_list">
						<a href='<?php echo $link ?>'>
						<img src='<?php echo JURI::base(); ?>images/fss/menu/<?php echo $menu['icon']; ?>' width="<?php echo $this->imagewidth; ?>" height="<?php echo $this->imageheight; ?>" />
						</a>
					</div>
				<?php endif; ?>
				<div class="fss_support_title_list">
					<a href='<?php echo $link ?>'>
						<?php echo JText::_($menu['title']); ?>
					</a>
				</div>
				<?php if ($menu['description'] && $this->show_desc): ?>
					<div class="fss_support_desc_list">
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