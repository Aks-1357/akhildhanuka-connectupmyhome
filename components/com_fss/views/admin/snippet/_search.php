<?php 
$basicstyle = ''; 
$advancedstyle = 'style="display:none;"';
$default = "basic";
if (FSS_Settings::get('support_advanced_default'))
	$default = "advanced";
$searchtype = JRequest::getVar('searchtype',$default);
if ($searchtype == "advanced")
{
	$basicstyle = $advancedstyle;
	$advancedstyle = "";
}
?>
<input type="hidden" id="searchtype" name="searchtype" value="<?php echo $searchtype ?>">
<input type="hidden" name="what" id="what" value="<?php echo JRequest::getVar('what','') ?>">

<div class="fss_admin_create_cont" id="basicsearch" <?php echo $basicstyle ?>>
<div class="fss_admin_search"><?php echo JText::_("SEARCH_TICKETS"); ?>:</div>
<div style="float:left;">
<span class="fss_admin_create_sub"><input name="search" id="basic_search" value="<?php echo JRequest::getVar('search','') ?>"></span>
<span class="fss_admin_create_sub"><input class='button' type="submit" onclick="$('what').value='search';document.adminForm.limitstart.value=0;" value="<?php echo JText::_("SEARCH") ?>"></span>
<span class="fss_admin_create_sub"><input class='button' type="submit" onclick="resetbasic(); return false;" value="<?php echo JText::_("RESET") ?>"></span>
<span class="fss_admin_create_sub"><input class='button' type="submit" onclick="showadvsearch(); return false;" value="<?php echo JText::_("ADVANCED_SEARCH") ?>"></span>
</div>
</div>
<div class="fss_clear"></div>
<div class="fss_admin_create_cont" id="advsearch" <?php echo $advancedstyle ?>>
<div class="fss_admin_search_adv"><?php echo JText::_("ADVANCED_SEARCH"); ?>:</div>

<table>
<?php
$fieldnum = 0;
$resetadvanced = "";
global $resetadvanced;

function NextField()
{
	global $fieldnum;
	$fieldnum++;

	if ($fieldnum == 1)
	{
		echo "<tr>";
		return;	
	}

	if ($fieldnum % 2 == 1)
	{
		echo "</tr><tr>";	
	}
}

function AddAdvancedReset($name)
{
	global $resetadvanced;
	$resetadvanced .= "$('$name').value = '';\n";
}
?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("SUBJECT") ?>:</td>
		<td><input name="subject" id="advanced_subject" value="<?php echo JRequest::getVar('subject','') ?>"></td>
		<?php AddAdvancedReset("advanced_subject"); ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("TICKET_REF") ?>:</td>
		<td><input name="reference" id="advanced_ref" value="<?php echo JRequest::getVar('reference','') ?>"></td>
		<?php AddAdvancedReset("advanced_ref"); ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("USER_NAME") ?>:</td>
		<td><input name="username" id="advanced_username" value="<?php echo JRequest::getVar('username','') ?>"></td>
		<?php AddAdvancedReset("advanced_username"); ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("EMAIL") ?>:</td>
		<td><input name="useremail" id="advanced_email" value="<?php echo JRequest::getVar('useremail','') ?>"></td>
		<?php AddAdvancedReset("advanced_email"); ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("NAME") ?>:</td>
		<td><input name="userfullname" id="advanced_name" value="<?php echo JRequest::getVar('userfullname','') ?>"></td>
		<?php AddAdvancedReset("advanced_name"); ?>
<?php if (!FSS_Settings::get('support_hide_handler')) : ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("HANDLER") ?>:</td>
		<td>
		    <select name="handler" id="advanced_handler">
				<option value=""><?php echo JText::_("ALL_HANDLERS") ?></option>		
				<?php $handlerid = JRequest::getVar('handler',''); ?>
				<?php foreach ($this->handlers as $handler) :?>
					<option value="<?php echo $handler['id'] ?>" <?php if ($handlerid == $handler['id']) echo " SELECTED "; ?>><?php echo $handler['name'] ?></option>
				<?php endforeach; ?>
			</select>			
			<?php AddAdvancedReset("advanced_handler"); ?>
		</td>
<?php endif; ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("MESSAGE") ?>:</td>
		<td><input name="content" id="advanced_message" value="<?php echo JRequest::getVar('content','') ?>"></td>
		<?php AddAdvancedReset("advanced_message"); ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("STATUS") ?>:</td>
		<td>
			<select name="status" id="advanced_status">
				<option value=""><?php echo JText::_("ALL_STATUSES") ?></option>		
				<?php $statusid = JRequest::getVar('status',''); ?>
				<?php FSS_Helper::Tr($this->statuss); ?>
				<?php foreach ($this->statuss as $status) :?>
					<option value="<?php echo $status['id'] ?>" <?php if ($statusid == $status['id']) echo " SELECTED "; ?> style='color: <?php echo $status['color']; ?>'><?php echo $status['title'] ?></option>
				<?php endforeach; ?>
			</select>				
		</td>
		<?php AddAdvancedReset("advanced_status"); ?>
