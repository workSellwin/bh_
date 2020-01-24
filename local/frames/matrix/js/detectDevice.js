/* jshint -W079 */
'use strict';
var $ = jQuery,
    $body = $('body'),
    ios7platform = navigator.userAgent.match(/(iPad|iPhone|iPod touch);.*CPU.*OS 7_\d/i),
    resizeTime,
    mobile = {
        init: function() {
            this.updateMobileClass();
            this.checkPlatform();
            this.initResize();
        },

        updateMobileClass: function() {
            window.isPhone  = ($('#phoneDeviceContainer').css('display') === 'none');
            window.isTablet  = ($('#tabletDeviceContainer').css('display') === 'none');
            $body.toggleClass('mobile', window.isPhone);
            $body.toggleClass('tablet', window.isTablet);

        },

        checkPlatform: function() {
            if (ios7platform != null) {
                $(document)
                    .on('focus', 'input, textarea', function() {
                        $body.addClass('fixfixed');
                    })
                    .on('blur', 'input, textarea', function() {
                        $body.removeClass('fixfixed');
                    });
            }
        },

        initResize: function() {
            $(window).resize(function() {
                clearTimeout(resizeTime);
                resizeTime = setTimeout(function() {
                    mobile.updateMobileClass();
                }, 500);
            });
        }

    };

mobile.init();
