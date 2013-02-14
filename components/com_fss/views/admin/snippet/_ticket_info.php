<?php 
global $curcol;
global $cols;
$cols = FSS_Settings::get('support_info_cols');
$curcol = 1;
function fss_open_table()
{
	global $cols, $curcol;
	$curcol = 1;
	echo "<table class='fss_table' cellspacing='0' cellpadding='0'>";
}

function fss_close_table()
{
	echo "</table>";
}

function fss_start_col()
{
	global $curcol;
	if ($curcol == 1)
	{
		echo "<tr>";		
	} else {
		echo "";
	}
	$curcol++;
}

function fss_end_col()
{
	global $curcol;
	global $cols;
	if ($curcol > $cols)
	{
		echo "</tr>";	
		$curcol = 1;
	} else {
		
	}
}

?>
<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td valign="top">

<?php fss_open_table(); ?>

<?php fss_start_col(); ?>
	<th><?php echo JText::_("TITLE"); ?></th>
	<td colspan="2">
		<div id="title_show">
			<div style='float:left' id="title_value">
				<?php echo $this->ticket['title']; ?>
			</div>
			<div style="float:right;">
				<a href='#' onclick='editTitle();return false;'>
					<img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/edit.png' title="<?php echo JText::_("CHANGE_TICKET_TITLE"); ?>">
				</a>		
			</div>
		</div>
		<div id='title_edit' style="display:none;">
			<div style='float:left'>
				<input class='fss_support_custom_edit' id="title_input" size="30" value='<?php echo $this->ticket['title']; ?>' />
			</div>
			<div style="float:right;padding-top:1px;">
				<a href='#' onclick='saveTitle();return false;'><img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/save_16.png' title="<?php echo JText::_("SAVE"); ?>"></a>
				<a href='#' onclick='cancelEditTitle();return false;'><img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/cancel_16.png' title="<?php echo JText::_("CANCEL"); ?>"></a>		
			</div>
		</div>
	</td>
<?php fss_end_col(); ?>


<?php fss_start_col(); ?>
	<th><?php echo JText::_("TICKET_ID"); ?></th>
	<td colspan="2"><?php echo $this->ticket['reference']; ?></td>
<?php fss_end_col(); ?>


<?php if ($this->ticket['product']): ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("PRODUCT"); ?></th>
<td colspan="2">
<div style="float:left">
			<?php echo FSS_Helper::TrF('title', $this->ticket['product'], $this->ticket['prtr']); ?>
		</div>
		<?php if (!$this->locked): ?>
			<div style="float:right;">
				<a href='<?php echo FSSRoute::x( '&option=com_fss&view=admin&layout=support&what=reply&limitstart=&forward=2&ticketid=' . $this->ticket['id'] ); ?>'>
					<img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/forward.png' title="<?php echo JText::_("FORWARD_TO_ANOTHER_PRODUCT"); ?>">
				</a>		
			</div>
		<?php endif; ?>	
	</td>
<?php fss_end_col(); ?>
<?php endif; ?>


<?php if ($this->ticket['dept']): ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("DEPARTMENT"); ?></th>
<td colspan="2">
<div style="float:left">
			<?php echo FSS_Helper::TrF('title', $this->ticket['dept'], $this->ticket['dtr']); ?>
		</div>
		<?php if (!$this->locked): ?>
			<div style="float:right;">
				<a href='<?php echo FSSRoute::x( '&option=com_fss&view=admin&layout=support&what=reply&limitstart=&forward=2&ticketid=' . $this->ticket['id'] ); ?>'>
					<img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/forward.png' title="<?php echo JText::_("FORWARD_TO_ANOTHER_DEPARTMENT"); ?>">
				</a>		
			</div>
		<?php endif; ?>
	</td>
<?php fss_end_col(); ?>
<?php endif; ?>


