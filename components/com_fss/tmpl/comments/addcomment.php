<?php if ($this->can_add): ?>

<script type="text/javascript">
 var RecaptchaOptions = {
    theme : '<?php echo FSS_Settings::get('recaptcha_theme'); ?>'
 };
</script>

<div class='fss_kb_comment_add' id='add_comment'>
<?php if ($this->comments_hide_add): ?>
	<a id="commentaddbutton" href='#' onclick='return false;' class='fss_kb_comment_add_text'><?php echo $this->add_a_comment; ?></a>
	<div id="commentadd" style="display:none;">
	
<script>
	jQuery(document).ready(function () {
		jQuery('#commentaddbutton').click( function (ev) {
			ev.preventDefault();
			jQuery('#commentadd').css('display','block');
			jQuery('#commentaddbutton').css('display','none');
		});
	});
</script>

<?php endif; ?>

	<?php echo FSS_Helper::PageSubTitle2($this->add_a_comment); ?>
	<form id='addcommentform' action="<?php echo FSSRoute::x( '&tmpl=component&task=commentpost' );?>" method="post">
	<input type='hidden' name='comment' value='add' >
	<input type='hidden' name='ident' value='<?php echo  $this->ident ?>' >
	<?php if ($this->itemid): ?>
	<input type='hidden' name='itemid' value='<?php echo  $this->itemid ?>' >
	<?php endif; ?>
	<table class="fsj_comment_table">
	<?php if ($this->show_item_select) : ?>
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
			<?php if (!$this->_permissions['mod_kb'] && $this->loggedin) : ?>
			<?php echo $this->post['name'] ?><input name='name' type='hidden' id='comment_name' value='<?php echo FSS_Helper::escape($this->post['name']) ?>' >
			<?php else: ?>
			<input name='name' id='comment_name' value='<?php echo FSS_Helper::escape($this->post['name']) ?>' >
			<?php endif; ?>
			</td>
			<?php if ($this->errors['name']): ?><td class='fss_must_have_field'><?php echo $this->errors['name'] ?></td><?php endif; ?>
		</tr>
	<?php if ($this->use_email && !($this->loggedin)): ?>
		<tr>
			<th><?php echo JText::_('EMail'); ?>&nbsp;</th>
			<td><input name='email' value='<?php echo FSS_Helper::escape($this->post['email']) ?>'></td>
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
			<td><input name='website' value='<?php echo FSS_Helper::escape($this->post['website']) ?>'></td>
			<?php if ($this->errors['website']): ?><td class='fss_must_have_field'><?php echo $this->errors['website'] ?></td><?php endif; ?>
		</tr>
	<?php endif; ?>

	<?php foreach ($this->customfields as $custfield): ?>
		<tr>
			<th><?php echo $custfield['description']; ?>&nbsp;</th>
			<td><?php echo FSSCF::FieldInput($custfield, $this->errors,'comments') ?></td>
		</tr>
	<?php endforeach; ?>

	<?php if ($this->errors['body']): ?><tr><td></td><td class='fss_must_have_field'><?php echo $this->errors['body'] ?></td></tr><?php endif; ?>
		<tr>
			<th><?php echo JText::_('COMMENT_BODY'); ?>&nbsp;</th>
			<td colspan=2><textarea name='body' rows='5' cols='40' id='comment_body'><?php echo FSS_Helper::escape($this->post['body']) ?></textarea></td>
		</tr>
		
		<?php if ($this->captcha) : ?>
		<?php if ($this->errors['captcha']): ?><tr><td></td><td class='fss_must_have_field'><?php echo $this->errors['captcha'] ?></td></tr><?php endif; ?>
		<tr>
			<th><?php echo JText::_('Verification'); ?></th>
			<td colspan=2 id='captcha_cont'><?php echo $this->captcha ?></td>
		</tr>	
		<?php endif; ?>	
		<tr>
			<td></td>
			<td>
				<input type=submit value=' <?php echo $this->post_comment ?> ' id='addcomment'>
			</td>
		</tr>
	</table>
	</form>
	<?php if ($this->comments_hide_add): ?>
	</div>
<?php endif; ?>
</div>
<?php endif; ?>