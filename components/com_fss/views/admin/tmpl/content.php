<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("SUPPORT_ADMIN","CONTENT_MANAGEMENT"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php';
	//include "components/com_fss/views/admin/snippet/_tabbar.php" ?>

<?php if ($this->permission['artperm'] > 0): ?>

	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_contentbar.php';
	//include "components/com_fss/views/admin/snippet/_contentbar.php" ?>

	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_contentinfo.php';
	//include "components/com_fss/views/admin/snippet/_contentinfo.php" ?>
<?php else: ?>

<?php endif; ?>


<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php';
//include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