<?php if ($this->ticket['cat']): ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("CATEGORY"); ?></th>
<td colspan="2">
<div id="cat_show">
<div style='float:left' id="cat_value">
				<?php echo FSS_Helper::TrF('title', $this->ticket['cat'], $this->ticket['ctr']); ?>
			</div>
			<div style="float:right;">
				<a href='#' onclick='editCat();return false;'>
					<img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/edit.png' title="<?php echo JText::_("CHANGE_TICKET_CATEGORY"); ?>">
				</a>		
			</div>
		</div>
		<div id='cat_edit' style="display:none;">
			<div style='float:left'>
			
					<select id='catid' name='catid'>
						<?php $sect = ""; $open = false; ?>
						<?php FSS_Helper::Tr($this->cats); ?>
						<?php foreach ($this->cats as $cat): ?>
							<?php 
								if ($cat['section'] != $sect) {
									if ($open)
										echo "</optgroup>";
									$open = true;
									echo "<optgroup label='" . $cat['section'] . "'>";
									$sect = $cat['section'];	
								}								
							?>
							<option value='<?php echo $cat['id']; ?>' <?php if ($cat['id'] == $this->ticket['ticket_cat_id']) echo " SELECTED"; ?> ><?php echo $cat['title']; ?></option>
						<?php endforeach; ?>
						<?php if ($open) echo "</optgroup>"; ?>
					</select>

			</div>
			<div style="float:right;padding-top:1px;">
				<a href='#' onclick='saveCat();return false;'><img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/save_16.png' title="<?php echo JText::_("SAVE"); ?>"></a>
				<a href='#' onclick='cancelEditCat();return false;'><img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/cancel_16.png' title="<?php echo JText::_("CANCEL"); ?>"></a>		
			</div>
		</div>
	</td>
<?php fss_end_col(); ?>
<?php endif; ?>


<?php fss_start_col(); ?>
<th><?php echo JText::_("USER"); ?></th>
	<td colspan="2">
		<div style="float:left">
			<?php if ($this->ticket['user_id'] == 0): ?>
				<?php echo $this->ticket['unregname']; ?> (<?php echo JText::_("UNREGISTERED"); ?>)
			<?php else: ?>
				<?php if (file_exists(JPATH_SITE.DS.'components'.DS.'com_community')): ?>
					<a href='<?php echo JRoute::_('index.php?option=com_community&view=profile&userid='. $this->ticket['user_id']);?>'>
				<?php endif; ?>
				<?php echo $this->ticket['name']; ?> (<?php echo $this->ticket['username']; ?>)
				<?php if (file_exists(JPATH_SITE.DS.'components'.DS.'com_community')): ?></a><?php endif; ?>
			<?php endif; ?>
			&nbsp;&nbsp;&nbsp;
		</div>
		
		<?php if (!$this->locked): ?>
			<div style="float:right;">
				<a href='<?php echo FSSRoute::x( '&option=com_fss&view=admin&layout=support&what=reply&limitstart=&forward=4&ticketid=' . $this->ticket['id'] ); ?>'>
					<img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/forward.png' title="<?php echo JText::_("FORWARD_TO_ANOTHER_USER"); ?>">
				</a>		
			</div>
		<?php endif; ?>
	</td>
<?php fss_end_col(); ?>

<?php if (count($this->groups) > 0): ?>
<?php fss_start_col(); ?>
<th><?php echo JText::_("USER_GROUPS"); ?></th>
	<td colspan="2">
		<div style="float:left">
			<?php $gl = array();
			foreach ($this->groups as $group)
				$gl[] = $group['groupname'];
			echo implode(", ", $gl); ?>
		</div>
	</td>
<?php fss_end_col(); ?>
<?php endif; ?>

<?php if ($this->ticket['email']): ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("EMAIL"); ?></th>
	<td colspan="2">
		<div id="email_show">
			<div style='float:left' id="email_value">
				<?php echo $this->ticket['email']; ?>
			</div>
			<div style="float:right;">
				<a href='#' onclick='editEMail();return false;'>
					<img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/edit.png' title="<?php echo JText::_("CHANGE_TICKET_EMAIL"); ?>">
				</a>		
			</div>
		</div>
		<div id='email_edit' style="display:none;">
			<div style='float:left'>
				<input class='fss_support_custom_edit' id="email_input" size="30" value='<?php echo $this->ticket['email']; ?>' />
			</div>
			<div style="float:right;padding-top:1px;">
				<a href='#' onclick='saveEMail();return false;'><img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/save_16.png' title="<?php echo JText::_("SAVE"); ?>"></a>
				<a href='#' onclick='cancelEditEMail();return false;'><img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/cancel_16.png' title="<?php echo JText::_("CANCEL"); ?>"></a>		
			</div>
		</div>
	</td>

