<?
global $USER;
if (!$USER->IsAuthorized()){?>

    <style>
        #msg_pop {
            display: none;
            position: fixed;
            bottom: 40%;
            left: 50%;
            z-index: 90;
            transform: translate(-50%, 25%);
            width: 600px;
            padding: 10px;
            color: #fff;
            font-size: 13px;
            line-height: 13px;
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
            z-index: 100;
        }

        #msg_close:hover {
            color: red;
        }


        #msg_button {
            position: absolute;
            bottom: 10px;
            left: 7%;
            width: 200px;
            top: 303px;
            height: 52px;
            font-weight: bold;
            font-size: 20px;
            background-image: url(/local/templates/main/img_discount/btn-popup-2.png);
            background-size: cover;

        }

        #msg_button:hover {
            background-image: url(/local/templates/main/img_discount/btn-popup-3.png);
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

        @media only screen and (max-width: 650px) {
            #msg_pop  {
                width: 370px;
            }
            #msg_button {
                width: 135px;
                top: 184px;
                height: 35px;
            }
        }

    </style>
    <div id="msg_pop">
        <span id="msg_close" onclick="document.getElementById('msg_pop').style.display='none'; return false;">⛌</span>
        <a style="position: relative">
            <img src="/local/templates/main/img_discount/popup-1.png" alt="25%">
        </a>
        <a href="/personal/?register=yes" id="msg_button"></a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function () {

            function ShowMessageDiscount() {
                $('#msg_pop').show();
                $('#msg_pop').addClass('.fadeIn');

                Cookies.set('ShowMessageDiscount', 'Y');
            }
            if(Cookies.get('ShowMessageDiscount')===undefined){
                setTimeout(ShowMessageDiscount, 1 * 1000);
            }
        });
    </script>
<?}; ?>