<?php if (count($this->products) > 0): ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("PRODUCT") ?>:</td>
		<td>
			<select name="product" id="advanced_product">
				<option value=""><?php echo JText::_("ALL_PRODUCTS") ?></option>		
				<?php $productid = JRequest::getVar('product',''); ?>
				<?php FSS_Helper::Tr($this->products); ?>
				<?php foreach ($this->products as $product) :?>
					<option value="<?php echo $product['id'] ?>" <?php if ($productid == $product['id']) echo " SELECTED "; ?>><?php echo $product['title'] ?></option>
				<?php endforeach; ?>
			</select>		
		</td>
		<?php AddAdvancedReset("advanced_product"); ?>
<?php endif; ?>
<?php if (count($this->departments) > 0): ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("DEPARTMENT") ?>:</td>
		<td>
			<select name="department" id="advanced_department">
				<option value=""><?php echo JText::_("ALL_DEPARTMENTS") ?></option>		
		<?php $departmentid = JRequest::getVar('department',''); ?>
				<?php FSS_Helper::Tr($this->departments); ?>
				<?php foreach ($this->departments as $department) :?>
					<option value="<?php echo $department['id'] ?>" <?php if ($departmentid == $department['id']) echo " SELECTED "; ?>><?php echo $department['title'] ?></option>
					<?php endforeach; ?>
			</select>		
		</td>
		<?php AddAdvancedReset("advanced_department"); ?>
<?php endif; ?>
<?php if (count($this->cats) > 0): ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("CATEGORY") ?>:</td>
		<td>
			<select name="cat" id="advanced_cat">
				<option value=""><?php echo JText::_("ALL_CATEGORIES") ?></option>		
				<?php $catid = JRequest::getVar('cat',''); ?>
				<?php FSS_Helper::Tr($this->cats); ?>
				<?php foreach ($this->cats as $cat) :?>
					<option value="<?php echo $cat['id'] ?>" <?php if ($catid == $cat['id']) echo " SELECTED "; ?>><?php echo $cat['title'] ?></option>
				<?php endforeach; ?>
			</select>			
		</td>
		<?php AddAdvancedReset("advanced_cat"); ?>
<?php endif; ?>
<?php if (FSS_Settings::get('support_hide_priority') != 1) : ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("PRIORITY") ?>:</td>
		<td>
			<select name="priority" id="advanced_priority">
				<option value=""><?php echo JText::_("ALL_PRIORITIES") ?></option>		
				<?php $priorityid = JRequest::getVar('priority',''); ?>
				<?php FSS_Helper::Tr($this->priorities); ?>
				<?php foreach ($this->priorities as $priority) :?>
					<option value="<?php echo $priority['id'] ?>" <?php if ($priorityid == $priority['id']) echo " SELECTED "; ?> style='color: <?php echo $priority['color']; ?>'><?php echo $priority['title'] ?></option>
					<?php endforeach; ?>
			</select>			
		</td>
		<?php AddAdvancedReset("advanced_priority"); ?>
<?php endif; ?>
<?php if (count($this->groups) > 0): ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("GROUPS") ?>:</td>
		<td>
			<select name="group" id="advanced_group">
					<option value=""><?php echo JText::_("ALL_GROUPS") ?></option>		
				<?php $groupid = JRequest::getVar('group',''); ?>
				<?php foreach ($this->groups as $groups) :?>
					<option value="<?php echo $groups['id'] ?>" <?php if ($groupid == $groups['id']) echo " SELECTED "; ?>><?php echo $groups['groupname'] ?></option>
				<?php endforeach; ?>
			</select>			
		</td>
		<?php AddAdvancedReset("advanced_group"); ?>
<?php endif; ?>
<?php
$customfields = FSSCF::GetAllCustomFields(true);
foreach($customfields as $field): ?>
<?php if (!$field['advancedsearch']) continue; ?>
<?php NextField(); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $field['description'] ?>:</td>
		<td>
			<?php if ($field['type'] == "area" || $field['type'] == "text") : ?>
				<input name="custom_<?php echo $field['id']; ?>" id="advanced_custom_<?php echo $field['id']; ?>" value="<?php echo JRequest::getVar('custom_'.$field['id'],'') ?>">
			<?php elseif ($field['type'] == "checkbox") : ?>
				<select name="custom_<?php echo $field['id']; ?>" id="advanced_custom_<?php echo $field['id']; ?>">
					<option value=""><?php echo JText::_("ALL_VALUES") ?></option>		
					<option value="1" <?php if (JRequest::getVar('custom_'.$field['id'],'') == "1") echo "SELECTED"; ?> ><?php echo JText::_("YES") ?></option>		
					<option value="0" <?php if (JRequest::getVar('custom_'.$field['id'],'') == "0") echo "SELECTED"; ?> ><?php echo JText::_("NO") ?></option>		
				</select>			
			<?php elseif ($field['type'] == "radio" || $field['type'] == "combo") : ?>
				<select name="custom_<?php echo $field['id']; ?>" id="advanced_custom_<?php echo $field['id']; ?>">
					<option value=""><?php echo JText::_("ALL_VALUES") ?></option>		
					<?php foreach($field['values'] as $value): ?>
						<option value="<?php echo $value; ?>" <?php if (JRequest::getVar('custom_'.$field['id'],'') == $value) echo "SELECTED"; ?> ><?php echo $value; ?></option>
					<?php endforeach; ?>	
				</select>			
			<?php endif; ?>
		</td>
		<?php AddAdvancedReset("advanced_custom_".$field['id']); ?>
