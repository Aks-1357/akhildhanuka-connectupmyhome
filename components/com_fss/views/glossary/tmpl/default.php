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

<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("GLOSSARY"); ?>
<div class="fss_spacer"></div>

<?php if ($this->use_letter_bar): ?>
	<div class="fss_glossary_letters">
	<?php foreach ($this->letters as &$letter): ?>
		<span class="fss_glossary_letter">
			<?php if ($this->use_letter_bar == 2): ?>
				<a href='<?php echo FSSRoute::x('index.php?option=com_fss&view=glossary&letter=' . strtolower($letter->letter)); ?>'>&nbsp;<?php echo $letter->letter; ?>&nbsp;</a>
			<?php else : ?>
				<a href='<?php echo FSSRoute::x('index.php?option=com_fss&view=glossary#letter_' . strtolower($letter->letter)); ?>'>&nbsp;<?php echo $letter->letter; ?>&nbsp;</a>
			<?php endif; ?>
		</span>
	<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php $letter = ""; ?>
<?php foreach($this->rows as $glossary) : ?>
<?php $thisletter = strtolower(substr($glossary->word,0,1)); 
	if ($thisletter != $letter)
	{
		$letter = $thisletter;
		echo "<a name='letter_$letter' ></a>";
	}
?>
<div class="fss_glossary_div">
<div class="fss_glossary_word"><a name='<?php echo $glossary->word; ?>'></a><?php echo $glossary->word; ?></div>
<div class="fss_glossary_text"><?php echo $glossary->description; ?><?php echo $glossary->longdesc; ?></div>
</div>

<?php endforeach; ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>