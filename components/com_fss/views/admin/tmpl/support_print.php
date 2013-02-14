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
<?php echo FSS_Helper::PageTitle('SUPPORT_TICKET',$this->ticket['title']); ?>

<?php if ($this->permission['support']): ?>

<?php if ($this->locked): ?>
<div class="fss_locked_warn">This support ticket is locked by <?php echo $this->co_user->name; ?> (<?php echo $this->co_user->email; ?>)</div>
<?php endif; ?>

<?php echo FSS_Helper::PageSubTitle("TICKET_DETAILS"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_ticket_info_print.php'; ?>

<?php echo FSS_Helper::PageSubTitle("MESSAGES"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_messages_print.php';
//include "components/com_fss/views/admin/snippet/_messages.php" ?>

<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>
jQuery(document).ready( function () {
	window.print();
});
</script>