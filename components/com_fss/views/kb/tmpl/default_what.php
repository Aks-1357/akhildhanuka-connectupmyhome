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

<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("KNOWLEDGE_BASE",$this->pagetitle); ?>

<?php $product = $this->product; ?>
<?php $cat = $this->cat; ?>
<?php if ($product['id'] > 0): ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod.php';
//include "components/com_fss/views/kb/snippet/_prod.php" ?>	
<?php endif; ?>
<?php if ($cat['id'] > 0): ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat.php';
//include "components/com_fss/views/kb/snippet/_cat.php" ?>	
<?php endif; ?>

<div class='kb_product' >
	<div class='kb_product_image'>
	    <img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/<?php echo $this->image; ?>' width='64' height='64'>
	</div>
	<div class='kb_product_head'>
		<A class='fss_highlight' href='<?php echo FSSRoute::x( '&what=' ); ?>'><?php echo $this->title; ?></a>
	</div>
</div>

<?php if (FSS_Settings::get('kb_view_top')): ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_views.php';
//include "components/com_fss/views/kb/snippet/_views.php" ?>
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_art_list.php';
//include "components/com_fss/views/kb/snippet/_art_list.php" ?>	

<?php if (!FSS_Settings::get('kb_view_top')): ?>
	<div class="fss_spacer"></div>
	<div class='kb_product'></div>
	<div class="fss_spacer"></div>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_views.php';
//include "components/com_fss/views/kb/snippet/_views.php" ?>
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>


<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js';
//include 'components/com_fss/assets/js/content_edit.js'; ?>
</script>
