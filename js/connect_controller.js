/**
 * 
 */

var Controller = function()
{
	var CV_obj;
	var CM_obj;

	var tracker;
	var main_divs;
	var controller;
	var prevDiv;

	this.init = function (trackdata)
	{
		controller = this;
       	CM_obj	= new Model();
		CV_obj	= new View();
		tracker = new Array();
		main_divs = new Array();
		CM_obj.setEmail(trackdata.Email);
		// tracker.push("Tracker Object");
		// tracker.push("-------------------------------------------------------- ");
		tracker.push("IP Address:"+CM_obj.getClientIP());
		tracker.push("Address :"+trackdata.Adress);
		tracker.push("Email:"+CM_obj.getEmail());
		tracker.push("decided to:"+trackdata.decidedto);
		tracker.push(" --------------------------------------------------------");

		if(document.getElementById("geocomplete"))
		{
//			controller.geoComplete("#geocomplete");
		}
		if(document.getElementById("category_accordion_div"))
		{
			controller.accordion("#category_accordion_div");
		}
		if(document.getElementById("tool_tip_1"))
		{
			controller.toolTip("#tool_tip_1");
		}
		if(document.getElementById("tool_tip_2"))
		{
			controller.toolTip("#tool_tip_2");
		}

		this.initScreenDivs();
	};

	this.initScreenDivs = function()
	{
		/* Aks : No need as Landing Div is now a separate Module
		if(document.getElementById("landing_div"))
		{
			main_divs.push("#landing_div");
		}
		*/

		if(document.getElementById("main_accordian_div"))
		{
			main_divs.push("#main_accordian_div");
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

		controller.switchDivs("#main_accordian_div");

		CM_obj.setCurrentState("#category_accordion_div");
	};

	/* Aks : Shifted To connect_module.js
	this.geoComplete = function (id)
	{
		$( id ).geocomplete({
			country: 'au'
		})
			.bind("geocode:result", function(event, result)
			{
				// Aks : No need
				// $.log("Result: " + result.formatted_address);
				CM_obj.setValidatedAddress(result.formatted_address);
			})

			.bind("geocode:error", function(event, status)
			{
				// Aks : No need
				// $.log("ERROR: " + status);
				CM_obj.setValidatedAddress("ERROR");
			})

			.bind("geocode:multiple", function(event, results)
			{
				// Aks : No need
				// $.log("Multiple: " + results.length + " results found");
			});

		/* Aks : Not Required
		$("#find").click(function()
		{
			$( id ).trigger("geocode");
		});
		//*
	};
	*/

	this.accordion = function (id)
	{
		$( id ).accordion(
		{
			header: "> div > h3",
			heightStyle: "content",
			duration: 10,
			animated: 'easeInCirc'
		});

		// Aks : Added To Set Selected accordion on top
		$(id+" h3").click(function(event)
		{
		    $(this).closest('div').prependTo(id);
		});
	};

	this.nextAccordion = function (main_accordion_id, index)
	{
		tracker.push("switch  to category"+ index);
		
		$('#ui-accordion-'+main_accordion_id+'-header-'+index).click();
		/* Aks : Not Needed
		$('#category_accordion_div').accordion('activate', 2);
		$("#category_accordion_div h3").click(function(event)
		{
		    $(this).closest('div').prependTo("#category_accordion_div");
		});
		*/
	};

	this.toolTip = function (id)
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
	};

	// SDs TLD email validation
	this.checkEmail = function (email)
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
	};

	this.switchDivs = function (id)
	{
		tracker.push("swich to view :"+id);
		CM_obj.setCurrentState(id);

		if(document.getElementById("geocomplete"))
		{
			// $( "#geocomplete" ).trigger("geocode");
		}

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
					if(value=="#thank_div")
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
	};

	// modified by SDs for switch multiple inner divs
	this.switchInnerDivs = function (h_ids, s_ids, product_id, cat_id)
	{
		tracker.push("switched from view "+h_ids+"to "+s_ids );
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
		if(cat_id > 0)
		{
			controller.setProductsOfCategory(hide_ids, show_ids,product_id, cat_id);
		}
		if(show_ids=="confirmation_div" || show_ids=="recommendation_div"  || show_ids=="installation_div")
		{
			controller.updateDiv(show_ids);
		}
	};

	this.setProductsOfCategory = function (prev_div, div_id, product_id, cat_id)
	{
		tracker.push("brands under each category display created");
		if(html == "" || !html)
		{
			var html = "";
			html += '<div  style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; width: 20px;">B '+product_id+'</div>'+
					'<div style="margin-top: 15px;"><div  style="cursor: pointer; border: 1px solid #ccc; padding: 16px; margin: 0 20px 0 0; float: left;"><INPUT onClick="javascript:controllerObj.AddToMyBundle(this.id)" id="'+cat_id+'_1" NAME="p" TYPE="CHECKBOX" VALUE="1"></div>'+
					'<div >Product A : </div>'+
					'<div>Main Features : ABC</div>'+
					'<div>Cost : $ pcm</div></div>'+
					'<div  style="margin-top: 15px;"><div  style="cursor: pointer; border: 1px solid #ccc; padding: 16px; margin: 0 20px 0 0; float: left;"><INPUT onClick="javascript:controllerObj.AddToMyBundle(this.id)" id="'+cat_id+'_2"NAME="p" TYPE="CHECKBOX" VALUE="1"></div>'+
					'<div>Product B : </div>'+
					'<div>Main Features : XYZ</div>'+
					'<div>Cost : $ pcm</div></div>'+
					'<div   style="margin-top: 15px;"><div  style="cursor: pointer; border: 1px solid #ccc; padding: 16px; margin: 0 20px 0 0; float: left;"><INPUT onClick="javascript:controllerObj.AddToMyBundle(this.id)" id="'+cat_id+'_3"  NAME="p" TYPE="CHECKBOX" VALUE="1"></div>'+
					'<div>Product C : </div>'+
					'<div>Main Features : DEF</div>'+
					'<div>Cost : $ pcm</div></div>'+
					'<div onClick="javascript:controllerObj.switchInnerDivs(\''+div_id+'\', \''+prev_div+'\', 0);" style="cursor: pointer; float: right;">'+
					'<=</div>'+
					'<div onClick="javascript:controllerObj.switchInnerDivs(\''+div_id+'\', \''+prev_div+'\', 0);" style="cursor: pointer; float: right;">'+
					'=></div>';
		}

		if(document.getElementById(div_id))
		{
			document.getElementById(div_id).innerHTML = html;
		}
	};

	this.showDiv = function (id)
	{
		CM_obj.setCurrentState(id);

		// get effect type from
		var selectedEffect = "slide";

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
		var selectedEffect = "slide";

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

	/* Aks : Shifted To connect_module.js
	this.oncreatebundle = function(main_accordian_div,target_div,default_div)
	{
		if(target_div=="main_category_div")
		{
			prevDiv="confirmation_div";
		}
		else
		{
			prevDiv=target_div;
		}

		controller.switchDivs("#"+main_accordian_div);
		controller.switchInnerDivs(default_div, target_div, 0);
	}; */

	// SDs for back navigation from details page
	this.switchInnerPrevDivs = function(source_div,prevDiv)
	{
		tracker.push("swich  from one view to another inner category "); 
		CM_obj.setCurrentState(prevDiv);

		controller.switchInnerDivs(source_div, prevDiv, 0, 0);
	};

	// SDs for addto my bundle
	this.AddToMyBundle = function(category)
	{
		var cat_product = category.split("_");
		var selected_product;

		if(document.getElementById(category).checked)
		{
			tracker.push("product added to my bundle"+cat_product[1]);

			CM_obj.setAddToMyBundleResult(cat_product[0], cat_product[1]);
		    selected_product = CM_obj.getSelectedProducts(cat_product[0]);

		    controller.viewtoMybundle(cat_product[0], selected_product);
		}
		else
		{
			tracker.push("product deleted to my bundle"+cat_product[1]);

			CM_obj.deleteAddedMyBundleResult(cat_product[0],cat_product[1]);
		    selected_product = CM_obj.getSelectedProducts(cat_product[0]);

		    controller.viewtoMybundle(cat_product[0],selected_product)
		}
	};

	// SDs for view in mybundle section
	this.viewtoMybundle = function(cat_id, selected_product)
	{
		var nofselection = selected_product.length;
		$(cat_id ).html(nofselection);
		$("#cat_"+cat_id+"_"+cat_id).html(nofselection);
	};

	// SDs for go to respecive selection page from my bundle section view edit
	this.switchtoSelectionpage = function(cat_id)
	{
		tracker.push("selected product under category"+cat_id+"edited");
		var selected_product;
		selected_product = CM_obj.getSelectedProducts(cat_id+1);

		if(CM_obj.getCurrentState() == "#category_accordion_div")
		{
			// controller.hideDiv('#category_accordion_div');
			controller.showDiv('#category_accordion_div');
			controller.nextAccordion('category_accordion_div',cat_id);
			controller.showDiv('#main_category_div');

			// Aks : Added as Temporary solution
			document.getElementById('main_category_inner_div_'+parseInt(cat_id+1)).style.display = "none";
			// controller.hideDiv('#main_category_inner_div_'+parseInt(cat_id+1));

			controller.showDiv('#main_category_inner_product_div_'+(cat_id+1));
			// main_category_inner_product_div_2
        }
		else
		{
			controller.hideDiv(CM_obj.getCurrentState());
			// controller.hideDiv('#category_accordion_div');
			controller.showDiv('#category_accordion_div');
			controller.nextAccordion('category_accordion_div',cat_id);
			controller.showDiv('#main_category_div');

			// Aks : Added as Temporary solution
			document.getElementById('main_category_inner_div_'+parseInt(cat_id+1)).style.display = "none";
			// controller.hideDiv('#main_category_inner_div_'+parseInt(cat_id+1));

			controller.showDiv('#main_category_inner_product_div_'+parseInt(cat_id+1));
		}
	};

	// SDs for set confirmation page on selected product and set installation page also [* reviewed ]
	this.updateDiv = function(id)
	{
		
		if(id == "confirmation_div")
		{
			tracker.push("selected product displayed confirmation page");
			for (var i = 1;i <= 5; i++)
			{
				var selected = CM_obj.getSelectedProducts(cat_id=i);
     			$("#c"+i).html(selected.length);
				
			}
		}

		if(id=="installation_div")
		{
			tracker.push("selected product displayed installation page");
			for (var i = 1;i <= 5; i++)
			{
				var selected = CM_obj.getSelectedProducts(cat_id = i);

				if(selected.length!=0)
				{
					$('#category_check_'+i).attr('disabled',false);
					$('#category_check_'+i).attr('checked',true);
					if(i!=4)
					$('#nof_installation_cat_'+i).attr('disabled',false);
				}
				else
				{
					$('#category_check_'+i).attr('disabled',true);
					if(i!=4)
					$('#nof_installation_cat_'+i).attr('disabled',true);
				}
			}
		}
	};

	// SDs for creating thank you page on selected brands [* reviewed]
	this.createThankpage = function()
	{
		
		for (var i = 1;i <= 5; i++)
		{
			var selected = CM_obj.getSelectedProducts(cat_id = i);
			if( selected.length!=0)
			{
				$("#thank_div").append('<div  style="cursor: pointer; border: 1px solid #ccc; padding: 25px; margin: 15px; float: left;"> B '+i+' </div>');
			}
	     }
	};

	// SDs for Details page validation and forward to next //[reviewed * ]
	this.ValidateAndForword = function(targetdiv)
	{
		tracker.push("validating user details ");
		var name			= document.getElementById('d_name').value;
		var phone			= document.getElementById('d_phone').value;
		var email			= document.getElementById('d_email').value;
		var haddress		= document.getElementById('d_haddress').value;
		var installaddress	= document.getElementById('d_install_address').value;
		var error			= "";
		var textfields		= ["d_name","d_phone","d_email","d_haddress","d_install_address"];
		for(var i=0;i<textfields.length-1;i++)
		{
			if(controller.emptyCheck(textfields[i]))
			{
				document.getElementById(textfields[i]).style.background="#FFFFFF";
				controller.ErrorMessage("");
			}
			else
			{
				
				document.getElementById(textfields[i]).style.background="#FFA07A";
				controller.ErrorMessage("field should not empty");
			}
		}

		if(  $("#errordiv").html() == "")
		{
			if(controller.checkEmail(email))
			{
				CM_obj.setEmail(email);
				tracker.push("preapring mail for customer");
				var mailbody;
				var subject		= "Your Order";
				mailbody="<html>Thank you for using ConnectupMyHome<br/>Your Order Details Are:<br>"+
				             "<table border='1'><tr><th>Category</th><th>No of Products</th></tr>";
						             
				for (var i = 1;i <= 5; i++)
				{
					var selected = CM_obj.getSelectedProducts(cat_id=i);
					
					mailbody=mailbody+"<tr><td>Category_"+i+"</td><td>"+selected.length+"</td></tr>";
					
				}
				mailbody=mailbody+"</table></html>";
				tracker.push("sending mail");
				controller.sendmail(email,mailbody,subject);
			}
		}
	};

	// SDs for error message on details page
	this.ErrorMessage = function(message)
	{
		$("#errordiv").html(message);
	};

	this.emptyCheck = function(id)
	{
		if(document.getElementById(id).value == "")
		{
			return false;
		}
		else
		{
			return true;
		}
	};
	//SDs phone number validati

	this.sendmail = function (mailTo, data, subject)
	{
		var ip=CM_obj.getClientIP();
		var email=CM_obj.getEmail();
		$.ajax(
		{
			url: 'index.php?option=com_homeconnect&task=createLog_SendEmail&format=raw',
			type: 'post',
			data:
			{
				data: data,
				email: mailTo,
				subject: subject,
				log : tracker,
				ip : ip,
			},
			datatype: 'json',
			success: function(data)
			{
				// Aks : Check if mail is sent then only switch divs here
				if(data=="success")
				{
					tracker.push("mail sent successfully");
					controller.switchDivs("#thank_div");
					tracker.push("swiched to thank u page ");
				}
				else
				{
					alert("ERROR in SENDING MAIL")
					tracker.push("error in sending mail error message displayed");
				}
			}
		});
	};

	// SDs for category greyed out  
	this.greyedoutCategory = function(cat_id,id)
	{
		tracker.push("user dont want any product under catgory"+id);
		tracker.push("trayed out category from my bundle");

		if(document.getElementById(cat_id).checked == true)
		{
			document.getElementById(cat_id+'_'+id).style.color = "#99CC00";
			if(id!=5)
			{
				controller.nextAccordion('category_accordion_div',id);
			}
			else
			{
				controller.switchInnerDivs('main_category_div','installation_div',0,0);
			}
		}
		else
		{
			document.getElementById(cat_id+'_'+id).style.color = "#000000";
		}
	};
};