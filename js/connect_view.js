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
};