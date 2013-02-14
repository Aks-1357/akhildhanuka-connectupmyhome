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
<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=members' );?>" method="post" name="adminForm" id="adminForm">
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
				<?php echo $this->lists['groupid']; ?>
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
            <th>
                <?php echo JHTML::_('grid.sort',   'Name', 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'EMAIL', 'email', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'ISADMIN', 'isadmin', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'ALL_EMAIL_USER', 'allemail', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'ALL_SEE_USER', 'allsee', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
		</tr>
    </thead>
    <?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
	 $row =& $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->user_id );
        
    	$allemail = FSS_GetYesNoText($row->allemail);
    	$isadmin = FSS_GetYesNoText($row->isadmin);
		
    	if ($row->allsee == 0)
    	{
    		$allsee = JText::_('INHERITED');//"None";	
    		$allsee .= " (";
    		$perm = $this->group->allsee;
    		if ($perm == 0)
    		{
    			$allsee .= JText::_('VIEW_NONE');//"None";	
    		} elseif ($perm == 1)
    		{
    			$allsee .= JText::_('VIEW');//"See all tickets";	
    		} elseif ($perm == 2)
    		{
    			$allsee .= JText::_('VIEW_REPLY');//"Reply to all tickets";	
    		} elseif ($perm == 3)
    		{
    			$allsee .= JText::_('VIEW_REPLY_CLOSE');//"Reply to all tickets";	
    		}
    		$allsee .= ")";
    	} elseif ($row->allsee == -1)
    	{
    		$allsee = JText::_('VIEW_NONE');//"See all tickets";	
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
                <?php echo $row->user_id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>
			<td>
			    <?php echo $row->username; ?>
			</td>
			<td>
			    <?php echo $row->name; ?>
			</td>
			<td>
			    <?php echo $row->email; ?>
			</td>
			<td>
				<a href='#' id='is_admin_<?php echo $row->user_id; ?>' class='is_admin'>
					<?php echo $isadmin; ?>
				</a>
			</td>
			<td>
				<a href='#' id='all_email_<?php echo $row->user_id; ?>' class='all_email'>
					<?php echo $allemail; ?>
				</a>
			</td>
			<td>
					<div style="float:right">
						<a href='#' class="edit_perm" id="editperm_<?php echo $row->user_id; ?>">
							<img src="<?php echo JURI::base(); ?>/components/com_fss/assets/edit.png" width="18" height="18" title="<?php echo JText::_('EDIT'); ?>" alt="<?php echo JText::_('EDIT'); ?>"/>
						</a>
					</div>
				<div id="perm_<?php echo $row->user_id; ?>"><?php echo $allsee; ?></div>
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
<input type="hidden" name="controller" value="member" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
<div id="popup_html" style="display: none;">

<div class="fss_gp_title"><?php echo JText::_("CHOOSE_NEW_PERM"); ?></div>
<div class="fss_gp_help"><?php echo JText::_('CHOOSE_NEW_PERM_HELP'); ?></div>
<div class="fss_gp_default"><?php echo JText::_('GROUP_DEFAULT'); ?>: 
	<?php 
	$perm = $this->group->allsee;
	if ($perm == 0)
	{
		echo JText::_('VIEW_NONE');//"None";	
	} elseif ($perm == 1)
	{
		echo JText::_('VIEW');//"See all tickets";	
	} elseif ($perm == 2)
	{
		echo JText::_('VIEW_REPLY');//"Reply to all tickets";	
	} elseif ($perm == 3)
	{
		echo JText::_('VIEW_REPLY_CLOSE');//"Reply to all tickets";	
	}
	?></div>
<div class="fss_gp_item"><a href="#" id="popup_perm_0" class="popup_perm"><?php echo JText::_('INHERITED'); ?></a></div>
<div class="fss_gp_item"><a href="#" id="popup_perm_-1" class="popup_perm"><?php echo JText::_('VIEW_NONE'); ?></a></div>
<div class="fss_gp_item"><a href="#" id="popup_perm_1" class="popup_perm"><?php echo JText::_('VIEW'); ?></a></div>
<div class="fss_gp_item"><a href="#" id="popup_perm_2" class="popup_perm"><?php echo JText::_('VIEW_REPLY'); ?></a></div>
<div class="fss_gp_item"><a href="#" id="popup_perm_3" class="popup_perm"><?php echo JText::_('VIEW_REPLY_CLOSE'); ?></a></div>
</div>
<script>

jQuery(document).ready(function () {
	jQuery('#toolbar-new a').unbind('click');
	jQuery('#toolbar-new a').attr('onclick','');
	jQuery('#toolbar-new a').click(function (ev) {
		ev.preventDefault();
		ev.stopPropagation();
		var url ='<?php echo 'index.php?option=com_fss&view=listusers&tmpl=component&groupid='. $this->groupid; ?>';
		TINY.box.show({iframe:url, width:630,height:440});
	});

	jQuery('#toolbar-new button').unbind('click');
	jQuery('#toolbar-new button').attr('onclick','');
	jQuery('#toolbar-new button').click(function (ev) {
		ev.preventDefault();
		ev.stopPropagation();
		var url ='<?php echo 'index.php?option=com_fss&view=listusers&tmpl=component&groupid='. $this->groupid; ?>';
		TINY.box.show({iframe:url, width:630,height:440});
	});

	var permtext = new Object();
	
    permtext['0'] = '<?php $allsee = JText::_('INHERITED');//"None";	
    $allsee .= " (";
    $perm = $this->group->allsee;
    if ($perm == 0)
    {
    	$allsee .= JText::_('VIEW_NONE');//"None";	
    } elseif ($perm == 1)
    {
    	$allsee .= JText::_('VIEW');//"See all tickets";	
    } elseif ($perm == 2)
    {
    	$allsee .= JText::_('VIEW_REPLY');//"Reply to all tickets";	
    } elseif ($perm == 3)
    {
    	$allsee .= JText::_('VIEW_REPLY_CLOSE');//"Reply to all tickets";	
    }
    $allsee .= ")";
    echo $allsee; ?>';
			
    permtext['-1'] = '<?php echo JText::_('VIEW_NONE'); ?>';
    permtext['1'] = '<?php echo JText::_('VIEW'); ?>';
    permtext['2'] = '<?php echo JText::_('VIEW_REPLY'); ?>';
    permtext['3'] = '<?php echo JText::_('VIEW_REPLY_CLOSE'); ?>';	

	var boxhtml = jQuery('#popup_html').html();
	jQuery('#popup_html').remove();
	
	jQuery('.all_email').click(function (ev) {
		ev.preventDefault();
		var id = jQuery(this).attr('id').split('_')[2];
		var url = '<?php echo FSSRoute::x('index.php?option=com_fss&view=members&task=toggleallemail&groupid=' . $this->group->id, false); ?>&userid=' + id;
		var t = this;
		jQuery(t).html('<?php echo JText::_('PLEASE_WAIT'); ?>');
		jQuery.ajax({
			url: url,
			context: document.body,
			success: function(result){
				jQuery(t).html(result);
			}
		});
	});
	
	jQuery('.is_admin').click(function (ev) {
		ev.preventDefault();
		var id = jQuery(this).attr('id').split('_')[2];
		var url = '<?php echo FSSRoute::x('index.php?option=com_fss&view=members&task=toggleadmin&groupid=' . $this->group->id, false); ?>&userid=' + id;
		var t = this;
		jQuery(t).html('<?php echo JText::_('PLEASE_WAIT'); ?>');
		jQuery.ajax({
			url: url,
			context: document.body,
			success: function(result){
				jQuery(t).html(result);
			}
		});
	});
	
    jQuery('.edit_perm').click(function (ev) {
		ev.preventDefault();
		
		var id = jQuery(this).attr('id').split('_')[1];
				
		TINY.box.show({html:boxhtml,animate:false, openjs: function () {
			jQuery('.popup_perm').click( function (ev) {
				ev.preventDefault();
				var newid = jQuery(this).attr('id').split('_')[2];
				var text = permtext[newid];
				var url = '<?php echo FSSRoute::x('index.php?option=com_fss&view=members&task=setperm&groupid=' . $this->group->id, false); ?>&userid=' + id + '&perm=' + newid;
				jQuery('#perm_' + id).html('<?php echo JText::_('PLEASE_WAIT'); ?>');
				jQuery.ajax({
					url: url,
					context: document.body,
					success: function(result){
						if (result == "1")
						{
							jQuery('#perm_' + id).html(text);
						} else {
							alert("Error changing permission");
						}
					}
				});
				
				TINY.box.hide();
			});
		}});
		
	});
});

</script>