<?php endforeach; ?>

<?php NextField(); ?>
		<?php AddAdvancedReset("advanced_date_from"); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("DATE_FROM") ?>:</td>
		<td>
			<input name="date_from" id="advanced_date_from" value="<?php echo JRequest::getVar('date_from','') ?>" size="12">
		</td>
<?php NextField(); ?>
		<?php AddAdvancedReset("advanced_date_to"); ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_("DATE_TO") ?>:</td>
		<td>
			<input name="date_to" id="advanced_date_to" value="<?php echo JRequest::getVar('date_to','') ?>"  size="12">
		</td>

	</tr>
	<tr>
		<td colspan="4" align="center" style="padding-top:3px;">
			<span class="fss_admin_create_sub"><input class='button' type="submit" onclick="$('what').value='search';document.adminForm.limitstart.value=0;" value="<?php echo JText::_("SEARCH") ?>"></span>
			<span class="fss_admin_create_sub"><input class='button' type="submit" onclick="resetadvanced(); return false;" value="<?php echo JText::_("RESET") ?>"></span>
			<span class="fss_admin_create_sub"><input class='button' type="submit" onclick="showbasicsearch();return false;" value="<?php echo JText::_("BASIC_SEARCH") ?>"></span>
		</td>
	</tr>
</table>
</div>

<?php if (!FSS_Settings::get('support_hide_tags')) : ?>
<div class="fss_admin_create_cont">
<div class="fss_admin_tags"><?php echo JText::_("TICKET_TAGS"); ?>:</div>
<span class="fss_admin_create_sub"><input class='button' type="submit" onclick="showtagpick();return false;" value="<?php echo JText::_("TAGS") ?>"></span>
<span class="fss_admin_create_sub" id="tag_list">
<?php if (isset($this->tags)): ?>
	<?php foreach($this->tags as $tag): ?>
		<span class="fss_ticket_tag_large" id="tag_<?php echo $tag; ?>"><a href="#" onclick="removetag('<?php echo $tag; ?>');return false;"><?php echo $tag; ?><img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tag_delete.png"></a></span>
	<?php endforeach; ?>
<?php else: ?>
	<?php echo JText::_('NO_TAGS_SELECTED'); ?>
<?php endif; ?>
</span>
</div>

<div id="tags_div" name="tags_div" class="fss_tags_div">
	<div class="fss_tags_div_inner">
	<?php if ($this->alltags && count($this->alltags) > 0): ?>
	<?php foreach ($this->alltags as $tag): ?>
	<div class="fss_tags_div_tag">
			<A href='#' onclick="addtag('<?php echo $tag['tag']; ?>');return false;"><?php echo $tag['tag']; ?></a>
		</div>
	<?php endforeach; ?>
	<?php else: ?>
		<?php echo JText::_('NO_TAGS_DEFINED'); ?>
	<?php endif; ?>
	</div>
</div>

<input name="tags" id="tags" type="hidden" value="<?php echo JRequest::getVar('tags','') ?>">
<?php endif; ?>

<script> 
function showadvsearch()
{
	$('advsearch').style.display = 'block';
	$('basicsearch').style.display = 'none';
	$('searchtype').value = 'advanced';
}
function showbasicsearch()
{
	$('basicsearch').style.display = 'block';
	$('advsearch').style.display = 'none';
	$('searchtype').value = 'basic';
}
function resetadvanced()
{
	<?php echo $resetadvanced; ?>
	$('tags').value = '';
	$('adminForm').submit();
}
function resetbasic()
{
	$('basic_search').value = '';
	$('tags').value = '';
	$('adminForm').submit();	
}


var tagsshown = 0;
jQuery(document).click(function() { 
	if (tagsshown == 1)
	{
		$('tags_div').style.display = 'none';
	}
	if (tagsshown > 0)
		tagsshown = tagsshown - 1;
}); 

function showtagpick()
{
	$('tags_div').style.display = 'block';
	tagsshown = 2;
}

function addtag(tagname)
{
	tagsshown = 1;
	$('tags').value = $('tags').value + ';' + tagname;
	if ($('what').value == "")
		$('what').value = "search";
	$('adminForm').submit();
}
function removetag(tagname)
{
	$('tags').value = $('tags').value.replace(tagname,'');
	$('tags').value = $('tags').value.replace(";;","");
	$('adminForm').submit();
}

jQuery(document).ready(function () {
	myCalendar = new dhtmlXCalendarObject(["advanced_date_from", "advanced_date_to"]);
	myCalendar.setSkin("omega");
	myCalendar.hideTime();
});
</script>
<style>

</style>