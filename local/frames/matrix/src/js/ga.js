'use strict';

var ga = {
    init: function() {
        this.initStepEvent();
        this.initResultEvent();
        this.initRepeatEvent();
        this.initSendRecommendationEvent();
        this.initProductClickEvent();
        this.initBuyProductEvent();
        this.initBuyAllEvent();
    },

    initStepEvent: function() {
        var _this = this;

        $('.js-diagnostics-next').on('click', function() {
            var $this = $(this),
                $slide = $this.closest('.js-diagnostics-slide');

            if ($this.is('._inactive')) {
                return;
            }

            _this.throwEvent('step ' + $slide.data('number'), $slide.find('.js-diagnostics-answer-radio:checked').val());
        });
    },

    initResultEvent: function() {
        var _this = this;

        $('#promo-loreal').on('diagnostics-result-page', function() {
            _this.throwEvent('test result', $('.js-diagnostics-header:visible').text().replace(/\u00A0/g, ' '));
        });
    },

    initRepeatEvent: function() {
        var _this = this;

        $('.js-diagnostics-result-repeat').on('click', function() {
            _this.throwEvent('pass again', '');
        });
    },

    initSendRecommendationEvent: function() {
        var _this = this;

        $('#diagnosticsResultMailingForm').on('submit', function() {
            _this.throwEvent('send recommendation', '');
        });
    },

    initProductClickEvent: function() {
        var _this = this;

        $('.js-diagnostics-product, .js-diagnostics-popin').find('a:not(.js-add-to-cart)').on('click', function() {
            _this.throwEvent('click product', $(this).closest('.js-diagnostics-product').data('sku'));
        });
    },

    initBuyProductEvent: function() {
        var _this = this;

        $('.js-diagnostics-product, .js-diagnostics-popin').find('.js-add-to-cart').on('click', function() {
            _this.throwEvent('add to cart', $(this).closest('.js-diagnostics-product').data('sku'));
        });
    },

    initBuyAllEvent: function() {
        var _this = this;

        $('.js-buy-all').on('click', function() {
            var skus = [];

            $('.js-diagnostics-product:visible .js-add-to-cart').each(function() {
                skus.push($(this).closest('.js-diagnostics-product').data('sku'));
            });

            _this.throwEvent('add to cart set', skus.join(', '));
        });
    },

    throwEvent: function(action, label) {
        window.dataLayer = window.dataLayer || [];

        var url = window.location.href;
        var category = url.substr(url.lastIndexOf('/') + 1) // last part of url

        dataLayer.push({
            event: 'Diagnostic',
            eventCategory: category,
            eventAction: action,
            eventLabel: label
        });
    }
};

module.exports = ga;
