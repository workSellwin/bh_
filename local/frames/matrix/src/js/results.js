'use strict';

module.exports = {
    init: function() {
        this._products   = this._getProducts();
        this._results    = this._getResults();
        this._skus       = this._getBaseSkus();
        this.$allTexts   = $('.js-diagnostics-text');
        this.$readMore   = $('.js-read-more');
        this._baseTexts  = [this._getBase()];
        this._extraTexts = [];

        this._processCurl();
        this._processLength();
        this._prepareNeed();
        this._prepareHeating();

        this._showHeader();
        this._hideText();
        this._showText(this._baseTexts, true);
        this._initReadMore();
        this._showProducts();

        return this._skus;
    },

    getBase: function() {
        return this._getBase();
    },

    _getProducts: function() {
        return $('.js-diagnostics-product').hide();
    },

    _showHeader: function() {
        $('.js-diagnostics-header').hide().filter('[data-id="' + this._getBase() + '"]').show();
        $('.js-diagnostics-result-header').addClass('_box-shadow');
    },

    _initReadMore: function() {
        this.$readMore.on('click', function() {
            this._showText(this._extraTexts);
            this.$readMore.hide();
        }.bind(this));
    },

    _hideText: function() {
        this.$allTexts.hide();
    },

    _showText: function(textsArray, isAppendReadMore) {
        var counter = 1;

        textsArray.forEach(function (id) {
            var delay = counter * 100;
            var $filteredTexts = this.$allTexts.filter('[data-id="' + id + '"]');

            if (isAppendReadMore) {
                $filteredTexts.children().last().append(this.$readMore);
            }

            $filteredTexts.show();

            setTimeout(function () {
                $filteredTexts.css('opacity', '1');
            }.bind(this), delay);

            counter += 3;
        }.bind(this));
    },

    _showProducts: function() {
        var products = this._products,
            availableIds = [],
            firstAvailableId = null,
            sum = 0;

        this._skus.forEach(function(sku) {
            var $product = products.filter('[data-sku=' + sku + ']').show();

            if ($product.data('isAvailable')) {
                sum += parseFloat($product.data('price'));

                if (firstAvailableId) {
                    availableIds.push($product.data('id'));
                } else {
                    firstAvailableId = $product.data('id');
                }
            }
        });

        var totalPrice = $('.js-buy-all-price .price').last().get(0);
        totalPrice.innerHTML = totalPrice.innerHTML.replace(/\d+/, Math.round(sum));

        var buyAllLink = $('.js-buy-all');

        if (!this._originalBuyAllLinkHrefAttributeValue) {
            this._originalBuyAllLinkHrefAttributeValue = buyAllLink.attr('href');
        }

        buyAllLink.attr('href', this._originalBuyAllLinkHrefAttributeValue.replace('main_product_id', firstAvailableId)
            .replace('related_product_ids', availableIds.join(',')));
    },

    _getResults: function() {
        var results = $('.js-diagnostics-answer-radio')
            .serialize()
            .replace(/question%5B\d+%5D=/g, '')
            .split('&')
            .map(function(v) {return v * 1;});

        results.unshift(null);
        return results;
    },

    _getHairType: function() {
        var results = this._results,
            types = [null, 0, 0, 0, 0]; // greasy, mix, normal, dry

        types[results[3]]++;

        if (results[5] === 1) types[4]++;
        else if (results[5] === 4) types[1]++;
        else types[results[5]]++;

        if (results[6] === 1) types[3]++;
        else if (results[6] === 2) types[1]++;
        else if (results[6] === 3) types[4]++;

        if (results[7] === 2) types[3]++;
        else if (results[7] === 3) types[2]++;
        else types[results[7]]++;

        var max = Math.max.apply(window, types),
            entries = types
                .map(function(v, i) {return v === max ? i : null;})
                .filter(function(v) {return v !== null;});


        if (entries.length === 1) {
            return entries[0];
        }

        return 2; // mix
    },

    _getBase: function () {
        return this._results[1] + '' + this._getHairType();
    },

    _getBaseSkus: function () {
        var base = {
            '11': ['3474630740259', '3474630740327', '884486227799'],
            '12': ['3474630740853', '3474630740921', '884486320193'],
            '13': ['3474630740853', '3474630740921', '884486227911'],
            '14': ['3474630741133', '3474630741218', '884486225504'],

            '21': ['3474630740259', '3474630740327', '884486227911'],
            '22': ['3474630740259', '3474630740921', '884486225504'],
            '23': ['3474630740853', '3474630740921', '884486227911'],
            '24': ['3474636265558', '3474636265572', '3474630741584'],

            '31': ['3474630740259', '3474630740327', '884486226709'],
            '32': ['3474630740259', '3474630741829', '884486227799'],
            '33': ['3474630741751', '3474630741829', '884486227799'],
            '34': ['3474636265558', '3474636265572', '3474630741584'],

            '41': ['3474630740259', '3474630740327', '884486225504'],
            '42': ['3474630740259', '3474630740785', '884486235633'],
            '43': ['3474630740716', '3474630740785', '884486227911'],
            '44': ['3474636265558', '3474636265572', '3474630741584']
        };

        return base[this._getBase()];
    },

    _processLength: function() {
        var lengthIsShort = this._results[2] === 1,
            hairTypeIsNormal = this._getHairType() === 3;

        if (lengthIsShort && hairTypeIsNormal) {
            this._skus[0] = '3474630741355';
            this._skus[1] = '3474630741362';
        }

        if (lengthIsShort) {
            this._extraTexts.push('short');
        }
    },

    _processCurl: function() {
        var isCurl = this._results[8] === 1,
            base = this._getBase(),
            hairTypeIsNormal = this._getHairType() === 3;

        if (isCurl && (hairTypeIsNormal || base === '24' || base === '44')) {
            this._skus[0] = '3474630740990';
            this._skus[1] = '3474630741065';
        }

        if (isCurl) {
            this._extraTexts.push('curl');
        }
    },

    _prepareNeed: function() {
        var need = this._results[9];

        if (need === 1) {
            this._prepareBlond();
        } else if (need === 2) {
            this._prepareShineNeed();
        } else if (need === 3) {
            this._prepareVolumeNeed();
        } else {
            this._prepareRepairing();
        }
    },

    _prepareBlond: function() {
        this._skus[0] = '3474636484805';
        this._extraTexts.push('blond');
    },

    _prepareShineNeed: function() {
        var shine = {
            '11': '3474636454402',
            '12': '3474636454402',
            '13': '3474636454402',
            '14': '3474636454426',

            '21': '3474636454419',
            '22': '3474636454402',
            '23': '3474636454419',
            '24': '3474636454402',

            '31': '884486227799',
            '32': '3474636454402',
            '33': '3474636454402',
            '34': '3474636454426',

            '41': '3474636454426',
            '42': '3474636454402',
            '43': '3474636454402',
            '44': '3474636454402',
        };

        this._skus.push(shine[this._getBase()]);
        this._extraTexts.push('shine');
    },

    _prepareVolumeNeed: function() {
        var base = this._getBase(),
            hairType = this._getHairType();

        if (base === '11' || base === '12' || base === '21' || base === '22' || base === '41') {
            this._skus[2] = '884486226709';
        } else if (hairType === 3) {
            this._skus[2] = '884486225641';
        } else if (base === '44') {
            this._skus[2] = '3474636265602';
            this._skus.push('884486226709');
        } else if (base === '24') {
            this._skus.push('3474636265596');
        } else if (base !== '31') {
            this._skus.push('884486226709');
        }

        this._extraTexts.push('volume');
        if (base === '13') {
            this._extraTexts.push('13_volume');
        } else if (base === '24') {
            this._extraTexts.push('24_volume');
        } else if (base === '34' || base === '44') {
            this._extraTexts.push('34_44_volume');
        }
    },

    _prepareRepairing: function() {
        var base = this._getBase(),
            coloring = this._results[1];

        if (base === '14') {
            return;
        }

        if (coloring <= 2) {
            this._skus[0] = '3474636597833';
            this._skus[1] = '3474636597857';
        } else {
            this._skus.push('884486354266');
        }

        this._extraTexts.push('repairing');
        if (this._results[1] >= 3) {
            this._extraTexts.push('3x_4x_repairing');
        }
    },

    _prepareHeating: function() {
        if (this._results[4] === 3) {
            this._skus.push('884486203090');
            this._extraTexts.push('heating');
        }
    }
};
