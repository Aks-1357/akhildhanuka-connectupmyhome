
<script>

jQuery(document).ready(function () {
	var target = jQuery('#contents_target');
	if (target.length == 0)
		return;
		
	var body = jQuery('.fss_main');
	
	var cont = jQuery('<div>');
	cont.addClass('fss_kb_contents');
	cont.attr('id','fss_kb_contents');
	
	target.html("");
	target.append(cont);
	
	AddChildren(body);
});

function AddChildren(node)
{
	jQuery(node).children().each(function () {
		if (this.tagName == "H2" || this.tagName == "H3" || this.tagName == "H4")
		{
			var ce = jQuery('<div>');
			var title = jQuery(this).html();
			var cls = "fss_kb_content_" + this.tagName;
			ce.addClass(cls);
			var ident = MakeIdent(title);
			ce.html('<a href="#' + ident + '">' + title + '</a>');
			jQuery('#fss_kb_contents').append(ce);
			
			jQuery(this).prepend("<a name='" + ident + "' />");

		}
		AddChildren(this);
	});
}

function MakeIdent(text)
{
	text = text.replace(/[^a-zA-Z 0-9]+/g,'');
	text = text.replace(/ /g,'_');
	text = text.toLowerCase();
	return text;
}

</script>