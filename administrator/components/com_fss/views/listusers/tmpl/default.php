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
<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=listusers' );?>" method="post" name="adminForm" id="adminForm">
<style>
table.adminlist td
{
	padding-top:0px;
	padding-bottom:0px;
	/*padding:0px;*/
}
</style>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo JViewLegacy::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_( 'Filter by title or enter article ID' );?>"/>
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('gid').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $this->lists['gid']; ?>
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
				<?php echo JHTML::_('grid.sort',   'Username', 'username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'Name', 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'EMail', 'email', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'Joomla_Group', 'gid', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
		</tr>
    </thead>
<?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row =& $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::x( 'index.php?option=com_fss&controller=listuser&task=adduser&tmpl=component&cid[]='. $row->id . '&groupid=' . JRequest::getVar('groupid') );


?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>





			<td>
			    <a href='<?php echo $link; ?>'>	<?php echo $row->username; ?></a>			</td>






			<td>
			    <?php echo $row->name; ?>			</td>






			<td>
			    <?php echo $row->email; ?>			</td>






			<td>
			    <?php echo $row->lf1; ?>			</td>


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
<div style="height:6px;"></div>

<div class="button2-left"><div class="blank"><a href='#' id="addlink" onclick='document.adminForm.task.value="adduser";document.adminForm.submit();return false;'><?php echo JText::_('ADD_USERS_TO_GROUP'); ?></a></div></div>
<div class="button2-left"><div class="blank"><a href='#' onclick='parent.SqueezeBox.close(); return false;'><?php echo JText::_('CANCEL'); ?></a></div></div>

<div style="clear:both;"></div>
<input type="hidden" name="tmpl" value="component" />
<input type="hidden" name="groupid" value="<?php echo JRequest::getVar('groupid'); ?>" />
<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="listuser" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
