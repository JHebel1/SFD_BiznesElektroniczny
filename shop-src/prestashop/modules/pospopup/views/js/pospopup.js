$(document).ready(function(){
    var $pnp       = $('#posnewsletterpopup');
    var $overlay  = $('#posnewsletterpopup-overlay');
    var pnpHeight = $pnp.outerHeight();
    var pnpWidth  = $pnp.outerWidth();
    var $wrapper  = $( window );
    var offset    = -30;

    var sizeData = {
        size: {
            width: $wrapper.width() + offset,
            height: $wrapper.height() + offset
        }
    };
    init();
    registerSubscription();
    
    function init() {

        setTimeout(function() {
            $pnp.addClass('showed-pnp');
            $overlay.addClass('showed-popup');
        },  pospopup.delay);

        $(document).on('click', '#posnewsletterpopup .pnp-close, #posnewsletterpopup-overlay', function () {
            $overlay.removeClass('showed-popup');
            $pnp.removeClass('showed-pnp');

            if ($("#pnp-checkbox").is(':checked')) {
                setCookie(0);
            }
        });
        doResize(sizeData, false);

        $wrapper.resize(function() {
            sizeData.size.width = $wrapper.width() + offset ;
            sizeData.size.height = $wrapper.height() + offset ;
            doResize(sizeData, true);

        });
    }


    function doResize(ui, resize) {
        if (pnpWidth >= ui.size.width  || pnpHeight >= ui.size.height) {
            var scale;
            scale = Math.min(
                ui.size.width / pnpWidth,
                ui.size.height / pnpHeight
            );
            $pnp.css({
                transform: "translate(-50%, -50%) scale(" + scale + ")"
            });
        }
        else{
            if (resize){
                $pnp.css({
                    transform: "translate(-50%, -50%) scale(1)"
                });
            }
        }
    }

    function setCookie(time) {
        var name = pospopup.name;
        var value = '1';
        var expire = new Date();
        console.log(time);
        if(time){
            expire.setDate(expire.getDate() + time);
        }else{
           expire.setDate(expire.getDate() + pospopup.time); 
        }
        document.cookie = name + "=" + escape(value) + ";path=/;" + ((expire == null) ? "" : ("; expires=" + expire.toGMTString()))
    }

    function registerSubscription(){
        $('.pnp-newsletter-form form').on('submit', function (e) {
            e.preventDefault();
            var url = $(this).data('action'),
                $this = $(this);
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: url,
                cache: false,
                data: $(this).serialize(),
                success: function (data) {
                    $this.find('.alert').remove();
                    if (data.nw_error) {
                        $this.prepend('<p class="alert alert-danger block_newsletter_alert">' + data.msg + '</p>');
                    } else {
                        $this.prepend('<p class="alert alert-success block_newsletter_alert">' + data.msg + '</p>');
                        setCookie(365);
                    }

                },
                error: function (err) {
                    console.log(err);
                }
            });
            return false;
        })
    }

});








