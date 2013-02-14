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
<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=ticketemails' );?>" method="post" name="adminForm" id="adminForm">
<?php if (!$this->imap_ok): ?>
	<?php JError::raiseWarning( 100, 'Your server currently does not have mod_imap enabled in your php configuration. Email account checking will NOT work without this enabled (even for POP3 accounts)' ); ?>
	<?php JError::raiseWarning( 100, 'For more information on this requirement <a href="http://freestyle-joomla.com/help/freestyle-support?kbartid=89" target="_blank">Click Here</a>' ); ?>
	<?php JError::raiseWarning( 100, 'Your php.ini file is currently located at ' . $this->ini_location ); ?>
	
<?php endif; ?>
<div><?php echo JText::sprintf('CRON_MSG', JURI::root() . 'index.php?option=com_fss&view=cron'); ?></div>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo JViewLegacy::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_( 'Filter by title or enter article ID' );?>"/>
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();this.form.getElementById('prod_id').value='0';this.form.getElementById('ispublished').value='-1';"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
	</table>
	
    <table class="adminlist table table-striped">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20" class="title">
   				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->data ); ?>);" />
			</th>
            <th class="title"   >
				<?php echo JText::_('VALIDATE_ACCOUNT'); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'ACCOUNT_NAME', 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'SERVER_ADDRESS', 'server', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'Username', 'username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th class="title"   >
				<?php echo JText::_('Destination'); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Published', 'a.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
		</tr>
    </thead>
<?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
		$row =& $this->data[$i];
        /*echo "<pre>";
		print_r($row);
		echo "</pre>";*/
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::x( 'index.php?option=com_fss&controller=ticketemail&task=edit&cid[]='. $row->id );

    	$img = 'tick.png';
		$alt = JText::_( 'Published' );

		if ($row->published == 0)
		{
			$img = 'cross.png';
			$alt = JText::_( 'Unpublished' );
		}

		$type_values = array(
			'pop3' => 'POP3',
			'imap' => 'IMAP',
			);
			
		$newticketsfrom_values = array(
			'registered' => 'Registered Users Only',
			'everyone' => 'Everyone',
			);
?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
			<td>
   				<?php echo $checked; ?>
</td>

<td>
   				<a href='#' onclick='return validateAccount(<?php echo $row->id; ?>);'><?php echo JText::_('VALIDATE_ACCOUNT'); ?></a> | 
   				<a href='#' onclick='return runCronNow(<?php echo $row->id; ?>,<?php echo $row->cronid; ?>);'><?php echo JText::_('Run Cron Now'); ?></a>
				<div id="validate_result_<?php echo $row->id; ?>"></div>
			</td>

			<td>
			    <a href='<?php echo $link; ?>'>	<?php echo $row->name; ?></a>			</td>






			<td>
			    <?php echo strtoupper($row->type); ?>, <?php echo $row->server; ?>:<?php echo $row->port;?>	<?php if ($row->usessl) echo "SSL"; ?> <?php if ($row->usetls) echo "TLS"; ?>		
			</td>

			<td>
			    <?php echo $row->username; ?>			
			</td>
			<td>
			    <?php 
					$out = array();
			    if ($row->prod_id) $out[] = "<strong>".JText::_('PRODUCT').": </strong> " . $row->lf1; 
			    if ($row->dept_id) $out[] = "<strong>".JText::_('DEPARTMENT').": </strong> " . $row->lf2; 
			    if ($row->cat_id) $out[] = "<strong>".JText::_('CATEGORY').": </strong> " . $row->lf3; 
			    if ($row->pri_id) $out[] = "<strong>".JText::_('PRIORITY').": </strong> " . $row->lf4; 
			    if ($row->handler) $out[] = "<strong>".JText::_('HANDLER').": </strong> " . $row->lf5; 
					if (count($out) > 0) echo implode(", ", $out);
					?>			
			</td>


         	<td align="center">
				<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->published ? 'unpublish' : 'publish' ?>')">
					<img src="<?php echo JURI::base(); ?>/components/com_fss/assets/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
				</a>
			</td>
		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>
	<tfoot>
		<tr>
			<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>

    </table>
</div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="ticketemail" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

<script>
function validateAccount(id)
{
	$('validate_result_'+id).innerHTML = "<?php echo JText::_('PLEASE_WAIT'); ?>";
	var url = "<?php echo FSSRoute::x('index.php?option=com_fss&view=ticketemails',false);?>&test=" + id;
<?php if (FSS_Helper::Is16()): ?>
	$('validate_result_' + id).load(url);
<?php else: ?>
	new Ajax(url, {
		method: 'get',
		update: $('validate_result_' + id)
	}).request();
<?php endif; ?>

	return false;
}

function runCronNow(id, cronid)
{
	$('validate_result_'+id).innerHTML = "<?php echo JText::_('PLEASE_WAIT'); ?>";
	var url = "<?php echo FSSRoute::x(JURI::root( true ) . '/index.php?option=com_fss&view=cron',false);?>&test=" + cronid;
<?php if (FSS_Helper::Is16()): ?>
	$('validate_result_' + id).load(url);
<?php else: ?>
	new Ajax(url, {
		method: 'get',
		update: $('validate_result_' + id)
	}).request();
<?php endif; ?>
}
</script>