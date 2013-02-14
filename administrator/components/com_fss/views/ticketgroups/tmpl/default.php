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
<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=ticketgroups' );?>" method="post" name="adminForm" id="adminForm">
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
                <?php echo JHTML::_('grid.sort',   'Name', 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Description', 'description', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'MEMBER_COUNT', 'cnt', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'ALL_EMAIL', 'allemail', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'ALL_SEE', 'allsee', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="1%" class="title" nowrap="nowrap">
                <?php echo JText::_("PRODUCTS"); ?>
            </th>
		</tr>
    </thead>
    <?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row =& $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::x( 'index.php?option=com_fss&controller=ticketgroup&task=edit&cid[]='. $row->id );
	
		$allemail = FSS_GetYesNoText($row->allemail);
		if ($row->allsee == 0)
    	{
    		$allsee = JText::_('VIEW_NONE');//"None";	
    	} elseif ($row->allsee == 1)
    	{
    		$allsee = JText::_('VIEW');//"See all tickets";	
    	} elseif ($row->allsee == 2)
    	{
    		$allsee = JText::_('VIEW_REPLY');//"Reply to all tickets";	
    	} elseif ($row->allsee == 3)
    	{
    		$allsee = JText::_('VIEW_REPLY_CLOSE');//"Reply to all tickets";	
    	}
	
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>
			<td>
			    <a href="<?php echo $link; ?>"><?php echo $row->groupname; ?></a>
			</td>
			<td>
			    <?php echo $row->description; ?>
			</td>
			<td>
				<a href="<?php echo FSSRoute::x("index.php?option=com_fss&view=members&groupid={$row->id}"); ?>" style="position:relative;top:-3px;" title="Edit Members">
				<img src="<?php echo JURI::root( true ); ?>/administrator/components/com_fss/assets/members.png" width="16" height="16" style="position:relative;top:3px;">	
				<?php 
				if ($row->cnt == 0)
				{
					echo JText::_("NO_MEMBERS"); 
				} else if ($row->cnt == 1) {
			    	echo JText::sprintf("X_MEMBER",$row->cnt); 
				} else {
			    	echo JText::sprintf("X_MEMBERS",$row->cnt); 
				}				
			    ?>
				</a>
			</td>
			<td>
				<?php echo $allemail; ?>
			</td>
			<td>
				<?php echo $allsee; ?>
			</td>
	            <td align='center'>
				<?php if ($row->allprods) { ?>
					<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = FSSRoute::x('index.php?option=com_fss&tmpl=component&controller=ticketgroup&view=ticketgroup&task=prods&groupid_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
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
<input type="hidden" name="controller" value="ticketgroup" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

