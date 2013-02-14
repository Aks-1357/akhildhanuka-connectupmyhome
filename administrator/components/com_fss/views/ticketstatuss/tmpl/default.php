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
<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=ticketstatuss' );?>" method="post" name="adminForm" id="adminForm">
<?php $ordering = ($this->lists['order'] == "ordering"); ?>
<?php JHTML::_('behavior.modal'); ?>
<div id="editcell">
	<!--<table>
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
	</table>-->

    <table class="adminlist table table-striped">
    <thead>

		<tr>
			<th colspan="4"></th>
			<th colspan="5" style="border-left:1px solid #888888;border-right:1px solid #888888;">Defaults</th>
			<th colspan="5"></th>
		</tr>
        <tr>
			<th width="5">#</th>
            <th width="20">
   				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->data ); ?>);" />
			</th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Title', 'title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Color', 'color', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="5%" nowrap style="border-left:1px solid #888888;">
                <?php echo JHTML::_('grid.sort',   'NEW_TICKET', 'def_open', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="5%" nowrap>
                <?php echo JHTML::_('grid.sort',   'AFTER_USER_REPLY', 'def_user', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="5%" nowrap>
                <?php echo JHTML::_('grid.sort',   'AFTER_ADMIN_REPLY', 'def_admin', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="5%" nowrap>
                <?php echo JHTML::_('grid.sort',   'CLOSED', 'def_closed', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="5%" nowrap style="border-right:1px solid #888888;">
                <?php echo JHTML::_('grid.sort',   'ARCHIVE', 'is_archive', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="5%" nowrap>
                <?php echo JHTML::_('grid.sort',   'IS_CLOSED', 'is_closed', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="5%" nowrap>
                <?php echo JHTML::_('grid.sort',   'CAN_AUTO_CLOSE', 'can_autoclose', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="5%" nowrap>
                <?php echo JHTML::_('grid.sort',   'OWN_TAB', 'own_tab', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
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
    	$link = FSSRoute::x( 'index.php?option=com_fss&controller=ticketstatus&task=edit&cid[]='. $row->id );

    	$published = FSS_GetPublishedText($row->published);
    	$def_open = FSS_GetYesNoText($row->def_open);
    	$def_user = FSS_GetYesNoText($row->def_user);
    	$def_admin = FSS_GetYesNoText($row->def_admin);
    	$def_closed = FSS_GetYesNoText($row->def_closed);
    	$is_closed = FSS_GetYesNoText($row->is_closed);
    	$def_archive = FSS_GetYesNoText($row->def_archive);
    	$can_autoclose = FSS_GetYesNoText($row->can_autoclose);
    	$own_tab = FSS_GetYesNoText($row->own_tab);

    ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>
			<td nowrap>
			    <a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
				<?php if ($row->userdisp): ?>
					<br /><span style='color:#666666'>(User: <?php echo $row->userdisp; ?>)</span>
				<?php endif; ?>
			</td>
			<td>
			    <?php echo $row->color; ?>
			</td>
			<td align="center">
				<?php if (!$row->def_open): ?>
				<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','def_open')">
				<?php echo $def_open; ?>
				</a>
				<?php else: ?>
				<?php echo $def_open; ?>
				<?php endif; ?>
			</td>
			<td align="center">
				<?php if (!$row->def_user): ?>
				<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','def_user')">
				<?php echo $def_user; ?>
				</a>
				<?php else: ?>
				<?php echo $def_user; ?>
				<?php endif; ?>
			</td>
			<td align="center">
				<?php if (!$row->def_admin): ?>
				<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','def_admin')">
				<?php echo $def_admin; ?>
				</a>
				<?php else: ?>
				<?php echo $def_admin; ?>
				<?php endif; ?>
			</td>
			<td align="center">
				<?php if ($row->def_open || $row->def_user || $row->def_admin || $row->def_archive): ?>
					<img src="<?php echo JURI::base() ?>components/com_fss/assets/na.png" width="16" height="16" border="0" />
				<?php else: ?>
					<?php if (!$row->def_closed): ?>
					<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','def_closed')">
					<?php echo $def_closed; ?>
					</a>
					<?php else: ?>
					<?php echo $def_closed; ?>
					<?php endif; ?>
				<?php endif; ?>
			</td>
			<td align="center">
				<?php if ($row->def_open || $row->def_user || $row->def_admin || $row->def_closed): ?>
					<img src="<?php echo JURI::base() ?>components/com_fss/assets/na.png" width="16" height="16" border="0" />
				<?php else: ?>
					<?php if (!$row->def_archive): ?>
					<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','def_archive')">
					<?php echo $def_archive; ?>
					</a>
					<?php else: ?>
					<?php echo $def_archive; ?>
					<?php endif; ?>
				<?php endif; ?>
			</td>
			<td align="center">
				<?php if ($row->def_open || $row->def_user || $row->def_admin): ?>
					<img src="<?php echo JURI::base() ?>components/com_fss/assets/na.png" width="16" height="16" border="0" />
				<?php elseif ($row->def_closed || $row->def_archive): ?>
					<img src="<?php echo JURI::base() ?>components/com_fss/assets/tickgr.png" width="16" height="16" border="0" />
				<?php else: ?>
					<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->is_closed ? 'not_closed' : 'is_closed' ?>')">
					<?php echo $is_closed; ?>
					</a>
				<?php endif; ?>
			</td>
			<td align="center">
				<?php if ($row->is_closed): ?>
					<img src="<?php echo JURI::base() ?>components/com_fss/assets/na.png" width="16" height="16" border="0" />
				<?php else : ?>
					<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->can_autoclose ? 'not_autoclosed' : 'can_autoclose' ?>')">
					<?php echo $can_autoclose; ?>
					</a>
				<?php endif; ?>
			</td>
			<td align="center">
				<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->own_tab ? 'not_tab' : 'own_tab' ?>')">
				<?php echo $own_tab; ?>
				</a>
			</td>
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
			<td colspan="14"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>

    </table>
</div>

<?php  ?>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="ticketstatus" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

<? 
