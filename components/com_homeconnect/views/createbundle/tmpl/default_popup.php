<script type="text/javascript">
	function HideDetailsPopup() {
		var id = document.getElementById("txtProductId").value;
		// document.getElementById('divchk_'+id).removeAttribute("style");
		// document.getElementById('divViewDetails_'+id).removeAttribute("style");
		document.getElementById('mc_detailspopup').style.display='none'
	}
</script>
<div id="mc_detailspopup_cover">
	<div id="mc_detailspopup" class="detailspopup" style="display: none;">
		<div style="float:right" class="backbutton">
			<a href="javascript:void(0);" onclick="HideDetailsPopup();">
				<div>Back</div>
			</a>
		</div>
		<div class="popupwrap">
			<div class="contentwrap">
				<div class="tile" style="margin-right:10px; float:left;">
					<div class="tile_inner" id="div_details_img">
					</div>
				</div>
				<div class="contentwrap_title" style="display:block" id="span_mc_DetailsHeading">
					&nbsp;
				</div>
				<input type="hidden" id="txtProductId" value=""/>
				<div class="contentwrap_quote_wrap">
					<div class="contentwrap_quote" id="table_mc_quotaA">Data Quota</div>
					<div class="contentwrap_quote" id="table_mc_quota">&nbsp;</div>
				</div>
			</div>
			<!--content wrap end-->

			<div class="contentwrap">
			<!--<div class="contentwrap_detail_title">Product Details</div>-->
				<p id="table_mc_shortDescription" style="float:left;"></p>
				<div class="contentwrap_detail_wrap">
					<div class="contentwrap_detail_divA" id="table_mc_equipmentA">Equipment</div>
					<div class="contentwrap_detail_divB" id="table_mc_equipment">&nbsp;</div>

					<div class="contentwrap_detail_divA" id="table_mc_monthlyPriceA">Ongoing monthly cost</div>
					<div class="contentwrap_detail_divB" id="table_mc_monthlyPrice">&nbsp;</div>

					<div class="contentwrap_detail_divA" id="table_mc_nationalCallCostA">National Calls</div>
					<div class="contentwrap_detail_divB" id="table_mc_nationalCallCost">&nbsp;</div>

					<!--
					<div class="contentwrap_detail_divA">Product Name</div>
					<div class="contentwrap_detail_divB" id="table_mc_title"></div>

					<div class="contentwrap_detail_divA">Quota</div>
					<div class="contentwrap_detail_divB" id="table_mc_quota">&nbsp;</div>

					<div class="contentwrap_detail_divA">Minimum Spend</div>
					<div class="contentwrap_detail_divB" id="table_mc_minSpend">&nbsp;</div>

					<div class="contentwrap_detail_divA">Calls To Same Supplier Mobile</div>
					<div class="contentwrap_detail_divB" id="table_mc_mobileCallCost">&nbsp;</div>-->
				</div>

				<div class="contentwrap_detail_wrap">
					<div class="contentwrap_detail_divA" id="table_mc_equipmentPriceA">Equipment Price</div>
					<div class="contentwrap_detail_divB" id="table_mc_equipmentPrice">&nbsp;</div>

					<div class="contentwrap_detail_divA" id="table_mc_contractLengthA">Contract Length</div>
					<div class="contentwrap_detail_divB" id="table_mc_contractLength">&nbsp;</div>

					<!--
					<div class="contentwrap_detail_divA">Download Speed</div>
					<div class="contentwrap_detail_divB" id="table_mc_downloadSpeed">&nbsp;</div>

					<div class="contentwrap_detail_divA">Download Speed</div>
					<div class="contentwrap_detail_divB" id="table_mc_uploadSpeed">&nbsp;</div>

					<div class="contentwrap_detail_divA">Home Phone</div>
					<div class="contentwrap_detail_divB" id="table_mc_homephone">&nbsp;</div> -->
				</div>

				<div class="contentwrap_detail_title">Conditions</div>
				<p id="table_mc_conditions" style="float:left;">&nbsp;</p>
			</div>
		</div>
	</div>
</div>

