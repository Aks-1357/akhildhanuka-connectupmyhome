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

<?php fss_open_table(); ?>

<?php fss_start_col(); ?>
	<th><?php echo JText::_("TITLE"); ?></th>
	<td colspan="2">
		<div id="title_show">
			<div style='float:left' id="title_value">
				<?php echo $this->ticket['title']; ?>
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
	<td colspan="2"><?php echo $this->ticket['product']; ?></td>
<?php fss_end_col(); ?>
<?php endif; ?>


<?php if ($this->ticket['dept']): ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("DEPARTMENT"); ?></th>
	<td colspan="2">
		<div style="float:left">
			<?php echo $this->ticket['dept']; ?>
		</div>
	</td>
<?php fss_end_col(); ?>
<?php endif; ?>


<?php if ($this->ticket['cat']): ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("CATEGORY"); ?></th>
	<td colspan="2">
		<div id="cat_show">
			<div style='float:left' id="cat_value">
				<?php echo $this->ticket['cat']; ?>
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
				<?php echo $this->ticket['name']; ?> (<?php echo $this->ticket['username']; ?>)
			<?php endif; ?>
			&nbsp;&nbsp;&nbsp;
		</div>
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
		<?php echo $this->ticket['email']; ?>
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
	<td colspan="2"><span style='color: <?php echo $this->ticket['scolor']; ?>'><?php echo $this->ticket['status']; ?></span></td>
<?php fss_end_col(); ?>


<?php if (FSS_Settings::get('support_hide_priority') != 1) : ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("PRIORITY"); ?></th>
	<td colspan="2"><span style='color:<?php echo $this->ticket['pcolor']; ?>'><?php echo $this->ticket['pri']; ?></span></td>
<?php fss_end_col(); ?>
<?php endif; ?>


<?php if (!FSS_Settings::get('support_hide_handler')) : ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("HANDLER"); ?></th>
	<td colspan="2">
		<div style="float:left">
				<?php if ($this->adminuser['name']) { echo $this->adminuser['name']; } else { echo JText::_("UNASSIGNED"); } ?>
		</div>
	</td>
<?php fss_end_col(); ?>
<?php endif; ?>

<?php if (!FSS_Settings::get('support_hide_tags')) : ?>
<?php fss_start_col(); ?>
	<th><?php echo JText::_("TAGS"); ?></th>
	<td colspan="2">
		<div id="tags">
			<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tags.php';
			//include "components/com_fss/views/admin/snippet/_tags.php" ?>
		</div>
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
		<td colspan="2">
			<?php echo FSSCF::FieldOutput($field,$this->fieldvalues,array('ticketid' => $this->ticket['id'], 'userid' => $this->userid, 'ticket' => $this->ticket)); ?>
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
		<td colspan="2">
			<?php echo FSSCF::FieldOutput($field,$this->fieldvalues,array('ticketid' => $this->ticket['id'], 'userid' => $this->userid, 'ticket' => $this->ticket)); ?>
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
