<style>
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
    if (customCert) {
        customCert.addEventListener('click', function (event) {

            $.fancybox.open({
                src  : '#cert-<?=CATALOG_CUSTOM_CERT_ENUM?>',
                type : 'inline',
                opts : {
                    afterShow : function( instance, current ) {
                        let width = document.querySelector('#cert-<?= CATALOG_CUSTOM_CERT_ENUM ?> img').width;
                        document.querySelector('#cert-<?= CATALOG_CUSTOM_CERT_ENUM ?> form').style.width = width + 'px';
                    }
                }
            });
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

        document.querySelector('.btn_ico.btn_ok').addEventListener('click', function(event) {

            let newPrice = document.getElementById('cert_summ').value;
            newPrice = newPrice && +newPrice > 30 ? newPrice : 30;
            let priceBlock = document.querySelector('[data-onevalue="<?= CATALOG_CUSTOM_CERT_ENUM ?>"]')
                .closest('.child-center').querySelector('.prod-price__new');
            priceBlock.textContent = priceBlock.textContent.replace(/\d+/, newPrice);

            let objPrice = window.withoutFaceValue.currentPrices[window.withoutFaceValue.currentPriceSelected];
            //console.log('BEFORE', objPrice);
            for(let key in objPrice){

                objPrice.BASE_PRICE = newPrice;
                objPrice.PRICE = newPrice;
                objPrice.RATIO_BASE_PRICE = newPrice;
                objPrice.RATIO_PRICE = newPrice;
                objPrice.UNROUND_BASE_PRICE = newPrice;
                objPrice.UNROUND_PRICE = newPrice;
                objPrice.PRINT_BASE_PRICE = objPrice.PRINT_BASE_PRICE.replace(/\d+/, newPrice);
                objPrice.PRINT_PRICE = objPrice.PRINT_PRICE.replace(/\d+/, newPrice);
                objPrice.PRINT_RATIO_BASE_PRICE = objPrice.PRINT_RATIO_BASE_PRICE.replace(/\d+/, newPrice);
                objPrice.PRINT_RATIO_PRICE = objPrice.PRINT_RATIO_PRICE.replace(/\d+/, newPrice);
            }
            //console.log(objPrice);
        });
    }
</script>