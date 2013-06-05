/**
 * 
 */

var View = function ()
{
	var VM_obj;
	var VC_obj;

	var view;

	this.init = function()
	{
		view = this;

		VC_obj	= new Controller();
		VM_obj	= new Model();
	};

	this.getLoadingHTML = function()
	{
		return "<div id='loading'>"+
					"<span class='loading_circle'></span>"+
					"<span class='loading_text'></span>"+
				"</div>";
	};
};