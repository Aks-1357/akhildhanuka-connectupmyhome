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
<?php foreach ($this->results as $product): ?>
	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod.php';
	//include "components/com_fss/views/kb/snippet/_prod.php" ?>
<?php endforeach; ?>
<?php if (count($this->results) == 0): ?>
<div class="fss_no_results"><?php echo JText::_("NO_PRODUCTS_MATCH_YOUR_SEARCH_CRITERIA"); ?></div>
<?php endif; ?>
<div class="fss_clear"></div>
<?php if ($this->main_prod_pages) echo $this->pagination->getListFooter(); ?>

