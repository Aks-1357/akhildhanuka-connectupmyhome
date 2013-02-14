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
<?php

// No direct access

defined('_JEXEC') or die('Restricted access'); ?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"EDIT_GROUP"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php';
//include "components/com_fss/views/admin/snippet/_tabbar.php" ?>

<style>
.tcontent
{
	text-align: left;
}
</style>

<form action="<?php echo FSSRoute::x('index.php?option=com_fss&view=groups&what=savegroup',false);?>" method="post" name="groupForm" id="groupForm">
	<input type="hidden" name="groupid" value="<?php echo $this->group->id; ?>" />
	<input type="hidden" name="what" value="savegroup" />
	<div class="fss_content_toolbar">
		<div class="fss_content_toolbar_title">
			<?php echo  FSS_Helper::PageSubTitle('GROUP_DETAILS'); ?>
		</div>
<?php if (!$this->creating && $this->permission['groups'] == 1): ?>
		<div class="fss_content_toolbar_item" id="fss_form_delete">
			<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/delete.png" /><br />
			<span><?php echo JText::_('DELETE');?></span>
		</div>
<?php endif; ?>
<?php if (!$this->creating): ?>
		<div class="fss_content_toolbar_item" id="fss_form_cancel">
			<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/cancel.png" /><br />
			<span><?php echo JText::_('CLOSE');?></span>
		</div>
		<div class="fss_content_toolbar_item" id="fss_form_saveclose">
			<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/save.png" /><br />
			<span><?php echo JText::_('SAVE_CLOSE');?></span>
		</div>
<?php else: ?>
		<div class="fss_content_toolbar_item" id="fss_form_cancel">
			<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/cancel.png" /><br />
			<span><?php echo JText::_('CANCEL');?></span>
		</div>
