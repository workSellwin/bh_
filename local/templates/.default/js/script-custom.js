$(document).ready(function () {
    // полёт в корзину по клику
    $('.prod__btn-wrp  .js-btn-basket').on('click', function () {
        var that = $('.prod__slider-wrp img:eq(0)');
        if ($(".cart-wrp").is(':visible')) {
            var bascket = $(".cart-wrp");
        } else {
            var bascket = $(".cart_white");
        }

        flyingToCart(that, bascket);
    });

    $('.product-item-info-container  .js-btn-basket').on('click', function () {
        var that = $(this).parents('.prod-item').find('img:eq(0)');
        if ($(".cart_white").is(':visible')) {
            var bascket = $(".cart_white");
        } else {
            var bascket = $(".cart-wrp");
        }

        flyingToCart(that, bascket);

    });

    $('.related__item  .js-btn-basket').on('click', function () {
        var that = $(this).parents('.related__item').find('img:eq(0)');
        if ($(".cart_white").is(':visible')) {
            var bascket = $(".cart_white");
        } else {
            var bascket = $(".cart-wrp");
        }

        flyingToCart(that, bascket);

    });

    $('body').on('click', '.login', function (e) {
        AuthLoginCookie();
    });


    $('#price-monitor  .var-1-option').on('click', function () {
        var id = $(this).data('id');
        var data = {
            ID: id,
            ACTION: 'ADD'
        };
        $.get("/ajax/price-monitor.php", data);
        $('#price-monitor  .var-1-option').removeClass('hide').addClass('hide');
        $('#price-monitor  .var-2-option').removeClass('hide');

        ga('send', 'event', 'submit', 'follow_price');
    });

    $('#price-monitor  .var-2-option').on('click', function () {
        var id = $(this).data('id');
        var data = {
            ID: id,
            ACTION: 'DELETE'
        };
        $.get("/ajax/price-monitor.php", data);
        $('#price-monitor  .var-1-option').removeClass('hide');
        $('#price-monitor  .var-2-option').removeClass('hide').addClass('hide');
    });


    $("#soa-property-3").mask("+375(99)999-99-99");

   /* $("body").on('change','#soa-property-3', function () {
        var phone = $(this).val();
        var re = /\(25\)|\(29\)|\(33\)|\(44\)/i;
        if(phone.match(re)){
            $('body #phone-prop').remove();
        }else{
            var text_error ='<div id="phone-prop"  class="bx-soa-tooltip bx-soa-tooltip-static bx-soa-tooltip-danger tooltip top" data-state="opened" style="opacity: 1; display: block;"><div class="tooltip-arrow"></div><div class="tooltip-inner">Поле "Телефон" проверьте номер телефона</div></div>';
            $(this).parent().prepend(text_error);
            $(this).val('');
        }

    });*/
});


function AuthLoginCookie() {
    BX.setCookie('BITRIX_SM_NCC', 'Y', {expires: 86400});
}

// полёт в корзину
function flyingToCart(that, bascket) {
    var w = that.width();
    that.clone()
        .css({
            'width': w,
            'position': 'absolute',
            'z-index': '9999',
            top: that.offset().top,
            left: that.offset().left
        })
        .appendTo("body")
        .animate({
            opacity: 0.05,
            left: bascket.offset()['left'],
            top: bascket.offset()['top'],
            width: 20
        }, 1000, function () {
            $(this).remove();
        });
}


function showBtnInBasket() {
    if (typeof arElementsBasket !== "undefined") {
        var array = arElementsBasket;
        $(".product-item-button-container .js-btn-basket").removeClass('hide');
        $(".btn_in_basket").addClass('hide');
        for (var key in array) {
            $(".product_id_" + array[key] + " .js-btn-basket").addClass('hide');
            if ($(".product_id_" + array[key] + " .btn_in_basket").hasClass('hide')) {
                $(".product_id_" + array[key] + " .btn_in_basket").removeClass('hide')
            }
        }
    }
}

$(function () {
    showBtnInBasket();

    $(document).on('submit', '#add_product_by_csv', function () {
        var forma = $(this),
            data = new FormData(forma.get(0));
        $('#add_product_by_csv button').attr('disabled', 'disabled');
        $.ajax({
            url: "/ajax/add_basket_barcode_csv_new.php?step=1",
            data: data,
            processData: false,
            contentType: false,
            dataType: "html",
            timeout: 20000,
            type: "POST",
            success: function (result) {
                var data = JSON.parse(result);
                if (data.error != '') {
                    $('#csv-error').html(data.error);
                    $('#add_product_by_csv button').removeAttr('disabled');
                } else {
                    $('#csv-message').html(data.message);

                    $.get(
                        '/ajax/add_basket_barcode_csv_new.php',
                        {
                            step: '2',
                            sessid: $('#sessid_1').val(),
                            fileName: data.filename
                        },
                        function (result) {
                            var data = JSON.parse(result);
                            if (data.error != '') {
                                $('#csv-error').html(data.error);
                                $('#add_product_by_csv button').removeAttr('disabled');
                                $('#csv-message').html('');
                            } else {
                                $('#csv-message').html(data.message);
                                $('#csv-status').html(data.status);

                                GetProgressJson(
                                    {
                                        step: 3,
                                        sessid: $('#sessid_1').val(),
                                        fileName: data.filename,
                                        all: data.all,
                                        start: data.start,
                                        message: data.message,
                                    }
                                );

                            }
                        }
                    );

                }
                /*forma.find('.err_mess,.complite').remove();
                forma.find('.btn').before(result);
                if (!forma.find('.err_mess').length) {
                    forma.find('input[type="text"],input[type="tel"],input[type="email"],textarea').val('');
                }*/
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
                console.log(errorThrown);
            }
        });

        //  setInterval(GetProgressCsv, 5000);

        function GetProgressJson(data) {
            if (data.start == data.all) {
                $('#add_product_by_csv_progress').val(data.all).attr('max', data.all);
                $('#csv-message').html(data.message);
                $('#add_product_by_csv button').removeAttr('disabled');
            } else {

                $('#add_product_by_csv_progress').val(data.start).attr('max', data.all);
                $('#csv-message').html("Загруженно " + data.start + " из " + data.all);

                $.get(
                    '/ajax/add_basket_barcode_csv_new.php',
                    {
                        step: '3',
                        sessid: $('#sessid_1').val(),
                        fileName: data.fileName,
                        start: data.start,
                        all: data.all
                    },
                    function (result) {
                        var dt = JSON.parse(result);
                        if (dt.error) {
                            $('#csv-error').html(dt.error);
                            $('#add_product_by_csv button').removeAttr('disabled');
                            $('#csv-message').html('');
                            $('#csv-status').html('');
                        } else {
                            $('#csv-status').prepend(dt.status);
                            $('#csv-message').prepend(dt.message);
                            setTimeout(GetProgressJson(
                                {
                                    step: 3,
                                    sessid: $('#sessid_1').val(),
                                    fileName: dt.fileName,
                                    all: dt.all,
                                    start: dt.start,
                                    message: dt.message,
                                }
                            ), 2000);
                        }

                    });
            }
        }

        return false;
    });


    /*var $calendar = $('.calendar-block').slick({
        arrows: false,
        dots: false,
        fade: false,
        infinite: false,
        slidesToShow: 2,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    fade: true,
                }
            }
        ]
    });
    $('.calendar-block .slider-button').click(function(){
        if($(this).hasClass('button-prev')){
            $calendar.slick('slickPrev');
        }
        else{
            $calendar.slick('slickNext');
        }
    });*/

});


