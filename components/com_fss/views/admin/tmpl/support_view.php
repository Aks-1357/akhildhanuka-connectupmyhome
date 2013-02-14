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
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"VIEW_SUPPORT_TICKET"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php';
//include "components/com_fss/views/admin/snippet/_tabbar.php" ?>
<?php if ($this->permission['support']): ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_supportbar.php';
//include "components/com_fss/views/admin/snippet/_supportbar.php" ?>

<?php if ($this->locked): ?>
<div class="fss_locked_warn"><?php echo JText::sprintf("TICKET_LOCKED_INFO",$this->co_user->name, $this->co_user->email); ?></div>
<?php endif; ?>

<div style='float:right; font-size: 120%;text-align:right;position:relative;'>
	<div><a href='#' id='print_ticket_link'><?php echo JText::_("Print"); ?> <img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/print.png' style='position: relative; top:3px;'></a></div>
	<div id='print_ticket' style='right: -3px;top: -3px;display:none;position:absolute;padding:4px;margin-top: 25px;border: 1px solid <?php echo FSS_Settings::get('css_bo'); ?>'>
		<div style='margin:2px;white-space: nowrap;'><a href='<?php echo FSSRoute::x('index.php?option=com_fss&view=admin&layout=support&what=print&tmpl=component&ticketid=' . $this->ticket['id']); ?>' target='_new' onclick="return doPrint(this);"><?php echo JText::_('ALL_DETAILS'); ?></a></div>
		<div style='margin:2px;white-space: nowrap;'><a href='<?php echo FSSRoute::x('index.php?option=com_fss&view=admin&layout=support&what=print&tmpl=component&clean=1&ticketid=' . $this->ticket['id']); ?>' taget='_blank' onclick="return doPrint(this);"><?php echo JText::_('NO_PRIVATE_MESSAGES'); ?></a></div>		
	</div>
</div>
<script>

jQuery(document).ready( function () {
	jQuery('#print_ticket_link').mouseenter( function () {
		jQuery('#print_ticket').show();
	});	
	jQuery('#print_ticket').mouseleave( function () {
		jQuery('#print_ticket').hide();
	});
	jQuery('#print_ticket_link').click (function (ev) {
		ev.preventDefault();
	});
});

function doPrint(link)
{
	jQuery('#print_ticket').hide();
	printWindow = window.open(jQuery(link).attr('href')); 
	return false;
}
 
