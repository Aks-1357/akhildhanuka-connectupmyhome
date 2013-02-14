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

function GetArtPermText($id)
{
	switch ($id)
	{
		case 1:
			return JText::_("AUTHOR");
		case 2:
			return JText::_("EDITOR");
		case 3:
			return JText::_("PUBLISHER");
		default:
			return JText::_('ART_NONE');	
	}	
}
?>
<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=fusers' );?>" method="post" name="adminForm" id="adminForm">
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
                <?php echo JHTML::_('grid.sort',   'Username', 'username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
<!--##NOT_TEST_START##-->
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Article Perm', 'artperm', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Own_Only', 'seeownonly', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'No_Auto', 'autoassignexc', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Prods', 'allprods', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Depts', 'alldepts', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Cats', 'allcats', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
<!--##NOT_TEST_END##-->
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'MODERATOR', 'mod_kb', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
<!--##NOT_TEST_START##-->
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Support', 'support', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>	
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'GROUPS', 'groups', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>	
			</th>
<!--##NOT_TEST_END##-->
		</tr>
    </thead>
    <?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row =& $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::x( 'index.php?option=com_fss&controller=fuser&task=edit&cid[]='. $row->id );

		if ($row->mod_kb)
		{
			$kb_img = "tick";
		} else {
			$kb_img = "cross";
		}
		
		if ($row->mod_test)
		{
			$test_img = "tick";
		} else {
			$test_img = "cross";
		}
		
		if ($row->support)
		{
			$supp_img = "tick";
		} else {
			$supp_img = "cross";
		}
    	
    	if ($row->seeownonly)
    	{
    		$own_img = "tick";
    	} else {
    		$own_img = "cross";
    	}
    	
    	if ($row->autoassignexc)
    	{
    		$auto_img = "tick";
    	} else {
    		$auto_img = "cross";
    	}
    	
    	if ($row->groups)
    	{
    		$group_img = "tick";
    	} else {
    		$group_img = "cross";
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
			    <a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
			</td>
<!--##NOT_TEST_START##-->
			<td align='center'>
				<?php echo GetArtPermText($row->artperm); ?>
			</td>
			<td align='center'>
				<img src='<?php echo JURI::base(); ?>/components/com_fss/assets/<?php echo $own_img; ?>.png' width='16' height='16' />
			</td>
			<td align='center'>
				<img src='<?php echo JURI::base(); ?>/components/com_fss/assets/<?php echo $auto_img; ?>.png' width='16' height='16' />
			</td>
			<td align='center'>
				<?php if ($row->allprods) { ?>
					<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = FSSRoute::x('?option=com_fss&tmpl=component&controller=fuser&view=fuser&task=prods&user_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
			</td>
			<td align='center'>
				<?php if ($row->alldepts) { ?>
					<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = FSSRoute::x('?option=com_fss&tmpl=component&controller=fuser&view=fuser&task=depts&user_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
			</td>
			<td align='center'>
				<?php if ($row->allcats) { ?>
					<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = FSSRoute::x('?option=com_fss&tmpl=component&controller=fuser&view=fuser&task=cats&user_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
			</td>
<!--##NOT_TEST_END##-->
			<td align='center'>
				<img src='<?php echo JURI::base(); ?>/components/com_fss/assets/<?php echo $kb_img; ?>.png' width='16' height='16' />
			</td>
<!--##NOT_TEST_START##-->
			<td align='center'>
				<img src='<?php echo JURI::base(); ?>/components/com_fss/assets/<?php echo $supp_img; ?>.png' width='16' height='16' />
			</td>
			<td align='center'>
				<img src='<?php echo JURI::base(); ?>/components/com_fss/assets/<?php echo $group_img; ?>.png' width='16' height='16' />
			</td>
<!--##NOT_TEST_END##-->
		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>
	<tfoot>
		<tr>
			<td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>

    </table>
</div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="fuser" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

