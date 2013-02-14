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
<?php

function start_faq_col_item()
{
	
}

function end_faq_col_item()
{
	
}

// No direct access

defined('_JEXEC') or die('Restricted access'); ?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("FREQUENTLY_ASKED_QUESTIONS",$this->curcattitle); ?>
<div class="fss_spacer"></div>

<?php $acl = 1; ?>

<?php if ($this->showcats) : ?>
	
	<?php echo FSS_Helper::PageSubTitle("PLEASE_SELECT_YOUR_QUESTION_CATEGORY"); ?>
	<div class="fss_faq_catlist" id='fss_faq_catlist'>
		<?php $colwidth = floor(100 / $this->num_cat_colums) . "%"; ?>
		<?php $column = 1; ?>
		<table width='100%' cellspacing="0" cellpadding="0">

	    <!-- START SEARCH -->
		<?php if (!$this->hide_search) : ?>
			<?php
					if ($column == 1)
					echo "<tr><td width='$colwidth' class='fss_faq_cat_col_first' valign='top'>";
				else 
					echo "<td width='$colwidth' class='fss_faq_cat_col' valign='top'>";        
			?>
           	<div class='faq_category'>
	    		<div class='faq_category_image'>
	    			<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/search.png' width='64' height='64'>
	    		</div>
				<div class='faq_category_head' style="padding-top:6px;padding-bottom:6px;">
					<?php if ($this->curcatid == -1) : ?><b><?php endif; ?>
					<?php echo JText::_("SEARCH_FAQS"); ?>
					<?php if ($this->curcatid == -1) : ?></b><?php endif; ?>
				</div>
				<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=faqs' );?>" method="get" name="adminForm">
					<input type='hidden' name='option' value='com_fss' />
					<input type='hidden' name='Itemid' value='<?php echo JRequest::getVar('Itemid'); ?>' />
					<input type='hidden' name='view' value='faq' />
					<input type='hidden' name='catid' value='<?php echo $this->curcatid; ?>' />
					<input name='search' value="<?php echo JViewLegacy::escape($this->search); ?>">
					<input type='submit' class='button' value='<?php echo JText::_("SEARCH"); ?>' >
				</form>
			</div>
			<div class='faq_category_faqlist'></div>

			<?php 
				if ($column == $this->num_cat_colums)
					echo "</td></tr>";
				else 
					echo "</td>";
	            
				$column++;
				if ($column > $this->num_cat_colums)
					$column = 1;
			?>

	    <?php endif; ?>
	    <!-- END SEARCH -->
		
		<!-- ALL FAQS START -->
		<?php if (!$this->hide_allfaqs) : ?>
			<?php
				if ($column == 1)
					echo "<tr><td width='$colwidth' class='fss_faq_cat_col_first' valign='top'>";
				else 
					echo "<td width='$colwidth' class='fss_faq_cat_col' valign='top'>";        
			?>
	    		<div class='faq_category'>
				<div class='faq_category_image'>
	    				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/allfaqs.png' width='64' height='64'>
					</div>
					<div class='faq_category_head'>
						<?php if ($this->curcatid == 0) : ?><b><?php endif; ?>
						<A class="fss_highlight" href='<?php echo FSSRoute::x( '&limitstart=&catid=' . 0 );?>'><?php echo JText::_("ALL_FAQS"); ?></a>
						<?php if ($this->curcatid == 0) : ?></b><?php endif; ?>
					</div>
					<div class='faq_category_desc'><?php echo JText::_("VIEW_ALL_FREQUENTLY_ASKED_QUESTIONS"); ?></div>
				</div>
				<div class='faq_category_faqlist'></div>
			<?php 
				if ($column == $this->num_cat_colums)
					echo "</td></tr>";
				else 
					echo "</td>";
			
				$column++;
				if ($column > $this->num_cat_colums)
					$column = 1;
			?>

        <?php endif; ?>
		<!-- END ALL FAQS -->
	

		<!-- TAGS START -->
		<?php if (!$this->hide_tags) : ?>
	    	<?php
				if ($column == 1)
					echo "<tr><td width='$colwidth' class='fss_faq_cat_col_first' valign='top'>";
				else 
					echo "<td width='$colwidth' class='fss_faq_cat_col' valign='top'>";        
			?>
				<div class='faq_category'>
					<div class='faq_category_image'>
	    				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tags-64x64.png' width='64' height='64'>
					</div>
					<div class='faq_category_head'>
						<?php if ($this->curcatid == 0) : ?><b><?php endif; ?>
						<A class="fss_highlight" href='<?php echo FSSRoute::x( '&limitstart=&catid=' . -4 );?>'><?php echo JText::_("TAGS"); ?></a>
						<?php if ($this->curcatid == 0) : ?></b><?php endif; ?>
					</div>
					<div class='faq_category_desc'><?php echo JText::_("VIEW_FAQ_TAGS"); ?></div>
				</div>
				<div class='faq_category_faqlist'></div>
			<?php 
			if ($column == $this->num_cat_colums)
				echo "</td></tr>";
			else 
				echo "</td>";
			
			$column++;
			if ($column > $this->num_cat_colums)
				$column = 1;
			?>

        <?php endif; ?>
		<!-- END TAGS -->
		
		<!-- FEATURED FAQS START -->
		<?php 
			if ($this->show_featured)
			{
			
				if ($column == 1)
					echo "<tr><td width='$colwidth' class='fss_faq_cat_col_first' valign='top'>";
				else 
					echo "<td width='$colwidth' class='fss_faq_cat_col' valign='top'>";        
			
				// set up fake $cat object and include the _cat.php template
				$cat = array();
				$cat['image'] = '/components/com_fss/assets/images/featured.png';
				$cat['id'] = -5;
				$cat['title'] = JText::_('FEATURED_FAQS');
				$cat['description'] = JText::_('VIEW_FEATURED_FREQUENTLY_ASKED_QUESTIONS');
				$cat['faqs'] = array();
				if (!empty($this->featured_faqs))
					$cat['faqs'] = $this->featured_faqs;
			
				include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'faq'.DS.'snippet'.DS.'_cat.php';

				if ($column == $this->num_cat_colums)
					echo "</td></tr>";
				else 
					echo "</td>";
			
				$column++;
				if ($column > $this->num_cat_colums)
					$column = 1;
			}
		 ?>
		<!-- END FEATURED FAQS -->

		<!-- ALL CATS -->
		<?php foreach ($this->catlist as $cat) : ?>
        <?php
        	if ($column == 1)
        		echo "<tr><td width='$colwidth' class='fss_faq_cat_col_first' valign='top'>";
        	else 
        		echo "<td width='$colwidth' class='fss_faq_cat_col' valign='top'>";        
        ?>
		<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'faq'.DS.'snippet'.DS.'_cat.php' ?>
	    <?php 
	        if ($column == $this->num_cat_colums)
	            echo "</td></tr>";
	        else 
	            echo "</td>";
	            
	        $column++;
	        if ($column > $this->num_cat_colums)
	            $column = 1;
	    ?>
		<?php endforeach; ?>
	    <!-- END CATS -->

		<!-- CAT LIST END -->
	    <?php 
	        if ($column > 1)
	        { 
	    		while ($column <= $this->num_cat_colums)
	    		{
	    			echo "<td class='fss_faq_cat_col' valign='top'><div class='faq_category'></div></td>";	
	    			$column++;
	    		}
	            echo "</tr>"; 
	            $column = 1;
	        }
	    ?>
	 
 			<tr><td colspan='<?php echo $this->num_cat_colums; ?>'>
	    	<div class='faq_category_footer'></div>
	    </td></tr>
	    </table>
	</div>


