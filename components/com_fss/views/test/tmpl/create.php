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
 
// No direct access
 
defined('_JEXEC') or die('Restricted access'); ?>

<?php echo $this->tmpl ? FSS_Helper::PageStylePopup() : FSS_Helper::PageStyle(); ?>


	<?php echo $this->tmpl ? FSS_Helper::PageTitlePopup("TESTIMONIALS","ADD_A_TESTIMONIAL") : FSS_Helper::PageTitle("TESTIMONIALS","ADD_A_TESTIMONIAL"); ?>
	<div class='fss_kb_comment_add' id='add_comment'>
		<?php $this->comments->DisplayAdd(); ?>
	</div>

	<div id="comments"></div>
<?php if ($this->tmpl): ?>
	<div class='fss_comments_result'></div>
<?php endif; ?>
<?php $this->comments->IncludeJS() ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>

<?php echo $this->tmpl ? FSS_Helper::PageStylePopupEnd() : FSS_Helper::PageStyleEnd(); ?>