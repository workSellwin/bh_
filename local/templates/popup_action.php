<style>
    #msg_pop {
        display: none;
        position: fixed;
        bottom: 50%;
        left: 50%;
        z-index: 9999;
        transform: translate(-50%, 25%);
        width: 400px;
        padding: 10px;
        color: #fff;
        font-size: 13px;
        line-height: 13px;
        -webkit-box-shadow: 0px 0px 10px #999;
        -moz-box-shadow: 0px 0px 10px #999;
        box-shadow: 0px 0px 10px #999;
        -webkit-border-radius: 15px;
        -moz-border-radius: 15px;
        border-radius: 15px;
    }


    #msg_close {
        font-size: 36px;
        font-weight: bold;
        color: #000;
        display: block;
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }

    #msg_close:hover {
        color: red;
    }

    #msg_button {
        position: absolute;
        bottom: 10px;
        width: 380px;
        height: 35px;
        font-weight: bold;
        color: #fff;
        font-size: 20px;
        background: #000;
    }

    #msg_button:hover {
        color: #fff;
        background: #ff231c;
    }


    .fadeIn3 {
        animation-name: fadeIn;
        -webkit-animation-name: fadeIn;
        animation-duration: 0.4s;
        -webkit-animation-duration: 0.4s;
        animation-timing-function: ease-in-out;
        -webkit-animation-timing-function: ease-in-out;
        visibility: visible !important;
    }

    @keyframes fadeIn {
        0% {
            transform: scale(0.7);
            opacity: 0.5;
        }
        80% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @-webkit-keyframes fadeIn {
        0% {
            -webkit-transform: scale(0.7);
            opacity: 0.5;
        }
        80% {
            -webkit-transform: scale(1.1);
        }
        100% {
            -webkit-transform: scale(1);
            opacity: 1;
        }
    }
</style>
<div id="msg_pop">
    <span id="msg_close" onclick="document.getElementById('msg_pop').style.display='none'; return false;">⛌</span>
    <a href="https://bh.by/aktsii/">
        <img src="/local/templates/main/img_discount/lenta.jpg" alt="25%">
        <button id="msg_button">Перейти</button>
    </a>
</div>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        function ShowMessageDiscount() {
            $('#msg_pop').show();
            $('#msg_pop').addClass('.fadeIn');
            Cookies.set('ShowMessageDiscount3', 'Y');
        }
        if(Cookies.get('ShowMessageDiscount3')===undefined){
            //setTimeout(ShowMessageDiscount, 10 * 1000);
        }
    });
</script>
