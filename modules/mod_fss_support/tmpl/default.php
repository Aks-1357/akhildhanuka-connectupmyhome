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
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if ($permissions['support']): ?>
	<div class="fss_module_support">
		<div class="fss_module_support_title"><?php echo JText::_("SUPPORT_TICKETS"); ?></div>
		<div class="fss_module_support_items">
		
				<?php
				//echo JText::sprintf("TICKET_STATUS",$this->ticketopen,$this->ticketfollow,$this->ticketuser,FSSRoute::x( '&layout=support' )); 
				FSS_Ticket_Helper::GetStatusList();
				$counts = $model->getTicketCount();
				FSS_Helper::Tr(FSS_Ticket_Helper::$status_list);
				foreach (FSS_Ticket_Helper::$status_list as $status)
				{
					if ($status->def_archive) continue;
					if ($status->is_closed) continue;
					if (!array_key_exists($status->id, $counts)) continue;
					if ($counts[$status->id] < 1) continue;
				?>
					<div class="fss_module_support_item">
						<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin&layout=support&tickets=' . $status->id ); ?>">
							<?php echo $status->title . " (" . $counts[$status->id] . ")"; ?>
						</a>
					</div>
				<?php
				}
				?>

		</div>
	</div>
<?php endif; ?>

<?php if ($permissions['mod_kb']): ?>
	<div class="fss_module_support">
		<div class="fss_module_support_title"><?php echo  JText::_("MODERATE"); ?></div>
		<div class="fss_module_support_items">
		<?php $comments->DisplayModStatus("modstatus_module.php"); ?>
		</div>
	</div>
<?php endif; ?>

