
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
<?php $unpubclass = ""; if ($faq['published'] == 0) $unpubclass = "content_edit_unpublished"; ?>
	<?php if (true) : ?>
	<div class='article'>
		<div class="article-title">
			<?php echo $this->content->EditPanel($faq); ?>
			<h2><?php echo $faq['question']; ?></h2>
		</div>
		<div class='description' style="color:#1099ce">
			<?php 
			if (FSS_Settings::get( 'glossary_faqs' )) {
				echo FSS_Glossary::ReplaceGlossary($faq['answer']); 
				if ($faq['fullanswer'])
				{
					echo FSS_Glossary::ReplaceGlossary($faq['fullanswer']); 
				}
			} else {
				echo $faq['answer']; 
				if ($faq['fullanswer'])
				{
					echo $faq['fullanswer']; 
				}
			}		
			?>
			<?php if (array_key_exists($faq['id'], $this->tags)): ?>
			<div class='fss_faq_tags'>
	
				<span><?php echo JText::_('TAGS'); ?>:</span>
				<?php echo implode(", ", $this->tags[$faq['id']]); ?>
			</div>
			<?php endif; ?>
		</div>	
	</div>	
	<?php elseif ($this->view_mode == 'questionwithtooltip'): ?>	
	<div class='fss_faq'>
		<div class="fss_faq_question <?php echo $unpubclass; ?>">
			<?php echo $this->content->EditPanel($faq); ?>
			<?php $text = $faq['answer']; 
			if ($faq['fullanswer'])
				$text .= "<div class='fss_faq_more'><a href='#'>click for more...</a></div>";
				
			$text = str_replace("'", "", $text);
			$question = $faq['question'];
			
			$question = str_replace("'", "", $question);
			
			$output = '<div class="fss_faq_question_tip">' . $question;
			$output .= '</div><div class="fss_faq_answer_tip">';
			$output .= $text;
			if (array_key_exists($faq['id'], $this->tags))
			{
				$output .= '<div class="fss_faq_tags">';
				$output .= '<span>' . JText::_('TAGS') . ':</span> ';
				$output .= str_replace("'","\"",implode(", ", $this->tags[$faq['id']]));
				$output .= '</div>';
			}
			$output .= '</div>'	
			
			?>
			<a href='<?php echo FSSRoute::x( '&faqid=' . $faq['id'] ); ?>' class='fsj_tip_wide fss_highlight' title='<?php echo $output ?>'>
				<?php echo $faq['question']; ?>
			</a>
		</div>  
	</div>	
	<?php elseif ($this->view_mode == 'questionwithlink'): ?>	
		<div class='fss_faq'>
			<div class="fss_faq_question <?php echo $unpubclass; ?>">
				<?php echo $this->content->EditPanel($faq); ?>
				<a class='fss_highlight' href='<?php echo FSSRoute::x( '&faqid=' . $faq['id'] ); ?>'>
					<?php echo $faq['question']; ?>
				</a>
			</div>
		</div>	
	<?php elseif ($this->view_mode == 'questionwithpopup'): ?>	
		<div class='fss_faq'>
			<div class="fss_faq_question <?php echo $unpubclass; ?>">
				<?php echo $this->content->EditPanel($faq); ?>
				<a class="modal fss_highlight" href='<?php echo FSSRoute::x( '&tmpl=component&faqid=' . $faq['id'] ); ?>' rel="{handler: 'iframe', size: {x: <?php echo FSS_Settings::get('faq_popup_width'); ?>, y: <?php echo FSS_Settings::get('faq_popup_height'); ?>}}">
					<?php echo $faq['question']; ?>
				</a>
			</div>		
		</div>	
	<?php elseif ($this->view_mode == 'accordian'): ?>	
		<div class='fss_faq'>
			<div class="fss_faq_question <?php echo $unpubclass; ?> <?php if ($this->view_mode == "accordian") echo "accordion_toggler_$acl"; ?>" style='cursor: pointer;'>
				<?php echo $this->content->EditPanel($faq); ?>
				<a class='fss_highlight' href="javascript:function Z(){Z=''}Z()"><?php echo $faq['question']; ?></a>
			</div>
			<div class='fss_faq_answer <?php if ($this->view_mode == "accordian") echo "accordion_content_$acl"; ?>'>
			<?php 
				if (FSS_Settings::get( 'glossary_faqs' )) {
					echo FSS_Glossary::ReplaceGlossary($faq['answer']); 
					if ($faq['fullanswer'])
					{
						echo FSS_Glossary::ReplaceGlossary($faq['fullanswer']); 
					}
				} else {
					echo $faq['answer']; 
					if ($faq['fullanswer'])
					{
						echo $faq['fullanswer']; 
					}
				}		
			?>
				<?php if (array_key_exists($faq['id'], $this->tags)): ?>
				<div class='fss_faq_tags'>
	
					<span><?php echo JText::_('TAGS'); ?>:</span>
					<?php echo implode(", ", $this->tags[$faq['id']]); ?>
				</div>
				<?php endif; ?>
			</div>
			
		</div>
	<?php endif; ?>

