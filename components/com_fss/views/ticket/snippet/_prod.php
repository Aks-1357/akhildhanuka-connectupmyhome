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

<div class='fss_ticket_prod' id='prod_cont_<?php echo $product['id']; ?>';
	 onmouseover="$('prod_cont_<?php echo $product['id']; ?>').style.background = '<?php echo FSS_Settings::get('css_hl'); ?>';"
	 onmouseout="$('prod_cont_<?php echo $product['id']; ?>').style.background = '';"
	 onclick="setCheckedValue(document.forms['prodselect'].elements['prodid'],'<?php echo $product['id']; ?>');"
	  >
	<table cellspacing=0 cellpadding=0>
		<tr>
			<td width="22" <?php if (FSS_Settings::get('support_next_prod_click')) echo "style='display:none'"; ?>>
				<label for="prodid_<?php echo $product['id']; ?>">
					<input type="radio" name="prodid" id="prodid_<?php echo $product['id']; ?>" value="<?php echo $product['id']; ?>" ></input>
				</label>
			</td>
  		<?php if ($product['image'] || $hasprodimages) : ?>
			<td width="74">
				<?php if ($product['image']) : ?>
				<div class='fss_ticket_prod_image' style="margin:0px'">
					<img src='<?php echo JURI::root( true ); ?>/images/fss/products/<?php echo $product['image']; ?>'>
				</div>
				<?php endif; ?>
			</td>
		<?php endif; ?>
			<td>
					<div class='fss_ticket_prod_head' style="margin:0px'">
			<!--<A href='<?php echo FSSRoute::x( '&prodid=' . $product['id'] );?>'>--><?php echo $product['title'] ?><!--</a>-->
		</div>
		<div class='fss_ticket_prod_desc' style="margin:0px'"><?php echo $product['description']; ?></div>
		</td>
		</tr>
	</table>
</div>

