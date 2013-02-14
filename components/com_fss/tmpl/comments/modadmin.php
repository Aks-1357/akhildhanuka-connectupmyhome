<div class="fss_spacer"></div>
<?php echo FSS_Helper::PageSubTitle('MODERATE'); ?>

<div class="fss_spacer"></div>
<?php echo JText::_('Comments:'); ?> <?php echo $this->whatcomm; ?> &nbsp; &nbsp; &nbsp;
<?php //##NOT_TEST_START## ?>
<?php echo JText::_('Section'); ?>: <?php echo $this->identselect; ?> &nbsp; &nbsp; &nbsp;
<?php //##NOT_TEST_END## ?>
<button onclick='fss_moderate_refresh(); return false;'><?php echo JText::_('REFRESH'); ?></button>

<div id="fss_moderate">
	<?php include $this->tmplpath . DS .'modadmin_inner.php' ?>	
</div>
 
<?php $this->IncludeJS() ?>
