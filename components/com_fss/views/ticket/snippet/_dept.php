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
<div class='fss_ticket_dept' id='dept_cont_<?php echo $dept['id']; ?>';
	 onmouseover="$('dept_cont_<?php echo $dept['id']; ?>').style.background = '<?php echo FSS_Settings::get('css_hl'); ?>';"
	 onmouseout="$('dept_cont_<?php echo $dept['id']; ?>').style.background = '';"
	 onclick="setCheckedValue(document.forms['deptselect'].elements['deptid'],'<?php echo $dept['id']; ?>');"
	  >
	<table cellspacing=0 cellpadding=0>
		<tr>
			<td <?php if (FSS_Settings::get('support_next_prod_click')) echo "style='display:none'"; ?>>
				<label for="deptid_<?php echo $dept['id']; ?>">
					<input type="radio" name="deptid" id="deptid_<?php echo $dept['id']; ?>" value="<?php echo $dept['id']; ?>"></input>
				</label>
			</td>
  		<?php if ($this->product['image']) : ?>
	<td>
	&nbsp;
			<!--<div class='fss_ticket_dept_image'>
			    <img src='<?php echo JURI::root( true ); ?>/images/fss/departments/<?php echo $this->product['image']; ?>' width='64' height='64'>
			</div>-->
			</td>
		<?php endif; ?>
			<td>
					<div class='fss_ticket_dept_head'>
			<!--<A href='<?php echo FSSRoute::x( '&deptid=' . $dept['id'] );?>'>--><?php echo $dept['title'] ?><!--</a>-->
		</div>
		<div class='fss_ticket_dept_desc'><?php echo $dept['description']; ?></div>
		</td>
		</tr>
	</table>
</div>

