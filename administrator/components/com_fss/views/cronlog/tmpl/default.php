<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=cronlog' );?>" method="post" name="adminForm" id="adminForm">
<table>
	<tr>
		<td>Task: <?php echo $this->tasks; ?></td>
		<td>Date: <?php echo $this->dates; ?> </td>
		<td><button onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button></td>
		<td><button onclick="this.form.getElementById('taskname').value='';this.form.getElementById('date').value='';this.form.submit();"><?php echo JText::_("RESET"); ?></button></td>
		<td><button onclick="return ClearCron();">Clear Cron Log</button></td>
		<input type="hidden" name="task" value="" id="task">
		<input type="hidden" name="page" value="<?php echo $this->page; ?>" id="page">
	</tr>
</table>
<?php if (count($this->rows) == 0): ?>
	<div>No log data found</div>
<?php else :?>
		<table>
			<tr>
				<th width="100" align="left">Task</th>
				<th width="180" align="left">When</th>
			</tr>
		</table>
<?php foreach ($this->rows as $row): ?>
	<div class="accordion_toggler_1" style="cursor:pointer;">
		<table>
			<tr>
				<td width="100"><?php echo $row->cron ?></td>
				<td width="180"><?php echo FSS_Helper::Date($row->when,FSS_DATETIME_MYSQL); ?></td>
			</tr>
		</table>
	</div>
	<div class="accordion_content_1" style='padding-left: 20px;padding-right: 20px;'><div style='border: 1px solid #DDDDDD; padding:2px;'><?php echo $row->log; ?></div></div>
<?php endforeach; ?>

	<?php if ($this->pagecount > 1) : ?>
	<div style='padding: 10px;'>
		Page : 
		<?php for ($i = 0; $i < $this->pagecount; $i++) : ?>
			<?php if ($i == $this->page) :?>
				<b><?php echo ($i+1); ?></b>
			<?php else: ?>
				<a href="#" onclick='SetPage(<?php echo $i; ?>); return false;'><?php echo ($i+1); ?></a>
			<?php endif; ?>
		<?php endfor; ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
<script>

function ClearCron()
{
	if (confirm('Are you sure? This cannot be undone.'))
	{
		document.getElementById('task').value='clear';
		return true;
	}

	return false;
}

function SetPage(page)
{
	document.getElementById('page').value=page;
	document.getElementById('adminForm').submit();
}


<?php $scrollf = FSS_Helper::Is16() ? "start" : "scrollTo"; ?>

window.addEvent('domready', function() {
	
	if(window.ie6) var heightValue='100%';
	else var heightValue='';
	
	var togglerName='div.accordion_toggler_';
	var contentName='div.accordion_content_';
	
	var acc_elem = null;
	var acc_toggle = null;
	
	var counter=1;	
	var toggler=$$(togglerName+counter);
	var content=$$(contentName+counter);
	
	while(toggler.length>0)
	{
		// Accordion anwenden
<?php if (FSSJ3Helper::IsJ3()): ?>
		new Fx.Accordion(toggler, content, {
<?php else: ?>
		new Accordion(toggler, content, {
<?php endif; ?>
		opacity: false,
		alwaysHide: true,
		display: -1,
		onActive: function(toggler, content) {
				acc_elem = content;
				acc_toggle = toggler;
			},
			onBackground: function(toggler, content) {
			},
			onComplete: function(){
				var element=$(this.elements[this.previous]);
				if(element && element.offsetHeight>0) element.setStyle('height', heightValue);			

				if (!acc_elem)
					return;

				var  scroll =  new Fx.Scroll(window,  { 
					wait: false, 
					duration: 250, 
					transition: Fx.Transitions.Quad.easeInOut
				}); 
			
				var window_top = window.pageYOffset;
				var window_bottom = window_top + window.innerHeight;
				var elem_top = acc_toggle.getPosition().y;
				var elem_bottom = elem_top + acc_elem.offsetHeight + acc_toggle.offsetHeight;

				// is element off the top of the displayed windows??
				if (elem_top < window_top)
				{
					scroll.<?php echo $scrollf; ?>(window.pageXOffset,acc_toggle.getPosition().y);
				} else if (elem_bottom > window_bottom)
				{
					var howmuch = elem_bottom - window_bottom;
					if (elem_top - howmuch > 0)
					{
						scroll.<?php echo $scrollf; ?>(window.pageXOffset,window_top + howmuch + 22);				
					} else {
						scroll.<?php echo $scrollf; ?>(window.pageXOffset,acc_toggle.getPosition().y);
					}
				}
			}
		});
		
		counter++;
		toggler=$$(togglerName+counter);
		content=$$(contentName+counter);
	}
});

</script>