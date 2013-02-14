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
<?php ('_JEXEC') or die('Restricted access'); ?>
<?php if (empty($catdepth)) $catdepth = 1; ?>
<div class='fss_kb_catlist' id='fss_kb_catlist'>
	<?php if (empty($this->hide_choose) && count($this->cats) > 0): ?>
		<?php echo FSS_Helper::PageSubTitle("PLEASE_CHOOSE_A_CATEGORY"); ?>
	<?php endif; ?>
	
	<?php if ($this->main_cat_colums > 1): ?>
		<?php $colwidth = floor(100 / $this->main_cat_colums) . "%"; ?>
	
		<table width='100%' cellspacing="0" cellpadding="0">
		<?php $column = 1; ?>
		
		<?php foreach ($this->cats as &$cat) : ?>
			<?php 
				$curcatid = JRequest::getVar('catid',0); 
				if ((int)$cat['parcatid'] != (int)$curcatid)
					continue;
			?>
			
	        <?php if ($column == 1) : ?>
	        	<tr><td width='<?php echo $colwidth; ?>' class='fss_faq_cat_col_first' valign='top'>
	        <?php else: ?>
	        	<td width='<?php echo $colwidth; ?>' class='fss_faq_cat_col' valign='top'>
	        <?php endif; ?>

			<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat.php';
			//include "components/com_fss/views/kb/snippet/_cat.php" ?>
			
		    <?php if ($column == $this->main_cat_colums): ?>
		            </td></tr>
		    <?php else: ?>
		        	</td>
		    <?php endif; ?>
		     
		    <?php        
		        $column++;
		        if ($column > $this->main_cat_colums)
		            $column = 1;
		    ?>
		<?php endforeach; ?>

		<?php	
		if ($column > 1)
		{ 
			while ($column <= $this->main_cat_colums)
			{
				echo "<td class='fss_faq_cat_col' valign='top'><div class='faq_category'></div></td>";	
				$column++;
			}
			echo "</tr>"; 
			$column = 1;
		}
		?>

</table> 	
		
	<?php else: ?>
	
		<?php if ($this->cats): ?>
			<?php foreach ($this->cats as &$cat): ?>
				<?php 
					$curcatid = JRequest::getVar('catid',0); 
					if ((int)$cat['parcatid'] != (int)$curcatid)
						continue;
				?>
	
				<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat.php';
				//include "components/com_fss/views/kb/snippet/_cat.php" ?>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>

<?php if ($this->view_mode_cat == "accordian") : ?>
<?php $scrollf = FSS_Helper::Is16() ? "start" : "scrollTo"; ?>

<script>
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
<?php endif; ?>
