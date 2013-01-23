/**
 * 
 */

var Model = function ()
{
	var MV_obj;
	var MC_obj;

	var model;
	var brands;
	var address;
	var brandProducts;
	var i=0;
	var currentscreen;

	var selected_product = new Array();
	for(i = 0; i <= 5; i++)
	{
		selected_product[i]=new Array();
	}
	var category_wise_selected = new Array();

	this.init = function()
	{
		model	= this;
		MV_obj	= new View();

		model.address	= "ERROR";
		model.currentscreen = "";
		model.brands	= new Array();
		model.brandProducts = new Array();
	};

	this.setValidatedAddress = function (value)
	{
		if(document.getElementById('main_category_div_address'))
		{
			document.getElementById('main_category_div_address').innerHTML = "";
			document.getElementById('main_category_div_address').innerHTML = value;
		}
		this.address = value;
	};

	this.getValidatedAddress = function ()
	{
		return this.address;
	};

	this.getClientIP = function ()
	{
		if (window.XMLHttpRequest)
		{
			xmlhttp = new XMLHttpRequest();
		}
	    else
	    {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	    }

		xmlhttp.open("GET","http://api.hostip.info/get_html.php",false);
		xmlhttp.send();

		hostipInfo = xmlhttp.responseText.split("\n");

		for (i=0; hostipInfo.length >= i; i++)
		{
			ipAddress = hostipInfo[i].split(":");
			if ( ipAddress[0] == "IP" )
			{
				return ipAddress[1];
			}
		}
		return false;
	};

	this.getBrandProductsAPI = function()
	{
		$.ajax(
		{
			url: 'index.php?option=com_homeconnect&task=callWebService&format=raw',
			type: 'post',
			data: {	},
			datatype: 'html',
			success: function(data)
			{
				console.log(data);
			}
		});
	};

	this.setBrandProducts = function (value)
	{
		model.brands.push(value);
	};

	this.getBrandProducts = function()
	{
		return model.brands;
	};

	this.setAddToMyBundleResult=function(category, product_id)
	{
		if(category_wise_selected[category] === undefined)
		{
			category_wise_selected[category]=0;
		}
		// console.log(category);

		category_wise_selected[category]++;
		selected_product[category].push(product_id);
		// console.log(selected_product[category]);
		// console.log(selected_product);
	};

	this.deleteAddedMyBundleResult=function(category, product_id)
	{
		if(category_wise_selected[category] === undefined)
		{
			category_wise_selected[category]=0;
		}
		category_wise_selected[category]--;
		selected_product[category].splice(selected_product[category].indexOf(product_id), 1);
		// model.selected_product.remByVal(product_id);
	};

	this.getSelectedProducts = function (cat_id)
	{
		if(cat_id === undefined )
			return selected_product;
		else
			return selected_product[cat_id];
	};

	// SDs global storage of currentscrren
	this.setCurrentState = function (screen)
	{
		this.currentscreen = screen;
	}

	this.getCurrentState = function ()
	{
		return this.currentscreen;
	}
};