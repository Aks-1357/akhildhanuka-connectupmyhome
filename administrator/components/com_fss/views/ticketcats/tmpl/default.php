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
<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=ticketcats' );?>" method="post" name="adminForm" id="adminForm">
<?php $ordering = ($this->lists['order'] == "ordering"); ?>
<?php JHTML::_('behavior.modal'); ?>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_("FILTER"); ?>:
				<input type="text" name="search" id="search" value="<?php echo JViewLegacy::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_("FILTER_BY_TITLE_OR_ENTER_ARTICLE_ID");?>"/>
				<button onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_("RESET"); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php FSSAdminHelper::LA_Filter(true); ?>
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
                <?php echo JHTML::_('grid.sort',   'Title', 'title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'List_Heading', 'section', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JText::_("PRODUCTS"); ?>
            </th>
            <th>
                <?php echo JText::_("DEPARTMENTS"); ?>
            </th>
			<?php FSSAdminHelper::LA_Header(true); ?>
		</tr>
    </thead>
    <?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row =& $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::x( 'index.php?option=com_fss&controller=ticketcat&task=edit&cid[]='. $row->id );

        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>
			<td>
			    <a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
			</td>
			<td>
			    <?php echo $row->section; ?>
			</td>
			<td>
				<?php if ($row->allprods) { ?>
					<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = FSSRoute::x('?option=com_fss&tmpl=component&controller=ticketcat&view=ticketcat&task=prods&ticket_cat_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
			</td>
			<td>
				<?php if ($row->alldepts) { ?>
					<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = FSSRoute::x('?option=com_fss&tmpl=component&controller=ticketcat&view=ticketcat&task=depts&ticket_cat_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
			</td>
			<?php FSSAdminHelper::LA_Row($row, true); ?>
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
<input type="hidden" name="controller" value="ticketcat" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