<?php endif; ?>
		<div class="fss_content_toolbar_item" id='fss_form_save'>
			<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/save2.png" /><br />
			<span><?php echo JText::_('SAVE');?></span>
		</div>
	</div>

		<table class="fss_table">
		<tr>
			<td width="90" align="right" class="key">
				<label for="title">
					<?php echo JText::_("NAME"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="groupname" id="groupname" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->group->groupname);?>" />
			</td>
		</tr>
		<tr>
			<td width="90" align="right" class="key">
				<label for="title">
					<?php echo JText::_("DESCRIPTION"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="description" id="description" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->group->description);?>" />
			</td>
		</tr>
		<tr>
			<td width="90" align="right" class="key">
				<label for="description">
					<?php echo JText::_("ALL_SEE"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->allsee; ?><br />
					<?php echo JText::_("ALL_SEE_HELP"); ?>
            </td>
		</tr>
		<tr>
			<td width="90" align="right" class="key">
				<label for="description">
					<?php echo JText::_("ALL_EMAIL"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='allemail' value='1' <?php if ($this->group->allemail) { echo " checked='yes' "; } ?>><br />
					<?php echo JText::_("ALL_EMAIL_HELP"); ?>
            </td>
		</tr>
		<tr>
			<td width="90" align="right" class="key">
				<label for="description">
					<?php echo JText::_("CCEXCLUDE"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='ccexclude' value='1' <?php if ($this->group->ccexclude) { echo " checked='yes' "; } ?>><br />
					<?php echo JText::_("CCEXCLUDE_HELP"); ?>
            </td>
		</tr>
		<tr>
			<td width="90" align="right" class="key" valign="top">
				<label for="eh">
					<?php echo JText::_("PRODUCTS"); ?>:
				</label>
			</td>
			<td>
				<div>
					<?php echo JText::_("SHOW_ALL_PRODUCTS_ON_TICKET_OPEN"); ?>
					<?php echo $this->allprod; ?>
				</div>
				<div id="prodlist" <?php if ($this->allprods) echo 'style="display:none;"'; ?>>
					<?php echo $this->products; ?>
				</div>
			</td>
		</tr>
	</table>
	
	<div class="fss_spacer"></div>
</form>
	
<?php if (!$this->creating): ?>
	<div class="fss_content_toolbar">
		<div class="fss_content_toolbar_title">
			<?php echo  FSS_Helper::PageSubTitle('MEMBERS'); ?>
		</div>
		<div class="fss_content_toolbar_item" id="fss_form_remove">
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/removeuser.png" /><br />
				<span><?php echo JText::_('REMOVE'); ?></span>
		</div>
		<div class="fss_content_toolbar_item" id='fss_form_add'>
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/adduser.png" /><br />
				<span><?php echo JText::_('ADD'); ?></span>
		</div>
	</div>

<form action="<?php echo FSSRoute::x('index.php?option=com_fss&view=groups&groupid=' . $this->group->id,false);?>" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="what" value="" />
    <table class="fss_table">
    <thead>

        <tr>
            <th width="20">
			</th>
            <th>
				<?php echo FSS_Helper::sort('User', 'username', @$this->order_Dir, @$this->order ); ?>
            </th>
            <th>
 				<?php echo FSS_Helper::sort('ISADMIN', 'isadmin', @$this->order_Dir, @$this->order ); ?>
			</th>
            <th>
 				<?php echo FSS_Helper::sort('ALL_EMAIL_USER', 'allemail', @$this->order_Dir, @$this->order ); ?>
			</th>
            <th>
				<?php echo FSS_Helper::sort('ALL_SEE_USER', 'allsee', @$this->order_Dir, @$this->order ); ?>
			</th>
		</tr>
    </thead>
    <?php
    if (count($this->groupmembers) == 0)
    {
    ?>
		<tbody>
			<tr>
				<td colspan="5"><?php echo JText::_('NO_USERS'); ?></td>
			</tr>
		</tbody>
	<?php	
    }
    $k = 0;
    for ($i=0, $n=count( $this->groupmembers ); $i < $n; $i++)
	{
		$row =& $this->groupmembers[$i];
    	$checked    = JHTML::_( 'grid.id', $i, $row->user_id );
    	
		$user = JFactory::getUser();
		$userid = $user->id;
	
		$allemail = FSS_Helper::GetYesNoText($row->allemail);
    	$isadmin = FSS_Helper::GetYesNoText($row->isadmin);
    	
	
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
				<?php if ($userid != $row->user_id || $this->permission['groups'] == 1): ?>
   					<?php echo $checked; ?>
				<?php endif; ?>
			</td>
			<td>
			    <?php echo $row->name; ?> (<?php echo $row->username; ?>)<br /><?php echo $row->email; ?>
			<td>
				<?php if ($userid != $row->user_id || $this->permission['groups'] == 1): ?>
					<a href='#' id='is_admin_<?php echo $row->user_id; ?>' class='is_admin'>
						<?php echo $isadmin; ?>
					</a>
				<?php else: ?>	
					<img src="<?php echo JURI::base(); ?>/components/com_fss/assets/images/tickgray.png" width="16" height="16" border="0" />
				<?php endif; ?>
			</td>
			<td>
				<a href='#' id='all_email_<?php echo $row->user_id; ?>' class='all_email'>
					<?php echo $allemail; ?>
				</a>
			</td>
			<td>
				<div style="float:right">
					<a href='#' class="edit_perm" id="editperm_<?php echo $row->user_id; ?>">
						<img src="<?php echo JURI::base(); ?>/components/com_fss/assets/images/edit.png" width="18" height="18" title="<?php echo JText::_('EDIT'); ?>" alt="<?php echo JText::_('EDIT'); ?>"/>
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

	<input type="hidden" name="limit_start" id="limitstart" value="<?php echo $this->limit_start; ?>">
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->order_Dir; ?>" />
</form>
<?php else: ?>
	<?php echo  FSS_Helper::PageSubTitle('MEMBERS'); ?>
	<?php echo FSS_Helper::PageSubTitle2("PLEASE_SAVE_FIRST"); ?>
<?php endif; ?>


<div id="popup_html" style="display:none;">

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

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>
function DoAllProdChange()
{
	var form = document.groupForm;
	var prodlist = document.getElementById('prodlist');
		
	if (form.allprods[1].checked)
    {
		prodlist.style.display = 'none';
	} else {
		prodlist.style.display = 'inline';
	}
}


jQuery(document).ready(function () {
	jQuery('.fss_content_toolbar_item').mouseenter(function () {
		jQuery(this).css('background-color', '<?php echo FSS_Settings::get('css_hl'); ?>');
	});
	
	jQuery('.fss_content_toolbar_item').mouseleave(function () {
		jQuery(this).css('background-color' ,'white');
	});

	jQuery('#fss_form_save').click(function (ev) {
		ev.preventDefault();
		document.groupForm.submit();
	});
	jQuery('#fss_form_saveclose').click(function (ev) {
		ev.preventDefault();
		document.groupForm.what.value = 'saveclose';
		document.groupForm.submit();
	});

	jQuery('#fss_form_remove').click(function (ev) {
		ev.preventDefault();
		if (document.adminForm.boxchecked.value == 0)
		{
			alert("<?php echo JText::_('MUST_SELECT'); ?>");
		} else {		
			document.adminForm.what.value = 'removemembers';
			document.adminForm.submit();
		}
	});

	jQuery('#fss_form_add').click(function (ev) {
		ev.preventDefault();
		TINY.box.show({iframe:'<?php echo FSSRoute::x('index.php?option=com_fss&view=groups&tmpl=component&what=pickuser&groupid='. $this->group->id); ?>', width:630, height:440 });
	});
	
	jQuery('#fss_form_cancel').click( function () {
		window.location = '<?php echo FSSRoute::x('index.php?option=com_fss&view=groups', false); ?>';
	});
	
	jQuery('#fss_form_delete').click( function () {
		if (confirm('<?php echo JText::_('CONFIRM_DELETE'); ?>'))
		{
			document.groupForm.what.value = "deletegroup";
			document.groupForm.submit();
		}
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
		var url = '<?php echo FSSRoute::x('index.php?option=com_fss&view=groups&what=toggleallemail&userid=XXUIDXX&groupid=' . $this->group->id, false); ?>';
		url = url.replace('XXUIDXX', id);
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
		var url = '<?php echo FSSRoute::x('index.php?option=com_fss&view=groups&what=toggleadmin&userid=XXUIDXX&groupid=' . $this->group->id, false); ?>';
		url = url.replace('XXUIDXX',id);
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
				var url = '<?php echo FSSRoute::x('index.php?option=com_fss&view=groups&what=setperm&userid=XXUIDXX&perm=XXPXX&groupid=' . $this->group->id, false); ?>';
				url = url.replace('XXUIDXX', id);
				url = url.replace('XXPXX', newid);
				
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

	jQuery('.pagenav').each(function () {
		jQuery(this).attr('href','#');
		jQuery(this).click(function (ev) {
			ev.preventDefault();
			jQuery('#limitstart').val(jQuery(this).attr('limit'));
			document.adminForm.submit( );
		});
	});

});

function ChangePageCount(perpage)
{
	document.adminForm.submit( );
}

</script>