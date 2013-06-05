/**
 * 
 */

var Model = function ()
{
	var MV_obj;
	var MC_obj;

	var i = 0;
	var model;
	var brands;
	var address;
	var brandProducts;
	var currentscreen;
	var customer_email;
    var activeCategory;
    var multi_rooms= new Array();
     multi_rooms['room1'] ="";
     multi_rooms['room2'] ="";
     multi_rooms['room3'] ="";
     multi_rooms['room4'] ="";
    
	var selected_product = new Array();
	var category_wise_selected = new Array();
	var confirm_selected_product = new Array();
	var temp_store_your_selection = new Array();

	this.init = function()
	{
		try
		{
			model	= this;
			MV_obj	= new View();
	
			model.customer_email = "";
			model.address		= "ERROR";
			model.currentscreen = "";
			model.brands		= new Array();
			model.brandProducts = new Array();
			model.activeCategory = "nbn";
		}
		catch(error)
		{
			console.log('Error :'+error);
		}
	};

	this.setValidatedAddress = function (value)
	{
		try
		{
			if(document.getElementById('main_category_div_address'))
			{
				document.getElementById('main_category_div_address').innerHTML = "";
				document.getElementById('main_category_div_address').innerHTML = value;
			}
			this.address = value;
		}
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	this.getValidatedAddress = function ()
	{
		return this.address;
	};

	this.getClientIP = function ()
	{
		try
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
	
			for (i = 0; hostipInfo.length >= i; i++)
			{
				ipAddress = hostipInfo[i].split(":");
				if ( ipAddress[0] == "IP" )
				{
					return ipAddress[1];
				}
			}
			return false;
		}
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	this.getBrandProductsAPI = function()
	{
		try
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
		}
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	this.setBrandProducts = function (value)
	{
		try
		{
			model.brands.push(value);
		}
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	this.getBrandProducts = function()
	{
		return model.brands;
	};

	this.setAddToMyBundleResult = function(category)
	{
		try
		{
			if(this.indexOfArray(category, selected_product) < 0)
			{
				selected_product.push(category);
			}
		}
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	this.deleteMyBundleResult = function(category)
	{
		try
		{
			var deleteindex = this.indexOfArray(category, selected_product);
			if(deleteindex >= 0)
			{
				selected_product.splice(deleteindex, 1);
			}
		}
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	//SD updated to delete  all products of perticular catgory
	this.deleteAddedMyBundleResult=function(category)
	{
		try
		{
			var arr_length = selected_product.length;
			for(var i = 0; i < arr_length; i++)
			{
				if(selected_product[i][0] == category)
				{
					selected_product.splice(i, 1); 
					this.deleteAddedMyBundleResult(category);
					break;
				}
			}
		}
		
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	this.getSelectedProducts = function (cat_id)
	{	
		return selected_product;
	};

	// SDs global storage of currentscrren
	this.setCurrentState = function (screen)
	{
		try
		{
			this.currentscreen = screen;
		}
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	this.getCurrentState = function ()
	{
		return this.currentscreen;
	};

	//SDs global storage of customers email
	this.setEmail	= function(email)
	{
		try
		{
			this.customer_email = email;
		}
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	this.getEmail = function ()
	{
		return this.customer_email;
	};

	this.deleteRecommendedselection = function()
	{
		selected_product = [];
	};

	this.setActiveCategory = function(cat)
	{
		try
		{
			this.activeCategory = cat;
		}
		
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	this.getActiveCategory = function ()
	{
		return this.activeCategory;
	};

	this.indexOfArray = function (val, array)
	{
		try
		{
			var hash = {}, indexes = {}, i, j;
			var array_length = array.length;

			for(i = 0; i < array_length; i++)
			{
				hash[array[i]] = i;
			}
			return (hash.hasOwnProperty(val)) ? hash[val] : -1;
		}
		catch(error)
		{
			console.log('Error : '+error);
		}
	};

	this.addToConfirmSelected = function (value)
	{
		confirm_selected_product.push(value);
	};
	
	this.deleteConfirmSelected = function (value)
	{
      try
      {
			var arr_length = confirm_selected_product.length;
			for(var i = 0; i < arr_length; i++)
			{
				if(this.indexOfArray(value, confirm_selected_product)>= 0)
				{
					confirm_selected_product.splice(i, 1); 
					this.deleteConfirmSelected(value);
					break;
				}
			}
      }
      catch(error)
		{
			console.log('Error :'+error);
		}
	};
	this.addToTempStore = function (value)
	{
		try
		{
			if(this.indexOfArray(value, temp_store_your_selection) < 0)
			{
				temp_store_your_selection.push(value);
			}
		}
		catch(error)
		{
			console.log('Error : '+error);
		}
		
	};

	this.getConfirmSelected = function ()
	{
		return confirm_selected_product;
	};
	this.getTempStoredSelection = function()
	{
		return temp_store_your_selection;
	}
	this.deleteAllProductSelection = function()
	{
		selected_product = [];
		return;
		
	}
	this.deleteAllConfirmSelection = function()
	{
		confirm_selected_product = [];
		return;
		
	}
	this.setRoomValue =	function(id,value)
	{
		   if(id)
			{
			   multi_rooms[id] = value;
			}
	}
	this.getRoomValue = function()
	{
		return multi_rooms;
	}
	
};