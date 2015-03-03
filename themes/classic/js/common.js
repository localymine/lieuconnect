var ajaxloader = '<div id="ajaxloader"><i class="ajaxloader fa fa-spinner fa-spin text-info"></i></div>';

var loader = {
    start: function() {
        var ajaxloader = '<div id="ajaxloader"><i class="ajaxloader fa fa-spinner fa-4x fa-spin text-info"></i></div>';
        $('body').append(ajaxloader);
    },
    stop: function() {
        $('#ajaxloader').remove();
    }
};

function validateEmail(sEmail) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
        return false;
    }
}

/*-------------
 * 
 * @string str
 * ex: validation.isEmailAddress(str)
 * ex: validation.isNotEmpty(str)
 * ex: validation.isNumber(str)
 * ex: validation.isSame(str1, str2)
 */
var validation = {
    isEmailAddress: function(str) {
        var pattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        return pattern.test(str);  // returns a boolean
    },
    isNotEmpty: function(str) {
        var pattern = /\S+/;
        return pattern.test(str);  // returns a boolean
    },
    isNumber: function(str) {
        var pattern = /^\d+$/;
        return pattern.test(str);  // returns a boolean
    },
    isSame: function(str1, str2) {
        return str1 === str2;
    }
};

/*--------------------------
 * scroll up down detect
 */
var scroll = {
    detect: function(div_id) {
        var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel" //FF doesn't recognize mousewheel as of FF3.x
        $(div_id).bind(mousewheelevt, function(e) {

            var evt = window.event || e //equalize event object     
            evt = evt.originalEvent ? evt.originalEvent : evt; //convert to originalEvent if possible               
            var delta = evt.detail ? evt.detail * (-40) : evt.wheelDelta //check for detail first, because it is used by Opera and FF

            if (delta > 0) {
                //scroll up
                return 'up';
            }
            else {
                //scroll down
                return 'down';
            }
        });
    }
};

$(function() {
    // close popover when click outside
    $('[data-toggle="popover"]').popover();

    $('body').on('click', function(e) {
        $('[data-toggle="popover"]').each(function() {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });

    $('body').on('click', function(e) {
        $('[data-toggle="popover"]').each(function() {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
});

/*  
 * serializeObject
 * EX: $('#result').text(JSON.stringify($('form').serializeObject()));  
 */
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] || o[this.name] == '') {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function response_message(message) {
    var err_mes = $.parseJSON(message);
    if (err_mes[0].CODE == 'ERR') {
        bootbox.alert(err_mes[0].MESS, function() {
        }).find("div.modal-dialog").addClass("largeWidth");
    } else {
        $('#myavatar').attr('src', err_mes[0].MESS);
    }
    loader.stop();
}

var cryout_toTop_offset = 1014;
var offset = 500;
var duration = 500;
jQuery(window).scroll(function() {
    if (jQuery(this).scrollTop() > offset) {
        jQuery('#toTop').css({'margin-left': '' + cryout_toTop_offset + 'px', 'opacity': 0.5});
        jQuery('#toTop').css({'margin-right': '' + cryout_toTop_offset + 'px', 'opacity': 0.5});
    } else {
        jQuery('#toTop').css({'margin-left': '' + (cryout_toTop_offset + 150) + 'px', 'opacity': 0});
        jQuery('#toTop').css({'margin-right': '' + (cryout_toTop_offset + 150) + 'px', 'opacity': 0});
    }
});
jQuery('#toTop').click(function(event) {
    event.preventDefault();
    jQuery('html, body').animate({scrollTop: 0}, duration);
    return false;
});

function scrollToAnchor(aid){
    var aTag = $("h2[id='"+ aid +"']");
    $('html,body').animate({scrollTop: aTag.offset().top}, duration);
}