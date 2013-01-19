/**
 * 
 */

var Module = function ()
{
	var thisObj = this;
	var address;

	this.init = function ()
	{
		thisObj.address = "ERROR";

		if(document.getElementById("geocomplete"))
		{
			this.geoComplete("#geocomplete");
		}
	};

	this.geoComplete = function (id)
	{
		$( id ).geocomplete({
			country: 'au'
		})
			.bind("geocode:result", function(event, result)
			{
				// Aks : No need
				// $.log("Result: " + result.formatted_address);
				thisObj.address = result.formatted_address;
			})

			.bind("geocode:error", function(event, status)
			{
				// Aks : No need
				// $.log("ERROR: " + status);
				thisObj.address = "ERROR";
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
		*/
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

	this.showBundle = function(bundle_type)
	{
		if(document.getElementById("geocomplete"))
		{
			$( "#geocomplete" ).trigger("geocode");
		}

		if(thisObj.address != "ERROR")
		{
			if (this.checkEmail(document.getElementById("email").value))
			{
				if(document.getElementById('bundle_type'))
				{
					document.getElementById('bundle_type').value = bundle_type;
				}
				document.getElementById('bundle').submit();
			}
			else
			{
				if(document.getElementById("email_error_div"))
				{
					document.getElementById("email_error_div").innerHTML = "Not a Valid Email Id !!!";
				}
			}
		}
		else
		{
			if(document.getElementById("address_error_div"))
			{
				document.getElementById("address_error_div").innerHTML = "Not a Valid Postcode !!!";
			}
		}
	};
};