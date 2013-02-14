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
<?php defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">
<?php if (FSS_Helper::Is16()): ?>
	Joomla.submitbutton = function(pressbutton) {
		if (pressbutton == 'resetviews' || pressbutton == 'resetrating') {
			/*if (document.adminForm.boxchecked.value==0){
				alert('<?php echo JText::_('FSS_PLEASE_MAKE_A_SELECTION', true); ?>');
				return false;
			}*/
			if (confirm('<?php echo JText::_('FSS_ARE_YOU_SURE', true); ?>')){
				submitform( pressbutton );
			} 
		}
	}
<?php else: ?>
	function submitbutton(pressbutton) {
		if (pressbutton == 'resetviews' || pressbutton == 'resetrating') {
			/*if (document.adminForm.boxchecked.value==0){
				alert('<?php echo JText::_('FSS_PLEASE_MAKE_A_SELECTION', true); ?>');
				return false;
			}*/
			if (confirm('<?php echo JText::_('FSS_ARE_YOU_SURE', true); ?>')){
				submitform( pressbutton );
			} 
		} else {
			submitform( pressbutton );
		}
	}
<?php endif; ?>
</script>

<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=kbarts' );?>" method="post" name="adminForm" id="adminForm">
<?php $ordering = ($this->lists['order'] == "k.ordering"); ?>
<?php JHTML::_('behavior.modal'); ?>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_("FILTER"); ?>:
				<input type="text" name="search" id="search" value="<?php echo JViewLegacy::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_("FILTER_BY_TITLE_OR_ENTER_ARTICLE_ID");?>"/>
				<button onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('kb_cat_id').value='0';this.form.getElementById('ispublished').value='-1';this.form.submit();"><?php echo JText::_("RESET"); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->lists['prods'];
				echo $this->lists['cats'];
				echo $this->lists['published'];
				?>
				<?php FSSAdminHelper::LA_Filter(); ?>
			</td>
		</tr>
	</table>

    <table class="adminlist table table-striped">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20">
   				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->data ); ?>);" />
			</th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Title', 'title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>           
			<th>
                <?php echo JHTML::_('grid.sort',   'Created', 'created', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
			<th>
                <?php echo JHTML::_('grid.sort',   'Modified', 'modified', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th  class="title" width="8%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort',   'Category', 'cattitle', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="1%" class="title" nowrap="nowrap">
                <?php echo JText::_("PRODUCTS"); ?>
            </th>
            <th width="1%" class="title" nowrap="nowrap">
                <?php echo JText::_("FILES"); ?>
            </th>
            <th width="1%" class="title" nowrap="nowrap">
                <?php echo JText::_("RATING"); ?>
            </th>
            <th width="1%" class="title" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort',   'Views', 'views', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
 			<?php FSSAdminHelper::LA_Header(); ?>
            <th width="<?php echo FSSJ3Helper::IsJ3() ? '130px' : '8%'; ?>">
				<?php echo JHTML::_('grid.sort',   'Order', 'ordering', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				<?php if ($ordering) echo JHTML::_('grid.order',  $this->data ); ?>
			</th>
		</tr>
    </thead>
    <?php
    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row =& $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::x( 'index.php?option=com_fss&controller=kbart&task=edit&cid[]='. $row->id );

    	$published = FSS_GetPublishedText($row->published);

        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>
			<td>
			    <a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
			</td>
            <td>
                <?php echo $row->created; ?>
			</td>
			<td>
                <?php echo FSS_Helper::Date($row->modified, FSS_DATETIME_MID); ?>
            </td>
            <td>
                <?php echo $row->cattitle ? $row->cattitle : "Uncategorized" ; ?>
            </td>
            <td align='center'>
				<?php if ($row->allprods) { ?>
					<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = FSSRoute::x('?option=com_fss&tmpl=component&controller=kbart&view=kbart&task=prods&kb_art_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
			</td>
			<td align='center'>
                <?php echo $row->filecount; ?>
			</td>
			<td align='center' nowrap>
			
				<?php if ($row->rating == "") $row->rating = "0"; ?>
				<?php if ($row->ratingdetail == "") $row->ratingdetail = "0|0|0"; ?>   
                <?php list($rate_up,$rate_same,$rate_down) = explode("|",$row->ratingdetail) ; ?>
                <?php if ($rate_up == "") $rate_up = 0; ?>
                <?php if ($rate_same == "") $rate_same = 0; ?>
                <?php if ($rate_down == "") $rate_down = 0; ?>
                <?php 
					$output = "<div style=\"font-size:160%\">";
                $output .= "&nbsp;&nbsp;<img src=\"". JURI::base() . "../components/com_fss/assets/images/rate_up.png\" width=\"16\" height=\"16\" />&nbsp;" . $rate_up . "&nbsp;&nbsp;";
                $output .= "&nbsp;&nbsp;<img src=\"". JURI::base() . "../components/com_fss/assets/images/rate_same.png\" width=\"16\" height=\"16\" />&nbsp;" . $rate_same . "&nbsp;&nbsp;";
                $output .= "&nbsp;&nbsp;<img src=\"". JURI::base() . "../components/com_fss/assets/images/rate_down.png\" width=\"16\" height=\"16\" />&nbsp;" . $rate_down . "&nbsp;&nbsp;";
                /*//$output .= '<img src=\"<?php echo JURI::base(); ?>/components/com_fss/assets/images/rate_same.png\" width=\"16\" height=\"16\" />$rate_same';
                //$output .= '<img src=\"<?php echo JURI::base(); ?>/components/com_fss/assets/images/rate_down.png\" width=\"16\" height=\"16\" />$rate_down';
					//$output = htmlentities($output);*/
					$output .= "</div>";
                ?>
				<a href='' class='fsj_tip fss_highlight' title='Rating Details::<?php echo $output ?>'>
					<?php echo $row->rating; ?>
				</a>
 
            </td>
        	<td align="center">
				<?php echo $row->views; ?>
</td>
<td align="center">
				<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->published ? 'unpublish' : 'publish' ?>')">
					<?php echo $published; ?>
				</a>
			</td>
			<?php FSSAdminHelper::LA_Row($row); ?>
<td class="order">
			<?php if ($ordering) : ?>
				<span><?php echo $this->pagination->orderUpIcon( $i ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n ); ?></span>
			<?php endif; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php if (!$ordering) {echo 'disabled="disabled"';} ?> class="text_area" style="text-align: center" />
			</td>
		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>
	<tfoot>
		<tr>
			<td colspan="14"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>

    </table>
</div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="kbart" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