<?php fss_end_col(); ?>
<?php endif; ?>


<?php fss_start_col(); ?>
	<th><?php echo JText::_("LAST_UPDATE"); ?></th>
	<td colspan="2">
		<?php echo FSS_Helper::Date($this->ticket['lastupdate'], FSS_DATETIME_MID); ?>
	</td>
<?php fss_end_col(); ?>

<?php $st = FSS_Ticket_Helper::GetStatusByID($this->ticket['ticket_status_id']);
if ($st->is_closed) : ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("CLOSED"); ?></th>
	<td colspan="2">
		<?php echo FSS_Helper::Date($this->ticket['closed'], FSS_DATETIME_MID); ?>
	</td>
<?php fss_end_col(); ?>
<?php endif; ?>

<?php fss_start_col(); ?>
	<th><?php echo JText::_("STATUS"); ?></th>
	<td colspan="2"><span style='color: <?php echo $this->ticket['scolor']; ?>'><?php echo FSS_Helper::TrF('title', $this->ticket['status'], $this->ticket['str']); ?></span></td>
<?php fss_end_col(); ?>


<?php if (FSS_Settings::get('support_hide_priority') != 1) : ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("PRIORITY"); ?></th>
	<td colspan="2"><span style='color:<?php echo $this->ticket['pcolor']; ?>'><?php echo FSS_Helper::TrF('title', $this->ticket['pri'], $this->ticket['ptl']); ?></span></td>
<?php fss_end_col(); ?>
<?php endif; ?>

<?php if (!FSS_Settings::get('support_hide_handler')) : ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("HANDLER"); ?></th>
	<td colspan="2">
		<div style="float:left">
				<?php if ($this->adminuser['name']) { echo $this->adminuser['name']; } else { echo JText::_("UNASSIGNED"); } ?>
		</div>
		<?php if (!$this->locked): ?>
		<div style="float:right;">
			<a href='<?php echo FSSRoute::x( '&option=com_fss&view=admin&layout=support&what=reply&forward=1&ticketid=' . $this->ticket['id'] ); ?>'>
				<img class="fsj_tip" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/forward.png' title="<?php echo JText::_("FORWARD_TO_ANOTHER_HANDLER"); ?>">
			</a>		
		</div>
		<?php endif; ?>
	</td>
<?php fss_end_col(); ?>
<?php endif; ?>


<?php if (!FSS_Settings::get('support_hide_users_tickets')) : ?>
<?php 
	FSS_Ticket_Helper::GetStatusList();
	$statuss = FSS_Ticket_Helper::$status_list;
	FSS_Helper::Tr($statuss);
	$tooltiptext = array();
	foreach ($statuss as $status)
	{
		$id = $status->id;
		if (array_key_exists($id, $this->userticketcount))
		{
			$tooltiptext[] = $this->userticketcount[$id] . ": " . $status->title;
		}	
	}
	$tooltiptext = htmlentities(implode("<br />",$tooltiptext));
	$tiplink = FSSRoute::x("&searchtype=advanced&userid=".$this->ticket['username']."&tickets=open&ticketid=" );
?>
<form name="searchform" action="<?php echo FSSRoute::x("&tickets=open&ticketid=" );?>" method="post">
	<input type="hidden" name="searchtype" value="advanced">
	<input type="hidden" name="what" value="search">
	<?php if ($this->ticket['username']) : ?>
	<input type="hidden" name="username" value="<?php echo $this->ticket['username']; ?>">
	<?php else : ?>
	<input type="hidden"  name="useremail" value="<?php echo $this->ticket['email']; ?>">
	<?php endif; ?>
