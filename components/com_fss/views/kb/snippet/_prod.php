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
<div class='kb_product' >
<?php FSS_Helper::TrSingle($product); ?>
	<?php if ($product['image']) : ?>
	<div class='kb_product_image'>
	    <img src='<?php echo JURI::root( true ); ?>/images/fss/products/<?php echo $product['image']; ?>' width='64' height='64'>
	</div>
	<?php endif; ?>
	<div class='kb_product_head'>	
		<?php if (JRequest::getVar('prodid',0) != 0) : ?>
			<A class='fss_highlight' href='<?php echo FSSRoute::x( '&limitstart=&what=&prodsearch=&tmpl=&catid=&prodid=' . $product['id'] );?>'><?php echo $product['title'] ?></a>
		<?php else : ?>
			<A class='fss_highlight' href='<?php echo FSSRoute::x( '&limitstart=&what=&prodsearch=&tmpl=&catid=&prodid=' . $product['id'] );?>'><?php echo $product['title'] ?></a>
		<?php endif; ?>
	</div>
	<div class='kb_product_desc'><?php echo $product['description']; ?></div>
</div>
