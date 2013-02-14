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
<?php echo FSS_Helper::PageStyle(); ?>
<?php if (FSS_Helper::IsTests()) : ?>
	<?php echo FSS_Helper::PageTitle('COMMENT_MODERATION'); ?>
<?php else: ?>
	<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN','COMMENT_MODERATION'); ?>
<?php endif; ?>
<?php //##NOT_TEST_START## ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php';
//include "components/com_fss/views/admin/snippet/_tabbar.php" ?>
<?php //##NOT_TEST_END## ?>
<?php $this->comments->DisplayModerate(); ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>