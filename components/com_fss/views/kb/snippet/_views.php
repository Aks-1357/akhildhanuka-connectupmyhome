<?php if (FSS_Settings::get( 'kb_show_views' )) : ?>
<?php $what = JRequest::getVar('what','','','string'); ?>
<div class='fss_kb_views'><?php echo JText::_('VIEW');?> : 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<?php if ($what != "") : ?>
<a href='<?php echo FSSRoute::x( '&what=' ); ?>'>
	<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/allkb.png" width="16" height="16" class="fss_kb_view_image">
	<?php echo JText::_("ALL_ARTICLES"); ?>
</a>
&nbsp;&nbsp;
<?php endif; ?>

<?php if (FSS_Settings::get( 'kb_show_recent' ) && $what != "recent") : ?>
<a href='<?php echo FSSRoute::x( '&what=recent' ); ?>'>
	<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/mostrecent-small.png" width="16" height="16" class="fss_kb_view_image">
	<?php echo JText::_("MOST_RECENT"); ?>
</a>
&nbsp;&nbsp;
<?php endif; ?>

<?php if (FSS_Settings::get( 'kb_show_viewed' ) && $what != "viewed") : ?>
<a href='<?php echo FSSRoute::x( '&what=viewed' ); ?>'>
	<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/mostviewed-small.png" width="16" height="16" class="fss_kb_view_image">
	<?php echo JText::_("MOST_VIEWED"); ?>
</a>
&nbsp;&nbsp;
<?php endif; ?>

<?php if (FSS_Settings::get( 'kb_show_rated' ) && $what != "rated") : ?>
<a href='<?php echo FSSRoute::x( '&what=rated' ); ?>'>
	<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/highestrated_small.png" width="16" height="16" class="fss_kb_view_image">
	<?php echo JText::_("HIGHEST_RATED"); ?>
</a>
<?php endif; ?>

</div>
<?php endif; ?>
