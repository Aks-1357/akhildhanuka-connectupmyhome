<style type="text/css">
.wraptocenter {
    display: table-cell;
    text-align: center;
    vertical-align: middle;
    width: ...;
    height: ...;
}
.wraptocenter * {
    vertical-align: middle;
}
/*\*//*/
.wraptocenter {
    display: block;
}
.wraptocenter span {
    display: inline-block;
    height: 100%;
    width: 1px;
}
/**/
</style>
<!--[if lt IE 8]><style>
.wraptocenter span {
    display: inline-block;
    height: 100%;
}
</style><![endif]-->
<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$files = glob("images/brandslider/*.*");

	
?>

<div class="image_carousel">
<div style="border: 1px solid #CCCCCC; height: 130px; margin: 10px 0; padding-left: 3px; padding-right: 3px; padding-top: 10px; width: 972px; -webkit-border-radius: 5px;-moz-border-radius: 5px; border-radius: 5px;">
	    <div id="foo2" style="height:140px">
	<?php     
	    for ($i=1; $i<count($files); $i++)
        {
			$num = $files[$i]; ?>
            
			<span class="wraptocenter" style="border:2px solid #ccc; float:left;height:100px;margin-top:10px;width:140px;border: 1px solid #fff;
	background-color: #fff;
	box-shadow: 0 0 0 5px rgba(150, 150, 150, 0.04),
				0 1px 3px rgba(25, 25, 25, 0.3),
				0 10px 15px rgba(200, 200, 200, 0.04) inset;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;margin-left:20px"><span></span>
	        <img src="<?php echo $num; ?>" alt="basketball" style="max-height:70px;max-width:120px;margin-top:0px"/></span>
	  <?php    }
	?>
	
    </div></div>
	    <div class="clearfix"></div>
	    <a class="prev" id="foo2_prev" href="#"><span>prev</span></a>
	    <a class="next" id="foo2_next" href="#"><span>next</span></a>
	    <div class="pagination" id="foo2_pag"></div>
	</div>
	
	


<script type="text/javascript">

$("#foo2").carouFredSel({
		    circular: true,
		    auto    : {
		    	       items           : 4,
		    	       duration        : 7500,
		    	       easing          : "linear",
		    	        timeoutDuration : 1,
		    	        pauseOnHover    : "immediate"
		    	    },
		   	height:130,
			border:'1px solid #000;',
		
 
		});
		
	
</script>
<style type="text/css">
.image_carousel {
		
	}
	.image_carousel img {
	padding:5px 0px;
	background-color: #fff;
	margin:0px 8px;
	}
	a.prev, a.next {
	    width: 45px;
   		 height: 50px;
	    display: block;
	    position: absolute;
	    top: 85px;
	}
	a.prev {            left: -22px;
	                    background-position: 0 0; }
	a.prev:hover {      background-position: 0 -50px; }
	a.prev.disabled {   background-position: 0 -100px !important;  }
	a.next {            right: -22px;
	                    background-position: -50px 0; }
	a.next:hover {      background-position: -50px -50px; }
	a.next.disabled {   background-position: -50px -100px !important;  }
	a.prev.disabled, a.next.disabled {
	    cursor: default;
	}
	 
	a.prev span, a.next span {
	    display: none;
	}
	.pagination {
	    text-align: center;
	}
	.pagination a {
	    height: 15px;
	    margin: 0 5px 0 0;
	    display: inline-block;
	}
	.pagination a.selected {
	    background-position: -25px -300px;
    cursor: default;
	}
	.pagination a span {
	    display: none;
	}
	.clearfix {
	    float: none;
	    clear: both;
	}
	.tile
{
	width: 141px;
	min-height: 77px;
	padding:5px;
	
	background-color: #ededed;
/*	box-shadow: 0 0 0 5px rgba(150, 150, 150, 0.04),
				0 1px 3px rgba(25, 25, 25, 0.3),
				0 10px 15px rgba(200, 200, 200, 0.04) inset;
	border: 1px solid #fff;*/
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}
</style>

