<?php
defined('_JEXEC') or die;
?>

<?php
$jsPath =  JURI::base()."js/";
$document =& JFactory::getDocument();
$config_object = new JConfig();
$document->addStyleSheet(JURI::base() . 'includes/assets/css/jquery/ui-lightness/jquery-ui.css');
$document->addStyleSheet(JURI::base() . 'includes/assets/css/jquery/ui-lightness/jquery.ui.theme.css');
?>

<script src="<?php echo $jsPath; ?>jquery/jquery-ui-1.9.2.custom.min.js"></script>

<script src="<?php echo $jsPath; ?>jquery/jquery.geocomplete.js"></script>

<script src="<?php echo $jsPath; ?>jquery/jquery.cluetip.js"></script>

<script src="<?php echo $jsPath; ?>connect_controller.js"></script>
<script src="<?php echo $jsPath; ?>connect_model.js"></script>
<script src="<?php echo $jsPath; ?>connect_view.js"></script>

<script src="<?php echo $jsPath; ?>logger.js"></script>

<script src="<?php echo $jsPath; ?>jquery/jquery.bxslider.js"></script>

<!-- landing div -->
<?php
echo $this->loadTemplate('landing');

$trackdata = array( 'Adress'	=> $this->userAddress,
					'Email'		=> $this->userEmail,
					'decidedto'	=> JRequest::getVar('bundle_type') );

$app = JFactory::getApplication();
?>
<!-- landing div -->

<div id="before-load" style="display: block;background: none repeat scroll 0 0 #FFFFFF;border: 1px solid #D3D2D2;border-radius: 5px 5px 5px 5px;float: left;margin-bottom: 16px;min-height: 600px;padding: 10px 0;width: 978px;">
	<span class='loading_circle' style="left: 410px;"></span>
	<span class='loading_text' style="left: 410px;"></span>
</div>

<!-- main accordian page -->
<div id="main_accordian_div" style="display: none;">
	<h3 class="main_accordian_title" id="main_accordian_title">
		<strong><label id="main_category_div_address"><?php echo $this->userAddress; ?></label></strong>
		&nbsp;&nbsp;&nbsp;
		<strong>
			<span>Avaiable in Your Area</span>
			<img src="<?php echo JURI::base().'templates/'.$app->getTemplate().'/images/chat.jpg';?>" />
			<img src="<?php echo JURI::base().'templates/'.$app->getTemplate().'/images/wifi.jpg';?>" />
		</strong>

        <span>
        	<strong>
        		NBN
        	</strong>&nbsp;&nbsp;Available on : <?php echo date('d / m / Y'); ?>
        </span>
	</h3>

	<div id="left">
		<!-- main category div -->
		<?php
		if($trackdata['decidedto'] == "create")
		{
			echo $this->loadTemplate('main_category');
		}
		?>
		<!-- main category div -->

		<!-- confirmation div -->
		<?php
			if($trackdata['decidedto'] == "create")
			{
				echo $this->loadTemplate('confirmation');
			}
		?>
		<!-- confirmation div -->

		<!-- details div -->
		<?php
			echo $this->loadTemplate('details');
		?>
		<!-- details div -->

		<!-- recommendation div -->
		<?php
			if($trackdata['decidedto'] == "recommend")
			{
				echo $this->loadTemplate('recommendation');
			}
		?>
		<!-- recommendation div -->
		
		<!-- installation div -->
		<?php
			 echo $this->loadTemplate('installation');
		?>
		<!-- installation div -->
	</div>

	<!-- right_sidebar div -->
	<?php
		echo $this->loadTemplate('right_sidebar');
	?>
	<!-- right_sidebar div -->

	<!-- popup div -->
	<?php
		echo $this->loadTemplate('popup');
	?>
	<!-- popup div -->
</div>
<!-- main accordian page -->


<!-- thank_you page -->
<?php
//	echo $this->loadTemplate('thank_you');
?>
<!-- thank_you page -->

<?php
	$closeImg = JURI::base() . 'templates/' . $app->getTemplate() . '/images/close.png';
?>

<script>
	var trackobj = JSON.parse('<?php echo json_encode($trackdata) ?>');
	var controllerObj = new Controller();
	controllerObj.init(trackobj, <?php echo json_encode($this->categories); ?>, '<?php echo $closeImg; ?>','<?php echo $config_object->api_url?>');
	<?php
	if (JRequest::getVar('bundle_type') == 'recommend')
	{ ?>
		controllerObj.initDragNDrop();
	<?php } ?>
	controllerObj.loadrecommendedtoallproduct(<?php echo json_encode($this->recommended); ?>);
</script>