</script>
<?php echo FSS_Helper::PageSubTitle("TICKET_DETAILS"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_ticket_info.php'; ?>

<?php if (!$this->locked): ?>
<?php echo FSS_Helper::PageSubTitle("EDIT_PROPERTIES"); ?>
<form id='newticket' action="<?php echo FSSRoute::x( '&what=' ); ?>" method="post">
<table  class='fss_table' cellspacing=0 cellpadding=0>
<tr>
	<th width=25%><?php echo JText::_("STATUS"); ?></th>
	<td width=25%>
	<select id='new_status' name='new_status'>
		<?php FSS_Helper::Tr($this->statuss); ?>
		<?php foreach ($this->statuss as $status): ?>
			<option value='<?php echo $status['id']; ?>' style='color: <?php echo $status['color']; ?>' <?php if ($status['id'] == $this->ticket['sid']) echo "selected='selected'"; ?>><?php echo $status['title']; ?></option>
		<?php endforeach; ?>
</select>
</td>

<?php if (FSS_Settings::get('support_hide_priority') != 1) : ?>	<th width=25%><?php echo JText::_("PRIORITY"); ?></th>
	<td width=25%>
	<select id='new_pri' name='new_pri'>
		<?php FSS_Helper::Tr($this->pris); ?>
		<?php foreach ($this->pris as $pri): ?>
			<option value='<?php echo $pri['id']; ?>' style='color: <?php echo $pri['color']; ?>' <?php if ($pri['id'] == $this->ticket['pid']) echo "selected='selected'"; ?>><?php echo $pri['title']; ?></option>
		<?php endforeach; ?>
	</select>
	</td>
<?php endif; ?>
</tr>
<tr>
<td colspan=4 align=center>
<input type='submit' class='button' value='<?php echo JText::_('UPDATE');?>'>
</td>
</tr>
</table>
<input type="hidden" name="what" value="statuschange">
</form>
<?php endif; // end EDIT_PROPERTIES ?>

<?php echo FSS_Helper::PageSubTitle("MESSAGES"); ?>

<table width=100%>
<tr>
<?php if (FSS_Settings::get('support_actions_as_buttons')) : ?>

<?php if (!$this->locked): ?>
	<td valign="middle" nowrap>
		<button class="button button_link" href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=reply&ticketid=' . $this->ticket['id'] ); ?>'>
			<?php echo JText::_("POST_REPLY"); ?>
		</button>
	</td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<?php endif; ?>
	<td valign="middle" nowrap>
		<button class="button button_link" href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=reply&forward=3&ticketid=' . $this->ticket['id'] ); ?>'>
			<?php echo JText::_("ADD_PRIVATE_COMMENT"); ?>
		</button>
	</td>
	<td width=100%>
		
	</td>
	<td valign="middle" nowrap>
		<button class="button"  onclick='toggleAudit();return false;'>
			<?php echo JText::_("AUDIT_LOG"); ?>
		</button>
	</td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td valign="middle" nowrap>
		<button class="button button_link" href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=&ticketid=' . $this->ticket['id'] ); ?>'>
			<?php echo JText::_("REFRESH"); ?>
		</button>
	</td>
	
<script>
jQuery(document).ready(function () {
	jQuery('.button_link').click(function (ev) {
		ev.preventDefault();
		var url = jQuery(this).attr('href');
		window.location = url;
	});
});
</script>
<?php else: ?>

<?php if (!$this->locked): ?>
	<td valign="middle" nowrap>
		<a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=reply&ticketid=' . $this->ticket['id'] ); ?>'>
			<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/reply.png'>
		</a>
	</td>
	<td valign="middle" nowrap>
		<a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=reply&ticketid=' . $this->ticket['id'] ); ?>'><?php echo JText::_("POST_REPLY"); ?></a>
	</td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<?php endif; ?>
	<td valign="middle" nowrap>
		<a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=reply&forward=3&ticketid=' . $this->ticket['id'] ); ?>'>
			<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/comment.png'>
		</a>
	</td>
	<td valign="middle" nowrap>
		<a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=reply&forward=3&ticketid=' . $this->ticket['id'] ); ?>'><?php echo JText::_("ADD_PRIVATE_COMMENT"); ?></a>
	</td>
	<td width=100%>
		
	</td>
	<td valign="middle" nowrap>
		<a href='#' onclick='toggleAudit();return false;'>
			<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/audit.png'>
		</a>
	</td>
	<td valign="middle" nowrap>
		<a href='#' onclick='toggleAudit();return false;'><?php echo JText::_("AUDIT_LOG"); ?></a>
	</td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td valign="middle" nowrap>
		<a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=&ticketid=' . $this->ticket['id'] ); ?>'>
			<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/refresh.png'>
		</a>
	</td>
	<td valign="middle" nowrap>
		<a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=&ticketid=' . $this->ticket['id'] ); ?>'><?php echo JText::_("REFRESH"); ?></a>
	</td>
<?php endif; ?>
</tr>
</table>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_messages.php';
//include "components/com_fss/views/admin/snippet/_messages.php" ?>

<?php if (count($this->attach) > 0) : ?>

<?php echo FSS_Helper::PageSubTitle("ATTACHEMNTS"); ?>

<table class='fss_ticket_attach' width='100%' cellspacing=0 cellpadding=4>

<?php foreach ($this->attach as $attach) : ?>
<?php
	$info = pathinfo($attach['filename']);
	$image = false;
	$images = array('jpg','jpeg','png','gif');
	if (in_array(strtolower($info['extension']), $images))
	{
		$image = true;	
	}
?>

	<tr>
	<td rowspan="2" class="fss_ticket_attach_size">
<?php if ($image): ?>
		<a class="modal" rel="{handler: 'image'}" href="<?php echo JRoute::_('index.php?option=com_fss&view=admin&layout=support&fileid=' . $attach['id']); ?>">
			<img src="<?php echo JRoute::_('index.php?option=com_fss&view=admin&what=attach_thumb&layout=support&fileid=' . $attach['id']); ?>" width="48" height="48">
		</a>
<?php endif; ?>
	</td>
	<td class='fss_ticket_attach_file' valign="middle" width=26>
		<a href='<?php echo FSSRoute::x( '&fileid=' . $attach['id'] ); ?>'><img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/download-24x24.png'></a>
	</td>
	<td class='fss_ticket_attach_filename' valign="middle" width=60%>
		<a href='<?php echo FSSRoute::x( '&fileid=' . $attach['id'] ); ?>'><?php echo $attach['filename']; ?></a>
	</td>
	<td class='fss_ticket_attach_size' align=right valign="middle">	
		<?php echo FSS_Helper::display_filesize($attach['size']); ?>
	</td>
</tr>
<tr>
	<td colspan=2 class='fss_ticket_attach_user' width='60%'>
		<?php echo JText::_("UPLOADED_BY"); ?> <?php echo $attach['name']; ?>
	</td>
	<td class='fss_ticket_attach_date' width='40%' align=right>	
		<?php echo FSS_Helper::Date($attach['added'], FSS_DATETIME_MID); ?>
	</td>
</tr>

<?php endforeach; ?>
</table>
<?php endif; ?>

<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
<script>

var edit_id = null;
var edit_title = null;
var edit_body = null;
var edit_ok = null;
var edit_cancel = null;
var edit_orig_message = null;
var edit_orig_title = null;
var edit_button = null;

jQuery(document).ready(function () {
    jQuery('.editmessage').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		
		if (edit_id != null)
		{
			if (confirm("Do you want to save the current edit?"))
			{
				SaveEdit();
			} else {
				CancelEdit();
			}			
		}
		
		var elem = this;
		edit_id = jQuery(elem).attr('id').replace('edit_','');
		
		// need to replace #subject_id with title edit
		edit_orig_title = jQuery('#subject_' + edit_id).text();
		var input = jQuery('<input>');
		input.attr('id','input_title_' + edit_id);
		input.attr('name','input_title_' + edit_id);
		input.val(edit_orig_title);
		var titlewidth = jQuery('#subject_' + edit_id).parent().outerWidth() - 70;
		input.css('width', titlewidth + 'px');
		
		jQuery('#subject_' + edit_id).html("");
		jQuery('#subject_' + edit_id).append(input);
		edit_title = input;
		
		// need to replace #message_id with message edit
		edit_orig_message = jQuery('#message_raw_' + edit_id).html();
		
		var input = jQuery('<textarea>');
		input.attr('id','input_title_' + edit_id);
		input.attr('name','input_title_' + edit_id);
		input.html(edit_orig_message);
		var textareawidth = jQuery('#message_' + edit_id).parent().outerWidth() - 18;
		var textareaheight = jQuery('#message_' + edit_id).parent().outerHeight() + 24;
		input.css('width', textareawidth + 'px');
		input.css('height', textareaheight + 'px');
		edit_body = input;
		
		jQuery('#message_' + edit_id).html("");
		jQuery('#message_' + edit_id).append(input);

		// need to add a ok and cancel element in place of elem
		jQuery(elem).parent().css('width','60px');
		jQuery(elem).parent().attr('width','60');
			
		edit_ok = jQuery('<a id="okbtn_' + edit_id + '" style="margin-right: 4px"><img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/save_16.png" alt="OK"/></a>');
		edit_cancel = jQuery('<a id="cancelbtn_' + edit_id + '"><img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/cancel_16.png" alt="Cancel"/></a>');
		jQuery(elem).parent().append(edit_ok);
		jQuery(elem).parent().append(edit_cancel);
		jQuery(elem).css('display','none');
		edit_button = jQuery(elem);
		
		jQuery(edit_ok).click(function () { SaveEdit(); });
		jQuery(edit_cancel).click(function () { CancelEdit(); });
		// add ok and cancel events to buttons
		
		return false;
	});


	$(document.body).addEvent('click', function(){
		if ($('fss_taglist') && $('fss_taglist').style.display == 'block')
		{
			hideTags();
			return false;
		}
		return true;
	});

	jQuery('#fss_show_taglist').click( function(e) {
		e.preventDefault();
		e.stopPropagation();
		if ($('fss_taglist')) $('fss_taglist').style.display = 'block';
		//alert("CLICK£");
	});

	jQuery('#new_tag').click( function(e) {
		e.preventDefault();
		e.stopPropagation();
	});

});

