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

<?php echo JHTML::_( 'form.token' ); ?>
<style>
label {
	width: auto !important;
	float: none !important;
	clear: none !important;
	display: inline !important;
}
input {
	float: none !important;
	clear: none !important;
	display: inline !important;
}
</style>

<script language="javascript" type="text/javascript">

function submitbutton(pressbutton) {
    var form = document.adminForm;
    if (pressbutton == 'cancel') {
            submitform( pressbutton );
            return;
    }

    <?php
            $editor =& JFactory::getEditor();
    echo $editor->save( 'body' );
    ?>
    submitform(pressbutton);
}

function DoAllProdChange()
{
	var form = document.adminForm;
	var prodlist = document.getElementById('prodlist');
		
	if (form.allprods[1].checked)
    {
		prodlist.style.display = 'none';
	} else {
		prodlist.style.display = 'inline';
	}
}

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_("DETAILS"); ?></legend>

		<table class="admintable">
		    <tr>
			    <td width="135" align="right" class="key">
				    <label for="question">
					    <?php echo JText::_("CATEGORY"); ?>:
				    </label>
			    </td>
			    <td>
				    <?php echo $this->lists['catid']; ?>
			    </td>
		    </tr>		
			<tr>
			    <td width="135" align="right" class="key">
				    <label for="title">
					    <?php echo JText::_("TITLE"); ?>:
				    </label>
			    </td>
			    <td>
				    <input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->kbart->title);?>" />
			    </td>
		    </tr>
		<?php FSSAdminHelper::LA_Form($this->kbart); ?>
			<tr>
			    <td width="135" align="right" class="key">
				    <label for="eh">
						<?php echo JText::_("PRODUCTS"); ?>:
				    </label>
			    </td>
			    <td>
					<div>
						<?php echo JText::_("SHOW_FOR_ALL_PRODUCTS"); ?>
						<?php echo $this->lists['allprod']; ?>
					</div>
					<div id="prodlist" <?php if ($this->allprods) echo 'style="display:none;"'; ?>>
						<?php echo $this->lists['products']; ?>
					</div>
			    </td>
		    </tr>
		    <tr>
			    <td width="135" align="right" class="key">
				    <label for="body">
					    <?php echo JText::_("ARTICLE"); ?>:
				    </label>
			    </td>
			    <td>
				    <?php
				    $editor =& JFactory::getEditor();
				    echo $editor->display('body', $this->kbart->body, '550', '400', '60', '20', array('pagebreak'));
				    ?>
                </td>

		    </tr>
		    <tr>
			    <td width="135" align="right" class="key">
				    <label for="body">
					    <?php echo JText::_("RELATED_ARTICLES"); ?>:
				    </label>
			    </td>
			    <td>
					<?php $relarts = array(); ?>
					<div id="relarts">
				    <?php foreach ($this->lists['related'] as $relart) : ?>
						<div id="relart_<?php echo $relart['id']; ?>" class="fss_kb_relart"><a href="javascript: return null;" onclick="removerelart(<?php echo $relart['id']; ?>); return false;"><img src='<?php echo JURI::base(); ?>/components/com_fss/assets/cross.png' width='16' height='16' /> <?php echo JText::_("REMOVE"); ?></a> - <?php echo $relart['title']; ?></div>
						<?php $relarts[] = $relart['id']; ?>
					<?php endforeach; ?>
					</div>
					<input type="hidden" name="relartfield" id="relartfield" value="<?php echo implode(",",$relarts); ?>">
					<div class="button2-left" style="clear:both;">
						<div class="blank">
							<a href="<?php echo FSSRoute::x('?option=com_fss&task=pick&tmpl=component&controller=kbart') ?>" class="modal" rel="{handler: 'iframe', size: {x: 650, y: 375}}">Add Related Article</a>
						</div>
					</div>
                </td>
		    </tr>
            <tr>
                <td width="135" align="right" class="key">
                    <label for="filedata">
                        <?php echo JText::_("UPLOAD_FILE"); ?> (Max <?php echo FSS_Helper::display_filesize(ini_get('upload_max_filesize')); ?>)
                    </label>
                </td>
                <td>        
                    <input type="file" id="filedata" name="filedata" />
                </td>
            </tr>            
            <tr>
                <td width="135" align="right" class="key">
                    <label for="filetitle">
                        <?php echo JText::_("FILE_TITLE"); ?>
                    </label>
                </td>
                <td>        
                    <input class="text_area" type="text" name="filetitle" id="filetitle" size="32" maxlength="250" value="" />
                </td>
            </tr>            
            <tr>
                <td width="135" align="right" class="key">
                    <label for="filedesc">
                        <?php echo JText::_("FILE_DESCRIPTION"); ?>
                    </label>
                </td>
                <td>        
                    <input class="text_area" type="text" name="filedesc" id="filedesc" size="32" maxlength="250" value="" />
                </td>
            </tr>
                <?php if (count($this->lists['files']) > 0) : ?>
            <tr>
                <td width="135" align="right" class="key">
                    <label for="filedesc">
                        <?php echo JText::_("FILES"); ?>
                    </label>
                </td> 
                <td> 
                    <table class="admintable">       
                        <tr>
                            <td class="key" style='text-align: center;' width='8%' nowrap>Filename</td>
                            <td class="key" style='text-align: center;' width='8%' nowrap>Title</td>
                            <td class="key" style='text-align: center;'>Description</td>
                            <td class="key" style='text-align: center;' width='8%' nowrap>Size</td>
                            <td class="key" style='text-align: center;' width='1%' nowrap>Delete</td>
                        </tr>
                        <?php $i = 0; ?>
                        <?php foreach ($this->lists['files'] as $file) : ?>
						<tr>
							<?php $filelink = FSSRoute::x( 'index.php?option=com_fss&controller=kbart&task=download&fileid=' . $file['id'] ); ?>
							<td width='8%' nowrap>
                                <a href='<?php echo $filelink; ?>' alt='Download'>
									<img src='<?php echo JURI::base(); ?>/components/com_fss/assets/disk.png' width='16' height='16' />
								</a>
                                <?php echo $file['filename']; ?>
                            </td>
                            <td width='8%' nowrap><?php echo $file['title']; ?></td>
                            <td><?php echo $file['description']; ?></td>
                            <td width='8%' nowrap align='right'><?php echo round($file['size'] / 1000,0); ?>Kb</td>
                            <td width='1%' nowrap align='center'>
                                <?php echo JHTML::_( 'grid.id', $i++, $file['id'] ); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </td>
            </tr>
            <?php endif ; ?>
            </table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->kbart->id; ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->kbart->ordering; ?>" />