<?php endif; ?>

<?php if ($this->showfaqs) : ?>

	<div class='faq_category'>
	    <?php if ($this->curcatimage) : ?>
		<div class='faq_category_image'>
			<?php if (substr($this->curcatimage,0,1) == "/") : ?>
			<img src='<?php echo JURI::root( true ); ?><?php echo $this->curcatimage; ?>' width='64' height='64'>
			<?php else: ?>
			<img src='<?php echo JURI::root( true ); ?>/images/fss/faqcats/<?php echo $this->curcatimage; ?>' width='64' height='64'>
			<?php endif; ?>
	    </div>
	    <?php endif; ?>
			<div class='fss_spacer contentheading' style="padding-top:6px;padding-bottom:6px;">
				<?php echo JText::_("FAQS"); ?> <?php if ($this->curcattitle) { echo " - " . $this->curcattitle; } ?>
			</div>
		<div class='faq_category_desc'><?php echo $this->curcatdesc; ?></div>
	</div>
	<div class='fss_clear'></div>
	

	<?php if ($this->curcatid == -1): ?>
		<div class='faq_category'>
			<div class='faq_category_image'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/search.png' width='64' height='64'>
			</div>
			<div class='faq_category_head' style="padding-top:6px;padding-bottom:6px;">
				<?php echo JText::_("SEARCH_FAQS"); ?>