function SaveEdit()
{
	if (edit_id == null)
		retrrn;
		
	var title = edit_title.val();
	var body = edit_body.val();
	
	var url = '<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=savecomment&messageid=XXMIDXX&subject=XXSXX&body=XXBXX' ); ?>';
	url = url.replace('XXMIDXX', encodeURIComponent(edit_id));
	url = url.replace('XXSXX', encodeURIComponent(title));
	url = url.replace('XXBXX', encodeURIComponent(body));

	jQuery.ajax({
	  url: url
	});	

	ReBuildMessage(edit_id, title, body)
		
	edit_id = null;
}

function CancelEdit()
{
	if (edit_id == null)
		retrrn;
		
	ReBuildMessage(edit_id, edit_orig_title, edit_orig_message)
	
	edit_id = null;
}

function ReBuildMessage(id, title, body)
{
	jQuery(edit_button).css('display','inline');
	jQuery(edit_ok).remove();
	jQuery(edit_cancel).remove();
		
	jQuery(edit_button).parent().css('width','40px');
	jQuery(edit_button).parent().attr('width','40');
	
	body = body.replace(/</g,"&lt;");
	body = body.replace(/>/g,"&gt;");
	body = body.replace(/&amp;/g,"&");
	body = body.replace(/\n/g,"<br />");

	jQuery('#subject_' + id).html(title);
	jQuery('#message_' + id).html(body);
}

