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
<?php echo FSS_Helper::PageSubTitle("CURRENT_SUPPORT_TICKETS"); ?>

<div class="fss_spacer"></div>

<div class='ffs_tabs'>
	<?php if (JRequest::getVar('what','') == "search") : ?>
		<?php $this->ticket_view = "search"; ?>
		<a class='ffs_tab <?php if ($this->ticket_view == "search") echo "fss_tab_selected";?>' href='#'><?php echo JText::sprintf("SA_RESULTS",$this->ticket_count); ?></a>
	<?php endif; ?>
	<?php if (JRequest::getVar('what','') == "settings") : ?>
		<?php $this->ticket_view = "settings"; ?>
		<a class='ffs_tab <?php if ($this->ticket_view == "settings") echo "fss_tab_selected";?>' href='#'><?php echo JText::_("MY_SETTINGS"); ?></a>
	<?php else: ?>
		
		<?php
		
		$cst = FSS_Ticket_Helper::GetStatusByID($this->ticket_view);
		FSS_Helper::TrSingle($cst);
		if ($cst)
		{
			if (!$cst->own_tab)	
			{
		?>
	<a class='ffs_tab fss_tab_selected' href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=' . $cst->id ); ?>'><?php echo $cst->title; ?> (<?php echo $this->count[$cst->id]; ?>)</a>
<?php				
			}
		}
		// get current status and if it doesnt have its own tab specified, then list it here 
		
		?>
		
	<?php endif; ?>

<?php 
$tabs = FSS_Ticket_Helper::GetStatuss("own_tab"); 
FSS_Helper::Tr($tabs);
?>

<?php foreach ($tabs as $tab): ?>
	
	<a class='ffs_tab <?php if ($this->ticket_view == $tab->id) echo "fss_tab_selected";?>' href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=' . $tab->id ); ?>'><?php echo $tab->title; ?> (<?php echo $this->count[$tab->id]; ?>)</a>
	
<?php endforeach; ?>	
	
<?php if (FSS_Settings::get('support_tabs_allopen') || $this->ticket_view == "allopen"): ?>
	<a class='ffs_tab <?php if ($this->ticket_view == "allopen") echo "fss_tab_selected";?>' href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=allopen' ); ?>'><?php echo JText::sprintf("SA_ALLOPEN",$this->count['allopen']); ?></a>
<?php endif; ?>

<?php if (FSS_Settings::get('support_tabs_allclosed') || $this->ticket_view == "closed"): ?>
	<a class='ffs_tab <?php if ($this->ticket_view == "closed") echo "fss_tab_selected";?>' href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=closed' ); ?>'><?php echo JText::sprintf("SA_CLOSED",$this->count['allclosed']); ?></a>
<?php endif; ?>

<?php if (FSS_Settings::get('support_tabs_all') || $this->ticket_view == "all"): ?>
	<a class='ffs_tab <?php if ($this->ticket_view == "all") echo "fss_tab_selected";?>' href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=all' ); ?>'><?php echo JText::sprintf("SA_ALL",$this->count['all']); ?></a>
<?php endif; ?>

	<?php 
		$nottabs = FSS_Ticket_Helper::GetStatuss("own_tab", true); 
		FSS_Helper::Tr($tabs);
		
	$showother = (count($nottabs) > 0);
	
	if ($showother || !FSS_Settings::get('support_tabs_allopen') || !FSS_Settings::get('support_tabs_allclosed') || !FSS_Settings::get('support_tabs_all')) :
	?>
	
	<div style="position:relative;display: inline;" id="order_status_tab">
		<a class='ffs_tab' href='#' onclick='return false;'>
			<?php echo JText::_('Other'); ?>
		</a>

		<div id='other_status_popup' class="" style="background-color: #888888;display:none;position:absolute;left:3px;top:22px;margin-left:0px !important;min-width:150px;z-index:500">
			<div class="" style="border: 1px solid #e0e0e0;padding: 3px;background-color: white;position: relative;left: -3px;top: -3px;">
		

				<?php foreach ($nottabs as $tab): ?>
	
					<a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=' . $tab->id ); ?>'><?php echo $tab->title; ?> (<?php echo $this->count[$tab->id]; ?>)</a><br />

				<?php endforeach; ?>	
		
				<?php if (count($nottabs) > 0 && (!FSS_Settings::get('support_tabs_allopen') || !FSS_Settings::get('support_tabs_allclosed') || !FSS_Settings::get('support_tabs_all'))): ?>
					<hr style="color: #aaa;background-color: #aaa;height: 2px;border: 0px;"/>
				<?php endif; ?>
				
				<?php if (!FSS_Settings::get('support_tabs_allopen')): ?>
					<a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=allopen' ); ?>'><?php echo JText::sprintf("SA_ALLOPEN",$this->count['allopen']); ?></a><br />
				<?php endif; ?>

				<?php if (!FSS_Settings::get('support_tabs_allclosed')): ?>
					<a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=closed' ); ?>'><?php echo JText::sprintf("SA_CLOSED",$this->count['allclosed']); ?></a><br />
				<?php endif; ?>

				<?php if (!FSS_Settings::get('support_tabs_all')): ?>
					<a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=all' ); ?>'><?php echo JText::sprintf("SA_ALL",$this->count['all']); ?></a><br />
				<?php endif; ?>

			</div>
		</div>
	</div>
	<?php endif; ?>
</div>


<div class="fss_clear"></div>

<script>

jQuery(document).ready(function () {
	jQuery('#order_status_tab').mouseenter(function () {
		jQuery("#other_status_popup").show();
	});	
	
	jQuery('#order_status_tab').mouseleave(function () {
		jQuery("#other_status_popup").hide();
	});
});

</script>