<input type="hidden" name="published" value="<?php echo $this->kbart->published; ?>" />
<input type="hidden" name="rating" value="<?php echo $this->kbart->rating; ?>" />
<input type="hidden" name="ratingdetail" value="<?php echo $this->kbart->ratingdetail; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="kbart" />
</form>
<style>
.fss_kb_relart
{
	clear: both;
}
</style>
<script>
function addrelart()
{
	
}

function jSelectArticle(id, title, field)
{
	SqueezeBox.close();
	
	var values = $('relartfield').value;
	
	// check that we dont have this one already
	var values = values.split(',');
	for (var i = 0 ; i < values.length ; i++)
	{
		if (values[i] == id)
			return;
	}				
	
	// store new value
	values[values.length] = id;
	$('relartfield').value = values.join(",");
	
	// update display with new item
	$('relarts').innerHTML = $('relarts').innerHTML + '<div id="relart_' + id + '" class="fss_kb_relart"><a href="javascript: return null;" onclick="removerelart(' + id + '); return false;"><img src="<?php echo JURI::base(); ?>/components/com_fss/assets/cross.png" width="16" height="16" /> <?php echo JText::_("REMOVE"); ?></a> - ' + title + '</div>';				
}

function removeItem(originalArray, itemToRemove) {
	var j = 0;
	while (j < originalArray.length) 
	{
		// alert(originalArray[j]);
		if (originalArray[j] == itemToRemove) {
			originalArray.splice(j, 1);
		} else { 
			j++; 
		}
	}
}

function removerelart(id)
{
	// remove id from array
	var values = $('relartfield').value;
	var values = values.split(',');
	
	var j = 0;
	while (j < values.length) 
	{
		if (values[j] == id) {
			values.splice(j, 1);
		} else { 
			j++; 
		}
	}
	
	$('relartfield').value = values.join(",");

	$('relarts').removeChild($('relart_' + id));
	return false;			
}
</script>
