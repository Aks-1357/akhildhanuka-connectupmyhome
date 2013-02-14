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
<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=fields' );?>" method="post" name="adminForm" id="adminForm">
<?php $ordering = ($this->lists['order'] == "ordering"); ?>
<?php JHTML::_('behavior.modal'); ?>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->lists['ident'];
				?>
			</td>
		</tr>
	</table>
    <table class="adminlist table table-striped">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20">
   				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->data ); ?>);" />
			</th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Description', 'description', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Section', 'ident', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Type', 'type', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Grouping', 'grouping', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Prods', 'allprods', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Depts', 'alldepts', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Search', 'basicsearch', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'ADV_SEARCH', 'advancedsearch', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'IN_LIST', 'inlist', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Per User', 'peruser', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="<?php echo FSSJ3Helper::IsJ3() ? '130px' : '8%'; ?>">
				<?php echo JHTML::_('grid.sort',   'Order', 'ordering', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				<?php if ($ordering) echo JHTML::_('grid.order',  $this->data ); ?>
			</th>
		</tr>
    </thead>
    <?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
    	$row =& $this->data[$i];
    	$checked    = JHTML::_( 'grid.id', $i, $row->id );
    	$link = FSSRoute::x( 'index.php?option=com_fss&controller=field&task=edit&cid[]='. $row->id );

    	$published = FSS_GetPublishedText($row->published);
    ?>
        <tr class="<?php echo "row$k"; ?>">
<td>
                <?php echo $row->id; ?>
</td>
<td>
   				<?php echo $checked; ?>
</td>
<td>
			    <a href="<?php echo $link; ?>"><?php echo $row->description; ?></a>
</td>
<td>
			    <?php echo $this->GetIdentLabel($row->ident); ?>
</td>
<td>
			    <?php echo $this->GetTypeLabel($row->type, $row); ?>
</td>
<?php if ($row->ident == 0): ?>
<td>
			    <?php echo $row->grouping; ?>
</td>
<td align='center'>
				<?php if ($row->allprods) { ?>
	<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = FSSRoute::x('?option=com_fss&tmpl=component&controller=field&view=field&task=prods&field_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
</td>
<td align='center'>
				<?php if ($row->alldepts) { ?>
	<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = FSSRoute::x('?option=com_fss&tmpl=component&controller=field&view=field&task=depts&field_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
</td>
<td align='center'>
	<?php 
		if ($row->type == "text" || $row->type == "combo" || $row->type == "area") 
		{ 
			echo FSS_GetYesNoText($row->basicsearch); 
		} else { 
			echo "<img src='" . JURI::base() . "/components/com_fss/assets/na.png'>";
		} ?>
</td>
<td align='center'>
	<?php echo FSS_GetYesNoText($row->advancedsearch); ?>
</td>
<td align='center'>
	<?php 
		if ($row->type != "area") 
		{ 
			echo FSS_GetYesNoText($row->inlist); 
		} else { 
			echo "<img src='" . JURI::base() . "/components/com_fss/assets/na.png'>";
		} ?>
</td>
<td align='center'>
	<?php echo FSS_GetYesNoText($row->peruser); ?>
</td>
<?php else: ?>
<td colspan="7" align="center">N/A</td>
<?php endif; ?>
<td align="center">
				<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->published ? 'unpublish' : 'publish' ?>')">
				<?php echo $published; ?>
</a>
</td>
<td class="order">
			<?php if ($ordering) : ?>
				<span><?php echo $this->pagination->orderUpIcon( $i ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n ); ?></span>
			<?php endif; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php if (!$ordering) {echo 'disabled="disabled"';} ?> class="text_area" style="text-align: center" />
</td>
</tr>
        <?php
        $k = 1 - $k;
       }
       ?>
	<tfoot>
		<tr>
			<td colspan="15"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>

    </table>
</div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="field" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

