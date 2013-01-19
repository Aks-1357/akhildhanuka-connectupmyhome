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
	var selected_product = new Array();
	var category_wise_selected = new Array();

	this.init = function()
	{
		model	= this;

		// MC_obj	= new Controller();
		MV_obj	= new View();

		model.address	= "ERROR";
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
		category_wise_selected[category]++;
		selected_product.push(product_id);
		console.log(this.selected_product);
	};

	this.deleteAddedMyBundleResult=function(category, product_id)
	{
		if(category_wise_selected[category] === undefined)
		{
			category_wise_selected[category]=0;
		}
		category_wise_selected[category]--;
		selected_product.splice(model.selected_product.indexOf(product_id),1);
		// model.selected_product.remByVal(product_id);
	};

	this.getSelectedProducts = function ()
	{
		return this.selected_product;
	};
};