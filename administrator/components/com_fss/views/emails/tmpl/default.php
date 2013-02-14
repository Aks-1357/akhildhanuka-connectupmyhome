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
<form action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=faqs' );?>" method="post" name="adminForm" id="adminForm">
<div id="editcell">
    <table class="adminlist table table-striped">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20">
   				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->data ); ?>);" />
			</th>
            <th width="20%">
                <?php echo JText::_("TEMPLATE"); ?>
            </th>
            <th>
                <?php echo JText::_("DESCRIPTION"); ?>
            </th>
            <th>
                <?php echo JText::_("SUBJECT"); ?>
            </th>
            <th width="8%" norwap>
                <?php echo JText::_("IS_HTML"); ?>
            </th>
		</tr>
    </thead>
    <?php
	$k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row =& $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::x( 'index.php?option=com_fss&controller=email&task=edit&cid[]='. $row->id );
		
    	if ($row->ishtml)
    	{
    		$ishtml_img = "tick";
    	} else {
    		$ishtml_img = "cross";
    	}

        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
			</td>
			<td>
   				<?php echo $checked; ?>
			</td>
			<td>
  			    <a href="<?php echo $link; ?>"><?php echo $row->tmpl; ?></a>
			</td>
			<td>
  			    <?php echo JText::_($row->description); ?>
			</td>
			<td>
  			    <?php echo $row->subject; ?>
			</td>
			<td align='center'>
				<img src='<?php echo JURI::base(); ?>/components/com_fss/assets/<?php echo $ishtml_img; ?>.png' width='16' height='16' />
			</td>
		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </table>
</div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="email" />
</form>