<!-- 
<div id="mc_detailspopup_cover">
	<div id="mc_detailspopup" class="detailspopup" style="display: none;">
		<div class="detailspopup_top">
			<span style="display:block" ></span>
			<a href="javascript:void(0);" onclick="HideDetailsPopup();" > 
				<div>Back</div>
			</a>
			<input type="hidden" id="txtProductId" value=""/>
		</div>

		<div style="padding:0px 20px; height:500px; overflow:scroll; overflow-x:hidden; width:624px;">
			<div class="detailspopup_content" id="div_mc_details"></div>
			<div class="detailspopup_content_footer" style="display:none;">
				!-- 
				<img src="<?php // echo $templateDir.'/images/share_16x16.png'?>" alt="Share"  style="margin-top:2px;"/>
				 --
				<span style="font-size:12px;color: black;">ShareThis</span> 
				<span  class="detailspopup_content_footer_btn">Compare all optus plans</span>
			</div>
			<div class="detailspopup_MiddlePart">
				<div class="detailspopup_MiddlePart_Middle">Product Features</div>
					<p class="detailspopup_MiddlePart_Middle_p" id="div_mc_DetailsPlanFeatures"></p>
					<div class="detailspopup_MiddlePart_Middle" id="div_mc_Details_DescHeading">
				</div>
				<div class="detailspopup_MiddlePart_Tables">
					<div class="detailspopup_MiddlePart_Table_left">
						<span >Product description detail</span>
						<table width="300" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td align="left" valign="top" width="100"><strong>Product Name</strong></td>
							<td align="left" valign="top" id="table_mc_title" width="200">&nbsp;</td>
						  </tr>
						  <tr>
						      <td align="left" valign="top" ><strong>Product Details Short</strong></td>
						 	  <td align="left" valign="top" id="table_mc_shortDescription" >&nbsp;</td>
					      </tr>
					      <tr>
						    <td align="left" valign="top" ><strong>Product Details Long</strong></td>
						 	<td align="left" valign="top" id="table_mc_longDescription" >&nbsp;</td>
					      </tr>
					      <tr id="table_mc_quota_tr">
						    <td align="left" valign="top" ><strong>Quota</strong></td>
						 	<td align="left" valign="top" id="table_mc_quota" >&nbsp;</td>
					      </tr>
					      <tr>
						    <td align="left" valign="top" ><strong>Equipment</strong></td>
						 	<td align="left" valign="top" id="table_mc_equipment" >&nbsp;</td>
					      </tr>
					      <tr>
						    <td align="left" valign="top" ><strong>Minimum Spend</strong></td>
						 	<td align="left" valign="top" id="table_mc_minSpend" >&nbsp;</td>
					      </tr>
					      <tr id="table_mc_downloadSpeed_tr">
						    <td align="left" valign="top" ><strong>Download Speed</strong></td>
						 	<td align="left" valign="top" id="table_mc_downloadSpeed" >&nbsp;</td>
					      </tr>
					      <tr id="table_mc_uploadSpeed_tr">
						    <td align="left" valign="top" ><strong>Upload Speed</strong></td>
						 	<td align="left" valign="top" id="table_mc_uploadSpeed" >&nbsp;</td>
					      </tr>
						  <tr>
						    <td align="left" valign="top" ><strong>Conditions</strong></td>
						    <td align="left" valign="top"  id="table_mc_conditions">&nbsp;</td>
					      </tr>
		                  <tr id="table_mc_homephone_tr">
						    <td align="left" valign="top" ><strong>Home Phone</strong></td>
						    <td align="left" valign="top"  id="table_mc_homephone">&nbsp;</td>
					      </tr>
		                  <tr id="table_mc_nationalCallCost_tr">
						    <td align="left" valign="top" ><strong>National Calls</strong></td>
						    <td align="left" valign="top"  id="table_mc_nationalCallCost">&nbsp;</td>
					      </tr>
		                  <tr id="table_mc_mobileCallCost_tr">
						    <td align="left" valign="top" ><strong>Calls To Same Supplier Mobile</strong></td>
						    <td align="left" valign="top"  id="table_mc_mobileCallCost">&nbsp;</td>
					      </tr>
						</table>
					</div>
					<div class="detailspopup_MiddlePart_Table_right">
						<span >Minimum total cost</span>
						<table width="300" border="0" cellspacing="0" cellpadding="0">
						  <tr>
						    <td align="left" valign="top" width="100"><strong>Monthly Price</strong></td>
						 	<td align="left" valign="top" id="table_mc_monthlyPrice" width="200">&nbsp;</td>
					      </tr>
					      <tr>
						    <td align="left" valign="top" ><strong>Installation Price</strong></td>
						 	<td align="left" valign="top" id="table_mc_installationPrice" >&nbsp;</td>
					      </tr>
					      <tr>
						    <td align="left" valign="top" ><strong>Equipment Price</strong></td>
						 	<td align="left" valign="top" id="table_mc_equipmentPrice" >&nbsp;</td>
					      </tr>
						</table>
					</div>
					<div class="detailspopup_MiddlePart_Table_right">
						<span >Additional Features</span>
						<table width="300" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td align="left" valign="top" width="100"><strong>--</strong></td>
							<td align="left" valign="top" width="200">&nbsp;</td>
						  </tr>
						</table>
					</div>
					<div class="detailspopup_MiddlePart_Table_right">
						<span >Additional Information</span>
						<table width="300" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td align="left" valign="top" width="100"><strong>--</strong></td>
							<td align="left" valign="top" width="200">&nbsp;</td>
						  </tr>
						</table>
					</div>
				</div>
			</div>
        </div>
	</div>
</div>
 -->