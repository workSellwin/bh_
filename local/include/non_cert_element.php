<style>
    .prod__right .product-item-scu-item-color-container[data-onevalue="<?= CATALOG_CUSTOM_CERT_ENUM ?>"],
    .product__list .product-item-scu-item-color-container[data-onevalue="<?= CATALOG_CUSTOM_CERT_ENUM ?>"]{
        display: inline-block;
    }
    #cert-<?= CATALOG_CUSTOM_CERT_ENUM ?> form{
        max-width: 80%;
        margin: 40px auto 20px auto;
        padding: 0 20px;
    }

    #cert-<?= CATALOG_CUSTOM_CERT_ENUM ?> form label{
        width: 80%;
        display: inline-block;
    }
    #cert-<?= CATALOG_CUSTOM_CERT_ENUM ?> form label span{
        width:60%;
    }
    #cert-<?= CATALOG_CUSTOM_CERT_ENUM ?> form label input{
        width:40%;
        margin: 0 10px;
    }
    #cert-<?=CATALOG_CUSTOM_CERT_ENUM?> img {
        margin: 0 auto;
        display: block;
        max-height: 500px;
    }
    .btn_ok{
        padding:5px;
        cursor:pointer;
    }
</style>
<div class="hide">
    <div id="cert-<?= CATALOG_CUSTOM_CERT_ENUM ?>">
        <img src="<?=$a['SRC']?>" alt="<?=$a['NAME']?>">
        <form action="">
            <label for="">
                <span>Укажите сумму сертификата</span>
                <input type="text" id="cert_summ">
            </label>
            <a class="btn_ico btn_ok" onclick="jQuery.fancybox.close();"> Принять!</a>
        </form>
    </div>
</div>
<script>
    let customCert = document.querySelector('[data-onevalue="<?=CATALOG_CUSTOM_CERT_ENUM?>"]');
    //console.log(customCert);
    if (customCert) {
        customCert.addEventListener('click', function (event) {

            $.fancybox.open({
                src  : '#cert-<?=CATALOG_CUSTOM_CERT_ENUM?>',
                type : 'inline',
                opts : {
                    afterShow : function( instance, current ) {
                        let width = document.querySelector('#cert-<?= CATALOG_CUSTOM_CERT_ENUM ?> img').width;
                        document.querySelector('#cert-<?= CATALOG_CUSTOM_CERT_ENUM ?> form').style.width = width + 'px';
                        // document.getElementById('cert_summ').addEventListener('keyup', function (event) {//validation
                        //     if( !(+event.key) ){
                        //         this.value = this.value.slice(0, -1);
                        //     }
                        // })
                    }
                }
            });
            document.getElementById('cert_summ').addEventListener('keyup', function (event) {
                console.log(event.key);
                if(event.key == 'Backspace'){
                    return false;
                }
                if( !(+event.key) && event.key !== 0 && event.key !== '0' ){
                    this.value = this.value.slice(0, -1);
                }
                if( this.value !== '' ){
                    this.value = +this.value;
                }
            });

            document.getElementById('cert_summ').addEventListener('keydown', function (event) {

                if(event.key == 'Enter'){
                    event.preventDefault();
                    return false;
                }
            });
            // document.getElementById('cert_summ').addEventListener('keyup', function (event) {
            //
            //     if(event.key == 'Backspace'){
            //         return false;
            //     }
            //     if( !(+event.key) && event.key !== 0 && event.key !== '0' ){
            //         this.value = this.value.slice(0, -1);
            //     }
            //     if( this.value !== '' ){
            //         this.value = +this.value;
            //     }
            // });
            //
            // document.getElementById('cert_summ').addEventListener('keydown', function (event) {
            //
            //     if(event.key == 'Enter'){
            //         event.preventDefault();
            //         return false;
            //     }
            // });

            document.querySelector('.btn_ico.btn_ok').addEventListener('click', function(event) {

                let newPrice = document.getElementById('cert_summ').value;
                newPrice = newPrice && +newPrice > 30 ? newPrice : 30;
                let priceBlock = document.querySelector('.prod__price .prod__price-current');
                priceBlock.textContent = priceBlock.textContent.replace(/[\d\s]+/, newPrice + ' ');

                let objPrice = window.withoutFaceValue.currentPrices;

                objPrice.DISCOUNT_VALUE = newPrice;
                objPrice.DISCOUNT_VALUE_NOVAT = newPrice;
                objPrice.DISCOUNT_VALUE_VAT = newPrice;
                objPrice.RATIO_PRICE = newPrice;
                objPrice.ROUND_VALUE_NOVAT = newPrice;
                objPrice.ROUND_VALUE_VAT = newPrice;
                objPrice.UNROUND_DISCOUNT_VALUE = newPrice;
                objPrice.VALUE = newPrice;
                objPrice.VALUE_NOVAT = newPrice;
                objPrice.VALUE_VAT = newPrice;
                objPrice.PRINT_DISCOUNT_VALUE = objPrice.PRINT_DISCOUNT_VALUE.replace(/[\d\s]+/, newPrice + ' ');
                objPrice.PRINT_DISCOUNT_VALUE_NOVAT = objPrice.PRINT_DISCOUNT_VALUE_NOVAT.replace(/[\d\s]+/, newPrice + ' ');
                objPrice.PRINT_DISCOUNT_VALUE_VAT = objPrice.PRINT_DISCOUNT_VALUE_VAT.replace(/[\d\s]+/, newPrice + ' ');
                objPrice.PRINT_VALUE_NOVAT = objPrice.PRINT_VALUE_NOVAT.replace(/[\d\s]+/, newPrice + ' ');
                objPrice.PRINT_VALUE_VAT = objPrice.PRINT_VALUE_VAT.replace(/[\d\s]+/, newPrice + ' ');
            });
        })
    }
</script>