function removetag(tagname)
{
	$('tags').innerHTML = "<?php echo JText::_('PLEASE_WAIT'); ?>";
	var url = '<?php echo FSSRoute::x("&what=removetag&tmpl=component&tag=", false); ?>&tag=' + encodeURIComponent(tagname);
	jQuery('#tags').load(url);
	return false;
}

function addtag(tagname)
{
	if (tagname == "")
		return;
	$('tags').innerHTML = "<?php echo JText::_('PLEASE_WAIT'); ?>";
	var url = '<?php echo FSSRoute::x("&what=addtag&tmpl=component&tag=", false); ?>&tag=' + encodeURIComponent(tagname);
	//jQuery('#tags').html(url);
	jQuery('#tags').load(url);
	return false;
}

function hideTags()
{
	if ($('fss_taglist')) $('fss_taglist').style.display = 'none';	
}
<?php if (FSS_Settings::get('support_lock_time') > 0 && !$this->locked): ?>
function lockTicket()
{
	var url = '<?php echo FSSRoute::x("&what=lockticket&tmpl=component"); ?>';
<?php if (FSS_Helper::Is16()): ?>
	$('lock_ticket').load(url);
<?php else: ?>
	new Ajax(url, {
		method: 'get',
		update: $('lock_ticket')
	}).request();
<?php endif; ?>
	setTimeout ( 'lockTicket()', <?php echo FSS_Settings::get('support_lock_time') < 10 ? 10000 : (FSS_Settings::get('support_lock_time')-10)*1000 ; ?> );
}

