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

<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
                submitform( pressbutton );
                return;
        }

        <?php
																        ?>
        submitform(pressbutton);
}
//-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'ACCOUNT_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->item->name);?>" />
			</td>
		</tr>		<tr>
			<td width="135" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'SERVER_ADDRESS' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="server" id="server" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->item->server);?>" />
			</td>
		</tr>		<tr>
			<td width="135" align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'SERVER_TYPE' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['type']; ?>
            </td>

		</tr>		<tr>
			<td width="135" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Port' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="port" id="port" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->item->port);?>" />
			</td>
		</tr>		<tr>
			<td width="135" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Username' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="username" id="username" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->item->username);?>" />
			</td>
		</tr>		<tr>
			<td width="135" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Password' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="password" name="password" id="password" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->item->password);?>" />
			</td>
		</tr>		<tr>
			<td width="135" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'CHECK_INTERVAL_IN_MINUTES' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="checkinterval" id="checkinterval" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->item->checkinterval);?>" />
			</td>
		</tr>
		
		<tr>
			<td width="135" align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'ALLOW_TICKETS_FROM' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['newticketsfrom']; ?>
            </td>

		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'Allow responses to tickets from any address' ); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='allowunknown' id='allowunknown' value='1' <?php if ($this->item->allowunknown == 1) { echo " checked='yes' "; } ?>>
            </td>

		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'After importing an email' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['onimport']; ?>
            </td>
			<td>	
				This option is always "Delete EMail" when a POP3 account is used.
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'Product' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['prod_id']; ?>
			</td>
		</tr>			<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'DEPARTMENT' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['dept_id']; ?>
			</td>
		</tr>			<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'Category' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['cat_id']; ?>
			</td>
		</tr>			<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'Priority' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['pri_id']; ?>
			</td>
		</tr>			<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'Handler' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['handler']; ?>
			</td>
		</tr>			<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'USE_SSL' ); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='usessl' id='usessl' value='1' <?php if ($this->item->usessl == 1) { echo " checked='yes' "; } ?>>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'USE_TLS' ); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='usetls' id='usetls' value='1' <?php if ($this->item->usetls == 1) { echo " checked='yes' "; } ?>>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'VALIDATE_SERVER_CERTIFICATE' ); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='validatecert' id='validatecert' value='1' <?php if ($this->item->validatecert == 1) { echo " checked='yes' "; } ?>>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'ALLOW_FROM_JOOMLA_ADDRESS' ); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='allow_joomla' id='allow_joomla' value='1' <?php if ($this->item->allow_joomla == 1) { echo " checked='yes' "; } ?>>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'LIMIT_TO_RECIEVED_ADDRESS' ); ?>:
				</label>
			</td>
			<td>
				<textarea rows="10" cols="60" name='toaddress'><?php echo $this->item->toaddress; ?></textarea>
            </td>
			<td>Leave blank to accept email sent to all addresses in this mail box<br><br>
			Put one email address per line to restrict to specific email addresses. This is used when multiple email addresses are forwarded to a single mailbox.</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'IGNORE_SENDER_ADDRESS' ); ?>:
				</label>
			</td>
			<td>
				<textarea rows="10" cols="60" name='ignoreaddress'><?php echo $this->item->ignoreaddress; ?></textarea>
            </td>
			<td>Leave blank to have no restrictions on sender address.<br><br>
			Put one email address per line to restrict to specific email addresses. You can use the * character to specify anything, ie. *@paypal.com will ignore any emails from the domain paypal.com.<br>
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>

<!---->

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="cronid" value="<?php echo $this->item->cronid; ?>" />
<input type="hidden" name="published" value="<?php echo $this->item->published; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="ticketemail" />
</form>
