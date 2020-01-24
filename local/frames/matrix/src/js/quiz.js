'use strict';

require('../../validation/js');
require('jquery-ui');
require('jquery-ui-touch-punch');

var fancybox = require('../../fancybox/js/custom-fancybox.js'),
    results = require('./results.js'),
    ajax = require('../../lib/js/ajax.js');

var quiz = {
    init: function() {
        $('#promo-loreal').addClass('_no-subscription-popup');
        this.initSelectingByImageClick();
        this.initMovement();
        this.fixMakeup();
        this.initRepeatBtn();
        this.initSlider();
        this.initAnimationLayers();
        this.initAnimation();
        this.onInit();
        this.initPopinLink();
    },

    onInit: function() {
        $('.js-diagnostics-answer-radio').prop('checked', false);
        $('.js-diagnostics-answer-radio[checked]').prop('checked', true);
        $('#diagnosticsResultMailingForm').get(0).reset();
    },

    initSelectingByImageClick: function() {
        $('.js-diagnostics-answer-img').on('click', function(ev) {
            $(ev.currentTarget).next().trigger('click');
        }.bind(this));
    },

    _updateAnimation: function() {
        var $currentSlide = $('.js-diagnostics-slide:visible'),
            key = $currentSlide.data('animation'),
            maxTense = $currentSlide.find('._three-items').length ? 3 : 4,
            tense = $currentSlide.find('.js-diagnostics-answer-radio + label:hover').prev().val() ||
                $currentSlide.find('.js-diagnostics-answer-radio:checked').val();

        if (Array.isArray(key)) {
            var arrOfAnimations = key;
            for (var name in key) {
                key = arrOfAnimations[tense - 1];
            }
            tense = '3';
        }

        $('._layer').css('visibility', 'hidden');
        $('._' + key + '-layer._tense-' + tense + '-' + maxTense).css('visibility', 'visible');
    },

    _isSupportedAnimationPausing: function() {
        // i-devices don't support this
        return !/iPad|iPhone|iPod/.test(navigator.userAgent);
    },

    initMovement: function() {
        var _this = this;

        $('.js-diagnostics-next').on('click', function(event) {
            event.preventDefault();

            if ($(this).hasClass('_inactive')) {
                return;
            }

            var $slide = jQuery('.js-diagnostics-slide:visible'),
                $nextSlide = $slide.next();

            $slide.fadeOut(400, function() {
                $slide.hide();
                $nextSlide.fadeIn(400);

                if ($nextSlide.hasClass('diagnostics-result')) {
                    _this._onResultPage($nextSlide);
                }

                if ($nextSlide.find('.js-diagnostics-slider:visible').length) {
                    $nextSlide.find('.js-diagnostics-answer-radio[value=2]').prop('checked', true).trigger('change');
                }

                _this._updateAnimation();
                _this._scrollToTop();
            });
        });

        $('.js-diagnostics-back').on('click', function(event) {
            event.preventDefault();

            var $slide = jQuery('.js-diagnostics-slide:visible');
            $slide.fadeOut(400, function() {
                $slide.hide().prev().fadeIn(400);
                _this._updateAnimation();
                _this._scrollToTop();
            });
        });

        $('.js-diagnostics-answer-radio').on('change', function () {
            $(this).closest('.js-diagnostics-answer').addClass('_selected').siblings().removeClass('_selected');

            $('.js-diagnostics-next:visible').removeClass('_inactive');

            $('.js-diagnostics-slide:visible');
            _this._updateAnimation();
        });
    },


    _scrollToTop: function() {
        var promoLorealTop = $('#promo-loreal').offset().top;
        $('html').scrollTop(promoLorealTop)
    },

    _onResultPage: function($slide) {
        var _this = this,
            skus = results.init(),
            base = results.getBase();

        $('.js-clamping').dotdotdot({watch: 'window'});
        $('#promo-loreal').addClass('_diagnostics-results');

        $('.js-add-to-cart[data-origin-title]').each(function() {
            var $element = $(this);
            $element.text($element.data('originTitle'));
        });

        $('.js-diagnostics-product').removeClass('_other-color').filter(':visible').each(function(i, elem) {
            if (i % 2 === 0) {
                $(elem).addClass('_other-color');
            }
        });

        $('.js-diagnostics-popin-link').removeClass('_color-1 _color-2').filter(':visible').each(function (i, elem) {
            $(elem).addClass('_color-' + (i % 2 + 1));
        });

        $.ajax({
            url: $slide.data('saveUrl'),
            type: 'post',
            data: {
                base: base,
                result: skus.join(','),
                answers: $('.js-diagnostics-answer-radio').serialize()
                    .replace(/question%5B\d+%5D=/g, '').replace(/&/g, ',')
            },

            success: function(data) {
                _this.hash = data.hash;
            }
        });

        $('#promo-loreal').trigger('diagnostics-result-page');
    },

    fixMakeup: function() {
        var timeoutHandler = null;

        $(window).on('resize', function() {
            clearTimeout(timeoutHandler);
            setTimeout(function () {
                fixPointSize();
                fixImageSize();
                fixPopins();
            }, 100);
        });

        function fixPopins() {
            $('.js-diagnostics-popin').each(function() {
                var $popin = $(this);

                if ($popin.css('opacity') !== '1') {
                    $popin.removeAttr('style');
                    return;
                }

                var left = $popin.offset().left - $popin.css('margin-left').slice(0, -2);
                if (left < 0) {
                    $popin.attr('style', 'margin-left: ' + (5 - left) + 'px');
                } else {
                    $popin.removeAttr('style');
                }
            });
        }

        fixPointSize();
        function fixPointSize() {
            var $labels = $('.js-diagnostics-answer-radio + label').attr('class', ''),
                visibleLabel = $labels.filter(':visible').get(0);

            if (visibleLabel) {
                var width = Math.round(parseFloat(window.getComputedStyle(visibleLabel, ':before').width));
                $labels.addClass('_fix-size-' + width);
            }
        }

        fixImageSize();
        function fixImageSize() {
            $('.diagnostics__img').find('img').css('width', ($(window).width() / 1180 * 100) + '%');
        }

        var $price = $('.diagnostics-result .price');
        $price.find('.rouble').text('i');

        $price.each(function() {
            var $this = $(this);
            $this.html($this.html().replace(/&nbsp;\d/g, function(match) {
                return match.slice(-1);
            }));
        });

        $('.js-close-shipping-info').on('click', function() {
            var $activeLabel = $('.js-diagnostics-answer-radio:visible:checked + label');
            $activeLabel.width($activeLabel.width());

            setTimeout(function() {
                $activeLabel.removeAttr('style');
            });
        });
    },

    initRepeatBtn: function() {
        var _this = this;

        $('.js-diagnostics-result-repeat').on('click', function(event) {
            event.preventDefault();
            $('.js-diagnostics-slide').hide().first().show();
            $('.js-diagnostics-answer-radio').prop('checked', false);
            $('.js-diagnostics-answer-radio[checked]').prop('checked', true);
            $('.js-diagnostics-next').addClass('_inactive');

            $('.js-diagnostics-slider').each(function() {
                var $this = $(this);
                $this.slider('value', $this.data('uiSlider').options.initialValue);
            });

            $('.js-clamping').trigger('destroy');
            $('#promo-loreal').removeClass('_diagnostics-results');
            $('.js-diagnostics-answer').removeClass('_selected');
            $('#diagnosticsResultMailingForm').get(0).reset();
            _this._scrollToTop();
            _this.hash = null;

            if (_this._isSupportedAnimationPausing()) {
                $('.js-diagnostics-border').removeClass('_stopped');
            } else {
                $('.js-diagnostics-border span').removeAttr('style');
            }
        });
    },

    initSlider: function() {
        function round(uiValue, percentRation) {
            uiValue = Math.round(uiValue);

            if (percentRation === 50) {
                if (uiValue === 1) {
                    return 1.1;
                } else if (uiValue === 2) {
                    return 2;
                }

                return 2.93;
            }

            if (uiValue === 1) {
                return 1.2;
            } else if (uiValue === 2) {
                return 2.137;
            } else if (uiValue === 3) {
                return 2.9;
            }

            return 3.7;
        }

        var _this = this;

        $('.js-diagnostics-slider').each(function() {
            var $this = $(this),
                $answer = $this.closest('._as-slider'),
                percentRation = $answer.hasClass('_three-items') ? 50 : 100/3,
                initialValue = round(2, percentRation),
                $trail = $this.find('._trail').css('width', (initialValue - 1) * percentRation + '%');

            function initTransition() {
                $this.find('._trail').addClass('_smooth');
                $this.find('.ui-slider-handle').addClass('_smooth');
            }

            function destroyTransition() {
                $this.find('._trail').removeClass('_smooth');
                $this.find('.ui-slider-handle').removeClass('_smooth');
            }

            $this.slider({
                step: .001,
                min: 1,
                max: $answer.hasClass('_three-items') ? 3 : 4,
                initialValue: initialValue,
                value: initialValue,

                change: function(event, ui) {
                    $trail.css('width', (ui.value - 1) * percentRation + '%');
                    initTransition();
                    setTimeout(function () {
                        destroyTransition();
                    },500);
                },
                slide: function(event, ui) {
                    initTransition();
                    $trail.css('width', (ui.value - 1) * percentRation + '%');
                },
                stop: function(event, ui) {
                    initTransition();
                    $answer.find('.js-diagnostics-answer-radio[value="' + Math.round(ui.value) + '"]')
                        .prop('checked', true)
                        .trigger('change', [percentRation]);
                }
            });

            $answer.find('input[type=radio]').on('change', function() {
                $(this).closest('._as-slider').find('.js-diagnostics-slider').slider('value', round(this.value, percentRation));
            });
        });
    },

    initAnimation: function() {
        var _this = this;

        $('.js-diagnostics-answer-radio + label')
            .on('mouseenter', function() {
                _this._updateAnimation();
            })
            .on('touchstart', function() {
                var $input = $(this).prev();

                if (!$input.prop('checked')) {
                    $input.trigger('click');
                }
            })
        ;
    },

    initAnimationLayers: function() {
        var $diagnosticsAnimation = $('.js-diagnostics-animation');

        function makeLayers(img, w, h, key, level, isArray) {

            var canvas = document.createElement('canvas');
            canvas.width = 2000;
            canvas.height = 2000;
            var ctx = canvas.getContext('2d');

            for (var t = 0; t < level; t++) {
                var i = 0;

                if (isArray && t !== 2) continue;

                for (var y = 0; y < 2000; y += 200) {
                    for (var x = 0; x < 2000; x += 200) {
                        if (i % level === t) {
                            ctx.drawImage(img, x + 100 + Math.round(Math.random() * 120 - 60 - w / 2), y + 100 + Math.round(Math.random() * 120 - 60 - h / 2), w, h);
                        }

                        i++;
                    }
                }

                $('<div>')
                    .addClass('_layer')
                    .addClass('_' + key + '-layer')
                    .addClass('_tense-' + (t + 1) + '-' + level)
                    .css({'background-image': 'url(' + canvas.toDataURL('image/png') + ')'})
                    .appendTo($diagnosticsAnimation)
                ;
            }
        }

        var drop = new Image,
            wave = new Image,
            lightning = new Image,
            star = new Image,
            curlyLight = new Image,
            curlyStrong = new Image,
            rough = new Image,
            zigzag = new Image,
            straight = new Image;

        drop.onload = function() {
            makeLayers(drop, 13, 22, 'rain', 4);
        };

        star.onload = function() {
            makeLayers(star, 26, 26, 'star', 4);
        };

        wave.onload = function() {
            makeLayers(wave, 23, 23, 'wind', 3);
        };

        lightning.onload = function() {
            makeLayers(lightning, 11, 24, 'lightning', 4);
        };

        curlyLight.onload = function() {
            makeLayers(curlyLight, 8, 25, 'curlyLight', 3, true);
        };

        curlyStrong.onload = function() {
            makeLayers(curlyStrong, 12, 33, 'curlyStrong', 3, true);
        };

        rough.onload = function() {
            makeLayers(rough, 5, 33, 'rough', 3, true);
        };

        zigzag.onload = function() {
            makeLayers(zigzag, 13, 33, 'zigzag', 3, true);
        };

        straight.onload = function() {
            makeLayers(straight, 7, 33, 'straight', 3, true);
        };

        drop.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAArCAMAAABVR7jJAAAAS1BMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADmYDp0AAAAGXRSTlMAQK9fIO//gJDfEGBwz7C/0DCg8OBQwG9/XYwfnQAAAQNJREFUeAFtkttyhCAQRMHoUQEvqGvy/1+aWqCcgrFfdvVgN3MxItv9mDf1A4xvYAJmo+UGAK9BAGDRYCbJmkaerLUFG7AD9KZSTO++ZKrBkVw8MLiquJJ7tjcegav8zu1dvXz56AOcrmRxCFifRwvgquhoki4gSJtgl/8sTXSSQ6qPJVryJqn6qG44i9PnAan6+Dg1bUtekziJl3YSr1SdGkHIM13VlO8cMZp2OIMxt16NKxU/6wXIZyVbA+Ib0BnJPeNQg9yjUS1Zn+uwai+70ryrbqKcDAC/ssXL470DbNnN/SGDjick1m0DUDZDiMiL743oqqbmV7L24Ewja721UtA/HggN9AztHOgAAAAASUVORK5CYII=';
        wave.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAtCAMAAAANxBKoAAAAUVBMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABcqRVCAAAAG3RSTlMAP3+fXxBA7/9w36Aw8LCAUNCvwCBgkG+/z+AAq7/JAAAAzElEQVR42s2UyRLDIAxDnVCIswItWdr//9AOSdockQ6dqc5vNEi2EUxVVdfmZhHUmUZ3tQDb6Ud9ibVGL7kCPJxvGCfvg8XgPkgWBMdBENnmiIbpzsCBgW3u+SGgfC4uieARPWVtf2I9M9bS7dZM17D1RAxGUrYemPqiMBlnJuP4DxmXPSOzIjO1Inh97PYptX09ZQ2fI2W9MJfu9CwE/iajRWFiQ9ZG4ZmnTVE4rU+FYGe2TU+NJdjqpSkVe/iyMUhZrwNtEzg/B9/gG+CeDIGiEv6PAAAAAElFTkSuQmCC';
        star.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADcAAAA2CAMAAABUWfHRAAAAAXNSR0IB2cksfwAAAFRQTFRFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFOB9uAAAABx0Uk5TAGDvQBD/vyCwUKDwkN8wcM+A0OCPT59fr29/wMx27LYAAAGrSURBVHicnZaLsoIgEIbFRFQQzVua7/+ehxVk6ZjpsjPNEPDV3vghSU6NpenjfPXUMs55HsExw3FB5wrgGJ0DjBdkrNw4nlG5ynIllUstVxGxh2Gk+aRETsF/SbqjNSAQoyJhessl5LQmcY2tHdCawrW2V6BnngRMuN6EHm0JHOzvEnsmKL0N/jUw6PbBLct8PhpSb0P++20kKL2tc6x3b4bDnQjHyXa0K5vavrTDzypmr5k7k/t/7xP1NH6HxNJyb9K3s8LJdH79D1UPIVSxYF00BS7xfMFgx6nGhV4dT07GCok7XLDvFKe652n8ZdXjvlrbCm1WNBf51qrze21vbDYvVxwmwTQvC11/n/r5mQR9dH041snUNUyCwh8PXDd1msI6iSUP6rAekiCaNUB9MQaclAX73uKmTh7cHfEhVb8VsaxsntyVqQ4hnZsGh905giSqu0qBukk6t9E6gbrUcooued2k6mCou919zOpmhM5H3yt4j/UUzOpmxL1p72k4PMR7Gt4FgMnrrZ/mVID6Dol+98S+s2LfddHvyNh3K5yFlY4lydPJ61f7A47eFU/Q1uUuAAAAAElFTkSuQmCC';
        lightning.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAA0CAMAAACjMti6AAAAAXNSR0IB2cksfwAAAEhQTFRFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAq4SsmwAAABh0Uk5TAGDvMP8g8BDPr4CQoNBAv3Cf4N9QwD+w/Y0M9gAAALpJREFUeJy109EOgyAMheEKRRlO5pyO93/TUdzFTE5HMFtvv0D+NEAEpzOmw2KZ2UBxJlOPZPBZGF54EQlIRpErjBOZBiBRpEdiS5xti7uJzEjulTgkJW5pjHvIoRVd50/EzSIbklXd6Zc4EY5I9J2WOP/c5yCBwex3IGEuNCHZX2FM28e8CeVSJRfuMqi71N9nbZeN77PEjWpcaowrm3NA3Jm43/5s+4ef3RhH6s8mWpSfncelhHaa5wWrhgngO3la0QAAAABJRU5ErkJggg==';
        curlyLight.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAzCAMAAACQcsmQAAAAAXNSR0IB2cksfwAAAEJQTFRFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAjuH28gAAABZ0Uk5TAKDvrxBQ/2CA0HBA4JAg8M/AMLDfn4oI98MAAAC+SURBVHichZLZEsIwCEVpTOne2sX//1UDToCrjvbtTEi4S4nq16RbNqCWmTvHvmDvOBQcjXIhnmB2NprkcKm0pkJb3ZPvHNc0Qk2lRWiHZwYTKAK2I4rl1lae8RkiPFR0o/LuRXi6Os5gVBWG6VWmH84neKU8gz06tj/MGGQneGXk0MI3NX5bMwrTI6ayfDbqqI1avOrLGx1BxqsYs5CiiLd+d7iobk7bmKDfDvpVAZ7r70YJxGpK4UcuMfnoE4sWCGXa1qKpAAAAAElFTkSuQmCC';
        curlyStrong.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAABBCAMAAADi4aRGAAAAAXNSR0IB2cksfwAAAFFQTFRFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAXKkVQgAAABt0Uk5TAE9fEKD/sO+f8N8gf88wr9Bg4HCQgMC/b49Qz6wB1gAAAM1JREFUeJztlMsSgyAMRakhRcQXilb7/x9awQGB2G5cOdMsD7l5kQlj1B4FnFCO+KQUBCJSWm5U5rSoNoo8Yap2EKWK1EWDu8n2EHfojUMQiwOGAHVgvQ6NgA85jHGmnUpz5LE2OTjlU5g3OtPRWOeWUOf9ot4utljyB+hdJWJtTzlis6iEm9Bll3yXMtI/iDKWgH4GyZqkh9EnqbLsPpgg5erBckPaA27dCWYKr+6J+LEnw7U9mf97Eiluvie3vyd2lIrQL/eXsbc+udYfcVARh4Pm8ckAAAAASUVORK5CYII=';
        rough.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAABBCAMAAADfc8UgAAAAAXNSR0IB2cksfwAAADZQTFRFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAt3ZocgAAABJ0Uk5TAFDP3+D/IPC/cMDvoEDQEIBgGZUSmgAAAGJJREFUeJzt0EsKwCAMBNBoG631f//LNjpCtgWhq2b1CDNIJGMPQ5iT2S6yzM8tOtALLzCMdYBvYUTEbUbAJPRTWQNFVLSVh2oUJe3UF515RdKDGtjG87NP9cs16Xp9eGdvHvcCCCeZQ4HSAAAAAElFTkSuQmCC';
        zigzag.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAABACAMAAADYtixuAAAAAXNSR0IB2cksfwAAAE5QTFRFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAxKKmWQAAABp0Uk5TAFD/72/PvxDgMPCPIMDfcH/QkJ9gQK+wgKC/cyTeAAAAv0lEQVR4nO2TSRKDMAwEbbEYCJtZw/8/mljikpohlxyDj+6SeiyXnJcsd/QUIlIGRqo3kcwTUjcJyYOUtZ2iviZlgyIZWceoqGwRhUkug3gLMpMg4acgBQniziALQasF2ViQ/UsQLevZ+LdEGtKu1HYTJu8VYLpF7yv0zwo6sIfiQrFmGgoVuRZEUART7KBoTfGETt4U+NmjKeBfgg5KDlDUphiuXrECcDrByH4j3rvwee5d+OtdOMh92gUyj3RePYMMH2E0rBQAAAAASUVORK5CYII=';
        straight.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAABACAMAAAD28w38AAAAAXNSR0IB2cksfwAAAEhQTFRFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAq4SsmwAAABh0Uk5TAFbY/wZxrGIBSDigiIp1wCyYYBCx7KIFXiUclAAAAEpJREFUeJztx0kSgCAQBEGYdsMNENT//9S52PMFibAuFem8iHdMADFBa1ddP4wTFfQztehXatPvvz6iCERTyjmZ3trUoQpUqed1Px+YCao5mDfNAAAAAElFTkSuQmCC';
    },

    initPopinLink: function() {
        var $allPopinLinks = $('.js-diagnostics-popin-link');

        $('#promo-loreal').on('click', function(event) {
            var $popinLink = $(event.target).closest('.js-diagnostics-popin-link');

            $allPopinLinks.removeClass('_opened');

            if ($popinLink.length) {
                $popinLink.addClass('_opened');
                var $popin = $popinLink.find('.js-diagnostics-popin');
                if (!$popin.length) {
                    return;
                }

                var left = $popin.offset().left;
                if (left < 0) {
                    $popin.attr('style', 'margin-left: ' + (5 - left) + 'px');
                }
            }
        });

        $('#promo-loreal').on('touchstart', function (event) {
            if (!$(event.target).closest('.js-diagnostics-popin-link').length) {
                $allPopinLinks.removeClass('_opened');
            }
        });
    },
};

module.exports = quiz;
