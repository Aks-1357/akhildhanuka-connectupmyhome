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
<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=groups&tmpl=component', false );?>" method="post" name="adminForm">
<style>
table.adminlist td
{
	padding-top:0px;
	padding-bottom:0px;
	/*padding:0px;*/
}
</style>
	<table class="fss_table">
<?php if ($this->permission['groups'] == 1): ?>		
		<tr>
			<td width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="filter" value="<?php echo JViewLegacy::escape($this->search);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_( 'Filter by title or enter article ID' );?>"/>
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('filter').value='';this.form.getElementById('gid').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $this->gid; ?>
			</td>
		</tr>
<?php else: ?>
		<tr>
			<td>
				<?php echo JText::_( 'UserName' ); ?>:
</td>
<td>
				<input type="text" name="username" id="username" value="<?php echo JViewLegacy::escape($this->username);?>" class="text_area" />
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_( 'EMail' ); ?>:
</td>
<td>
				<input type="text" name="email" id="email" value="<?php echo JViewLegacy::escape($this->email);?>" class="text_area" />
			</td>
			<td>
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('username').value='';this.form.getElementById('email').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
		</tr>
<?php endif; ?>
	</table>
	
   <div class="fss_spacer"></div>
   <table class="fss_table" width="100%">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20" class="title">
   				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->users ); ?>);" />
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'Username', 'username', @$this->order_Dir, @$this->order ); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'Name', 'name', @$this->order_Dir, @$this->order ); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'EMail', 'email', @$this->order_Dir, @$this->order ); ?>
			</th>

<?php if ($this->permission['groups'] == 1): ?>	
            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'Joomla_Group', 'gid', @$this->order_Dir, @$this->order ); ?>
			</th>
<?php endif; ?>
		</tr>
    </thead>
<?php
if (count($this->users) == 0): ?>
	<tbody>
		<tr>
			<td colspan="5">
				<?php if ($this->permission['groups'] == 1): ?>	
					<?php echo JText::_('NO_USERS_FOUND'); ?>
				<?php else: ?>
					<?php echo JText::_('NO_USERS_FOUND_ENTER'); ?>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
<?php endif;
    $k = 0;
    for ($i=0, $n=count( $this->users ); $i < $n; $i++)
    {
        $row =& $this->users[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::x( 'index.php?option=com_fss&view=groups&what=adduser&cid[]='. $row->id . '&groupid=' . JRequest::getVar('groupid') );


?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>
			<td>
			    <a href='<?php echo $link; ?>'>	<?php echo $row->username; ?></a>			
			</td>
			<td>
			    <?php echo $row->name; ?>			
			</td>
			<td>
			    <?php echo $row->email; ?>			
			</td>
<?php if ($this->permission['groups'] == 1): ?>				
			<td>
			    <?php echo $row->lf1; ?>			
			</td>
<?php endif; ?>	
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

<div class="fss_spacer"></div>

<div class="button2-left"><div class="blank"><a href='#' id="addlink" onclick='document.adminForm.what.value="adduser";document.adminForm.submit();return false;'><?php echo JText::_('ADD_USERS_TO_GROUP'); ?></a></div></div>
<div class="button2-left"><div class="blank"><a href='#' onclick='parent.TINY.box.hide(); return false;'><?php echo JText::_('CANCEL'); ?></a></div></div>

<div style="clear:both;"></div>
<input type="hidden" name="groupid" value="<?php echo JRequest::getVar('groupid'); ?>" />
<input type="hidden" name="what" value="pickuser" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->order; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->order_Dir; ?>" />
<input type="hidden" name="limit_start" id="limitstart" value="<?php echo $this->limit_start; ?>">
</form>

<script>

function ChangePageCount(perpage)
{
	document.adminForm.submit( );
}
	
jQuery(document).ready(function () {
	jQuery('.pagenav').each(function () {
		jQuery(this).attr('href','#');
		jQuery(this).click(function (ev) {
			ev.preventDefault();
			jQuery('#limitstart').val(jQuery(this).attr('limit'));
			document.adminForm.submit( );
		});
	});
});

</script>