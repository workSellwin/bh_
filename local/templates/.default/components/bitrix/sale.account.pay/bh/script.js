"use strict";

let personalAccountPay = function () {

    var PersonalAccountPay = function () {

        this.coupon = null;
        this.couponSum = 0;
        this.site = 's1';

        let couponBtn = document.getElementById('coupon-ok-btn');
        let applyBtn = document.getElementById('coupon-apply-btn');

        couponBtn.addEventListener('click', this.getCouponSum() );
        applyBtn.addEventListener('click', this.applyCoupon() );
    };

    PersonalAccountPay.prototype.getSum = function () {
        return this.couponSum;
    };

    PersonalAccountPay.prototype.getCouponSum = function (event) {

        let $this = this;

        return function(event) {

            $this.coupon = document.getElementById('coupon').value;
            let xhr = new XMLHttpRequest();
            xhr.open('get', '/ajax/personalAccount.php?coupon=' + $this.coupon
                + '&site=' + $this.site + '&action=get-sum');

            xhr.onload = function (data) {

                if (data) {

                    data = data.currentTarget;

                    if (data.status === 200) {
                        console.log(data);
                        let objResp = JSON.parse(data.responseText);
                        let sum = objResp.VALUES[0];

                        if(sum > 0) {
                            document.querySelector('.sale-acountpay-input').value = sum;
                        }
                    } else {

                    }
                }
            };
            xhr.send();
        };
    };

    PersonalAccountPay.prototype.applyCoupon = function (event) {

        let $this = this;

        return function(event) {

            $this.coupon = document.getElementById('coupon').value;
            let xhr = new XMLHttpRequest();
            xhr.open('get', '/ajax/personalAccount.php?coupon=' + $this.coupon
                + '&site=' + $this.site + '&action=apply-coupon');

            xhr.onload = function (data) {

                if (data) {

                    data = data.currentTarget;

                    if (data.status === 200) {
                        console.log(data);
                        let objResp = JSON.parse(data.responseText);
                        let sum = objResp.VALUES[0];

                        if(sum > 0) {
                            //alert('Зачисленно ' + sum + ' руб.');
                            location.reload(true);
                        }
                    } else {

                    }
                }
            };
            xhr.send();
        };
    };

    PersonalAccountPay.prototype.checkData = function (data) {

        data = data.currentTarget;

        if( data.satus !== 200 ){
            return false;
        }

        let objResp = JSON.parse(data.responseText);

        if(objResp.ERRORS.length){
            return false;
        }
        return true;
    };

    return new PersonalAccountPay();
}();