</form>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("USERS_TICKETS"); ?></th>
<td colspan="2">
<div style="float:left">
				<?php echo JText::sprintf("UC_TICKETS",$this->userticketcount['total']); ?>
</div>
<div style="float:right;">
			<span class="editlinktip fsj_tip" title="<?php echo JText::sprintf("UC_TICKETS",$this->userticketcount['total']); ?>::<?php echo $tooltiptext; ?>" >
<a href="#" onclick='searchform.submit();return false;'>
					<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/othertickets.png" border="0" alt="Tooltip"/>
				</a>
			</span>
		</div>
	</td>
<?php fss_end_col(); ?>
<?php endif; ?>


<?php if (!FSS_Settings::get('support_hide_tags')) : ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("TAGS"); ?></th>
	<td style="border-right:none;">
		<div id="tags">
			<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tags.php';
			//include "components/com_fss/views/admin/snippet/_tags.php" ?>
		</div>
	</td>
	<td align="right">
		<?php if (!FSS_Settings::get('support_hide_tags') && !$this->locked) : ?>
			<div style="position: relative">
				<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tag_list.php';
					//include "components/com_fss/views/admin/snippet/_tag_list.php" ?>
				</div>
					
			<a href="#" id="fss_show_taglist">
				<img class="fsj_tip" src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tag_add.png" border="0" alt="Tooltip" title="<?php echo JText::_('ADD_TAGS'); ?>"/>
			</a>
		<?php endif;?>
	</td>
<?php fss_end_col(); ?>
<?php endif; ?>


<?php // fields with no grouping should be in main table
if ($this->fields && count($this->fields) > 0)
{
	foreach ($this->fields as $field)
	{
		if ($field['grouping'] != "")
			continue;
			
		fss_start_col();
		?>
		<th width='100'><?php echo FSSCF::FieldHeader($field); ?></th>
		<td style="border-right:none;">
			<?php echo FSSCF::FieldOutput($field,$this->fieldvalues,array('ticketid' => $this->ticket['id'], 'userid' => $this->userid, 'ticket' => $this->ticket)); ?>
		</td>
		<td align="right" width="20">
		<?php if (!$this->locked && $this->CanEditField($field)): ?>
			<a class='modal' rel="{handler: 'iframe', size: {x: 400, y: 400}}" href="<?php echo FSSRoute::x("&tmpl=component&what=editfield&editfield=" . $field['id'] ); ?>"><img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/edit.png" alt="Edit"></a>
		<?php endif; ?>
		</td>
		<?php
		fss_end_col();
	}
}

fss_close_table();

$grouping = "";
$open = false;
if ($this->fields && count($this->fields) > 0)
{
	foreach ($this->fields as $field)
	{
		if ($field['grouping'] == "")
			continue;
		
		if ($field['grouping'] != $grouping)
		{
			if ($open)
			{
				fss_close_table();
			}
	
			echo FSS_Helper::PageSubTitle($field['grouping']);
			fss_open_table();
			$open = true;	
			$grouping = $field['grouping'];
		}
		
		fss_start_col();	
		?>
		<th width='100'><?php echo FSSCF::FieldHeader($field); ?></th>
		<td style="border-right:none;">
			<?php echo FSSCF::FieldOutput($field,$this->fieldvalues,array('ticketid' => $this->ticket['id'], 'userid' => $this->userid, 'ticket' => $this->ticket)); ?>
		</td>
		<td align="right" width="20">
		<?php if (!$this->locked && $this->CanEditField($field)): ?>
			<a class='modal' rel="{handler: 'iframe', size: {x: 400, y: 400}}" href="<?php echo FSSRoute::x("&tmpl=component&what=editfield&editfield=" . $field['id'] ); ?>"><img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/edit.png" alt="Edit"></a>
		<?php endif; ?>
		</td>
		<?php
		fss_end_col();
	}
}

if ($open)
{
	fss_close_table();
}

?>

</td></tr></table>
