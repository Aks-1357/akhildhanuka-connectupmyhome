<?php
	$app = JFactory::getApplication();
	$subcategories = array();
	$accordion_no = 0;
?>
<div id="cumh_right">
	<div class="accordion vertical">
		<ul>
		<?php
		    $i = 0;
		    
			foreach ($this->data as $d)
			{
				if(in_array($d->name, $subcategories)== false )
				{
					array_push($subcategories, $d->name);
			
					$i++;
					$on_click = "javascript:controllerObj.switchtoSelectionpage('".JFilterOutput::stringURLSafe($d->name)."','".JFilterOutput::stringURLSafe($v->name)."',".$accordion_no.",'0')";
					if (JRequest::getVar('bundle_type') == 'recommend')
					{
						$on_click = "";
					}
				?>
				<li>
				    
					<input type="checkbox" id="<?php echo 'checkbox-'.$i;?>" name="checkbox-accordion" />
					<label for="<?php echo 'checkbox-'.$i;?>">
						<div id="<?php echo JFilterOutput::stringURLSafe('myselection-div-in-'.$d->name);?>" class=" <?php echo JFilterOutput::stringURLSafe($d->name); ?> commen">
							<?php echo $d->name;?>
							<span style="top:-6;" id="<?php echo JFilterOutput::stringURLSafe('myselection-in-'.$d->name);?>" onclick="<?php echo $on_click; ?>">
							</span>
						</div>
					</label>
                    
					<div class="content">
						<div class="mainwrap" id="<?php echo 'inner-brands-'.JFilterOutput::stringURLSafe($d->name); ?>">
						<div id="<?php echo 'basket-noproduct-'.JFilterOutput::stringURLSafe($d->name); ?>"><strong>No product Selected...</strong></div>
						</div>
					</div>
				</li>
				
			<?php 
				}
				$accordion_no++;
			} ?>
			
		</ul>
        
       		<div class="totalcost commen">TOTAL COST</div>
            <div class="totalcostwrap">
            <span style="margin-top:10px;"><div class="cost_month">One-off cost</div><div class="costing" id="fixcost">$0</div></span>
            <span style="border-bottom:none;"><div class="cost_month">Monthly Price</div> <div class="costing" id="monthlycost">$0</div></span>
            </div>
      </div>
</div>