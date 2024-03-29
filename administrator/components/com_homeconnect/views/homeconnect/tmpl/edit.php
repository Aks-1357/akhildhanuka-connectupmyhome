<?php
/**
 * @version     1.0.0
 * @package     com_homeconnect
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      akshay <akshay1357agarwal@gmail.com> - http://
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_homeconnect/assets/css/homeconnect.css');

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'homeconnect.cancel' || document.formvalidator.isValid(document.id('homeconnect-form')))
		{
			Joomla.submitform(task, document.getElementById('homeconnect-form'));
		}
		else
		{
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_homeconnect&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="homeconnect-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_HOMECONNECT_LEGEND_HOMECONNECT'); ?></legend>
			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('id'); ?>
					<?php echo $this->form->getInput('id'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('category'); ?>
					<?php echo $this->form->getInput('category'); ?>
				</li>

				<li>
					<?php echo $this->form->getLabel('category_description'); ?>
					<?php echo $this->form->getInput('category_description'); ?>
				</li>

				<li>
					<?php echo $this->form->getLabel('created_by'); ?>
					<?php echo $this->form->getInput('created_by'); ?>
				</li>
			</ul>
		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>

<style type="text/css">
	/* Temporary fix for drifting editor fields */
	.adminformlist li
	{
		clear: both;
	}
</style>