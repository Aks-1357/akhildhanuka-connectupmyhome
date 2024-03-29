<?php if ($this->can_add): ?>
<div class="fss_edit_comment"><?php echo JText::_('EDIT_COMMENT'); ?></div>
<script type="text/javascript">
 var RecaptchaOptions = {
    theme : '<?php echo FSS_Settings::get('recaptcha_theme'); ?>'
 };
</script>

	<form id='editcommentform' action="<?php echo FSSRoute::x( '&tmpl=component&task=savecomment' );?>" method="post">
	<input type='hidden' name='comment' value='add' >
	<input type='hidden' name='ident' value='<?php echo  $this->ident ?>' >
	<?php if ($this->itemid): ?>
	<input type='hidden' name='itemid' value='<?php echo  $this->itemid ?>' >
	<?php endif; ?>
	<?php if ($this->commentid > 0): ?>
	<input type='hidden' name='commentid' value='<?php echo  $this->commentid ?>' >
	<?php endif; ?>
	<table class="fsj_comment_table">
	<?php if ($this->show_item_select && $this->handler) : ?>
		<tr>
			<th><?php echo $this->handler->email_article_type; ?>&nbsp;</th>
			<td>
				<?php echo $this->GetItemSelect(); ?>
			</td>
			<?php if ($this->errors['itemid']): ?><td class='fss_must_have_field'><?php echo $this->errors['itemid'] ?></td><?php endif; ?>
		</tr>
	<?php endif; ?>
		<tr>
			<th><?php echo JText::_('Name'); ?>&nbsp;</th>
		<td>
			<input name='name' id='comment_name' value='<?php echo FSS_Helper::escape($this->comment['name']) ?>' >
		</td>
		<?php if ($this->errors['name']): ?><td class='fss_must_have_field'><?php echo $this->errors['name'] ?></td><?php endif; ?>
		</tr>
	<?php if ($this->use_email): ?>
		<tr>
			<th><?php echo JText::_('EMail'); ?>&nbsp;</th>
			<td><input name='email' value='<?php echo FSS_Helper::escape($this->comment['email']) ?>'></td>
			<td>
			<?php if ($this->errors['email']): ?>
				<div class='fss_must_have_field'><?php echo $this->errors['email'] ?></div>
			<?php else: ?>
				(<?php echo JText::_('WILL_NOT_BE_PUBLISHED'); ?>)
			<?php endif; ?>
			</td>
		</tr>
	<?php endif; ?>
	<?php if ($this->use_website): ?>
		<tr>
			<th><?php echo JText::_('Website'); ?>&nbsp;</th>
			<td><input name='website' value='<?php echo FSS_Helper::escape($this->comment['website']) ?>'></td>
			<?php if ($this->errors['website']): ?><td class='fss_must_have_field'><?php echo $this->errors['website'] ?></td><?php endif; ?>
		</tr>
	<?php endif; ?>

	<?php foreach ($this->customfields as $custfield): ?>
		<tr>
			<th><?php echo $custfield['description']; ?>&nbsp;</th>
			<td><?php echo FSSCF::FieldInput($custfield, $this->errors,'comments'); ?></td>
		</tr>
	<?php endforeach; ?>

	<?php if ($this->errors['body']): ?><tr><td></td><td class='fss_must_have_field'><?php echo $this->errors['body'] ?></td></tr><?php endif; ?>
		<tr>
			<th><?php echo JText::_('COMMENT_BODY'); ?>&nbsp;</th>
			<td colspan=2><textarea name='body' rows='5' cols='40' id='comment_body'><?php echo FSS_Helper::escape($this->comment['body']) ?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type=submit value='<?php echo JText::_('SAVE_COMMENT'); ?>' id='addcomment'>
				<input type=submit value='<?php echo JText::_('CANCEL'); ?>' id='canceledit' commentid='<?php echo $this->commentid; ?>'>
			</td>
		</tr>
	</table>
	</form>
<?php endif; ?>