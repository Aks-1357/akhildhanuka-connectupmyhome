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
		<?php $hasprodimages = false; ?>
		<?php $multitype = false; $hast0 = false; $hast1 = false; ?>
		<?php foreach ($this->results as &$product): ?>
			<?php if ($product['image']) { $hasprodimages = true;} ?>
			<?php if ($product['maxtype'] == 0)	$hast0 = true; ?>
			<?php if ($product['maxtype'] == 1)	$hast1 = true; ?>
		<?php endforeach; ?>
		
		<?php if ($hast0 && $hast1) $multitype = true; ?>
		<?php $curtype = -1; ?>
		<?php foreach ($this->results as &$product): ?>
			<?php if ($multitype && $curtype != $product['maxtype']) : ?>
				<?php echo $product['maxtype'] == 0 ? FSS_Helper::PageSubTitle2("OTHER_PRODUCTS") : FSS_Helper::PageSubTitle2("MY_PRODUCTS") ?>
				<?php $curtype = $product['maxtype']; ?>
			<?php endif ;?>
			<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_prod.php';
			//include "components/com_fss/views/ticket/snippet/_prod.php" ?>
			<?php endforeach; ?>
<?php if (count($this->results) == 0): ?>
<div class="fss_no_results"><?php echo JText::_("NO_PRODUCTS_MATCH_YOUR_SEARCH_CRITERIA"); ?></div>
<?php endif; ?>
		<div class='fss_ticket_prod_foot'></div>
		<?php if ($this->support_advanced) echo $this->pagination->getListFooter(); ?>
		<div class="fss_clear" style="padding-top:4px;"></div>
	</div>
