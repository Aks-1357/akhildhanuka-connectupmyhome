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
<?php if ($product && $product['title'] != ""): ?>
<div class='comment_product' >
	<?php if ($product['image'] && substr($product['image'],0,1) != "/"): ?>
	<div class='kb_product_image'>
	    <img src='<?php echo JURI::root( true ); ?>/images/fss/products/<?php echo $product['image']; ?>' width='64' height='64'>
	</div>
	<?php elseif ($product['image']) : ?>
		<div class='kb_product_image'>
			<img src='<?php echo JURI::root( true ); ?><?php echo $product['image']; ?>' width='64' height='64'>
		</div>
	<?php endif; ?>
	<div class='kb_product_head accordion_toggler_1'>
		<?php $endlink = false; if (empty($hideprodlink)): ?>
			<?php if ($this->test_show_prod_mode == "accordian"): ?>
				<a class="fss_highlight" href="javascript:function Z(){Z=''}Z()">
				<?php $endlink = true; ?>
			<?php elseif ($this->test_show_prod_mode != "inline"): ?>
				<a class='fss_highlight' href='<?php echo FSSRoute::x( '&prodid=' . $product['id'] );?>'>
				<?php $endlink = true; ?>
			<?php endif; ?>	
		<?php endif; ?>	
			<?php echo $product['title'] ?>
		<?php if ($endlink): ?>
			</a>
		<?php endif; ?>	
	</div>
	<div class='kb_product_desc'><?php echo $product['description']; ?></div>
<div class='fss_clear'></div></div>
<?php endif; ?>