window.addEvent( 'domready', function( e )
{
	setTimeout ( 'lockTicket()', <?php echo FSS_Settings::get('support_lock_time') < 10 ? 10000 : (FSS_Settings::get('support_lock_time')-10)*1000 ; ?> );
});
<?php endif; ?>

var audit = 0;
function toggleAudit()
{
	if (audit == 0)
	{
		audit = 1;
		$$('.fss_support_msg_audit').each(function(el){
			el.setStyle('display', 'table-row');
		});
		$$('.fss_support_msg_normal').each(function(el){
			el.setStyle('display', 'table-row');
		});
	} else if (audit == 1)
	{
		audit = 2;
		$$('.fss_support_msg_audit').each(function(el){
			el.setStyle('display', 'table-row');
		});
		$$('.fss_support_msg_normal').each(function(el){
			el.setStyle('display', 'none');
		});
	} else {
		audit = 0;
		$$('.fss_support_msg_audit').each(function(el){
			el.setStyle('display', 'none');
		});
		$$('.fss_support_msg_normal').each(function(el){
			el.setStyle('display', 'table-row');
		});
	}
}

function editTitle()
{
	$('title_input').value = $('title_value').innerHTML;
	$('title_input').value = $('title_input').value.replace(/^\s+|\s+$/g, '');
	$('title_show').style.display = 'none';
	$('title_edit').style.display = 'block';
}

function cancelEditTitle()
{
	$('title_edit').style.display = 'none';
	$('title_show').style.display = 'block';
}

function saveTitle()
{
	$('title_value').innerHTML = "<?php echo JText::_('PLEASE_WAIT'); ?>";
	cancelEditTitle();

	var url = "<?php echo FSSRoute::x("&what=tickettitle", false); ?>&title=" + escape($('title_input').value);

	<?php if (FSS_Helper::Is16()): ?>
		$('title_value').load(url);
	<?php else: ?>
		new Ajax(url, {
			method: 'get',
			update: $('title_value')
		}).request();
	<?php endif; ?>

}

function editEMail()
{
	$('email_input').value = $('email_value').innerHTML;
	$('email_input').value = $('email_input').value.replace(/^\s+|\s+$/g, '');
	$('email_show').style.display = 'none';
	$('email_edit').style.display = 'block';
}

function cancelEditEMail()
{
	$('email_edit').style.display = 'none';
	$('email_show').style.display = 'block';
}

function saveEMail()
{
	$('email_value').innerHTML = "<?php echo JText::_('PLEASE_WAIT'); ?>";
	cancelEditEMail();

	var url = "<?php echo FSSRoute::x("&what=ticketemail", false); ?>&email=" + escape($('email_input').value);

	<?php if (FSS_Helper::Is16()): ?>
	$('email_value').load(url);
	<?php else: ?>
new Ajax(url, {
method: 'get',
update: $('email_value')
}).request();
	<?php endif; ?>

}

function editCat()
{
	$('cat_show').style.display = 'none';
	$('cat_edit').style.display = 'block';
}


function cancelEditCat()
{
	$('cat_edit').style.display = 'none';
	$('cat_show').style.display = 'block';
}

function saveCat()
{

	$('cat_value').innerHTML = "<?php echo JText::_('PLEASE_WAIT'); ?>";
	
	cancelEditCat();

	var url = "<?php echo FSSRoute::x("&what=ticketcat", false); ?>&catid=" + escape($('catid').value);

	<?php if (FSS_Helper::Is16()): ?>
		$('cat_value').load(url);
	<?php else: ?>
		new Ajax(url, {
			method: 'get',
			update: $('cat_value')
		}).request();
	<?php endif; ?>

}

</script>

<div id='lock_ticket'></div>