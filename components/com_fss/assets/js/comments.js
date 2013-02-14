
var fss_comment_add = false;

function commentFormRedirect() {

    jQuery("#addcommentform").submit(function (event) {
        event.preventDefault();

        if (fss_comment_add)
            return false;

        fss_comment_add = true;
        /*if (jQuery('#comment_name').val() == "" || jQuery('#comment_body').val() == "") {
        alert("<?php echo JText::_('YOU_MUST_ENTER_A_NAME_AND_COMMENT'); ?>");
        return;
        }*/

        var $form = jQuery(this),
        term = $form.find('input[name="s"]').val(),
        url = $form.attr('action');
        jQuery('#addcomment').attr("disabled", true);

        jQuery.post(url, jQuery("#addcommentform").serialize(),
            function (data) {

                var reevent = false;
                var result = JSON.parse(data);

                // update comments display
                jQuery('.fss_comments_result').each(function () {
                    if (result['display'] == "before") {
                        jQuery(this).html(result['comment'] + jQuery(this).html());
                    } else if (result['display'] == "after") {
                        jQuery(this).html(jQuery(this).html() + result['comment']);
                    } else if (result['display'] == "replace") {
                        jQuery(this).html(result['comment']);
                    }
                });

                // update form display
                if (result['form_display'] == "replace") {
                    jQuery('#add_comment').html(result['form']);
                    reevent = true;
                } else if (result['form_display'] == "clear_comment") {
                    jQuery('#comment_body').val("");
                    jQuery('#addcomment').attr("disabled", false);
                }

                if ($('captcha_cont') && typeof Recaptcha != "undefined") {
                    Recaptcha.create("6LcQbAcAAAAAAHuqZjftCSvv67KiptVfDztrZDIL", $('captcha_cont'));
                }

                if (reevent) {
                    commentFormRedirect();
                }

                if (result['valid'] == 0)
                    jQuery('#commentaddbutton').click();

                sortCommentHeights();

                fss_comment_add = false;
            }
        );

    });

    jQuery("#editcommentform").submit(function (event) {
        event.preventDefault();

        var $form = jQuery(this),
        term = $form.find('input[name="s"]').val(),
        url = $form.attr('action');
        var commentid = jQuery('#canceledit').attr('commentid');

        jQuery.post(url, jQuery("#editcommentform").serialize(),
            function (data) { 
                var newcomment = jQuery(data);
                newcomment.insertAfter(jQuery('#fss_comment_' + commentid));
                jQuery('#fss_comment_' + commentid).remove();
                sortCommentHeights();
            }
        );

    });
}

function sortCommentHeights() {
    jQuery('.fss_comment').each(function () {
        var baseheight = jQuery(this).innerHeight();
        var top = parseInt(jQuery(this).css('padding-top').replace('px', ''));
        if (top) {
            baseheight -= top;
        } else {
            top = 0;
        }
        var bottom = parseInt(jQuery(this).css('padding-bottom').replace('px', ''));
        if (bottom) {
            baseheight -= bottom;
        } else {
            bottom = 0;
        }
        var height = jQuery(this).children('.fss_comment_left').outerHeight();
        height = Math.max(height, jQuery(this).children('.fss_kb_mod_this').outerHeight());
        height = Math.max(height, jQuery(this).children('.fss_comment_comment').outerHeight());

        var pad = height - baseheight + top;
        jQuery(this).css('padding-bottom', pad + 'px');
    });    
}

jQuery(document).ready(function () {
    commentFormRedirect();
    sortCommentHeights();
});

function fss_remove_comment(commentid) {
    jQuery('#fss_comment_' + commentid).css('background-color', '#ffeeee');
    jQuery('#fss_comment_' + commentid + '_cross').css('display', 'none');
    jQuery('#fss_comment_' + commentid + '_tick').css('display', 'inline');
    jQuery('#fss_comment_' + commentid + '_delete').css('display', 'inline');
    var url = "<?php echo FSSRoute::x('&task=removecomment&commentid=XXCIDXX',false); ?>";
    url = url.replace("XXCIDXX",commentid);
    jQuery.get(url);
}
function fss_approve_comment(commentid) {
    jQuery('#fss_comment_' + commentid).css('background-color', '#eeffee');
    jQuery('#fss_comment_' + commentid + '_cross').css('display', 'inline');
    jQuery('#fss_comment_' + commentid + '_tick').css('display', 'none');
    jQuery('#fss_comment_' + commentid + '_delete').css('display', 'none');
    var url = "<?php echo FSSRoute::x('&task=approvecomment&commentid=XXCIDXX',false); ?>";
    url = url.replace("XXCIDXX",commentid);
    jQuery.get(url);
}
function fss_delete_comment(commentid) {
    jQuery('#fss_comment_' + commentid).css('background-color', '#dddddd');
    jQuery('#fss_comment_' + commentid).html('<?php echo JText::_("COMMENT_DELETED"); ?>');
    var url = "<?php echo FSSRoute::x('&task=deletecomment&commentid=XXCIDXX',false); ?>";
    url = url.replace("XXCIDXX",commentid);
    jQuery.get(url);
}
function fss_edit_comment(commentid) {
    jQuery('#fss_comment_' + commentid).html('<?php echo JText::_("PLEASE_WAIT"); ?>');
    var url = "<?php echo FSSRoute::x('&task=editcomment&commentid=XXCIDXX',false); ?>";
    url = url.replace("XXCIDXX",commentid);
    jQuery('#canceledit').each(function() {
        var commentid = jQuery(this).attr('commentid');
        cancel_edit(commentid);
    });
    jQuery.get(url, function (data) {
        jQuery('#fss_comment_' + commentid).html(data);
        setup_edit_form();
    });
}
function setup_edit_form()
{
    commentFormRedirect();

    jQuery('#canceledit').click(function(ev) {
        ev.preventDefault();
        var commentid = jQuery(this).attr('commentid');
        cancel_edit(commentid);
    });
}
function cancel_edit(commentid)
{
    var url = "<?php echo FSSRoute::x('&task=showcomment&commentid=XXCIDXX',false); ?>";
    url = url.replace('XXCIDXX', commentid);

    jQuery.get(url, function (data) {
        var newcomment = jQuery(data);
        newcomment.insertAfter(jQuery('#fss_comment_' + commentid));
        jQuery('#fss_comment_' + commentid).remove();
        sortCommentHeights();
        //jQuery('#fss_comment_' + commentid).html(data);
    });
}
var fss_ident = '<?php echo JRequest::getVar("ident",0); ?>';
var fss_published = '<?php echo JRequest::getVar("published",0); ?>';

function fss_moderate_refresh() {
    fss_ident = jQuery('#ident').val();
    fss_published = jQuery('#published').val();
    
    jQuery('#fss_moderate').html("<div class='fss_please_wait'><?php echo JText::_('PLEASE_WAIT'); ?></div>");

    var url = "<?php echo FSSRoute::x('&task=modinner&ident=XXXIDXX&published=XXPXX',false); ?>";
    url = url.replace('XXXIDXX',fss_ident);
    url = url.replace('XXPXX',fss_published);

    jQuery.get(url, function (data) {
        jQuery('#fss_moderate').html(data);
        sortCommentHeights();
    });
}
