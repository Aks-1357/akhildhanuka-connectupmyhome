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
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"CURRENT_SUPPORT_TICKETS"); ?>

<form id='adminForm' action="<?php echo str_replace("&amp;","&",FSSRoute::x( '&ticketid=&limitstart=' ));?>" name="adminForm" method="post">

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php';
//include "components/com_fss/views/admin/snippet/_tabbar.php" ?>

<?php if ($this->permission['support']): ?>

	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_supportbar.php';
	//include "components/com_fss/views/admin/snippet/_supportbar.php" ?>

	<div class="fss_admin_create_cont">
	<div class="fss_admin_create_130"><?php echo JText::_("CREATE_TICKET_FOR"); ?></div>
	<span class="fss_admin_create_sub"><a href="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=newregistered' ); ?>"><?php echo JText::_("REGISTERED_USER"); ?></a></span>
	<span class="fss_admin_create_sub"><a href="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=newunregistered' ); ?>"><?php echo JText::_("UNREGISTERED_USER"); ?></a></span>
		<div class="fss_admin_create_130" style="float:right;">
			<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin&layout=support&what=settings'); ?>">
				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/mysettings.png' width="16" height="16" style='position:relative;top:3px;'>
				<?php echo JText::_('MY_SETTINGS'); ?>
			</a>
		</div>
	</div>
	<div class="fss_clear"></div>
	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_search.php';
	//include "components/com_fss/views/admin/snippet/_search.php" ?>
	