</div>

			<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=faqs' );?>" method="get" name="adminForm">
				<input type='hidden' name='option' value='com_fss' />
				<input type='hidden' name='Itemid' value='<?php echo JRequest::getVar('Itemid'); ?>' />
				<input type='hidden' name='view' value='faq' />
				<input type='hidden' name='catid' value='<?php echo $this->curcatid; ?>' />
				<input name='search' value="<?php echo JViewLegacy::escape($this->search); ?>">
				<input type='submit' class='button' value='<?php echo JText::_("SEARCH"); ?>' >
			</form>
		</div>
		<div class='faq_category_faqlist'></div>
	<?php endif; ?>
	
	
	<div class='fss_faqs' id='fss_faqs'>
	<?php if (count($this->items)) foreach ($this->items as $faq) : ?>
	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'faq'.DS.'snippet'.DS.'_faq.php';
	//include "components/com_fss/views/faq/snippet/_faq.php" ?>
	<?php endforeach; ?>
	<?php if (count($this->items) == 0): ?>
	<div class="fss_no_results"><?php echo JText::_("NO_FAQS_MATCH_YOUR_SEARCH_CRITERIA");?></div>
	<?php endif; ?>
	</div>

	<?php if ($this->enable_pages): ?>
		<form id="adminForm" action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=faq&catid=' . $this->curcatid );?>" method="post" name="adminForm">
		<input type='hidden' name='catid' value='<?php echo $this->curcatid; ?>' />
		<input type='hidden' name='enable_pages' value='<?php echo $this->enable_pages; ?>' />
		<input type='hidden' name='view_mode' value='<?php echo $this->view_mode; ?>' />
			<?php echo $this->pagination->getListFooter(); ?>
		</form>
	<?php endif; ?>

<?php endif; ?>

<?php $scrollf = FSS_Helper::Is16() ? "start" : "scrollTo"; ?>
	
<?php 
$doacc = 0;

if ($this->curcatid < 1 && $this->view_mode_cat == "accordian") 
{
	$doacc = 1;
}
elseif ($this->curcatid < 1 && $this->view_mode_incat == "accordian")
{ 
	$doacc = 1;
}
if ($this->view_mode == "accordian")
{
	$doacc = 1;	
}

if ($doacc): ?>
<script>
jQuery(document).ready(function () {
	
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

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php if (FSS_Settings::get( 'glossary_faqs' )) echo FSS_Glossary::Footer(); ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>


<!--##NOT_EXT_START##-->
<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js';
//include 'components/com_fss/assets/js/content_edit.js'; ?>
</script>
<!--##NOT_EXT_END##-->
