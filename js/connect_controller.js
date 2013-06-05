/**
 * 
 */

var Controller = function()
{
	var CV_obj;
	var CM_obj;
    var api_url;
	var tracker;
	var main_divs;
	var controller;
	var prevDiv;
	var ip_add;
	var email_add;
	var screen;
	var track_info;
	var product_html_div;
	var category_brands;
	var allCategory;
	var no_of_category;
	var track_data;
	var noproduct_supplier;
	var total_price_per_month = 0;
	var total_fixed_price = 0;
	var temp_price_per_month = 0;
	var temp_total_fixed_price = 0;
	var image_PATH = "";
	var recommended_flag = 0;
	var allproduct;
	var selection;
	var accordian_flag= 0;
	var total_monthly_price_in_new_selection = 0;
	var total_oneoff_price_in_new_selection = 0;
	
	var navigation_in_basket = new Array();

	var recommend_select_total_price = 0;
	var recommend_select_total_fixed_price = 0;

	var delay;
//	var liIndex;

	var carouselFlag = "PLAY";

	var final_prices = 0;

	this.init = function (trackdata, categories, closeImg,apiurl)
	{
		try
		{
			controller = this;
	       	CM_obj	= new Model();
			CV_obj	= new View();
			selection = new Array();
			tracker = new Array();
			main_divs = new Array();
			allproduct = new Array();
		    api_url = apiurl;

			delay = 4000;
			liIndex = 0;

			product_html_div = new Array();

			CM_obj.setEmail(trackdata.Email);
			track_data = trackdata;
			ip_add = CM_obj.getClientIP();
			email_add = CM_obj.getEmail();
			noproduct_supplier = new Array();
			category_brands = new Array();
			screen = "HOME_CONTENT_SCREEN";

			allCategory	=	new Array();

			if(categories != null)
			{
				allCategory	=	categories;
				no_of_category	=	allCategory.length;
			}
			
			//created arrays to store selected products basket html [for navigation purpose)
			categories.each(function(value,index)
					{
					navigation_in_basket[value] = new Array();
					});

			track_info = new Array(ip_add, email_add, screen, "FILLED ADRESS", "VALIDATION OF ADRESS FIELD");
			tracker.push(track_info);

			track_info = new Array(ip_add, email_add, screen, "CLICKED_"+trackdata.decidedto, "ENTER INTO MAIN ACCORDIAN");
			tracker.push(track_info);

			//SDs added for getting Categories here
			if(categories != null)
			{
				categories.each(function(value, index)
				{
					if(document.getElementById(value))
					{
						document.getElementById(value).checked =false;
					}
				});
			}

			if(document.getElementById("category-accordion-div"))
			{
				controller.accordion("#category-accordion-div");
			}
			/*if(document.getElementById("cumh_right"))
			{
				controller.accordion("#cumh_right");
			}*/
			if(document.getElementById("tool_tip_broadband"))
			{
				controller.clueTip("tool_tip_broadband", closeImg);
			}
			if(document.getElementById("tool_tip_tv"))
			{
				controller.clueTip("tool_tip_tv", closeImg);
			}
			if(document.getElementById("tool_tip_gaming"))
			{
				controller.clueTip("tool_tip_gaming", closeImg);
			}
			if(document.getElementById("tool_tip_electronics"))
			{
				controller.clueTip("tool_tip_electronics", closeImg);
			}
			if(document.getElementById("tool_tip_music"))
			{
				controller.clueTip("tool_tip_music", closeImg);
			}
			if(document.getElementById("tool_tip_1"))
			{
				this.clueTip("tool_tip_1", closeImg);
				// this.toolTip("#tool_tip_1");
			}

			this.initScreenDivs();
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	$(window).bind('beforeunload', function(event)
	{
		try
		{
			$.ajax(
			{
				url: 'index.php?option=com_homeconnect&task=createcsv&format=raw',
				type: 'post',
				async: false,
				data:
				{
					ip : CM_obj.getClientIP(),
					log : tracker,
					email: CM_obj.getEmail()
				},
				datatype: 'json',
				success: function(data)
				{
				}
			});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	});

	this.initScreenDivs = function()
	{
		try
		{
			if(document.getElementById("before-load"))
			{
				main_divs.push("#before-load");
			}
			if(document.getElementById("main-accordian-div"))
			{
				main_divs.push("#main-accordian-div");
			}
			if(document.getElementById("installation_div"))
			{
				main_divs.push("#installation_div");
			}
			if(document.getElementById("confirmation_div"))
			{
				main_divs.push("#confirmation_div");
			}
			if(document.getElementById("details_div"))
			{
				main_divs.push("#details_div");
			}
			if(document.getElementById("thank_div"))
			{
				main_divs.push("#thank_div");
			}
			if(document.getElementById("recommendation_div"))
			{
				main_divs.push("#recommendation_div");
			}

			controller.showDiv("#main_accordian_div");
			controller.switchDivs("#main-accordian-div");

			CM_obj.setCurrentState("#category-accordion-div");
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.accordion = function (id)
	{
		try
		{
			$( id ).accordion(
			{
				header: "> div > h3",
				heightStyle: "content",
				duration: 10,
				animated: 'easeInCirc'
			});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}

		//SDs commnented to remove effect
		// Aks : Added To Set Selected accordion on top
		//$(id+" h3").click(function(event)
		//{
		 //   $(this).best('div').prependTo(id);
		//});
	};

	//	SD changed next accordion fn to update last accordion i.e installation screen 
	this.nextAccordion = function (main_accordion_id, index , last_index,category)
	{
		try
		{
			accordian_flag = 1;
			controller.SelectionData(category);
			/*var selected = CM_obj.getSelectedProducts();
			var params ="category="+category+"&selection=";
			var selected_values = "";

			if(selection.length > -1  && selected.length > -1 && selection.toString()!= selected.toString() )
			{
				selection = [];
				selected.each(function(value,index)
				{
					selection.push(value);
					params += value[0]+'/'+value[1]+'/'+value[3]+',';
					selected_values += value[0]+'/'+value[1]+'/'+value[3]+',';
				});

				$.ajax(
				{
					url: 'index.php?option=com_homeconnect&task=sendSelectionChanges&format=raw',
					type: 'post',
					data:
					{
						category: category,
						selection: selected_values
					},
					datatype: 'json',
					success: function(data)
					{
						// var d = JSON.parse(data);
					}
				});
			}*/

			//this.setBrandsOfCategory(category);

			track_info = new Array(ip_add, email_add, screen, "FORWORD_CLICK", "ENTER TO CATEGORY '' FROM CATEGORY ''");
			tracker.push(track_info);

			$('#ui-accordion-'+main_accordion_id+'-header-'+index).click();

			if(index == last_index)
			{
				controller.updateDiv('installation_div');
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.clueTip = function (className, closeImg)
	{
		try
		{
			$('a.'+className).cluetip(
			{
				local: true,
				sticky: true,
				showTitle: false,
				cursor: 'pointer',
				mouseOutClose: false,
				positionBy: 'bottomTop',
				topOffset: 2,
				leftOffset: -200,
				onShow: function()
				{
					// close cluetip when users click outside of it
					$(".close").click(function(e)
					{
						// Not Needed
						// var isInClueTip = $(e.target).best('#cluetip');
						// if (isInClueTip.length === 0)
						// {
							$('.cluetip-default').hide();
						// }
					})
				},
				width: '240px'
				/*,
				closePosition: 'title',
				closeText: '<img style="float: right; margin: 5px 5px 5px 250px;" src="templates/beez_20/images/close.png" alt="Close">'
				*/
			});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.toolTip = function (id)
	{
		try
		{
			$( id ).tooltip(
			{
				show: null,
				position:
				{
					my: "left top",
					at: "left bottom"
				},
				open: function( event, ui )
				{
					ui.tooltip.animate({ top: ui.tooltip.position().top + 10 }, "fast" );
				}
			});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs TLD email validation
	this.checkEmail = function (email)
	{
		try
		{
			if(email == "")
			{
				return true;
			}
			else
			{
				var tlds = ".ac .ad .ae .af .ag .ai .al"+
							".am .an .ao .aq .ar .as .at .au .aw .ax"+
							".az .ba .bb .bd .be .bf .bg .bh .bi .bj"+
							".bm .bn .bo .br .bs .bt .bu .bv .bw .by"+
							".bz .ca .cc .cd .cf .cg .ch .ci .ck .cl"+
							".cm .cn .co .cr .cs .cu .cv .cx .cy .cz"+
							".dd .de .dj .dk .dm .do .dz .ec .ee .eg"+
							".eh .er .es .et .eu .fi .fj .fk .fm .fo"+
							".fr .ga .gb .gd .ge .gf .gg .gh .gi .gl"+
							".gm .gn .gp .gq .gr .gs .gt .gu .gw .gy"+
							".hk .hm .hn .hr .ht .hu .id .ie .il .im"+
							".in .io .iq .ir .is .it .je .jm .jo .jp"+
							".ke .kg .kh .ki .km .kn .kp .kr .kw .ky"+
							".kz .la .lb .lc .li .lk .lr .ls .lt .lu"+
							".lv .ly .ma .mc .md .mg .mh .mk .ml .mm"+
							".mn .mo .mp .mq .mr .ms .mt .mu .mv .mw"+
							".mx .my .mz .na .nc .ne .nf .ng .ni .nl"+
							".no .np .nr .nu .nz .om .pa .pe .pf .pg"+
							".ph .pk .pl .pm .pn .pr .ps .pt .pw .py"+
							".qa .re .ro .ru .rw .sa .sb .sc .sd .se"+
							".sg .sh .si .sj .sk .sl .sm .sn .so .sr"+
							".st .su .sv .sy .sz .tc .td .tf .tg .th"+
							".tj .tk .tl .tm .tn .to .tp .tr .tt .tv"+
							".tw .tz .ua .ug .uk .um .us .uy .uz .va"+
							".vc .ve .vg .vi .vn .vu .wf .ws .ye .yt"+
							".yu .za .zm .zr .zw .com .net .org .mil"+
							".gov .edu .nato .info .int .name .biz "+
							".museum .pro";

				email = email.replace(/^\s+|\s+$/, '');
				var rex = new RegExp("^[A-Za-z0-9\.\-_]+@[A-Za-z0-9\.\-_]+\.[A-Za-z]+$");
				var endofString = email.split('.');
				var ending = endofString.length - 1;
				var tld = endofString[ending];

				if (! email.match(rex))
				{
					return false;
				}
				else if (tlds.search(tld) < 0)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.switchDivs = function (id)
	{
		try
		{
			track_info = new Array(ip_add, email_add, screen, "FORWORD_CLICK","ENTER TO SCREEN '' FROM SCREEN ''");
			tracker.push(track_info);

			CM_obj.setCurrentState(id);

			if(CM_obj.getValidatedAddress() != "ERROR")
			{
				var useremail = document.getElementById("email").value;
				if (controller.checkEmail(useremail))
				{
					main_divs.each(function(value, index)
					{
						controller.hideDiv(value);
						if(id == value)
						{
							controller.showDiv(value);
						}
						if(value == "#thank_div")
						{
							controller.createThankpage();
						}
					});
				}
				else
				{
					var errorhtml="not a valid email !!!!";
					if(document.getElementById("email_error_div"))
					{
						document.getElementById("email_error_div").innerHTML = errorhtml;
					}
				}
			}
			else
			{
				var errorhtml="not a valid postcode !!!!";
				document.getElementById("address_error_div").innerHTML = errorhtml;
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.initSwitchInnerDivs = function (h_ids, s_ids, cat_id, product_id, sub_cat_name, brand_name)
	{
		try
		{
			// following if block is written for preventing recreation of product html
			if(product_html_div.indexOf(s_ids) < 0)
			{
				product_html_div.push(s_ids);
				controller.switchInnerDivs(h_ids, s_ids, product_id, cat_id);
				controller.setProductsOfCategory(h_ids, s_ids, sub_cat_name, brand_name);
			}
			else
			{
				if(noproduct_supplier.indexOf(s_ids)< 0)
				{	
					controller.switchInnerDivs(h_ids, s_ids, product_id, cat_id);
				}
				else
				{
					document.getElementById(s_ids).innerHTML = CV_obj.getLoadingHTML();
					document.getElementById(s_ids).style.display="none";
					document.getElementById(h_ids).style.display="block";
					controller.displayerror('error_message', "No product Found");
				}
			}
			//controller.switchInnerDivs(h_ids, s_ids, product_id, cat_id);
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// modified by SDs for switch multiple inner divs
	this.switchInnerDivs = function (h_ids, s_ids, product_id, cat_id)
	{
		try
		{
			if(h_ids != 'details_div')
			{
				s_ids = controller.allowNavigation(h_ids,s_ids);
			}

			CM_obj.setCurrentState(s_ids);

			hide_ids = h_ids.split(",");
			show_ids = s_ids.split(",");
			
			hide_ids.each(function(value, index)
			{
				controller.hideDiv("#"+value);
				
			});

			show_ids.each(function(value, index)
			{
				controller.showDiv("#"+value);
				
			});

			if(show_ids == "confirmation_div" || show_ids == "recommended_div"  || show_ids == "installation_div")
			{
				if(show_ids == "installation_div"){
					document.getElementById("left").className = "";
				}
				controller.updateDiv(show_ids);
			}

			if(show_ids == "installation_div")
			{
				if(document.getElementById('cumh_right'))
				{
				  document.getElementById('cumh_right').style.display="none";
				}
				document.getElementById('left').style.width='97%';
			}

			if(show_ids == 'details_div')
			{
			    //SDs for add multiroom selection prices
				controller.multiRoom_selection();
			    controller.updateDiv(show_ids);

				if(document.getElementById('cumh_right'))
				{
				  document.getElementById('cumh_right').style.display="block";
				}
				document.getElementById('left').style.width='665px';
			  /*if(document.getElementsByClassName('.edit'))
				{
				  $('.edit').css('display', 'none');
				}*/
			}
				
			if(show_ids =='main-category-div')
			{
				if(document.getElementById("main_accordian_title"))
				{
					document.getElementById("main_accordian_title").style.display="block";
				}
				if(document.getElementById('cumh_right'))
				{
					document.getElementById('cumh_right').style.display="block";
					document.getElementById('left').style.width='665px';
					document.getElementById("left").className = "";
					
				}
				if(document.getElementsByClassName('.edit'))
				{
					$('.edit').css('display', 'block');
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.setProductsOfCategory = function (prev_id, div_id, sub_cat_name, brand_name)
	{
		try
		{
			track_info = new Array(ip_add, email_add, screen, "_CLICK", "BRANDS DISPLAYED");
			tracker.push(track_info);

	   		if(document.getElementById(div_id))
			{
				document.getElementById(div_id).style.height = "425px";
				document.getElementById(div_id).innerHTML = CV_obj.getLoadingHTML();
			}
			var sub_cat		= sub_cat_name.toLowerCase();
			sub_cat = 	sub_cat.replace(' ','-');
			var acc_index	= allCategory.indexOf(sub_cat)+1;
			var sup_cat_name = "";
			$.ajax(
			{
				url: 'index.php?option=com_homeconnect&task=getProductsDataFromAPI&format=raw',
				type: 'post',
				data:
				{
					sup_cat_name : sup_cat_name,
					sub_cat_name : sub_cat_name,
					brand_name	:  brand_name,
					next_id		:  div_id,
					prev_id		:  prev_id,
					noc 		: no_of_category,
					accordion_no: acc_index
				},
				datatype: 'json',
				success: function(data)
				{
					var d = JSON.parse(data);
					d.results.each(function(value,index)
					{
						allproduct.push(value);	
					});
				
					if(d=="error_empty")
					{
						noproduct_supplier.push(div_id);
						document.getElementById(div_id).style.display="none";
						document.getElementById(prev_id).style.display="block";
						controller.displayerror('error_message', "No product Found");
					}
					else
					{	
						if(document.getElementById(div_id))
						{
							document.getElementById(div_id).style.height = "";
							document.getElementById(div_id).innerHTML = d.html;
						}
					}
				}
			});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.showDiv = function (id)
	{
		CM_obj.setCurrentState(id);

		// get effect type from
		var selectedEffect = "fade";

		// most effect types need no options passed by default
		var options = {};

		// some effects have required parameters
		if ( selectedEffect === "scale" )
		{
			options = { percent: 100 };
		}
		else if ( selectedEffect === "size" )
		{
			options = { to: { width: 280, height: 185 } };
		}

		// run the effect
		$( id ).show( selectedEffect, options, 1000, callback );

		// callback function to bring a hidden box back
		function callback()
		{
			/* Aks : No need to display again
			setTimeout(function()
			{
				$( "#effect:visible" ).removeAttr( "style" ).fadeOut();
			}, 1000 );
			*/
		};
	};

	this.hideDiv = function (id)
	{
		// get effect type from
		var selectedEffect = "fade";

		// most effect types need no options passed by default
		var options = {};

		// some effects have required parameters
		if ( selectedEffect === "scale" )
		{
			options = { percent: 0 };
		}
		else if ( selectedEffect === "size" )
		{
			options = { to: { width: 200, height: 60 } };
		}

		// run the effect
		$( id ).hide( selectedEffect, options, 100, callback );

		// callback function to bring a hidden box back
		function callback()
		{
			/* Aks : No need to display again
			setTimeout(function()
			{
				$( id ).removeAttr( "style" ).hide().fadeIn();
			}, 1000 );
			*/
		};
	};

	// SDs for back navigation from details page
	this.switchInnerPrevDivs = function(source_div, prevDiv)
	{
		try
		{
			track_info = new Array(ip_add, email_add, screen, "FORWORD_CLICK", "ENTER TO CATEGORY "+source_div+" FROM CATEGORY "+prevDiv);
			tracker.push(track_info);

			CM_obj.setCurrentState(prevDiv);

			if(prevDiv =='confirmation_div' && recommended_flag == 1)
			{
				total_fixed_price = 0;
				// total_fixed_price = temp_total_fixed_price;

				total_price_per_month = 0;
				// total_price_per_month = temp_total_price_per_month;

				var yourselection = CM_obj.getSelectedProducts();
				var tempselection = CM_obj.getTempStoredSelection();

				for(var i = 0; i < yourselection.length; i++)
				{
					// CM_obj.addToTempStore(yourselection[i]);
					$('#basket'+yourselection[i][3]).remove();
				}
				CM_obj.deleteAllProductSelection();

				tempselection.each(function(value,index)
				{
					controller.AddToMyBundleC(value,image_PATH);	
				});
			}
			controller.switchInnerDivs(source_div, prevDiv, 0, 0);
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs for addto my bundle
	this.AddToMyBundle = function(product_detail, img_src)
	{
		
		try
		{
			image_PATH = img_src;

			// controller.productRemove(product_detail);
			// here product detail is  string contain 4 details like category,brand,pname, product id 

			var cat_product = product_detail.split(",");
			var selected_product= CM_obj.getSelectedProducts();

			cat_product[1]= cat_product[1].toLowerCase();
			cat_product[1] = cat_product[1].replace(' ','-');

			var checkid = 'add-'+cat_product[0]+'-'+cat_product[1]+'-'+cat_product[3];

			// shyam commented to use checkbox
			/* if(document.getElementById(removebtn))
			{
				document.getElementById(removebtn).style.display='block';
			}*/
			if(document.getElementById(checkid).checked)
			{
				if(CM_obj.indexOfArray(cat_product, selected_product) < 0)
				{
					track_info = new Array(ip_add, email_add, screen, "CHECK_PRODUCT", "PRODUCT SELECTED UNDER CATEGORY");
					tracker.push(track_info);

					CM_obj.setAddToMyBundleResult(cat_product);
					controller.calculateAndDisplayTotalPrice(cat_product,'+');

					var accordion_id = allCategory.indexOf(cat_product[0]);
					if(document.getElementById('basket-noproduct-'+cat_product[0]))
					{
						$('#basket-noproduct-'+cat_product[0]).remove();
					}
					
					var price1= (cat_product[7] != "" && cat_product[7] != null)? (cat_product[7]).replace('$',''):"0.00";
					var equipmentPrice =(cat_product[8] != "" && cat_product[8] != null)? (cat_product[8]).replace('$',''):"0.00";

					if(cat_product[8] != null && cat_product[8] != "" )
					{
						price1 = parseFloat(parseFloat(price1).round(2)+parseFloat(equipmentPrice).round(2)).round(2); //parseFloat().round(2)
					}

					var installationPrice =(cat_product[9] != "" && cat_product[9] != null)? (cat_product[9]).replace('$',''):"0.00";

					if(cat_product[9] != null && cat_product[9] != "" )
					{
						price1 = parseFloat(parseFloat(price1).round(2)+parseFloat(installationPrice).round(2)).round(2); //parseFloat().round(2)
					}

					if(price1 == "0" || price1 == "0.00")
						price1 = 'NA';
					else
						price1 = "$"+price1;

					var price2 = cat_product[6];
					if(cat_product[6] == "")
					{
						price2 = 'NA';
					}

					var equipInstallPrice = parseInt(equipmentPrice) + parseInt(installationPrice);

					if(equipInstallPrice > 0){
						equipInstallPrice = "<br/>Equipment & InstallationPrice $"+equipInstallPrice;
					}
					else
					{
						equipInstallPrice ="";
					}
					if(cat_product[6]=="")
						price2='NA';
					var addPrdQuota = cat_product[5].trim();

					if(addPrdQuota == undefined || addPrdQuota == null){
						addPrdQuota = "";
					}

					var contract = cat_product[4];

					if(contract != "")
						contract = "<br/>"+contract;
					else
						contract = "";

					var brandname = "";
					var title = controller.capitaliseFirstLetter(cat_product[2].replace(/-/g, ' '));
					allproduct.each(function(value, index)
					{
						if(value.id == cat_product[3])
						{
							brandname = value.supplier;
							title = value.title;
						}
					});

					var image1="";
					if(brandname.indexOf('+') > 0)
					{
					  var img = brandname.split('+');
					  img[0]= img[0].trim().toLowerCase();
					  img[1]= img[1].trim().toLowerCase();
					  image1='<img src="'+image_PATH+img[0]+'.jpg" alt='+img[0]+'><img src="'+image_PATH+img[1]+'.jpg" alt='+img[1]+'>'
					}
					else
					{
						image1='<img src="'+image_PATH+cat_product[1]+'.jpg" alt='+cat_product[1]+'>';
					}

					var html = 	'<div id="basket'+cat_product[3]+'" class="prowrap"><div class="prowrap_left">'+
									'<div class="brandlogobg"><div class="brandlogo">'+image1+
									'</div></div>'+
									'<div class="edit">'+
									'<img src="templates/beez_20/images/delete.gif" title="Delete" alt="Delete" onclick="javascript:controllerObj.productRemove(\''+product_detail+'\')" /> Delete'+
									'</div></div><div class="prowrap_right">'+
									'<div class="brandtext"><strong>'+title+'</strong><br>'+addPrdQuota+equipInstallPrice+contract+'</div>'+
									
									'<div class="price">One-off Price: '+price1+'</div>'+
									'<div class="price" id="monthly_price_'+cat_product[3]+'">Monthly Price: '+price2+'</div>'+
									'<div id="next_basket'+cat_product[3]+'" class="basket_nav"><img src="templates/beez_20/images/basket_next.png" onclick="javascript:controllerObj.navigateSelectionList(\'next\',\'basket'+cat_product[3]+'\',\''+cat_product[0]+'\')"></div>'+
									'<div id="prev_basket'+cat_product[3]+'" class="basket_nav"><img src="templates/beez_20/images/basket_prev.png" onclick="javascript:controllerObj.navigateSelectionList(\'prev\',\'basket'+cat_product[3]+'\',\''+cat_product[0]+'\')"></div>'+
									'</div></div>';
					if(navigation_in_basket[cat_product[0]].indexOf('basket'+cat_product[3]) < 0)
					{
						navigation_in_basket[cat_product[0]].push('basket'+cat_product[3]);
					}

					$("#inner-brands-"+cat_product[0]).append(html);
					/*
					if(navigation_in_basket[cat_product[0]].indexOf('basket'+cat_product[3])== 0)
					{
						document.getElementById('prev_basket'+cat_product[3]).style.display="block";
						document.getElementById('next_basket'+cat_product[3]).style.display="block";
					}
					else
					{
					    var index = navigation_in_basket[cat_product[0]].indexOf('basket'+cat_product[3]);

					    document.getElementById(navigation_in_basket[cat_product[0]][index-1]).style.display="none";
						document.getElementById('next_basket'+cat_product[3]).style.display="block";
					}
					*/
					controller.navigateSelectionList('update', 'basket'+cat_product[3], cat_product[0]);

					controller.viewtoMybundle(cat_product[0], selected_product);
				}
			}
			else
			{
				controller.productRemove(product_detail);
			}
			// cat_product[1] is subcategory which is binded in rightside myselections div id
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};
	
	this.ParseUsAmount =function(value){
		if(value == "" || value == null)
			return (value).replace('$','');
		else
			return "0.00";
	}

	// SDs for view in mybundle section
	this.viewtoMybundle = function(sub_cat, selected_product)
	{
		try
		{
			var brands = new Array();
			no_of_selection = 0;

			for(var i = 0; i < selected_product.length; i++)
			{
				if(selected_product[i][0] == sub_cat)
				{
					brands.push(sub_cat);
					no_of_selection = no_of_selection + 1;

					if(brands.indexOf(selected_product[i][1], brands) < 0)
					{
						brands.push(selected_product[i][1]);
					}
				}
			}
			/*
			$('#inner-brands-'+sub_cat ).innerHTML="";
			brands.each(function(value)
			{
				if( selected_product.length != 0)
				{
					//$('#inner-brands-'+sub_cat ).append('<a href="">'+value+'</a>');
				}
			});*/

			if(document.getElementById('checkbox-'+(allCategory.indexOf(sub_cat)+ 1)))
			{
				$('#checkbox-'+(allCategory.indexOf(sub_cat)+ 1)).attr('checked',true);
				//$('#checkbox-'+(allCategory.indexOf(sub_cat)+ 1)).attr('disabled',true);
			}

			controller.updateNavigationList(sub_cat);
		}
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	// SDs for go to respecive selection page from my bundle section view edit
	this.switchtoSelectionpage = function(category, supplier, accordion_id, brand_id)
	{
		try
		{
			track_info = new Array(ip_add, email_add, screen, "EDIT_CATEGORY", "PRODUCT SELECTION EDITED UNDER CATEGORY");
			tracker.push(track_info);

			if(CM_obj.getCurrentState() == "#category-accordion-div")
			{
				// controller.hideDiv('#category-accordion-div');
				controller.showDiv('#category-accordion-div');
				controller.nextAccordion('category-accordion-div', accordion_id, no_of_category, category);
				controller.showDiv('#main-category-div');
				document.getElementById('main-category-inner-div-'+category).style.display = "block";

				// SDs commented to show edit flow only up to brand level
				if(brand_id == 1 || brand_id != 0)
				{
					controllerObj.initSwitchInnerDivs('main-category-inner-div-'+category,'main-category-inner-product-div-'+category+'-'+supplier, accordion_id+1, brand_id, category, supplier);
				}
			}
			else
			{
				
				controller.hideDiv(CM_obj.getCurrentState());
				// controller.hideDiv('#category-accordion-div');
				controller.showDiv('#main-category-div');
				controller.showDiv('#category-accordion-div');
				controller.nextAccordion('category-accordion-div',accordion_id,no_of_category, category);
               if(document.getElementById('main-category-inner-div-'+category))
            	   {
            	   	document.getElementById('main-category-inner-div-'+category).style.display = "block";
            	   }
				if(brand_id == 1 || brand_id != 0)
				{
					controllerObj.initSwitchInnerDivs('main-category-inner-div-'+category,'main-category-inner-product-div-'+category+'-'+supplier, accordion_id+1, brand_id, category, supplier);
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.capitaliseFirstLetter= function(string) 
	{
		var CapString = "";
		var stringParts = string.split(" ");
		for(var index=0; index < stringParts.length;index++)
		{
			if(index > 0)
				CapString += " ";
			if(stringParts[index].length > 0)
			{
				CapString +=  stringParts[index].charAt(0).toUpperCase() + stringParts[index].slice(1); 
			}
		}
	    return CapString;
	}

	// SDs for set confirmation page on selected product and set installation page also [* reviewed ]
	this.updateDiv = function(id)
	{
		try
		{
			if(id == "confirmation_div")
			{
				var category_selection = allCategory[allCategory.length-1]; //getting last accordion category
				controller.SelectionData(category_selection);
				var i = 0;
				document.getElementById('cumh_right').style.display = "none";

				$('#confirmyourselection').attr('checked',false);
				$('#confirmnewselection').attr('checked',false);

				document.getElementById("left").className = "confirmationleft";
				document.getElementById("main_accordian_title").style.display = "none";

				track_info = new Array(ip_add, email_add, screen, "FORWORD_CLICK", "ENTER TO CONFIRMATION PAGE");
				tracker.push(track_info);
				
				for(var k = 1; k <= allCategory.length; k++)
				{
					document.getElementById("c"+k).className = "no_product";
				}
				var ig = 0;
				allCategory.each(function(sub_cat,index)
				{
					var selected = CM_obj.getSelectedProducts();
					i++;

					// Aks : Change to position drag cursor and product div
//					var html = '<ul id="ul_'+i+'" class="columns" style="position: inherit !important; padding:none !important">';
					var html = '';

					for(var j = 0; j < selected.length; j++)
					{
						if(sub_cat == selected[j][0])
						{
							var inf = "";
							inf += '<div class="info"><div class="subinfodiv">'+
							'<strong>'+title+'</strong><br>'+
							'<span class="hovercategory">'+controller.capitaliseFirstLetter(selected[j][0])+' </span><br>';
							if(selected[j][5]!=0)
							{
								inf+='<span class="hoverquota">'+selected[j][5]+'</span>';
							}
							if(selected[j][4]!=0)
							{
								inf+='<span class="hoverdescription">'+selected[j][4]+'  contract</span>';
							}
							if(selected[j][6]!=0)
							{
								inf+='<div class="hoveramount"><span>'+selected[j][6]+'</span>  per month</div>';
							}
							if(selected[j][7]!=0)
							{
								inf+='<span class="hoveramount span">Minimum total cost '+selected[j][5]+'</span>';
							}
							inf+='</div></div>';
							ig++;
							//following lines added to calculate and correct display in formation in tile [addition of installation,price,equipment price and display as one off in tile
							//*add calculation price start  here
							var price1= (selected[j][7] != "" && selected[j][7] != null)? (selected[j][7]).replace('$',''):"0.00";
							var equipmentPrice =(selected[j][8] != "" && selected[j][8] != null)? (selected[j][8]).replace('$',''):"0.00";
							var installationPrice =(selected[j][9] != "" && selected[j][9] != null)? (selected[j][9]).replace('$',''):"0.00";
							if(selected[j][8] != null && selected[j][8] != "" )
							{
								price1 = parseFloat(parseFloat(price1).round(2)+parseFloat(equipmentPrice).round(2)).round(2); //parseFloat().round(2)
							}

							if(selected[j][9] != null && selected[j][9] != "" )
							{
								price1 = parseFloat(parseFloat(price1).round(2)+parseFloat(installationPrice).round(2)).round(2); //parseFloat().round(2)
							}
							if(price1=="0" || price1=="0.00")
								price1='';
							else
								price1 = "One-Off Price: <span class='ulstrong'>$"+price1+'</span></br>';

							var equipInstallPrice = parseInt(equipmentPrice) + parseInt(installationPrice);

							if(equipInstallPrice > 0){
								equipInstallPrice = "<br/>Equipment & InstallationPrice $"+equipInstallPrice;
							}
							else
							{
								equipInstallPrice = "";
							}

							var price2 = selected[j][6];
							if(selected[j][6]=="")
								price2='';
							else
								price2 = "Monthly Price: <span class='ulstrong'>"+price2+'</span></br>';
							var addPrdQuota = selected[j][5].trim();
							if(addPrdQuota == undefined || addPrdQuota == null || addPrdQuota=="")
							{
								addPrdQuota = "";
							}
							else
							{	
								addPrdQuota = 	"Quota: "+addPrdQuota+'</br>';
							}
							var contract = selected[j][4];
							if(contract != "")
								contract = 'Contract: '+contract+'<br>';
							else
								contract = "";

							// *add calculation price ends  here
							var brandname="";
							var title = controller.capitaliseFirstLetter(selected[j][2].replace(/-/g, ' '));
							allproduct.each(function(value, index)
							{
								if(value.id == selected[j][3])
								{
									brandname = value.supplier;
									title = value.title;
								}
							});
							var image1 = "";
							var imagepath = "";
							if(brandname.indexOf('+') > 0)
							{
								var img = brandname.split('+');
								img[0] = img[0].trim().toLowerCase();
								img[1] = img[1].trim().toLowerCase();
								image1='<img class="two_imageSize" src="images/small_logo/'+img[0]+'.jpg" alt='+img[0]+'><img style="two_imageSize" src="images/small_logo/'+img[1]+'.jpg" alt='+img[1]+'>';
								imagepath = 'images/small_logo';
							}
							else
							{
								image1='<img class="imageSize" src="images/main_accordian/'+selected[j][1]+'.jpg" alt='+selected[j][1]+'>';
								imagepath="images/main_accordian";
							}

							// Aks : Change to position drag cursor and product div
//							html += "<li><div id='myselection_"+ig+"' class='RecProduct addDraggableProduct' style='margin:0px;'><div  class='myselection_"+ig+"' data-product-detail='"+selected[j][0]+","+selected[j][1]+","+selected[j][2]+","+selected[j][3]+","+selected[j][4]+","+selected[j][5]+","+selected[j][6]+","+selected[j][7]+","+selected[j][8]+","+selected[j][9]+"'>"+
							html += "<div id='myselection_"+ig+"' class='RecProduct addDraggableProduct' style='margin:0px;'><div  class='myselection_"+ig+"' data-product-detail='"+selected[j][0]+","+selected[j][1]+","+selected[j][2]+","+selected[j][3]+","+selected[j][4]+","+selected[j][5]+","+selected[j][6]+","+selected[j][7]+","+selected[j][8]+","+selected[j][9]+"'>"+
									"<ul class='columns' style='position: inherit !important; padding:none !important'>" +
									"<li  style='padding:none !important;margin-left:px;'>" +
									"<a style='display:block;width:180px;height:70px'>"+image1+"</a>"+
							        "<div ><span class='hovertitle'>"+title+"</span><br>"+price2+price1+contract+addPrdQuota+
							        "<div class='showdetails'>" +
							        "<a onclick=\"javascript:controllerObj.detailspopupwindow('"+selected[j][3]+"','"+imagepath+"')\">Details</a>" +
							        "</div></div>";

							html += '<div class="info"><div class="subinfodiv" style="line-height: 1.2em;">'+
									'<span class="hovertitle">'+title+'</span><br>'+
									'<span class="hovercategory">'+controller.capitaliseFirstLetter(selected[j][0])+' </span><br>';

							if(selected[j][5]!=0)
							{
								html+='<span class="hoverquota">'+selected[j][5]+'</span><br>';
							}
							if(selected[j][4]!=0)
							{
								html+='<span class="hoverdescription">'+selected[j][4]+'  contract</span><br>';
							}
							if(selected[j][6]!=0)
							{
								html+='<div class="hoveramount"><span>'+selected[j][6]+'</span>  per month</div><br>';
							}
							if(selected[j][7]!=0)
							{
								html+='<span class="hoveramount span">Minimum total cost '+selected[j][5]+'</span><br>';
							}
							// Aks : Change to position drag cursor and product div
//							html+='</div></div></li></ul></div></div></li>';
							html+='</div></div></li></ul></div></div></li>';

							document.getElementById("c"+(allCategory.indexOf(sub_cat)+ 1)).className = "product";
						}
					}
					// Aks : Change to position drag cursor and product div
//					html += '</ul>';
					html += '';

					$("#c1" + i).html(html);

					if(document.getElementById('monthlyprice_yourselection_row'))
					{
						document.getElementById('monthlyprice_yourselection_row').innerHTML = "<strong>$"+total_price_per_month+"</strong>";
				    }
					if(document.getElementById('oneoff_yourselection_row'))
				    {
						document.getElementById('oneoff_yourselection_row').innerHTML = "<strong>$"+total_fixed_price+"</strong>";
				    }

					// Aks : Change to position drag cursor and product div
//					controller.startCustomSlider("ul_" + i);
					controller.startCustomSlider("c1" + i, "div");

					controller.initDragNDrop();
				});
			}

			if(id == "installation_div")
			{
				document.getElementById('cumh_right').style.display="block";
				document.getElementById('left').className="";

				track_info = new Array(ip_add, email_add, screen, "FORWORD_CLICK", "ENTER TO INSTALLATION PAGE");
				tracker.push(track_info);

				var selected = CM_obj.getSelectedProducts();
				controller.SetInstallation_div();
				if(selected.length!=0)
				{
					selected.each(function(record, index)
					{
						$('#category_check_'+record[0]).attr('checked',true);
					});
				}
				else
				{
					allCategory.each(function(sub_cat, index)
					{
						$('#category_check_'+sub_cat).attr('disabled',true);
					});
				}
				document.getElementById('installation_div').className = "";
			}
			if(id == 'recommended_div')
			{
				if(document.getElementById('cumh_right'))
				{
					document.getElementById('cumh_right').style.display="none";
				}
//				controller.startSlider();
				controller.initDragNDrop();
			}
			var selected_product = CM_obj.getSelectedProducts();

			for(var i = 0; i < selected_product.length; i++)
			{
				$('#'+selected_product[i][0]).attr('checked',true);
			}
				
			if(id=='details_div')
			{
				document.getElementById("left").className = "";
				document.getElementById('d_haddress').value=track_data.Adress;
				if(document.getElementById('d_email'))
				{
					document.getElementById('d_email').value=track_data.Email;
				}

				var address = document.getElementById('d_haddress').value.split(',');
				var addressLenght = parseInt(address.length);
				addressLenght--;

				document.getElementById('d_addressCountry').value = ""
				document.getElementById('d_addressCity').value = "";
				document.getElementById('d_addressState').value = "";

				//prefill of addresses required for now :SD
				for(var i = addressLenght;i >=0;i--) {
					if(i == (addressLenght))
						document.getElementById('d_addressCountry').value=address[i];
					else if (i == (addressLenght - 1))
						document.getElementById('d_addressState').value=address[i];
					else 
					{
						var substring = address[addressLenght-1]+','+address[addressLenght];
						var str = document.getElementById('d_haddress').value.substr(0,document.getElementById('d_haddress').value.indexOf(substring)- 1);

					    document.getElementById('d_addressCity').value= str;
					}
    //					else if(i== (addressLenght - 3))
    //						document.getElementById('d_address_suburb').value=address[i];
	//				else if(i== (addressLenght - 4))
	//					document.getElementById('d_address_streetadd').value=address[i];
	//				else
	//				  document.getElementById('d_address_streetno').value+=address[i]+" ";
				}

				if(document.getElementById("main_accordian_title"))
				{
					document.getElementById("main_accordian_title").style.display="none";
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.SetInstallation_div= function()
	{
		try
		{
			var selected = CM_obj.getSelectedProducts();

			var broadband_count = 0;
			var tv_content_count = 0;
			var games_count = 0;
			var music_content_count = 0;

			selected.each(function(record,index)
			{
				switch(record[0]){
					case "broadband":
						broadband_count++;
						break;
					case "tv-content":
						tv_content_count++;
						if(record[1].indexOf("foxtel") != -1 ){ $("#div_ShowsSomeTime").show(); }else { $("#div_ShowsSomeTime").hide(); }
						break;
					case "games":
						games_count++;
						break;
					case "music-content":
						music_content_count++;
						break;
					default:
						break;
				}
			});
			$('#category_check_broadband').attr('checked',false);
			$('#category_check_tv-content').attr('checked',false);
			$('#category_check_games').attr('checked',false);
			$('#category_check_music-content').attr('checked',false);
			$('#category_check_broadband').attr('disabled',true);
			$('#category_check_tv-content').attr('disabled',true);
			$('#category_check_games').attr('disabled',true);
			$('#category_check_music-content').attr('disabled',true);
		}
		catch(error){
			console.log('Error: '+error);
		}
	}

	// SDs for creating thank you page on selected brands [* reviewed]
	this.createThankpage = function()
	{
		try
		{
			var selected = CM_obj.getSelectedProducts();
			var brands = new Array();

			for(var i = 0; i < selected.length; i++)
			{
				if(brands.indexOf(selected[i][1], brands) < 0)
				{
					brands.push(selected[i][1]);
				}
			}
	   
			brands.each(function(value)
			{
				if( selected.length != 0)
				{
					$("#thank_div").append('<div  style="cursor: pointer; padding: 25px; float: left;"> <img src="images/main_accordian/'+value+'.jpg" class="imageSize"</div>');
				}
			});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs for Details page validation and forward to next //[reviewed * ]
	this.ValidateAndForword = function(targetdiv)
	{
		try
		{
			var name			= document.getElementById('d_name').value;
			var phone			= document.getElementById('d_phone').value;
			var email			= document.getElementById('d_email').value;
			var haddress		= document.getElementById('d_haddress').value;
			var installaddress	= document.getElementById('d_install_address').value;

			var error			= "";
			var textfields		= ["d_name", "d_phone", "d_email", "d_haddress", "d_install_address"];

			if(controller.isstring("d_name") && controller.isnumber("d_phone")
					&& controller.isvalidphone("d_phone") && controller.isEmail("d_email") && controller.emptyCheck("d_haddress")&& controller.emptyCheck("d_addressCity")  )
			{
				if(document.getElementById('d_accept_terms').checked)
				{
					CM_obj.setEmail(email);

					track_info = new Array(ip_add, email_add, screen, "FORWORD_CLICK", "VALIDATED FIELDS AND PREPARING MAIL");
					tracker.push(track_info);

					var subject	= "Your Order";
					var selected = CM_obj.getSelectedProducts();
					var mailbody = "";
					selected.each(function(value, index)
					{
						mailbody += value+'#$#';
					});

					///we have added three special character here as #$# so need to remove at last time
					mailbody = mailbody.substring(0, mailbody.length-1);
					mailbody = mailbody.substring(0, mailbody.length-1);
					mailbody = mailbody.substring(0, mailbody.length-1);
					mailbody = mailbody.replace(",",", ");

					var brands = new Array();
					
					var id="";
					var name="";
					for(var i = 0; i < selected.length; i++)
					{
						allproduct.each(function(value, index)
						{
							if(value.id == selected[i][3])
							{
								brandname = value.supplier;
								id= value.id;
								name=value.title;
								
								
								brands.push(id+'::'+brandname+'::'+name);
								
							}
						 });
					}

					document.getElementById('ip').value = CM_obj.getClientIP();
					document.getElementById('log').value = JSON.stringify(tracker);
					document.getElementById('data').value = mailbody;
					document.getElementById('emailid').value = CM_obj.getEmail();
					document.getElementById('subject').value = subject;
					document.getElementById('suppliers').value = JSON.stringify(brands);
					if(final_prices == '0')
						{
						final_prices= "0,0";
						}
					document.getElementById('final_prices').value = final_prices+','+$("#monthlycost").html()+','+$("#fixcost").html();


					document.getElementById('cumh_installer_help').value = false;

					if(controller.getRadioValue('foxtel') == 'Yes' || controller.getRadioValue('music') == 'Yes'
						|| controller.getRadioValue('gaming') == 'Yes' || controller.getRadioValue('broadband') == 'Yes'
						|| document.getElementById('drdl_TV_Connect_Devices').value > 0 || document.getElementById('drdl_TV_Connect_Rooms').value > 0
						|| document.getElementById('drdl_Music_Devices').value > 0 || document.getElementById('drdl_Music_Rooms').value > 0
						|| document.getElementById('tellusrequirement').value.length > 0)
					{
						document.getElementById('cumh_installer_help').value = true;
					}

					document.getElementById('customer_number').value = document.getElementById("d_phone").value;

					document.getElementById('customer_address').value = //document.getElementById("d_address_streetno").value+'#$#'
																		//+document.getElementById("d_address_streetadd").value+'#$#'
																		document.getElementById("d_addressCity").value+'#$#'
																		+document.getElementById("d_addressState").value+'#$#'
																		+document.getElementById("d_addressCountry").value;
																		//+document.getElementById("d_addressPincode").value;

					document.getElementById('customer_installation_address').value = //document.getElementById("d_address_streetnoC").value+'#$#'
																		//+document.getElementById("d_address_streetaddC").value+'#$#'
																		document.getElementById("d_addressCityC").value+'#$#'
																		+document.getElementById("d_addressStateC").value+'#$#'
																		+document.getElementById("d_addressCountryC").value;
																		//+document.getElementById("d_addressPincodeC").value;

//					if((document.getElementById('customer_installation_address').value).trim() == '')
//					{
//						document.getElementById('customer_installation_address').value = document.getElementById('customer_address').value;
//					}

					controller.sendConfirmation(name,haddress,phone);
					document.getElementById('your_detail_form_div').submit();

					if(document.getElementById("details_div"))
					{
						document.getElementById("details_div").innerHTML = CV_obj.getLoadingHTML();
					}
					// controller.sendmail(email, mailbody, subject);
				}
				else
				{
					controller.displayerror('errordiv',"Please read terms and condition and accept ")
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};
	//added SD for The confirmation service
	this.sendConfirmation = function (name,adress,telephone)
	{
		try
		{
				var selected = CM_obj.getSelectedProducts();
				var prod ="";
				selected.each(function(value, index)
						{
					     prod += 'product'+(index+1)+": "+value[3]+','; 
						});
			    var products = '{'+prod+'}';
			//	var ip		= CM_obj.getClientIP();
				//var email	= CM_obj.getEmail();
				$.ajax(
				{
					url: 'http://50.18.20.35/api/v1/confirm',
					type: 'post',
					data:
					{
						products: products,
						name : name,
						address : adress,
				        telephone : telephone,
						
					},
					datatype: 'json',
					success: function(data)
					{
						
						
					}
				});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};


	// SDs for error message on details page
	this.ErrorMessage = function(message)
	{
		try
		{
			$("#errordiv").html(message);
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs for string validation date:29/1/13
	this.isstring = function(id)
	{
		try
		{
				if(controller.emptyCheck(id))
				{
					var letters = /^[A-Za-z' ]+$/;
					if(document.getElementById(id).value.match(letters))
					{
						document.getElementById(id).style.border = "1px solid #C8C8C8";
						return true;
					}
					else
					{
						document.getElementById(id).style.border = "1px solid #FF0000";
						return false;
					}
				}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs for email check  callsemptycheck and  TLD email vaildation
	this.isEmail = function(id)
	{
		try
		{
			if(controller.emptyCheck(id))
			{
				if(controller.checkEmail(document.getElementById(id).value))
				{
					document.getElementById(id).style.border = "1px solid #C8C8C8";
					return true;
				}
				else
				{
					document.getElementById(id).style.border = "2px solid #B60000";
					return false;
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs for  number validation date:29/1/13
	this.isnumber = function(id)
	{
		try
		{
			if(controller.emptyCheck(id))
			{
				var numbers = /^[0-9]+$/;
				if(document.getElementById(id).value.match(numbers))
				{
					document.getElementById(id).style.border = "1px solid #C8C8C8";
					return true;
				}
				else
				{
					document.getElementById(id).style.border = "2px solid #B60000";
					return false;
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};
	this.isvalidphone = function(id)
	{
		try
		{
			if(controller.emptyCheck(id))
			{
				var numbers = /^[0-9]+$/;
				if(document.getElementById(id).value.length == 10)
				{
					document.getElementById(id).style.border = "1px solid #C8C8C8";
					return true;
				}
				else
				{
					document.getElementById(id).style.border = "2px solid #B60000";
					return false;
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs for empty check
	this.emptyCheck = function(id)
	{
		try
		{
			if(document.getElementById(id).value == "")
			{
				document.getElementById(id).style.border = "2px solid #B60000";
				$('#'+id).focus();
				return false;
			}
			else
			{
				document.getElementById(id).style.border = "1px solid #C8C8C8";
				return true;
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs phone number validation
	this.sendmail = function (mailTo, data, subject)
	{
		try
		{
				var ip		= CM_obj.getClientIP();
				var email	= CM_obj.getEmail();
				$.ajax(
				{
					url: 'index.php?option=com_homeconnect&task=createlogsendemail&format=raw',
					type: 'post',
					data:
					{
						ip		: ip,
						log		: tracker,
						data	: data,
						email	: mailTo,
						subject	: subject
					},
					datatype: 'json',
					success: function(data)
					{
						// Aks : Check if mail is sent then only switch divs here
						if(data == "success")
						{
							track_info = new Array(ip_add, email_add, screen, "WAITING", "MAIL SENT");
							tracker.push(track_info);
							controller.switchDivs("#thank_div");
						}
						else
						{
							alert("ERROR in SENDING MAIL");
							track_info = new Array(ip_add, email_add, screen, "WAITING", "MAIL SENT ERROR");
							tracker.push(track_info);
						}
					}
				});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs for category greyed out
	this.greyedoutCategory = function(cat_id, id)
	{
		try
		{
			track_info = new Array(ip_add, email_add, screen, "CHECK_IGNORE_CATEGORY", "USER DONT WANT CATEGORY AND SCREEN CHANGED WITH OTHER CATEGORY");
			tracker.push(track_info);
	
			track_info = new Array(ip_add, email_add, screen, "WAITING", "IGNORED CATEGORY GREYED OUT FROM MY BUNDLE");
			tracker.push(track_info);
	
			if(document.getElementById(cat_id).checked == true)
			{
				document.getElementById(cat_id+'_'+id).style.color = "#99CC00";
				if(id != 5)
				{
					controller.nextAccordion('category-accordion-div', id);
				}
				else
				{
					controller.switchInnerDivs('main_category_div', 'installation_div', 0, 0);
				}
			}
			else
			{
				document.getElementById(cat_id+'_'+id).style.color = "#000000";
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs for add recommended bundle
	this.PickRecommendedBundle = function(bundle_id)
	{
		try
		{
				var recommended_bundle = new Array ('rec_select_lowerp_1', 'rec_select_lowerp_2', 'rec_select_lowerp_3', 'rec_select_best_1', 'rec_select_best_2', 'rec_select_best_3', 'rec_select_popular_1', 'rec_select_popular_2', 'rec_select_popular_3');
		
				if(document.getElementById(bundle_id).checked == true)
				{
					// code for uncheck all other bundle checked
		
					var recommended_bundle_length = recommended_bundle.length;
		
					for(var i=0; i < recommended_bundle_length; i++)
					{
						if(bundle_id!=recommended_bundle[i])
						{
							$('#'+recommended_bundle[i]).attr('checked',false);
						}
					}
		
					// statically considered 5 product under 5 different category
					for(i = 0; i < 5; i++)
					{
						CM_obj.setAddToMyBundleResult(i, i);
					}
					selected_product = CM_obj.getSelectedProducts();
					document.getElementById("my_selection").style.display = "block";
				}
				else
				{
					/*
					for(i = 0; i < 5; i++)
					{
						CM_obj.deleteAddedMyBundleResult(i, i+1);
					}
					*/
					selected_product = CM_obj.getSelectedProducts();
					document.getElementById("my_selection").style.display = "none";
				}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// navigation check
	this.allowNavigation = function (hid, sid)
	{
		try
		{
			// this flow is only to check empty selection and redirect to selection if empty so only check is for recommended and confirmation screen
			var errordiv_id, s_id, h_id;
	
			if(sid == "confirmation_div" || hid == "recommended_div" )
			{
				var empty_flag = 0;
				selected_product = CM_obj.getSelectedProducts();

				var selected_product_length = selected_product.length;
	
				for(var i = 0; i < selected_product_length; i++)
				{
					if(selected_product[i].length!=0)
					{
						empty_flag = 1;
					}
				}
				if(empty_flag == 0)
				{
					if(sid == "confirmation_div")
					{
						errordiv_id = "error_message";
						sid = "main-category-div";
					}
					if(hid == "recommended_div")
					{
						errordiv_id = "rec_error_message";
						sid = hid;
					}
					controller.displayerror(errordiv_id,"Please Make Selection First");
				}
				track_info = new Array(ip_add, email_add, screen, "FORWORD_CLICK", "ENTER TO CATEGORY "+hid+" FROM CATEGORY "+sid+"AS EMPTY SELECTION");
				tracker.push(track_info);
	
				return sid;
			}
			else
			{
				track_info = new Array(ip_add, email_add, screen, "FORWORD_CLICK", "ENTER TO CATEGORY "+hid+" FROM CATEGORY "+sid+"AS EMPTY SELECTION");
				tracker.push(track_info);
	
				return sid;
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// display error message
	this.displayerror = function (errdiv_id, errorMessage)
	{
		try
		{
			if(document.getElementById(errdiv_id))
			{
				document.getElementById(errdiv_id).style.display = "block";
	
				var err_html="<div class='errormessage'>"+errorMessage+"!!!</h1>";
	
				document.getElementById(errdiv_id).innerHTML = err_html;
	
				$("#"+errdiv_id).delay(3200).fadeOut(1000);
				err_html="";
	
				return;
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs added to reject category product from installation screen
	this.rejectCategoryProduct = function(category)
	{
		try
		{
			if(document.getElementById(category).checked == false)
			{
				document.getElementById('myselection-in-'+category).innerHTML = "";
				CM_obj.deleteAddedMyBundleResult(category)
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	}

	this.detectAccordion = function(acc_id)
	{
		
		try
		{
			//no need more :SDs
			/*if(acc_id == 'cumh')
			{
				controller.updateDiv('installation_div');
			}*/
			
			var cat_name = allCategory[acc_id-1];
			if(accordian_flag == 0)
			{
				var cat_name 	= allCategory[acc_id-1];
				controller.SelectionData(cat_name);
				
			}
			
			accordian_flag =0;
			
			
			
			
			
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	}

	// SD added to reject single product selected from my bundle
	this.productRemove = function(product_detail)
	{
		
		try
		{
			var cat_product = product_detail.split(",");
			var selected_product= CM_obj.getSelectedProducts();
	
			cat_product[1]= cat_product[1].toLowerCase();
			cat_product[1] = cat_product[1].replace(' ', '-');
	
			if(CM_obj.indexOfArray(cat_product, selected_product)  >= 0)
			{
				var removebtn = 'delete-'+cat_product[0]+'-'+cat_product[1]+'-'+cat_product[3];
				/*
				if(document.getElementById(removebtn))
				{
					document.getElementById(removebtn).style.display = 'none';
				}
				*/

				track_info = new Array(ip_add, email_add, screen, "CHECK_PRODUCT", "PRODUCT DELETED FROM BUNDLE");
				tracker.push(track_info);
	
				CM_obj.deleteMyBundleResult(cat_product);

				controller.navigateSelectionList('next', 'basket'+cat_product[3], cat_product[0]);
				var temp_index = navigation_in_basket[cat_product[0]].indexOf('basket'+cat_product[3]);
				if(temp_index >= 0)
				{
					navigation_in_basket[cat_product[0]].splice(temp_index, 1);
				}

				controller.calculateAndDisplayTotalPrice(cat_product,'-');
				/*var pric = cat_product[6];
				
				if(pric!="")
					{
						if(pric.charAt(0)=='$')
							{
							pric = pric.substr(1);
							}
						total_price = total_price - parseFloat(pric);
						$('#monthlycost').html('monthly plan total cost - $'+total_price);
					}*/
				//$('#add-'+cat_product[0]+'-'+cat_product[3]).attr();
				if(document.getElementById('add-'+cat_product[0]+'-'+cat_product[1]+'-'+cat_product[3]))
					{
						$('#add-'+cat_product[0]+'-'+cat_product[1]+'-'+cat_product[3]).attr('checked',false);
					}
				$('#basket'+cat_product[3]).remove();
				var no_of_selection = 0;
				for(var i=0;i<selected_product.length;i++)
					{
						if(selected_product[i][0] == cat_product[0])
						{
							no_of_selection = no_of_selection + 1;
	
						}	
					
					}
				  if(no_of_selection == 0)
					 {
					  
					  var html = '<div id="basket-noproduct-'+cat_product[0]+'"><strong>No Product selected...</strong></div>';
					  $("#inner-brands-"+cat_product[0]).append(html);
					  
					  //$("#myselection-div-in-"+cat_product[0]).trigger('click');
//					  controller.SetAccordianCheckBoxAttrFalse(cat_product[0]);
					  //$('#checkbox-1').click();
					  //document.getElementById("checkbox-1").checked = false;
					  //controller.switchtoSelectionpage(cat_product[0],'',0,0);
						/*var checkboxId = 'checkbox-'+(allCategory.indexOf(cat_product[0])+ 1);
						if(document.getElementById('checkbox-'+(allCategory.indexOf(cat_product[0])+ 1)))
						{
							
							document.getElementById(checkboxId).setAttribute("checked", "");
							document.getElementById(checkboxId).removeAttribute("checked");
							document.getElementById(checkboxId).checked = false;
							$('#'+checkboxId).removeAttr('checked');
							$('#'+checkboxId).removeAttr('disabled');
						}*/
					 }
				  	else{
//						 controller.SetAccordianCheckBoxAttrTrue(cat_product[0]);
					}
				
				
				
				//$('#basket-noproduct'+cat_product[0]).remove();
				
				//$('#inner-brands'+cat_product[3]).fadeOut('fast').load('default_right_sidebar.php').fadeIn("fast");
				
				controller.viewtoMybundle(cat_product[0], selected_product);
			}
			
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	}
	
	this.SetAccordianCheckBoxAttrFalse = function(productName){
		switch(productName){
		  case "tv-content":
			  $('#checkbox-1').attr('checked',false).checkboxradio("refresh");
			  break;
		  case "music-content":
			  $('#checkbox-2').attr('checked', false).checkboxradio("refresh");
			  break;
		  case "games":
			  $('#checkbox-3').attr('checked', false).checkboxradio("refresh");
			  break;
		  case "broadband":
			  $('#checkbox-4').attr('checked', false).checkboxradio("refresh");
			  break;
		  }
	}
	
	this.SetAccordianCheckBoxAttrTrue = function(productName){
		switch(productName){
		  case "tv-content":
			  $('#checkbox-1').attr('checked',true).checkboxradio("refresh");
			  break;
		  case "music-content":
			  $('#checkbox-2').attr('checked', true).checkboxradio("refresh");
			  break;
		  case "games":
			  $('#checkbox-3').attr('checked', true).checkboxradio("refresh");
			  break;
		  case "broadband":
			  $('#checkbox-4').attr('checked', true).checkboxradio("refresh");
			  break;
		  }
	}

	this.nextAccordionFromProductScreen = function(h_id, s_id, accordion_div, accordion_index, noc,category)
	{
		try
		{
			controller.switchInnerDivs(h_id, s_id, 0, 0);
			controller.nextAccordion(accordion_div, accordion_index, noc,category);
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.showExpansionDivs = function(target_div, flag, iconadd_id, iconclose_id)
	{
		try
		{
			if(flag == 0)
			{
				document.getElementById(iconadd_id).style.display = "none";
				document.getElementById(iconclose_id).style.display = "block";
	
				show_ids = target_div.split(",");
	
				show_ids.each(function(value, index)
				{
					if(document.getElementById(value))
					{
						controller.showDiv("#"+value);
					}
				});
			}
			else
			{
				document.getElementById(iconadd_id).style.display = "block";
				document.getElementById(iconclose_id).style.display = "none";
	
				hide_ids = target_div.split(",");
	
				hide_ids.each(function(value, index)
				{
					if(document.getElementById(value))
					{
						controller.hideDiv("#"+value);
					}
				});
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SDs added for autofill text on details page check checkbox
	this.autofillText = function(src_id, dest_id, check_id)
	{
		try
		{
			
			if(document.getElementById(check_id).checked)
			{
				if(document.getElementById(dest_id)	&& document.getElementById(src_id))
				{
					document.getElementById(dest_id).value = document.getElementById(src_id).value;
					document.getElementById('d_addressCountryC').value = document.getElementById('d_addressCountry').value;
					document.getElementById('d_addressStateC').value = document.getElementById('d_addressState').value;
					document.getElementById('d_addressCityC').value = document.getElementById('d_addressCity').value;
					//document.getElementById('d_addressPincodeC').value = document.getElementById('d_addressPincode').value;
					//streetno,streetadd n suburb field added
					//document.getElementById('d_address_streetnoC').value = document.getElementById('d_address_streetno').value;
					//document.getElementById('d_address_streetaddC').value = document.getElementById('d_address_streetadd').value;
//					document.getElementById('d_address_suburbC').value = document.getElementById('d_address_suburb').value;
				}
			}
			else
			{
				if(	document.getElementById(dest_id))
				{
					document.getElementById(dest_id).value = "";
					document.getElementById('d_addressCountryC').value ="";
					document.getElementById('d_addressStateC').value = "";
					document.getElementById('d_addressCityC').value = "";
					//document.getElementById('d_addressPincodeC').value = "";
					//new added fields
					//document.getElementById('d_address_streetnoC').value = "";
					//document.getElementById('d_address_streetaddC').value = "";
//					document.getElementById('d_address_suburbC').value ="";
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.startSlider = function(id)
	{
		try
		{
			$(function()
			{
				$(id).bxSlider(
				{
					mode: 'fade',
					pager: false,
					auto: true,
					autoStart: true,
					autoHover: true,
					infiniteLoop: true,
					controls: false,
					minSlides: 2
				});
				/*
				$('.carousel').carouFredSel(
				{
					items: 1,
					scroll:
					{
						onAfter: function()
						{
							controller.setRandomFX( $(this) );
						}
					},
					onCreate: function()
					{
						controller.setRandomFX( $(this) );
					}
				});
				*/
			});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.HideAll = function (totalLi)
	{
		for(var i=0; i < totalLi.length; i++)
		{
			totalLi[i].style.display = 'none';

//			totalLi[i].hide( "fade", {}, 100 );
		}
	};

	this.getDirectChild = function (parent, name)
	{
		var lis = new Array();
		var childs = parent.childNodes;
		for(var index = 0; index < childs.length; index++)
		{
			var child = childs[index];
			if(child.tagName != null && name.toLowerCase() == child.tagName.toLowerCase())
			{
				lis.push(child);
			}
		}
		return lis;
	};

	this.rotateulcontent = function(id, liIndex, divType)
	{
		try
		{
			var ul = document.getElementById(id);

			// Aks : Change to position drag cursor and product div
//			var totalLi = controller.getDirectChild(ul,'li');
			var totalLi = controller.getDirectChild(ul, divType);

//			var totalLi = ul.getElementsByTagName('li');

			if(liIndex >= totalLi.length)
				liIndex = 0;

			// Aks : Added to pause slide when a product is being dragged
			if(carouselFlag == "PLAY")
			{
				controller.HideAll(totalLi);

				if(totalLi[liIndex])
				{
					totalLi[liIndex].style.display = 'inline';

//					totalLi[liIndex].show( "fade", {}, 1000 );
				}
			}

//			liIndex++;

			liIndex = liIndex + 1;

			setTimeout(function()
			{
				controller.rotateulcontent(id, liIndex, divType);
			}, delay );
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.startCustomSlider = function(id, divType)
	{
		try
		{
//			setTimeout(function()
//			{
				controller.rotateulcontent(id, 0, divType);
//			}, delay );
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.setRandomFX = function ( $elem )
	{
		try
		{
			var allFXs = [ 'scroll', 'crossfade', 'cover', 'uncover' ];
			var newFX = Math.floor( Math.random() * allFXs.length );

			$elem.trigger( 'configuration',
			{
				auto:
				{
					fx: allFXs[ newFX ]
				}
			});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.draggable = function (id, handle)
	{
		try
		{
			if(document.getElementById(id))
			{
				$( handle ).draggable(
				{
					revert: "invalid",
					handle: handle,
					helper: "clone",
					cursor: "move",
					zIndex: '999999',
					start: function(event, ui) {
						// Aks : Added to pause slide when a product is being dragged
						carouselFlag = "PAUSE";
					},
					stop: function() {
						// Aks : Added to resume pause when a product is stopped dragging
						carouselFlag = "PLAY";
					},
					cursorAt: { top: 1, left: 1 }
				});
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.droppable = function (id, content, className)
	{
		try
		{
			$(id).droppable(
			{
				activeClass: "droppableClass-hover",
				hoverClass: "droppableClass-active",
				drop: function( event, ui )
				{
					var productselected = $(ui.draggable.html()).attr("data-product-detail").split(",");

					if(CM_obj.indexOfArray(productselected, CM_obj.getConfirmSelected()) < 0)
					{
						$( content )
							.html( ui.draggable.html() )
							.addClass(className)
							.appendTo("#new_selected_ul_"+productselected[0]);

//							controller.startSlider("#new_selected_ul_"+productselected[0]);
							controller.startCustomSlider("new_selected_ul_"+productselected[0], 'li');
							$('#staticid').attr('id',productselected[0]+'-'+productselected[3]);
							$('#static').attr('id',$(ui.draggable.html()).attr("data-product-detail"));

							CM_obj.addToConfirmSelected(productselected);
							//added to calculate monthly and one off price

							controller.calculateAndDisplayTotalPriceOfRecommended(productselected,'+');
					}
				}
			});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.loadrecommendedtoallproduct = function(data)
	{
		data.each(function(val, index)
		{
			val.each(function(d, ind)
			{
				allproduct.push(d["broadband"]);
				allproduct.push(d["gaming"]);
				allproduct.push(d["music"]);
				allproduct.push(d["tV"]);
			});
		});
	};

	this.initDragNDrop = function()
	{
		document.getElementById("left").className = "confirmationleft";
		document.getElementById('cumh_right').style.display = "none";
		try
		{
			for(var i = 1; i <= 4; i++)
			{
				for(var j = 1; j <= 5; j++)
				{
					if(document.getElementById("lower" + "_" + i + "_" + j))
					{
						controller.draggable("lower" + "_" + i + "_" + j, "#lower" + "_" + i + "_" + j);
					}
					if(document.getElementById("best" + "_" + i + "_" + j))
					{
						controller.draggable("best" + "_" + i + "_" + j, "#best" + "_" + i + "_" + j);
					}
					if(document.getElementById("popular" + "_" + i + "_" + j))
					{
						controller.draggable("popular" + "_" + i + "_" + j, "#popular" + "_" + i + "_" + j);
					}
				}
			}
			// 50 max product selected in accordion
			for(var j=0;j< 50;j++)
			{
				if(document.getElementById("myselection" + "_" + j))
				{
					controller.draggable("myselection" + "_" + j, "#myselection" + "_" + j);
				}
			}
			controller.droppable("#new_selected_product_div", "<li id='staticid' style='float: none; list-style: none outside none; width: 140px; z-index: 50;'><div id='static' class='RecProduct'></div></li>", "cart-item");
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.confirmSelection = function(src_id,dest_id,imagepath,flag_string)
	{
		try
		{
			// var radios = document.getElementsByName('confirm');

			if(flag_string=='new_selection' && document.getElementById('confirmnewselection').checked ==true && CM_obj.getConfirmSelected().length > 0 )
			{
				recommended_flag = 1;
				temp_total_fixed_price = total_fixed_price;
				total_fixed_price = 0;
				temp_total_price_per_month = total_price_per_month;
				total_price_per_month = 0;

				var newselection =  CM_obj.getConfirmSelected();
				var yourselection = CM_obj.getSelectedProducts();

				for(var i = 0; i < yourselection.length; i++)
				{
					CM_obj.addToTempStore(yourselection[i]);
					$('#basket'+yourselection[i][3]).remove();	

					var temp_index = navigation_in_basket[yourselection[i][0]].indexOf('basket'+yourselection[i][3]);
					if(temp_index >= 0)
					{
						navigation_in_basket[yourselection[i][0]].splice(temp_index, 1);
					}
				}
//				var html = '<div id="basket-noproduct-'+cat_product[0]+'"><strong>No Product selected...</strong></div>';
//				  $("#inner-brands-"+cat_product[0]).append(html);
				CM_obj.deleteAllProductSelection();

				newselection.each(function(value,index)
				{
					controller.AddToMyBundleC(value,imagepath);	
				});

				if( CM_obj.getConfirmSelected().length > 0)
				{
					controller.switchInnerDivs(src_id, dest_id, 0, 0);
					$('#monthlycost').html('$'+recommend_select_total_price);
					$('#fixcost').html('$'+recommend_select_total_fixed_price);
				}
				document.getElementById("id_new_selected_ul_tv-content").innerHTML =document.getElementById("new_selected_ul_tv-content").innerHTML;
				document.getElementById("id_new_selected_ul_music-content").innerHTML =document.getElementById("new_selected_ul_music-content").innerHTML;
				document.getElementById("id_new_selected_ul_gaming-content").innerHTML =document.getElementById("new_selected_ul_games").innerHTML;
				document.getElementById("id_new_selected_ul_broadband-content").innerHTML =document.getElementById("new_selected_ul_broadband").innerHTML;
				controller.startCustomSlider("id_new_selected_ul_tv-content", "li");
				controller.startCustomSlider("id_new_selected_ul_music-content", "li");
				controller.startCustomSlider("id_new_selected_ul_gaming-content", "li");
				controller.startCustomSlider("id_new_selected_ul_broadband-content", "li");
			}
			else
			{
				if(document.getElementById('confirmyourselection').checked ==true  && CM_obj.getSelectedProducts().length > 0)
				{
					recommended_flag = 0;

					controller.switchInnerDivs(src_id, dest_id, 0, 0);
					$('#monthlycost').html('$'+total_price_per_month);
					$('#fixcost').html('$'+total_fixed_price);

					/* Aks : Change to position drag cursor and product div
					document.getElementById("id_new_selected_ul_tv-content").innerHTML = document.getElementById("ul_1").innerHTML;
					document.getElementById("id_new_selected_ul_music-content").innerHTML = document.getElementById("ul_2").innerHTML;
					document.getElementById("id_new_selected_ul_gaming-content").innerHTML = document.getElementById("ul_3").innerHTML;
					document.getElementById("id_new_selected_ul_broadband-content").innerHTML = document.getElementById("ul_4").innerHTML;
					*/
					document.getElementById("id_new_selected_ul_tv-content").innerHTML = document.getElementById("c11").innerHTML;
					document.getElementById("id_new_selected_ul_music-content").innerHTML = document.getElementById("c12").innerHTML;
					document.getElementById("id_new_selected_ul_gaming-content").innerHTML = document.getElementById("c13").innerHTML;
					document.getElementById("id_new_selected_ul_broadband-content").innerHTML = document.getElementById("c14").innerHTML;

					controller.startCustomSlider("id_new_selected_ul_tv-content", "li");
					controller.startCustomSlider("id_new_selected_ul_music-content", "li");
					controller.startCustomSlider("id_new_selected_ul_gaming-content", "li");
					controller.startCustomSlider("id_new_selected_ul_broadband-content", "li");
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	}

	// SD added this function as only single line of removebundle button id is used in original AddToMyBundle function which is not required in this flow.
	// so after replacing checkbox we can optimize code and make a single AddToMyBundle function
	this.AddToMyBundleC = function(product_detail,imagepath)
	{
		try
		{
			image_PATH = imagepath;
			// here product detail is  string contain 4 details like category,brand,pname, product id

			var selected_product= CM_obj.getSelectedProducts();
			if(CM_obj.indexOfArray(product_detail, selected_product) < 0)
			{
				track_info = new Array(ip_add, email_add, screen, "CHECK_PRODUCT", "PRODUCT SELECTED UNDER CATEGORY");
				tracker.push(track_info);

				CM_obj.setAddToMyBundleResult(product_detail);
				controller.calculateAndDisplayTotalPrice(product_detail,'+');

				var accordion_id = allCategory.indexOf(product_detail[0]);

				// parameter 1 is passed as this swichselection call directs products level page so default brand_id is 1
				if(document.getElementById('basket-noproduct-'+product_detail[0]))
				{
					$('#basket-noproduct-'+product_detail[0]).remove();
				}

				var price1= (product_detail[7] != "" && product_detail[7] != null)? (product_detail[7]).replace('$',''):"0.00";
				var equipmentPrice =(product_detail[8] != "" && product_detail[8] != null)? (product_detail[8]).replace('$',''):"0.00";
				var installationPrice =(product_detail[9] != "" && product_detail[9] != null)? (product_detail[9]).replace('$',''):"0.00";
				if(product_detail[8] != null && product_detail[8] != "" )
				{
					price1 = parseFloat(parseFloat(price1).round(2)+parseFloat(equipmentPrice).round(2)).round(2); //parseFloat().round(2)
				}

				if(product_detail[9] != null && product_detail[9] != "" )
				{
					price1 = parseFloat(parseFloat(price1).round(2)+parseFloat(installationPrice).round(2)).round(2); //parseFloat().round(2)
				}
				if(price1=="0" || price1=="0.00")
					price1='NA';
				else
					price1 = "$"+price1;

//				var price1= product_detail[7];
//				if(product_detail[8] != null && product_detail[8] != "" )
//				{
//					price1 = parseFloat(parseFloat(price1).round(2)+parseFloat(product_detail[8]).round(2)).round(2); //parseFloat().round(2)
//				}
//				if(product_detail[9] != null && product_detail[9] != "" )
//				{
//					price1 = parseFloat(parseFloat(price1).round(2)+parseFloat(product_detail[9]).round(2)).round(2); //parseFloat().round(2)
//				}
//				if(product_detail[7]=="")
//					price1='NA';

				var price2= product_detail[6];
				var equipInstallPrice = parseInt(equipmentPrice) + parseInt(installationPrice);

				if(equipInstallPrice > 0){
					equipInstallPrice = "<br/>Equipment & InstallationPrice $"+equipInstallPrice;
				}
				else
				{
					equipInstallPrice ="";
				}
				 if(product_detail[6]=="")
					price2='NA';
				 var addPrdQuota = product_detail[5].trim();
					if(addPrdQuota == undefined || addPrdQuota == null){
						addPrdQuota = "";
					}
				var contract = product_detail[4];
				if(contract != "")
					contract = "<br/>"+contract;
				else
					contract = "";
				
				var title = product_detail[2];
				var brandname="";
				 allproduct.each(function(value, index)
						 {
							 
							if(value.id == product_detail[3])
							{
								brandname = value.supplier;
								title = value.title;
							}
						 });
				 
				
				var image1="";
				if(brandname.indexOf('+') > 0)
					{
					 
					  var img = brandname.split('+');
					  img[0]= img[0].trim().toLowerCase();
					  img[1]= img[1].trim().toLowerCase();
					  image1='<img src="'+image_PATH+img[0]+'.jpg" alt='+img[0]+'><img src="'+image_PATH+img[1]+'.jpg" alt='+img[1]+'>'
					}
				else
					{
					
					image1='<img src="'+image_PATH+product_detail[1]+'.jpg" alt='+product_detail[1]+'>';
					}
				

				var html = '<div id="basket'+product_detail[3]+'" class="prowrap"><div class="prowrap_left">'+
				'<div class="brandlogobg"><div class="brandlogo">'+image1+
				'</div></div>'+
				'<div class="edit">'+
				'<img src="templates/beez_20/images/delete.gif" title="Delete" alt="Delete" onclick="javascript:controllerObj.productRemove(\''+product_detail+'\')" /> Delete'+
				'</div></div><div class="prowrap_right">'+
				'<div class="brandtext"><strong>'+title+'</strong><br>'+addPrdQuota+equipInstallPrice+contract+'</div>'+
				//'<div class="edit">'+
				//	'<a style="cursor: pointer;" onclick="javascript:controllerObj.switchtoSelectionpage(\''+product_detail[0]+'\',\''+product_detail[1]+'\','+accordion_id+',1)">'+
				//		'<img src="templates/beez_20/images/edit.gif" title="Edit"  alt="Edit" />'+
				//	'</a>'+
				//'</div>'+
				'<div class="price">One-off Price: '+price1+'</div>'+
				'<div class="price" id="monthly_price_'+product_detail[3]+'">Monthly Price: '+price2+'</div>'+
				'<div id="next_basket'+product_detail[3]+'" class="basket_nav"><img src="templates/beez_20/images/basket_next.png" onclick="javascript:controllerObj.navigateSelectionList(\'next\',\'basket'+product_detail[3]+'\',\''+product_detail[0]+'\')"></div>'+
				'<div id="prev_basket'+product_detail[3]+'" class="basket_nav"><img src="templates/beez_20/images/basket_prev.png" onclick="javascript:controllerObj.navigateSelectionList(\'prev\',\'basket'+product_detail[3]+'\',\''+product_detail[0]+'\')"></div>'+
				'</div></div>';
				if(navigation_in_basket[product_detail[0]].indexOf('basket'+product_detail[3])< 0)
				{
					navigation_in_basket[product_detail[0]].push('basket'+product_detail[3]);
				}

				$("#inner-brands-"+product_detail[0]).append(html);
				/*
				if(navigation_in_basket[product_detail[0]].indexOf('basket'+product_detail[3])== 0)
				{
					document.getElementById('prev_basket'+product_detail[3]).style.display = "block";
					document.getElementById('next_basket'+product_detail[3]).style.display = "block";
				}
			   else
				{
				    var index = navigation_in_basket[product_detail[0]].indexOf('basket'+product_detail[3]);

				    document.getElementById(navigation_in_basket[product_detail[0]][index-1]).style.display = "none";
					document.getElementById('next_basket'+product_detail[3]).style.display = "block";
				}
				*/
				controller.navigateSelectionList('update', 'basket'+product_detail[3], product_detail[0]);

	            controller.viewtoMybundle(product_detail[0], selected_product);
			}
			// cat_product[1] is subcategory which is binded in rightside myselections div id
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.AddRecommendedPackage = function(elements, rowid, id)
	{
		try
		{
			var datastring = new Array();

			for(var i = 1; i < 5; i++)
			{
				if(datastring.indexOf($("."+rowid+"_"+i).attr("data-product-detail")< 0))
				{
					datastring.push($("."+rowid+"_"+i).attr("data-product-detail"));
				}
			}
			var confirmselected = CM_obj.getConfirmSelected();

			if(document.getElementById(id).checked)
			{
				

				for(i = 0; i < 4; i++)
				{
					var value = datastring[i].split(",");
					if(CM_obj.indexOfArray(value,confirmselected) < 0)
					{
						$( "#new_selected_ul_"+value[0]).append("<li id="+value[0]+'-'+value[3]+" class='cart-item' style='float: none; list-style: none outside none; width: 140px; z-index: 50;'>"+document.getElementById(rowid+'_'+(i+1)).innerHTML+"</li>");
						CM_obj.addToConfirmSelected(value);
						controller.calculateAndDisplayTotalPriceOfRecommended(value,'+');

//						controller.startSlider("#new_selected_ul_"+value[0]);
						controller.startCustomSlider("new_selected_ul_"+value[0], "li");
					}
				}
			}
			else
			{
				datastring.each(function(value,index)
				{
					var value1 = value.split(",");
					var element = document.getElementById(value1[0]+'-'+value1[3]);
					if(document.getElementById(value1[0]+'-'+value1[3]))
					{
						element.parentNode.removeChild(element);
					}
					var value1 = value.split(",");
					if(CM_obj.indexOfArray(value1,confirmselected) > -1)
					{
			    		CM_obj.deleteConfirmSelected(value1);
			    		controller.calculateAndDisplayTotalPriceOfRecommended(value1,'-');
			    		CM_obj.deleteMyBundleResult(value1);
						
			    		$('#basket'+value1[3]).remove();
			    		controller.updateDiv('confirmation_div');
			    		controller.viewtoMybundle(value1[0], CM_obj.getSelectedProducts());
					}
				});
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	// SD added to display popup info details of each product
	this.detailspopupwindow = function (p_id,imagePath,popupFor)
	{

			
		 allproduct.each(function(value, index)
		 {
			 var image1="";
			 var brandname="";
			 
			if(value.id == p_id)
			{
				var host = window.location.hostname;

				    var portNumber = new String();
				    portNumber = window.location.host;
				    portNumber = portNumber.substring(portNumber.indexOf(":") + 1);
				    if (portNumber != undefined)
				        host += ":" + portNumber;
				        brandname = value.supplier;
				    
					if(brandname.indexOf('+') > 0)
						{
						 
						  var img = brandname.split('+');
						  img[0]= img[0].trim().toLowerCase();
						  img[1]= img[1].trim().toLowerCase();
						  image1='<img class="two_imageSize" src="images/small_logo/'+img[0]+'.jpg" alt='+img[0]+'><img style="two_imageSize" src="images/small_logo/'+img[1]+'.jpg" alt='+img[1]+'>';
						  
						}
					else
						{
						 brandname = brandname.toLowerCase();
						// brandname = brandname.replace(' ','-');
						 //replace if two tab space occure in name
						 brandname=brandname.split(" ").join("-");
						image1='<img class="imageSize" src="images/main_accordian/'+brandname+'.jpg" alt='+brandname+'>';
						
						}


				if(document.getElementById('span_mc_DetailsHeading'))
					document.getElementById('span_mc_DetailsHeading').innerHTML = value.title;

				if(document.getElementById('div_mc_DetailsPlanFeatures'))
					document.getElementById('div_mc_DetailsPlanFeatures').innerHTML = value.shortDescription;

				if(document.getElementById('div_mc_Details_DescHeading'))
					document.getElementById('div_mc_Details_DescHeading').innerHTML = value.category +" Details";

				if(document.getElementById('table_mc_title'))
					document.getElementById('table_mc_title').innerHTML=(value.title == "" || value.title == null)? "--" : value.title ;

				if(document.getElementById('table_mc_shortDescription'))
					document.getElementById('table_mc_shortDescription').innerHTML=(value.shortDescription == "" || value.shortDescription == null)? "--" : value.shortDescription ;

				if(document.getElementById('table_mc_longDescription'))	
					document.getElementById('table_mc_longDescription').innerHTML=(value.longDescription == "" || value.longDescription == null)? "--" : value.longDescription ;

				if(document.getElementById('table_mc_monthlyPrice'))
				{
//					document.getElementById('table_mc_monthlyPrice').innerHTML=(value.monthlyPrice == "" || value.monthlyPrice == null)? "--" : value.monthlyPrice ;
					if((value.monthlyPrice == "" || value.monthlyPrice == null))
					{
						document.getElementById('table_mc_monthlyPrice').style.display = "none";
						if(document.getElementById('table_mc_monthlyPriceA'))
							document.getElementById('table_mc_monthlyPriceA').style.display = "none";
					}
					else
					{
						document.getElementById('table_mc_monthlyPrice').style.display = "block";
						if(document.getElementById('table_mc_monthlyPriceA'))
							document.getElementById('table_mc_monthlyPriceA').style.display = "block";
						document.getElementById('table_mc_monthlyPrice').innerHTML = value.monthlyPrice;
					}
				}

				if(document.getElementById('table_mc_quota'))
				{
					if((value.quota == "" || value.quota == null))
					{
						document.getElementById('table_mc_quota').style.display = "none";
						if(document.getElementById('table_mc_quotaA'))
							document.getElementById('table_mc_quotaA').style.display = "none";
					}
					else
					{
						document.getElementById('table_mc_quota').style.display = "block";
						if(document.getElementById('table_mc_quotaA'))
							document.getElementById('table_mc_quotaA').style.display = "block";
						document.getElementById('table_mc_quota').innerHTML = value.quota ;
					}
				}

				if(document.getElementById('table_mc_installationPrice'))
				{
//					document.getElementById('table_mc_installationPrice').innerHTML=(value.installationPrice == "" || value.installationPrice == null)? "--" : value.installationPrice ;
					if((value.installationPrice == "" || value.installationPrice == null))
					{
						document.getElementById('table_mc_installationPrice').style.display = "none";
						if(document.getElementById('table_mc_installationPriceA'))
							document.getElementById('table_mc_installationPriceA').style.display = "none";
					}
					else
					{
						document.getElementById('table_mc_installationPrice').style.display = "block";
						if(document.getElementById('table_mc_installationPriceA'))
							document.getElementById('table_mc_installationPriceA').style.display = "block";
						document.getElementById('table_mc_installationPrice').innerHTML = value.installationPrice;
					}
				}

				if(document.getElementById('table_mc_equipment'))
				{
//					document.getElementById('table_mc_equipment').innerHTML= (value.equipment == "" || value.equipment == null)? "--" : value.equipment ;
					if((value.equipment == "" || value.equipment == null))
					{
						document.getElementById('table_mc_equipment').style.display = "none";
						if(document.getElementById('table_mc_equipmentA'))
							document.getElementById('table_mc_equipmentA').style.display = "none";
					}
					else
					{
						document.getElementById('table_mc_equipment').style.display = "block";
						if(document.getElementById('table_mc_equipmentA'))
							document.getElementById('table_mc_equipmentA').style.display = "block";
						document.getElementById('table_mc_equipment').innerHTML = value.equipment;
					}
				}

				if(document.getElementById('table_mc_equipmentPrice'))
				{
//					document.getElementById('table_mc_equipmentPrice').innerHTML=(value.equipmentPrice == "" || value.equipmentPrice == null)? "--" : value.equipmentPrice ;
					if((value.equipmentPrice == "" || value.equipmentPrice == null))
					{
						document.getElementById('table_mc_equipmentPrice').style.display = "none";
						if(document.getElementById('table_mc_equipmentPriceA'))
							document.getElementById('table_mc_equipmentPriceA').style.display = "none";
					}
					else
					{
						document.getElementById('table_mc_equipmentPrice').style.display = "block";
						if(document.getElementById('table_mc_equipmentPriceA'))
							document.getElementById('table_mc_equipmentPriceA').style.display = "block";
						document.getElementById('table_mc_equipmentPrice').innerHTML = value.equipmentPrice;
					}
				}

				if(document.getElementById('table_mc_minSpend'))
					document.getElementById('table_mc_minSpend').innerHTML=(value.minspend == "" || value.minspend == null)? "--" : value.minspend ;
				
				if(document.getElementById('table_mc_downloadSpeed'))
					document.getElementById('table_mc_downloadSpeed').innerHTML=(value.downloadSpeed == "" || value.downloadSpeed == null)? "--" : value.downloadSpeed ;
				
				if(document.getElementById('table_mc_uploadSpeed'))
					document.getElementById('table_mc_uploadSpeed').innerHTML=(value.uploadSpeed == "" || value.uploadSpeed == null)? "--" : value.uploadSpeed ;
				
				if(document.getElementById('table_mc_conditions'))
					document.getElementById('table_mc_conditions').innerHTML=(value.conditions == "" || value.conditions == null)? "--" : value.conditions ;
				
				if(document.getElementById('table_mc_homephone'))
					document.getElementById('table_mc_homephone').innerHTML=(value.homephone == "" || value.homephone == null)? "--" : value.homephone ;

				if(document.getElementById('table_mc_nationalCallCost'))
				{
//					document.getElementById('table_mc_nationalCallCost').innerHTML=(value.nationalCallCost == "" || value.nationalCallCost == null)? "--" : value.nationalCallCost ;
					if((value.nationalCallCost == "" || value.nationalCallCost == null))
					{
						document.getElementById('table_mc_nationalCallCost').style.display = "none";
						if(document.getElementById('table_mc_nationalCallCostA'))
							document.getElementById('table_mc_nationalCallCostA').style.display = "none";
					}
					else
					{
						document.getElementById('table_mc_nationalCallCost').style.display = "block";
						if(document.getElementById('table_mc_nationalCallCostA'))
							document.getElementById('table_mc_nationalCallCostA').style.display = "block";
						document.getElementById('table_mc_nationalCallCost').innerHTML = value.nationalCallCost;
					}
				}

				if(document.getElementById('table_mc_contractLength'))
				{
					if((value.contractLength == "" || value.contractLength == null))
					{
						document.getElementById('table_mc_contractLength').style.display = "none";
						if(document.getElementById('table_mc_contractLengthA'))
							document.getElementById('table_mc_contractLengthA').style.display = "none";
					}
					else
					{
						document.getElementById('table_mc_contractLength').style.display = "block";
						if(document.getElementById('table_mc_contractLengthA'))
							document.getElementById('table_mc_contractLengthA').style.display = "block";
						document.getElementById('table_mc_contractLength').innerHTML = value.contractLength;
					}
				}

				if(document.getElementById('table_mc_mobileCallCost'))
					document.getElementById('table_mc_mobileCallCost').innerHTML=(value.mobileCallCost == "" || value.mobileCallCost == null)? "--" : value.mobileCallCost ;
				
				if(value.category.trim() == "Broadband"){
					if(document.getElementById('table_mc_quota_tr'))
						document.getElementById('table_mc_quota_tr').removeAttribute("style");

					if(document.getElementById('table_mc_downloadSpeed_tr'))
						document.getElementById('table_mc_downloadSpeed_tr').removeAttribute("style");

					if(document.getElementById('table_mc_uploadSpeed_tr'))
						document.getElementById('table_mc_uploadSpeed_tr').removeAttribute("style");

					if(document.getElementById('table_mc_homephone_tr'))
						document.getElementById('table_mc_homephone_tr').removeAttribute("style");

					if(document.getElementById('table_mc_nationalCallCost_tr'))
						document.getElementById('table_mc_nationalCallCost_tr').removeAttribute("style");

					if(document.getElementById('table_mc_mobileCallCost_tr'))
						document.getElementById('table_mc_mobileCallCost_tr').removeAttribute("style");
				}
				else{
					if(document.getElementById('table_mc_quota_tr'))
						document.getElementById('table_mc_quota_tr').style.display="none";

					if(document.getElementById('table_mc_downloadSpeed_tr'))
						document.getElementById('table_mc_downloadSpeed_tr').style.display="none";

					if(document.getElementById('table_mc_uploadSpeed_tr'))
						document.getElementById('table_mc_uploadSpeed_tr').style.display="none";

					if(document.getElementById('table_mc_homephone_tr'))
						document.getElementById('table_mc_homephone_tr').style.display="none";

					if(document.getElementById('table_mc_nationalCallCost_tr'))
						document.getElementById('table_mc_nationalCallCost_tr').style.display="none";

					if(document.getElementById('table_mc_mobileCallCost_tr'))
						document.getElementById('table_mc_mobileCallCost_tr').style.display="none";
				}
				if(document.getElementById('div_details_img'))
					{
					document.getElementById('div_details_img').innerHTML=image1;
					}
				
				if(document.getElementById('div_mc_details'))
					{
					document.getElementById('div_mc_details').innerHTML = "";
					}

//				var imgname = value.supplier.toLowerCase();
//				imgname = imgname.replace(' ','-');
//				imgname = imagePath+imgname+".png";
			
				var html = "";//"<img src='"+imgname+".png' alt='Image' />";

				html =	'<div><div class="image1"><img src="'+imagePath+'" alt="Image" /><br/><span class="title1">'+value.supplier+'</span></div> <div class="seperator1"></div>';
				if(value.monthlyPrice != "" && value.monthlyPrice != null)
					html += '<div class="ammount1 commen1">Monthly Price '+value.monthlyPrice+'</div><div class="seperator1"></div>';
				if(value.installationPrice != "" && value.installationPrice != null)
					html += '<div class="ammount1 commen1">Installation Price <br/>'+value.installationPrice+'</div><div class="seperator1"></div>';
				if(value.quota != "" && value.quota != null)
				    html += '<div class="quota1 commen1">'+value.quota+'</div><div class="seperator1"></div>';
//				if(value.contractLength != "" && value.contractLength != null)
//					html +='<div class="description1 commen1">'+value.contractLength+'</div><div class="seperator1"></div>';
				if(value.equipment != ""&& value.equipment != null)
					html +='<div class="description1 commen1">'+value.equipment+'</div><div class="seperator1"></div>';
				html+="</div>";

				if(document.getElementById('div_mc_details'))
					document.getElementById('div_mc_details').innerHTML = html;

				if(document.getElementById('txtProductId'))
					document.getElementById('txtProductId').value= value.id;
				if(popupFor == "cb")
				{
					if(document.getElementById('mc_detailspopup_cover'))
						document.getElementById('mc_detailspopup_cover').removeAttribute("style");
				}
				else{
					$('#mc_detailspopup_cover').css("left","24%");
					$('#mc_detailspopup_cover').css("position","fixed");
					$('#mc_detailspopup_cover').css("top","20%");
					$('#mc_detailspopup_cover').css("z-index","9999");
				}
			}
		});
		document.getElementById('mc_detailspopup').style.display = 'block';
	};

	this.handleNullorEmpty = function(id,innervalue)
	{
		if(innervalue=="")
		{
			innervalue = 'NA';
			document.getElementById(id).innerHTML=innervalue;
		}
		if(innervalue==null)
		{
			innervalue = 'NULL';
			document.getElementById(id).innerHTML=innervalue;
		}
	}

	this.calculateAndDisplayTotalPrice = function(product, action)
	{
		var equipment_installation_price = 0;
		if(product[9] != null && product[9] != "" )
		{
			
			var installation_price = product[9];
			
			if(installation_price.charAt(0)=='$')
			{
				equipment_installation_price = parseFloat(installation_price.substr(1));
			}
			
		}
		if(product[8] != null && product[8] != "" )
		{
			 var equipment_price = product[8];
			
			if(equipment_price.charAt(0)=='$')
			{
				equipment_installation_price = (parseFloat(equipment_installation_price) + parseFloat(equipment_price.substr(1)));
			}
		}
		var monthlyprice = product[6];
		
		var fixprice =product[7];
		if(monthlyprice!="" )
		{
			if(monthlyprice.charAt(0)=='$')
			{
				monthlyprice = monthlyprice.substr(1);
			}

			if(action == '+')
			{
				total_price_per_month = parseFloat(parseFloat(total_price_per_month).round(2) + parseFloat(monthlyprice).round(2)).toFixed(2);
			}
			else
			{
				total_price_per_month = parseFloat(parseFloat(total_price_per_month).round(2) - parseFloat(monthlyprice).round(2)).toFixed(2);
			}
			$('#monthlycost').html('$'+total_price_per_month);
		}
		if(fixprice!=""  )
		{
			if(fixprice.charAt(0)=='$')
			{
				fixprice = fixprice.substr(1);
			}
			if(action == '+')
			{
				total_fixed_price = parseFloat(parseFloat(total_fixed_price).round(2) + parseFloat(fixprice).round(2)).toFixed(2);
				
			}
			else
			{
				total_fixed_price = parseFloat(parseFloat(total_fixed_price).round(2) - parseFloat(fixprice).round(2)).toFixed(2);
			}
			$('#fixcost').html('$'+total_fixed_price);
		}
		if(equipment_installation_price!="" && equipment_installation_price!= 0)
		{
			if(action == '+')
			{
				total_fixed_price = parseFloat(parseFloat(total_fixed_price).round(2)+ parseFloat(equipment_installation_price).round(2)).toFixed(2);
				
			}
			else
			{
				total_fixed_price = parseFloat(parseFloat(total_fixed_price).round(2)- parseFloat(equipment_installation_price).round(2)).toFixed(2);
			}
			$('#fixcost').html('$'+total_fixed_price);
		}
	}

	this.calculateAndDisplayTotalPriceOfRecommended = function(product, action)
	{

		var monthlyprice = product[6];
		var fixprice =product[7];
		var equipmentp = product[8];
		var installationp = product [9];
		var equipment_installation_price = 0;
		if(product[9] != null && product[9] != "" )
		{
			
			var installation_price = product[9];
			
			if(installation_price.charAt(0)=='$')
			{
				equipment_installation_price = parseFloat(installation_price.substr(1));
			}
			
		}
		if(product[8] != null && product[8] != "" )
		{
			 var equipment_price = product[8];
			
			if(equipment_price.charAt(0)=='$')
			{
				equipment_installation_price = (parseFloat(equipment_installation_price) + parseFloat(equipment_price.substr(1)));
			}
		}

		if(monthlyprice!="" )
		{
			if(monthlyprice.charAt(0)=='$')
			{
				monthlyprice = monthlyprice.substr(1);
			}

			if(action == '+')
			{
				recommend_select_total_price = parseFloat(parseFloat(recommend_select_total_price).round(2) + parseFloat(monthlyprice).round(2)).toFixed(2);
			}
			else
			{
				recommend_select_total_price = parseFloat(parseFloat(recommend_select_total_price).round(2) - parseFloat(monthlyprice).round(2)).toFixed(2);
			}
		
			$('#monthlycost').html('$'+recommend_select_total_price);
			if(document.getElementById('monthlyprice_newselection_row'))
		    {
				document.getElementById('monthlyprice_newselection_row').innerHTML = "<strong>$"+recommend_select_total_price+"</strong>";
		    }
			
		}
		if(fixprice!="" )
		{
			if(fixprice.charAt(0)=='$')
			{
				fixprice = fixprice.substr(1);
			}
			if(action == '+')
			{
				recommend_select_total_fixed_price = parseFloat(parseFloat(recommend_select_total_fixed_price).round(2) + parseFloat(fixprice).round(2)).toFixed(2);
			}
			else
			{
				recommend_select_total_fixed_price = parseFloat(parseFloat(recommend_select_total_fixed_price).round(2) - parseFloat(fixprice).round(2)).toFixed(2);
			}
			$('#fixcost').html('$'+recommend_select_total_fixed_price);
			if(document.getElementById('oneoff_newselection_row'))
		    {
				document.getElementById('oneoff_newselection_row').innerHTML = "<strong>$"+recommend_select_total_fixed_price+"</strong>";
		    }
		}
		if(equipment_installation_price!="" && equipment_installation_price!= 0)
		{
			if(action == '+')
			{
				recommend_select_total_fixed_price = parseFloat(parseFloat(recommend_select_total_fixed_price).round(2)+ parseFloat(equipment_installation_price).round(2)).toFixed(2);
				
			}
			else
			{
				recommend_select_total_fixed_price = parseFloat(parseFloat(recommend_select_total_fixed_price).round(2)- parseFloat(equipment_installation_price).round(2)).toFixed(2);
			}
			$('#fixcost').html('$'+recommend_select_total_fixed_price);
			if(document.getElementById('oneoff_newselection_row'))
		    {
				document.getElementById('oneoff_newselection_row').innerHTML = "<strong>$"+recommend_select_total_fixed_price+"</strong>";
		    }
		}
	};
	
	this.clearSelection = function()
	{
		var confirmselected = CM_obj.getConfirmSelected();
		var tempselectionarray = confirmselected;
		
		if(confirmselected)
			{
				
				confirmselected.each(function(value1, index)
    		    {
					
					var element = document.getElementById(value1[0]+'-'+value1[3]);
					
					if(document.getElementById(value1[0]+'-'+value1[3]))
					{
						
						element.parentNode.removeChild(element);
					}
					
					//CM_obj.deleteConfirmSelected(value1);
					//controller.calculateAndDisplayTotalPriceOfRecommended(value1,'-');
					//CM_obj.deleteMyBundleResult(value1);
					//$('#basket'+value1[3]).remove();
				//	controller.updateDiv('confirmation_div');
					//controller.viewtoMybundle(value1[0], CM_obj.getSelectedProducts());
    		    });
			}
		if(tempselectionarray)
			{
			
			tempselectionarray.each(function(value1, index)
			      {
					CM_obj.deleteConfirmSelected(value1);
					controller.calculateAndDisplayTotalPriceOfRecommended(value1,'-');
					
			      });
			}
		
		
		var confirmselected = CM_obj.getConfirmSelected();
		
		
		CM_obj.deleteAllConfirmSelection();
		var confirmselected = CM_obj.getConfirmSelected();
	
		recommend_select_total_fixed_price = 0;
		recommend_select_total_price = 0;
		if(document.getElementById('oneoff_newselection_row'))
	    {
			document.getElementById('oneoff_newselection_row').innerHTML = "<strong>$"+recommend_select_total_fixed_price+"</strong>";
	    }
		if(document.getElementById('monthlyprice_newselection_row'))
	    {
			document.getElementById('monthlyprice_newselection_row').innerHTML = "<strong>$"+recommend_select_total_price+"</strong>";
	    }
		   
		 
	};
	
	this.setBrandsOfCategory = function (category)
	{
		try
		{
			
			var sub_cat		= category.toLowerCase();
			sub_cat = 	sub_cat.replace(' ','-');
			var acc_index	= allCategory.indexOf(sub_cat)+1;
			var cat_name 	= allCategory[acc_index];
			if(accordian_flag == 0)
				{
				 cat_name =  category;
				}
			if(document.getElementById('main-category-wrapper-'+cat_name))
			{
				document.getElementById('main-category-wrapper-'+cat_name).style.height = "425px";
				document.getElementById('main-category-wrapper-'+cat_name).innerHTML = "";
				document.getElementById('main-category-wrapper-'+cat_name).innerHTML = CV_obj.getLoadingHTML();
			}
			
			$.ajax(
			{
				url: 'index.php?option=com_homeconnect&task=getBrandsDataFromAPI&format=raw',
				type: 'post',
				data:
				{
					
					cat_name : cat_name,
					
				},
				datatype: 'json',
				success: function(data)
				{
										
					if(document.getElementById('main-category-wrapper-'+cat_name))
						{
						
						 document.getElementById('main-category-wrapper-'+cat_name).innerHTML="";
						 document.getElementById('main-category-wrapper-'+cat_name).innerHTML=data;
						}
					
				}
			});
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.multiRoom_selection =	function()
	{
		if(document.getElementById("rdoMultiRoomYes").checked == true)
		{
			var drdl_TV_Connect_Rooms = document.getElementById("drdl_TV_Connect_Rooms");

			var multiroom_data = '{"multiroomselection": [ { "name": "TV Content", "count": "3", "values": [ { "name": "Foxtel", "values": { "room1": { "HQBox": "$15", "HQ Box in HD": "$25", "XBox": "$0" }, "room2": { "HQBox": "$15", "HQ Box in HD": "$25", "XBox": "$0" }, "room3": { "HQBox": "$15", "HQ Box in HD": "$25", "XBox": "$0" }, "room4": { "HQBox": "$15", "HQ Box in HD": "$25", "XBox": "$0" } } }, { "name": "Foxtel Telstra", "values": { "room1": { "HQBox": "$15", "HQ Box in HD": "$15", "XBox": "$0" }, "room2": { "HQBox": "$15", "HQ Box in HD": "$15", "XBox": "$0" }, "room3": { "HQBox": "$15", "HQ Box in HD": "$15", "XBox": "$0" }, "room4": { "HQBox": "$15", "HQ Box in HD": "$15", "XBox": "$0" } } }, { "name": "Foxtel Optus", "values": { "room1": { "HQBox": "$15", "HQ Box in HD": "$20", "XBox": "$0" }, "room2": { "HQBox": "$15", "HQ Box in HD": "$20", "XBox": "$0" }, "room3": { "HQBox": "$15", "HQ Box in HD": "$20", "XBox": "$0" }, "room4": { "HQBox": "$15", "HQ Box in HD": "$20", "XBox": "$0" } } } ] } ] }';
			var res = JSON.parse(multiroom_data);

			var myselection = CM_obj.getSelectedProducts();

			var totalp = 0;
			var temp_product = [];

			var price_product_id = 0;

			var price1 = "";
			var price2 = "";
			var price3 = "";
			var price4 = "";

			var myselection_length = myselection.length;
			for(i = 0; i < myselection_length; i++)
			{
				res.multiroomselection.each(function(datavalue, index)
				{
					if(datavalue.name == 'TV Content')
					{
						datavalue.values.each(function(value, index)
						{
							var value_name1 = value.name.toLowerCase();
							var value_name2 = myselection[i][1].toLowerCase().replace('-',' ');

							if(value_name1 == value_name2)
							{
								temp_product = myselection[i];

								price_product_id = myselection[i][3];

								price1 = value.values.room1;
								price2 = value.values.room2;
								price3 = value.values.room3;
								price4 = value.values.room4;
							}
						});
					}
				});

				if(price_product_id != 0)
				{
					break;
				}
			}

			var roomvalue = CM_obj.getRoomValue();

			// if(roomvalue['room1'] =='')
			price1	=  price1[roomvalue.room1];
			price2	=  price2[roomvalue.room2];
			price3	=  price3[roomvalue.room3];
			price4	=  price4[roomvalue.room4];

			if(price1 != "" && price1 != undefined)
			{
				if(price1.charAt(0) == '$')
				{
					price1 = price1.substr(1);
					totalp = (totalp + parseFloat(price1).round(2));
				}
			}
			if(price2 != "" && price2 != undefined)
			{
				if(price2.charAt(0) == '$')
				{
					price2 = price2.substr(1);
					totalp = (totalp	+	parseFloat(price2).round(2));
				}
			}
			if(price3 != "" && price3 != undefined)
			{
				if(price3.charAt(0) == '$')
				{
					price3 = price3.substr(1);
					totalp = (totalp	+	parseFloat(price3).round(2));
				}
			}
			if(price4 != "" && price4 != undefined)
			{
				if(price4.charAt(0) == '$')
				{
					price4 = price4.substr(1);
					totalp = (totalp	+ 	parseFloat(price4).round(2));
				}
			}
			var mprice = 0;
			if(temp_product[6] != "" && temp_product[6] != undefined)
			{
				if(temp_product[6].charAt(0) == '$')
				{
					mprice = temp_product[6].substr(1);
				}
			}

			var total_price = parseFloat(total_price_per_month); // $("#monthlycost").html();
//			if(total_price.charAt(0) == '$')
//			{
//				total_price = parseFloat(total_price.substr(1));
//			}

			final_prices = price_product_id+','+totalp;
			total_price += parseFloat(totalp);
			$("#monthlycost").html('$'+(parseFloat(total_price).toFixed(2)));

			totalp = parseFloat(parseFloat(totalp).round(2) + parseFloat(mprice).round(2));

			totalp = '$'+parseFloat(totalp).toFixed(2);

			$("#monthly_price_"+price_product_id).html('Monthly Price: '+totalp);

//			controller.productRemove(temp_product.toString());

//			temp_product[6] = totalp;

//			controller.AddToMyBundleC(temp_product,image_PATH);
		}
	};

	this.setRoom =	function(nofrooms, id, value)
	{
		for(var i = 1; i <= nofrooms; i++)
		{
			value = document.getElementById('room'+i).value;
			id = 'room'+i;
			CM_obj.setRoomValue(id, value);
		}
	};

	this.navigateSelectionList = function(direction, prouct_div_in_basket, category)
	{
		try
		{
			var selection_length ;
			selection_length = navigation_in_basket[category].length;

			var index, display_div;
			index = navigation_in_basket[category].indexOf(prouct_div_in_basket);

			if(direction == 'update')
			{
				for(var i = 0; i < selection_length; i++)
				{
					if(document.getElementById(navigation_in_basket[category][i]))
					{
						document.getElementById(navigation_in_basket[category][i]).style.display = "none";
					}

					if(navigation_in_basket[category][i] == prouct_div_in_basket)
					{
						if(document.getElementById(navigation_in_basket[category][i]))
						{
							document.getElementById(navigation_in_basket[category][i]).style.display = "block";
						}
					}
				}
			}
			else
			{
				for(var i = 0; i < selection_length; i++)
				{
					if(document.getElementById(navigation_in_basket[category][i]))
					{
						document.getElementById(navigation_in_basket[category][i]).style.display = "none";
					}
	
					if(i == index)
					{
						var temp_index = navigation_in_basket[category].indexOf(navigation_in_basket[category][i]);
						if(direction == 'next')
						{
							if(temp_index == (selection_length-1))
							{
								if(document.getElementById(navigation_in_basket[category][0]))
								{
									document.getElementById(navigation_in_basket[category][0]).style.display = "block";
								}
							}
							else
							{
								if(document.getElementById(navigation_in_basket[category][temp_index+1]))
								{
									document.getElementById(navigation_in_basket[category][temp_index+1]).style.display = "block";
								}
							}
						}
						else if(direction == 'prev')
						{
							if(temp_index == 0)
							{
								if(document.getElementById(navigation_in_basket[category][selection_length-1]))
								{
									document.getElementById(navigation_in_basket[category][selection_length-1]).style.display = "block";
								}
							}
							else
							{
								if(document.getElementById(navigation_in_basket[category][temp_index-1]))
								{
									document.getElementById(navigation_in_basket[category][temp_index-1]).style.display = "block";
								}
							}
						}
						break;
					}
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.updateNavigationList = function(cat_name) {
		try {
			var selection_length ;
			selection_length = navigation_in_basket[cat_name].length;

			for(var i = 0; i < selection_length; i++) {
				if(document.getElementById('prev_'+navigation_in_basket[cat_name][i]))
				{
					document.getElementById('prev_'+navigation_in_basket[cat_name][i]).style.display = "block";
				}
				if(document.getElementById('next_'+navigation_in_basket[cat_name][i]))
				{
					document.getElementById('next_'+navigation_in_basket[cat_name][i]).style.display = "block";
				}
				if(i == 0) {
					if(document.getElementById('prev_'+navigation_in_basket[cat_name][i]))
					{
						document.getElementById('prev_'+navigation_in_basket[cat_name][i]).style.display = "none";
					}
				}
				if(i == (selection_length-1))
				{
					if(document.getElementById('next_'+navigation_in_basket[cat_name][i]))
					{
						document.getElementById('next_'+navigation_in_basket[cat_name][i]).style.display = "none";
					}
				}
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.getRadioValue = function(name) {
		var radios = document.getElementsByName(name);

		for (var i = 0; i < radios.length; i++) {
			if (radios[i].checked) {
				return radios[i].value;
				break;
			}
		}
	};

	// same function is above but ony change is this function called on next button click and above is on checkbox tick
	this.confirmRecommendedSelection = function(src_id,dest_id,imagepath,flag_string)
	{

		try
		{
			if(flag_string=='new_selection' && CM_obj.getConfirmSelected().length > 0 )
			{
				recommended_flag = 1;
				temp_total_fixed_price = total_fixed_price;
				total_fixed_price = 0;
				temp_total_price_per_month = total_price_per_month;
				total_price_per_month = 0;

				var newselection =  CM_obj.getConfirmSelected();
				var yourselection = CM_obj.getSelectedProducts();

				for(var i = 0; i < yourselection.length; i++)
				{
					CM_obj.addToTempStore(yourselection[i]);
					$('#basket'+yourselection[i][3]).remove();	
				}
				CM_obj.deleteAllProductSelection();

				newselection.each(function(value,index)
				{
					controller.AddToMyBundleC(value,imagepath);	
				});

				if( CM_obj.getConfirmSelected().length > 0)
				{
					controller.switchInnerDivs(src_id, dest_id, 0, 0);
					$('#monthlycost').html('$'+recommend_select_total_price);
					$('#fixcost').html('$'+recommend_select_total_fixed_price);
				}
				document.getElementById("id_new_selected_ul_tv-content").innerHTML =document.getElementById("new_selected_ul_tv-content").innerHTML;
				document.getElementById("id_new_selected_ul_music-content").innerHTML =document.getElementById("new_selected_ul_music-content").innerHTML;
				document.getElementById("id_new_selected_ul_gaming-content").innerHTML =document.getElementById("new_selected_ul_games").innerHTML;
				document.getElementById("id_new_selected_ul_broadband-content").innerHTML =document.getElementById("new_selected_ul_broadband").innerHTML;
				controller.startCustomSlider("id_new_selected_ul_tv-content", "li");
				controller.startCustomSlider("id_new_selected_ul_music-content", "li");
				controller.startCustomSlider("id_new_selected_ul_gaming-content", "li");
				controller.startCustomSlider("id_new_selected_ul_broadband-content", "li");
			}
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};
	
	this.SelectionData = function(category)
	{
		
		var sub_cat	= category.toLowerCase();
		sub_cat		= 	sub_cat.replace(' ','-');

		var acc_index	= allCategory.indexOf(sub_cat)+1;
		var cat_name	= allCategory[acc_index];
		if(accordian_flag == 0)
        {
			cat_name = category;
        }
		var selected = CM_obj.getSelectedProducts();
		var params	= "category="+cat_name+"&selection=";
		var selected_values = "";

		if(selected.length > 0 )
		{
			selection = [];
			var brandname = "";
			var each_category = "";
			var id = "";
			selected.each(function(value,index)
			{
				brandname= "";
				each_category="";
				id ="";

				allproduct.each(function(value1, index)
				{
					if(value1.id == value[3])
					{
					   //brandname = value1.supplier;
						brandname = value[1];

						//id= value1.id;
						id = value[3];

						each_category = value[0];
						//each_category = value1.category;
					}
				});

				selection.push(value);
				params += each_category+'/'+brandname+'/'+id+',';
				selected_values += each_category+'/'+brandname+'/'+id+',';
			});

			// Aks : Added so that brands are displayed according to selection
	   		if(document.getElementById('main-category-wrapper-'+cat_name))
			{
				document.getElementById('main-category-wrapper-'+cat_name).style.height = "425px";
				document.getElementById('main-category-wrapper-'+cat_name).innerHTML = "";
				document.getElementById('main-category-wrapper-'+cat_name).innerHTML = CV_obj.getLoadingHTML();
			}

			$.ajax(
			{
				url: 'index.php?option=com_homeconnect&task=sendSelectionChanges&format=raw',
				type: 'post',
				data:
				{
					category: cat_name,
					selection: selected_values
				},
				datatype: 'json',
				success: function(data)
				{
					if(document.getElementById('main-category-wrapper-'+cat_name))
					{
						// Aks : Added to change height to default because extra height was added for loading div to display
						document.getElementById('main-category-wrapper-'+cat_name).style.height = "";

						document.getElementById('main-category-wrapper-'+cat_name).innerHTML = "";
						document.getElementById('main-category-wrapper-'+cat_name).innerHTML = data;
					}
				}
			});
		}
		else
		{
			
			this.setBrandsOfCategory(category);
		}
	};
	
	$("#main_category_accordion").accordion({
		  beforeActivate: function(event, ui) {
		   if(ui.newHeader) {
		    console.log('accordian created');
		                    }
		                                      }
		                          });
	
};