<div class="fss_clear"></div>

	<?php
	$def_archive = FSS_Ticket_Helper::GetStatusID('def_archive');
	$closed = FSS_Ticket_Helper::GetClosedStatus();
	if (array_key_exists($this->ticket_view, $closed) || $this->ticket_view == "closed"): ?>
	<div class="fss_admin_create_cont">
			<span class="fss_admin_create_sub"><a href="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets='.$def_archive ); ?>"><?php echo JText::_("VIEW_ARCHIVED_TICKETS"); ?></a></span>
			<span class="fss_admin_create_sub"><a href="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=closed&archive=all' ); ?>" onclick="return confirm('<?php echo JText::_('ARCHIVE_CONFIRM'); ?>');"><?php echo JText::_("ARCHIVE_ALL_CLOSED_TICKETS"); ?></a></span>
			<?php if (FSS_Settings::get('support_delete')): ?>
				<span class="fss_admin_create_sub"><a href="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=closed&delete=closed' ); ?>" onclick="return confirm('<?php echo JText::_('DELETE_ALL_CONFIRM'); ?>');"><?php echo JText::_("DELETE_ALL_CLOSED_TICKETS"); ?></a></span>
			<?php endif; ?>
		</div>
	<?php elseif ($this->ticket_view == $def_archive): ?>
		<?php if (FSS_Settings::get('support_delete')): ?>
	<div class="fss_admin_create_cont">
				<span class="fss_admin_create_sub"><a href="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=archived&delete=archived' ); ?>" onclick="return confirm('<?php echo JText::_('DELETE_ALL_ARCHIVED_CONFIRM'); ?>');"><?php echo JText::_("DELETE_ALL_ARCHIVED_TICKETS"); ?></a></span>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if (count($this->tickets) < 1) : ?>
	<div class="fss_ticket_nonefound">
			<?php echo JText::_("THERE_ARE_CURRENTLY_NO_TICKETS_THAT_MATCH_YOUR_SEARCH"); ?>
		</div>
	<?php else: ?>
	
		<?php
			$table_indent = 0;
			if (FSS_Helper::getUserSetting("group_products")) $table_indent++;
			if (FSS_Helper::getUserSetting("group_departments")) $table_indent++;
			if (FSS_Helper::getUserSetting("group_cats")) $table_indent++;
			if (FSS_Helper::getUserSetting("group_group")) $table_indent++;
			if (FSS_Helper::getUserSetting("group_pri")) $table_indent++;
			$grp_prod = -1;
			$grp_dept = -1;
			$grp_cat = -1;
			$grp_group = -1;
			$grp_pri = -1;
			$grp_open = 1;
			$tab_style = "style='margin-left:". ($table_indent * 16) . "px;'";
		?>
			<div <?php echo $tab_style; ?>><table class='fss_ticket_list' cellspacing=0 cellpadding=4 width=100%>
			<?php $this->OutputHeader(); ?>
			
			<?php foreach ($this->tickets as $ticket): ?>
				<?php 
					$ticket['status'] = FSS_Helper::TrF('title', $ticket['status'], $ticket['str']);
					$ticket['department'] = FSS_Helper::TrF('title', $ticket['department'], $ticket['dtr']);
					$ticket['priority'] = FSS_Helper::TrF('title', $ticket['priority'], $ticket['ptl']);
					$ticket['category'] = FSS_Helper::TrF('title', $ticket['category'], $ticket['ctr']);
					$ticket['product'] = FSS_Helper::TrF('title', $ticket['product'], $ticket['prtr']);
				?>
				
				<?php 
					if (FSS_Helper::getUserSetting("group_products")) {
						if ($ticket['prod_id'] != $grp_prod)
						{
							if ($grp_open == 1)
								echo "</table></div>";
							$grp_open = 0;	
							
							echo $this->grouping("prod",$ticket['product'],$ticket);
							$grp_prod = $ticket['prod_id'];
							$grp_dept = -1;
							$grp_cat = -1;
							$grp_group = -1;
						}
					} 
					if (FSS_Helper::getUserSetting("group_departments")) {
						if ($ticket['ticket_dept_id'] != $grp_dept)
						{
							if ($grp_open == 1)
								echo "</table></div>";
							$grp_open = 0;
							
							echo $this->grouping("dept",$ticket['department'],$ticket);
							$grp_dept = $ticket['ticket_dept_id'];
							$grp_cat = -1;
							$grp_group = -1;
						}
					} 
					if (FSS_Helper::getUserSetting("group_cats")) {
						if ($ticket['ticket_cat_id'] != $grp_cat)
						{
							if ($grp_open == 1)
								echo "</table></div>";
							$grp_open = 0;
							
							echo $this->grouping("cat",$ticket['category'],$ticket);
							$grp_cat = $ticket['ticket_cat_id'];
							$grp_group = -1;
						}
					} 
					if (FSS_Helper::getUserSetting("group_group")) {
						if ($ticket['group_id'] != $grp_group)
						{
							if ($grp_open == 1)
								echo "</table></div>";
							$grp_open = 0;
							
							echo $this->grouping("group",$ticket['groupname'],0);
							$grp_group = $ticket['group_id'];
						}
					} 
					if (FSS_Helper::getUserSetting("group_pri")) {
						if ($ticket['ticket_pri_id'] != $grp_pri)
						{
							if ($grp_open == 1)
								echo "</table></div>";
							$grp_open = 0;
							
							echo $this->grouping("pri",$ticket['priority'],0);
							$grp_pri = $ticket['ticket_pri_id'];
						}
					} 
					
					if ($grp_open == 0)
					{
						?>
							<div <?php echo $tab_style; ?>><table class='fss_ticket_list' cellspacing=0 cellpadding=4 width=100%>
						<?php
						$grp_open = 1;
					}
					
					?>
				<?php $this->OutputRow($ticket); ?>
			<?php endforeach; ?>

		</table></div>

		<div class="fss_spacer"></div>

		<?php echo $this->pagination->getListFooter(); ?>

	<?php endif; ?>

<?php endif; ?>

</form>

<script>

function highlightticket(ticketid)
{
	$$('.ticket_' + ticketid).each(function(el){
		el.style.background = '<?php echo FSS_Settings::get('css_hl'); ?>';
	});
}

function unhighlightticket(ticketid)
{
	$$('.ticket_' + ticketid).each(function(el){
		el.style.background = '';
	});
}

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
