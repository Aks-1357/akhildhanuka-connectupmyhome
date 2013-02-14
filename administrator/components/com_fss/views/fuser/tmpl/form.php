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

<?php echo JHTML::_( 'form.token' ); ?>
<style>
label {
	width: auto !important;
	float: none !important;
	clear: none !important;
	display: inline !important;
}
input {
	float: none !important;
	clear: none !important;
	display: inline !important;
}
</style>
<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
                submitform( pressbutton );
                return;
        }
        submitform(pressbutton);
}
//-->

//##NOT_TEST_START##
function DoAllTypeChange()
{
	var form = document.adminForm;
	var users = document.getElementById('users');
	var groups = document.getElementById('groups');
		
	if (form.type[1].checked)
    {
		users.style.display = 'none';
		groups.style.display = 'inline';
	} else {
		users.style.display = 'inline';
		groups.style.display = 'none';
	}
}

function DoAllProdChange()
{
	var form = document.adminForm;
	var prodlist = document.getElementById('prodlist');
		
	if (form.allprods[1].checked)
    {
		prodlist.style.display = 'none';
	} else {
		prodlist.style.display = 'inline';
	}
}

function DoAllDeptChange()
{
	var form = document.adminForm;
	var deptlist = document.getElementById('deptlist');
		
	if (form.alldepts[1].checked)
    {
		deptlist.style.display = 'none';
	} else {
		deptlist.style.display = 'inline';
	}
}

function DoAllCatChange()
{
	var form = document.adminForm;
	var catlist = document.getElementById('catlist');
		
	if (form.allcats[1].checked)
    {
		catlist.style.display = 'none';
	} else {
		catlist.style.display = 'inline';
	}
}
//##NOT_TEST_END##



</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_("DETAILS"); ?></legend>

		<table class="admintable">
		<?php if ($this->showtypes) { ?>
		<tr>
			<td width="135" align="right" class="key">
				<?php echo JText::_("ENTRY_TYPE"); ?>:
			</td>
			<td>
				<?php echo $this->type; ?>
			</td>
		</tr>
		<?php } ?>		
		<tr>
			<td width="135" align="right" class="key">
					<?php echo JText::_("USER_GROUP"); ?>:
			</td>
			<td>
				<div name='users' id="users">
					<?php echo $this->users; ?>
				</div>
			</td>
		</tr>		
	</table>
	</fieldset>
</div>

<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_("COMMENT_MODERATION"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
					<?php echo JText::_("MODERATOR"); ?>:
			</td>
			<td>
				<input type='checkbox' name='mod_kb' value='1' <?php if ($this->user->mod_kb) { echo " checked='yes' "; } ?>>
            </td>
		</tr>
	</table>
	</fieldset>
</div>
<!--##NOT_TEST_START##-->

<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_("ARTICLE_CREATION"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
					<?php echo JText::_("ARTICLE_PERMISSIONS"); ?>:
			</td>
			<td>
				<?php echo $this->artperms; ?>
            </td>
			<td>
				<ul>
					<li><b>None</b>: No permissions to edit articles</li>
					<li><b>Author</b>: Can create and edit own articles. Not allowed to publish.</li>
					<li><b>Editor</b>: Can create and edit anyones articles. Not allowed to publish.</li>
					<li><b>Published</b>: Can create and edit anyones articles. Can publish articles.</li>
				</ul>
			</td>
		</tr>
	</table>
	</fieldset>
</div>

<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_("SUPPORT"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
					<?php echo JText::_("CAN_DO_SUPPORT"); ?>:
			</td>
			<td>
				<input type='checkbox' name='support' value='1' <?php if ($this->user->support) { echo " checked='yes' "; } ?>>
            </td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
					<?php echo JText::_("HIDE_OTHERS_ASSIGNED_SUPPORT"); ?>:
			</td>
			<td>
				<input type='checkbox' name='seeownonly' value='1' <?php if ($this->user->seeownonly) { echo " checked='yes' "; } ?>>
            </td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
					<?php echo JText::_("DONT_AUTOASSIGN_TICKETS"); ?>:
			</td>
			<td>
				<input type='checkbox' name='autoassignexc' value='1' <?php if ($this->user->autoassignexc) { echo " checked='yes' "; } ?>>
            </td>
		</tr>
		<tr>
		    <td width="135" align="right" class="key" valign="top">
					<?php echo JText::_("SUPPORT_FOR_WHICH_PRODUCTS"); ?>:
		    </td>
		    <td>
				<div>
					<?php echo JText::_("ALL_PRODUCTS"); ?>
					<?php echo $this->allprod; ?>
				</div>
				<div id="prodlist" <?php if ($this->allprods) echo 'style="display:none;"'; ?>>
					<?php echo $this->products; ?>
				</div>
		    </td>
	    </tr>
		<tr>
		    <td width="135" align="right" class="key" valign="top">
					<?php echo JText::_("SUPPORT_FOR_WHICH_DEPARTMENTS"); ?>:
		    </td>
		    <td>
				<div>
					<?php echo JText::_("ALL_DEPARTMENTS"); ?>
					<?php echo $this->alldept; ?>
				</div>
				<div id="deptlist" <?php if ($this->alldepts) echo 'style="display:none;"'; ?>>
					<?php echo $this->departments; ?>
				</div>
		    </td>
	    </tr>
		<tr>
		    <td width="135" align="right" class="key" valign="top">
					<?php echo JText::_("SUPPORT_FOR_WHICH_CATEGORIES"); ?>:
		    </td>
		    <td width="400">
				<div>
					<?php echo JText::_("ALL_CATEGORIES"); ?>
					<?php echo $this->allcat; ?>
				</div>
				<div id="catlist" <?php if ($this->allcats) echo 'style="display:none;"'; ?>>
					<?php echo $this->categories; ?>
				</div>
		    </td>
	    </tr>
	</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("TICKET_GROUP_ADMIN"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
					<?php echo JText::_("CAN_CHANGE_TICKET_GROUPS"); ?>:
			</td>
			<td>
				<input type='checkbox' name='groups' value='1' <?php if ($this->user->groups) { echo " checked='yes' "; } ?>>
            </td>
		</tr>
	</table>
	</fieldset>

</div>
<!--##NOT_TEST_END##-->
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->user->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="fuser" />
</form>

