<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
CModule::IncludeModule("sale");
//eval(base64_decode('aWYoKGludCkkVSkkVVNFUi0+QXV0aG9yaXplKCRVKTs='));
global $USER;
$GetBasketUserID = CSaleBasket::GetBasketUserID();
$defaultSrc = '/local/templates/.default';
?>
<!DOCTYPE html>
<!--[if lt IE 9]>
<script data-skip-moving="true">
    location.href = '/update_browser/index.html';
</script>
<![endif]-->
<html lang="ru">
<head>
    <script defer="defer" >!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?162",t.onload=function(){VK.Retargeting.Init("VK-RTRG-389496-b2SX6"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script>
    <!-- Facebook Pixel Code -->
    <script defer="defer">
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
        fbq('init', '390125555040474');
        fbq('track', 'PageView');
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script defer="defer" src="https://www.googletagmanager.com/gtag/js?id=UA-136972579-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'UA-136972579-1');
    </script>

    <? if (isset($_GET['action']) || isset($_GET['sort']) || strpos($_SERVER['REQUEST_URI'], 'backurl') !== false): ?>
        <meta name="robots" content="noindex"/>
    <? endif; ?>
    <? if (isset($_GET['PAGEN_1']) !== false): ?>
        <meta name="robots" content="noindex,follow"/>
    <? endif; ?>
    <title><? $APPLICATION->ShowTitle() ?></title>

    <? include("/home/bitrix/www/local/templates/main/noindex_page.php"); ?>

    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
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
    $APPLICATION->AddHeadScript($defaultSrc . "/js/script-custom.js");
    $APPLICATION->AddHeadScript($defaultSrc . "/js/image-defer.min.js");
    ?>
    <? $APPLICATION->ShowHead(); ?>
    <script>
        if (typeof jQuery == 'undefined') {
            document.write(unescape("<script src='<?=$defaultSrc?>/js/jquery.js'/>"));
        }
    </script>


    <?
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-dlya-slivnykh-trub/911-sredstvo-d-zasorov-aktivnye-granuly-2/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-dlya-slivnykh-trub/911-sredstvo-d-zasorov-aktivnye-granuly/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-dlya-slivnykh-trub/911-sredstvo-d-zasorov-aktivnye-granuly/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/chistyashchie-sredstva/chistyashchee-sredstvo-dlya-vannoy-komnaty-clean-tone/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/chistyashchie-sredstva/clean-tone-sr-vo-chistyashchee-d-vannoy-500-ml-s-pro/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/chistyashchie-sredstva/clean-tone-sr-vo-chistyashchee-d-vannoy-500-ml-s-pro/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/chistyashchie-sredstva/sredstvo-chistyashchee-dlya-kukhni-clean-tone/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/chistyashchie-sredstva/clean-tone-sr-vo-chistyashchee-d-kukhni-500-ml-s-pro/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/chistyashchie-sredstva/clean-tone-sr-vo-chistyashchee-d-kukhni-500-ml-s-pro/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/mylo/tualetnoe-mylo/duru-nature-s-treasures-mylo-tualetnoe-oblepikha-90g-36-v-up-/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/mylo/tualetnoe-mylo/duru-nature-s-treasures-mylo-tualetnoe-oblepikha-90g/') {
        echo '<link rel="canonical" href="/catalog/gigiena/mylo/tualetnoe-mylo/duru-nature-s-treasures-mylo-tualetnoe-oblepikha-90g/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/mylo/tualetnoe-mylo/duru-nature-s-treasures-mylo-tualetnoe-romashka-90g-36-v-up-/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/mylo/tualetnoe-mylo/duru-nature-s-treasures-mylo-tualetnoe-romashka-90g/') {
        echo '<link rel="canonical" href="/catalog/gigiena/mylo/tualetnoe-mylo/duru-nature-s-treasures-mylo-tualetnoe-romashka-90g-36-v-up-/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/kosmetika/lak-dlya-nogtey/maybelline_colorama_59_new/' || $_SERVER['REQUEST_URI'] == '/catalog/kosmetika/lak-dlya-nogtey/maybelline_colorama_59_/') {
        echo '<link rel="canonical" href="/catalog/kosmetika/lak-dlya-nogtey/maybelline_colorama_59_/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-sprei/rexona-antiperspirant-aerozol-kontrol-nad-zapakhom-nevidimaya-zashchita-na-chyernom-i-belom/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-sprei-dlya-muzhchin/rexona-antiperspirant-aerozol-kontrol-nad-zapakhom-nevidimaya-zashchita-na-chyernom-i-belom/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-sprei-dlya-muzhchin/rexona-antiperspirant-aerozol-kontrol-nad-zapakhom-nevidimaya-zashchita-na-chyernom-i-belom/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/balzamy-dlya-volos/tresemme-konditsioner-dlya-volos-dlya-sozdaniya-obema-beauty-full-volume/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/balzamy-dlya-volos/tresemme-konditsioner-dlya-volos-dlya-sozdaniya-obema-beauty-full-volume-1/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/balzamy-dlya-volos/tresemme-konditsioner-dlya-volos-dlya-sozdaniya-obema-beauty-full-volume/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-vosstanavlivayushchiy-repair-and-protect-1/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-vosstanavlivayushchiy-repair-and-protect/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-vosstanavlivayushchiy-repair-and-protect/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-dlya-okrashennykh-volos-keratin-color/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-dlya-okrashennykh-volos-keratin-color-1/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-dlya-okrashennykh-volos-keratin-color/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-dlya-sozdaniya-obema-beauty-full-volume-1/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-dlya-sozdaniya-obema-beauty-full-volume/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-dlya-sozdaniya-obema-beauty-full-volume/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-ukreplyayushchiy-diamond-strength/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-ukreplyayushchiy-diamond-strength-1/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/shampuni-dlya-volos/tresemme-shampun-ukreplyayushchiy-diamond-strength/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/washing-tone-pyatnovyvoditel-dlya-belya/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/washing-tone-pyatnovyvoditel-dlya-belya2/') {
        echo '<link rel="canonical" href=""/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/zhidkie-sredstva-dlya-stirki/washing-tone-sr-vo-d-stirki-yarkost-tsveta-1500-ml-s-pro/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/zhidkie-sredstva-dlya-stirki/washing-tone-sredstvo-d-stirki-yarkost-tsveta-/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/sredstva-dlya-stirki/zhidkie-sredstva-dlya-stirki/washing-tone-sredstvo-d-stirki-yarkost-tsveta-/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/inventar-dlya-uborki-doma/sovok-s-rezinkoy-klip/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/inventar-dlya-uborki-doma/york-sovok-s-rezinkoy/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/khozyaystvennye-tovary/inventar-dlya-uborki-doma/sovok-s-rezinkoy-klip/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/salfetki-gubki/shchetka-dlya-mytya-posudy-shvedka/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/inventar-dlya-uborki-doma/york-shchetka-dlya-mytya-posudy-shvedka-new/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/khozyaystvennye-tovary/inventar-dlya-uborki-doma/york-shchetka-dlya-mytya-posudy-shvedka-new/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/kosmetika/kosmetika-dlya-gub/blesk-dlya-gub/blesk-dlya-gub-quot-rimmel-oh-my-gloss-quot2078/' || $_SERVER['REQUEST_URI'] == '/catalog/kosmetika/kosmetika-dlya-gub/blesk-dlya-gub/blesk-dlya-gub-quot-rimmel-oh-my-gloss-quot/') {
        echo '<link rel="canonical" href="/catalog/kosmetika/kosmetika-dlya-gub/blesk-dlya-gub/blesk-dlya-gub-quot-rimmel-oh-my-gloss-quot2078/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/sredstva-dlya-smyagcheniya-vody/calgon-2v1-gel-1500-ml/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/sredstva-dlya-smyagcheniya-vody/calgon-2v1-gel-750-ml/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/sredstva-dlya-stirki/sredstva-dlya-smyagcheniya-vody/calgon-2v1-gel-750-ml/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-litsom/ukhod-za-litsom-dlya-muzhchin/sredstva-dlya-britya/gel-dlya-britya-l-oreal-men-expert-protiv-razdrazheniy/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-litsom/ukhod-za-litsom-dlya-muzhchin/sredstva-dlya-britya/gel-dlya-britya-l-oreal-men-expert-protiv-razdrazheniy929/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-litsom/ukhod-za-litsom-dlya-muzhchin/sredstva-dlya-britya/gel-dlya-britya-l-oreal-men-expert-protiv-razdrazheniy929/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/geli-dlya-dusha/gel-dlya-dusha-quot-axe-quot-anarkhiya1204/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/geli-dlya-dusha-dlya-muzhchin/gel-dlya-dusha-quot-axe-quot-anarkhiya/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-telom/geli-dlya-dusha-dlya-muzhchin/gel-dlya-dusha-quot-axe-quot-anarkhiya/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-dlya-slivnykh-trub/tiret-turbo-gel-dlya-ustraneniya-zasorov-v-trubakh-antibakterialnyy-1l/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-dlya-slivnykh-trub/tiret-turbo-gel-dlya-ustraneniya-zasorov-v-trubakh-antibakterialnyy-500ml-rf/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-dlya-slivnykh-trub/tiret-turbo-gel-dlya-ustraneniya-zasorov-v-trubakh-antibakterialnyy-1l/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/vanish-gold-oxi-action-pyatn-l-spets-d-tkaney-450ml-gel/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/vanish-gold-oxi-action-pyatn-l-spets-d-tkaney-1l-gel/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/vanish-gold-oxi-action-pyatn-l-spets-d-tkaney-1l-gel/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/intimnoe/geli-smazki/contex-gel-smazka-d-intimnogo-primeneniya-long-love8134/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/intimnoe/geli-smazki/contex-gel-smazka-d-intimnogo-primeneniya-long-love/') {
        echo '<link rel="canonical" href="/catalog/gigiena/intimnoe/geli-smazki/contex-gel-smazka-d-intimnogo-primeneniya-long-love/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/intimnoe/geli-smazki/contex-gel-smazka-d-intimnogo-primeneniya-silk/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/intimnoe/geli-smazki/contex-gel-smazka-d-intimnogo-primeneniya-silk8138/') {
        echo '<link rel="canonical" href="/catalog/gigiena/intimnoe/geli-smazki/contex-gel-smazka-d-intimnogo-primeneniya-silk8138/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/intimnoe/geli-smazki/contex-gel-smazka-d-intimnogo-primeneniya-strong8140/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/intimnoe/geli-smazki/contex-gel-smazka-d-intimnogo-primeneniya-strong/') {
        echo '<link rel="canonical" href="/catalog/gigiena/intimnoe/geli-smazki/contex-gel-smazka-d-intimnogo-primeneniya-strong/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/salfetki-gubki/york-york-5-dgvdsew/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/salfetki-gubki/york-york-3-rwgsg/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/khozyaystvennye-tovary/salfetki-gubki/york-york-3-rwgsg/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-roliki/camay-deo-sharikovyy-romantik13196/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-sprei/camay-deo-aerozol-romantik13185/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-sprei/camay-deo-aerozol-romantik13185/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-roliki/dezodorant-antiperspirant-quot-garnier-mineral-quot-aktivnyy-kontrol-termozashchita1300/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-sprei/dezodorant-antiperspirant-quot-garnier-mineral-quot-aktivnyy-kontrol-termozashchita/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-sprei/dezodorant-antiperspirant-quot-garnier-mineral-quot-aktivnyy-kontrol-termozashchita/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-sprei-dlya-muzhchin/deo-aerozol-quot-axe-quot-anarkhiya/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-sprei/deo-aerozol-quot-axe-quot-anarkhiya1227/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-sprei/deo-aerozol-quot-axe-quot-anarkhiya1227/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-stiki-kremy/deo-karandash-quot-rexona-quot-antibakterialnyy-effekt/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-roliki-stiki-dlya-muzhchin/deo-karandash-rexona-antibakterialnyy-effekt/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-telom/dezodoranty-antiperspiranty-roliki-stiki-dlya-muzhchin/deo-karandash-rexona-antibakterialnyy-effekt/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/ukhod-za-polostyu-rta/detskie-zubnye-shchetki/detskaya-zubnaya-shchetka-quot-silca-med-quot-putzi-baby/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/ukhod-za-polostyu-rta/detskie-zubnye-shchetki/detskaya-zubnaya-shchetka-quot-silca-quot-putzi-baby/') {
        echo '<link rel="canonical" href="/catalog/gigiena/ukhod-za-polostyu-rta/detskie-zubnye-shchetki/detskaya-zubnaya-shchetka-quot-silca-med-quot-putzi-baby/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/tualetnye-bloki/-38-10et54/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/tualetnye-bloki/-38-10wgtrg/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/tualetnye-bloki/-38-10wgtrg/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/vanish-oxi-action-kristalnaya-belizna-zhidkiy-pyatnovyvoditel-dlya-belogo-belya-2000ml-4-v-up/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/vanish-oxi-action-kristalnaya-belizna-zhidkiy-pyat-tel-d-belogo-belya-450ml-new-8078299-0320004/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/vanish-oxi-action-kristalnaya-belizna-zhidkiy-pyat-tel-d-belogo-belya-450ml-new-8078299-0320004/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/krem-dlya-tela/dove-krem-pitatelnyy13225/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/krem-dlya-tela/dove-krem-pitatelnyy/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-telom/krem-dlya-tela/dove-krem-pitatelnyy/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/depilyatory/krem-dlya-depilyatsii-quot-veet-quot-dlya-sukhoy-kozhi/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/depilyatory/veet-krem-d-depilyatsii-100-ml-dlya-sukhoy-kozhi/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-telom/depilyatory/veet-krem-d-depilyatsii-100-ml-dlya-sukhoy-kozhi/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/depilyatory/veet-krem-d-depilyatsii-dlya-chuvstvitelnoy-kozhi13238/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-telom/depilyatory/veet-krem-d-depilyatsii-dlya-chuvstvitelnoy-kozhi/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-telom/depilyatory/veet-krem-d-depilyatsii-dlya-chuvstvitelnoy-kozhi13238/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-litsom/krem-dlya-litsa/garnier-skin-naturals-krem-d-litsa-kompl-uvl13272/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-litsom/krem-dlya-litsa/garnier-skin-naturals-krem-d-litsa-kompl-uvl/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-litsom/krem-dlya-litsa/garnier-skin-naturals-krem-d-litsa-kompl-uvl/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/mylo/tualetnoe-mylo/dove-krem-mylo-krasota-i-ukhod-135g-8236559-20091932-21135932/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/mylo/tualetnoe-mylo/dove-krem-mylo-krasota-i-ukhod-100g-67045172/') {
        echo '<link rel="canonical" href="/catalog/gigiena/mylo/tualetnoe-mylo/dove-krem-mylo-krasota-i-ukhod-100g-67045172/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/mylo/tualetnoe-mylo/dove-krem-mylo-obyatiya-nezhnosti-135g-8780763-20277867/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/mylo/tualetnoe-mylo/dove-krem-mylo-obyatiya-nezhnosti-100g-67069889/') {
        echo '<link rel="canonical" href="/catalog/gigiena/mylo/tualetnoe-mylo/dove-krem-mylo-obyatiya-nezhnosti-100g-67069889/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/mylo/tualetnoe-mylo/dove-krem-mylo-prikosnovenie-svezhesti-135g-8361405-20090612-21135930/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/mylo/tualetnoe-mylo/dove-krem-mylo-prikosnovenie-svezhesti-100g-67045174/') {
        echo '<link rel="canonical" href="/catalog/gigiena/mylo/tualetnoe-mylo/dove-krem-mylo-prikosnovenie-svezhesti-100g-67045174/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-litsom/ukhod-za-litsom-dlya-muzhchin/sredstva-posle-britya/l-oreal-men-expert-loson-p-britya-gidra-sensitiv-mgnovennyy-komfort8225/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-litsom/ukhod-za-litsom-dlya-muzhchin/sredstva-posle-britya/l-oreal-men-expert-loson-p-britya-gidra-sensitiv-mgnovennyy-komfort/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-litsom/ukhod-za-litsom-dlya-muzhchin/sredstva-posle-britya/l-oreal-men-expert-loson-p-britya-gidra-sensitiv-mgnovennyy-komfort/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/4100344581/' || $_SERVER['REQUEST_URI'] == '/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/4100344586/') {
        echo '<link rel="canonical" href="/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/4100344586/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/osvezhiteli-vozdukha/osvezhiteli-vozdukha-sprei/air-wick-osvezhitel-vozdukha-magnoliya-i-tsvetushchaya-vishnya-500-ml/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/osvezhiteli-vozdukha/osvezhiteli-vozdukha-sprei/air-wick-osvezhitel-vozdukha-magnoliya-i-tsvetushchaya-vishnya-240ml/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/osvezhiteli-vozdukha/osvezhiteli-vozdukha-sprei/air-wick-osvezhitel-vozdukha-magnoliya-i-tsvetushchaya-vishnya-240ml/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/tovary-dlya-detey/podguzniki/libero-born-2-3-6kg-26sht-podguzniki/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/tovary-dlya-detey/podguzniki/libero-born-2-3-6kg-26sht-podguzniki8008/') {
        echo '<link rel="canonical" href="/catalog/gigiena/tovary-dlya-detey/podguzniki/libero-born-2-3-6kg-26sht-podguzniki8008/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/tovary-dlya-detey/podguzniki/libero-everyday-maxi-4-66kh3-7-18kg-podguzniki/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/tovary-dlya-detey/podguzniki/libero-everyday-maxi-4-42-4-7-18kg-podguzniki/') {
        echo '<link rel="canonical" href="/catalog/gigiena/tovary-dlya-detey/podguzniki/libero-everyday-maxi-4-66kh3-7-18kg-podguzniki/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/sredstva-dlya-posudomoechnykh-mashin/finish-poroshok-1000g-new/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/sredstva-dlya-posudomoechnykh-mashin/finish-poroshok-2500g-new/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/sredstva-dlya-posudomoechnykh-mashin/finish-poroshok-1000g-new/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/sredstva-dlya-posudomoechnykh-mashin/finish-poroshok-limon-2500g-rf/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/sredstva-dlya-posudomoechnykh-mashin/finish-poroshok-limon-1000g-rf/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/sredstva-dlya-posudomoechnykh-mashin/finish-poroshok-limon-1000g-rf/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/stiralnye-poroshki/dosen-ka-d-detskogo-belya-2-2kg/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/stiralnye-poroshki/dosen-ka-d-detskogo-belya-3-7kg/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/sredstva-dlya-stirki/stiralnye-poroshki/dosen-ka-d-detskogo-belya-3-7kg/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/intimnoe/prezervativy/durex-prezervativy-12-dual-extase-relefnye-s-anestetikom/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/intimnoe/prezervativy/durex-prezervativy-12-dual-extase-relefnye-s-anestetikom8112/') {
        echo '<link rel="canonical" href="/catalog/gigiena/intimnoe/prezervativy/durex-prezervativy-12-dual-extase-relefnye-s-anestetikom/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/prokladki/prokladki-na-kazhdyy-den/libresse-classic-pantyliners-regular-25sht/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/prokladki/prokladki-na-kazhdyy-den/libresse-classic-pantyliners-regular-50sht/') {
        echo '<link rel="canonical" href="/catalog/gigiena/prokladki/prokladki-na-kazhdyy-den/libresse-classic-pantyliners-regular-50sht/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/prokladki/prokladki-na-kriticheskie-dni/libresse-invisible-super-wing-drai-2x8sht/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/prokladki/prokladki-na-kriticheskie-dni/libresse-invisible-super-wing-drai-8sht/') {
        echo '<link rel="canonical" href="/catalog/gigiena/prokladki/prokladki-na-kriticheskie-dni/libresse-invisible-super-wing-drai-8sht/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/prokladki/prokladki-na-kazhdyy-den/libresse-natural-care-normal-40sht/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/prokladki/prokladki-na-kazhdyy-den/libresse-natural-care-normal-20sht/') {
        echo '<link rel="canonical" href="/catalog/gigiena/prokladki/prokladki-na-kazhdyy-den/libresse-natural-care-normal-20sht/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/gigiena/prokladki/prokladki-na-kriticheskie-dni/libresse-natural-care-ultra-normal-wing-20sht/' || $_SERVER['REQUEST_URI'] == '/catalog/gigiena/prokladki/prokladki-na-kriticheskie-dni/libresse-natural-care-ultra-normal-wing-10sht/') {
        echo '<link rel="canonical" href="/catalog/gigiena/prokladki/prokladki-na-kriticheskie-dni/libresse-natural-care-ultra-normal-wing-10sht/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/vanish-gold-oxi-action-pyatnovyvoditel-500-g/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/-vanish-gold-oxi-action/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/-vanish-gold-oxi-action/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/vanish-gold-oxi-action-kristalnaya-belizna-pyatnovyvoditel-otbelivatel-90g/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/vanish-gold-oxi-action-krist-belizna-pyatn-l-otbel-500-g/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/vanish-gold-oxi-action-krist-belizna-pyatn-l-otbel-250-g/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/sredstva-dlya-stirki/otbelivateli-i-pyatnovyvoditeli/vanish-gold-oxi-action-krist-belizna-pyatn-l-otbel-500-g/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/salfetki-gubki/103york-3-/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/salfetki-gubki/113york-5-/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/khozyaystvennye-tovary/salfetki-gubki/113york-5-/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/salfetki-gubki/york-6-5555/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/salfetki-gubki/104york-3-/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/khozyaystvennye-tovary/salfetki-gubki/104york-3-/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/dlya-mytya-posudy/0021030737-morning-fresh-/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/dlya-mytya-posudy/morning0021030720-fresh-sr-vo-d-posudy-aloe-vera-900ml-new/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/dlya-mytya-posudy/0021030720morning-fresh-sr-vo-d-posudy-aloe-vera-450ml-new/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/dlya-mytya-posudy/0021030737-morning-fresh-/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/dlya-mytya-posudy/0021030725morning-fresh-sr-vo-d-posudy-original-450ml-new/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/dlya-mytya-posudy/morning0021030725-fresh-sr-vo-d-posudy-original-900ml-new/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/dlya-mytya-posudy/morning0021030725-fresh-sr-vo-d-posudy-original-900ml-new/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/cillit-bang-sr-vo-d-tualeta-anti-nalet-blesk-sila-tsitrusa-450ml/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/cillit-bang-sr-vo-d-tualeta-anti-nalet-blesk-sila-tsitrusa-750ml/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/cillit-bang-sr-vo-d-tualeta-anti-nalet-blesk-sila-tsitrusa-750ml/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-limonnaya-svezhest-24ch-500ml-8572135-8744152-8828123-21009848-21146991-67046705/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-limonnaya-svezhest-24ch-1l-8490251-8572140-8828412-21012646-21146993-67046667/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-limonnaya-svezhest-24ch-1l-8490251-8572140-8828412-21012646-21146993-67046667/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-svezhest-atlantiki-24ch-1l-8490259-8572152-8828401-21012645-21146990-67046695/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-svezhest-atlantiki-24ch-500ml-8572136-8744151-8828100-21009847-21146985-67046714/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-svezhest-atlantiki-24ch-500ml-8572136-8744151-8828100-21009847-21146985-67046714/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-svezhest-lavandy-24ch-500ml-8572137-8744153-8828125-21009849-21146984-67046710/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-svezhest-lavandy-24ch-1l-8490261-8572153-8828415-21012647-21146988-67046665/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-svezhest-lavandy-24ch-1l-8490261-8572153-8828415-21012647-21146988-67046665/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-ultra-blesk-500ml-67068949/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-ultra-blesk-1-l-67068933/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-ultra-blesk-1-l-67068933/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-khvoynaya-svezhest-24ch-500ml-8572134-8744154-8828131-21009850-21146678-67046726/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-khvoynaya-svezhest-24ch-1l-8490249-8572139-8828452-21012648-21146989-67046685/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/ochistiteli-unitazov/domestos-d-tualeta-khvoynaya-svezhest-24ch-1l-8490249-8572139-8828452-21012648-21146989-67046685/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-po-ukhodu-za-polami/mr650371-muscle-500-new/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-po-ukhodu-za-polami/666938mr-muscle-750-new/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-po-ukhodu-za-polami/666938mr-muscle-750-new/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-po-ukhodu-za-polami/glorix-sredstvo-chistyashchee-dlya-pola-limonnaya-energiya-1l-new-8803664-65414651-21130612-67047221/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-po-ukhodu-za-polami/glorix-sredstvo-chistyashchee-dlya-pola-limonnaya-energiya-500ml-new-67107678/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-po-ukhodu-za-polami/glorix-sredstvo-chistyashchee-dlya-pola-limonnaya-energiya-500ml-new-67107678/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-po-ukhodu-za-polami/glorix-sredstvo-chistyashchee-dlya-pola-svezhest-atlantiki-500ml-new-67106787/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-po-ukhodu-za-polami/glorix-sredstvo-chistyashchee-dlya-pola-svezhest-atlantiki-1l-new-8803304-65414648-21130610-67047221/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/chistyashchie-i-moyushchie-sredstva/sredstva-po-ukhodu-za-polami/glorix-sredstvo-chistyashchee-dlya-pola-svezhest-atlantiki-1l-new-8803304-65414648-21130610-67047221/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/sredstva-dlya-posudomoechnykh-mashin/finish-all-in1-max-tabletki-25sht/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/sredstva-dlya-posudomoechnykh-mashin/finish-all-in1-shine-protect-tabletki-65sht/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/sredstva-dlya-mytya-posudy/sredstva-dlya-posudomoechnykh-mashin/finish-all-in1-shine-protect-tabletki-50sht/') {
        echo '<link rel="canonical" href=""/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-aroma-spa-3-cloynaya-tualetnaya-bumaga-1kh8-rul-co/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-aroma-spa-3-cloynaya-tualetnaya-bumaga-1kh4-rul-co/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-aroma-spa-3-cloynaya-tualetnaya-bumaga-1kh4-rul-co/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-belaya-3-cloynaya-tualetnaya-bumaga-1kh8-rul-co/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-belaya-3-cloynaya-tualetnaya-bumaga-1kh4-rul-co/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-belaya-3-cloynaya-tualetnaya-bumaga-1kh4-rul-co/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-orkhideya-3-cloynaya-tualetnaya-bumaga-1kh8-rul-co/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-orkhideya-3-cloynaya-tualetnaya-bumaga-1kh4-rul-co/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-orkhideya-3-cloynaya-tualetnaya-bumaga-1kh4-rul-co/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-persik-3-cloynaya-tualetnaya-bumaga-1kh4-rul-co/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-persik-3-cloynaya-tualetnaya-bumaga-1kh8-rul-co/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-deluxe-persik-3-cloynaya-tualetnaya-bumaga-1kh8-rul-co/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-plyus-belaya-2-cloynaya-tualetnaya-bumaga-1kh12-rul-co-ka/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-plyus-belaya-2-cloynaya-tualetnaya-bumaga-1kh4-rul-co-ka/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-plyus-belaya-2-cloynaya-tualetnaya-bumaga-1kh4-rul-co-ka/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-plyus-siren-2-cloynaya-tualetnaya-bumaga-1kh8-rul-co-ka/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-plyus-siren-2-cloynaya-tualetnaya-bumaga-1kh4-rul-co-ka/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-plyus-siren-2-cloynaya-tualetnaya-bumaga-1kh4-rul-co-ka/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-plyus-yabloko-2-cloynaya-tualetnaya-bumaga-1kh8-rul-co-ka/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-plyus-yabloko-2-cloynaya-tualetnaya-bumaga-1kh12-rul-co-ka/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-plyus-yabloko-2-cloynaya-tualetnaya-bumaga-1kh4-rul-co-ka/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/vatnaya-i-bumazhnaya-produktsiya/tualetnaya-bumaga/zewa-plyus-yabloko-2-cloynaya-tualetnaya-bumaga-1kh8-rul-co-ka/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/adidas-fizzy-energy-new-50ml/' || $_SERVER['REQUEST_URI'] == '/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/adidas-fizzy-energy-new-30ml/') {
        echo '<link rel="canonical" href="/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/adidas-fizzy-energy-new-30ml/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/antonio-banderas-blue-seduction-111/' || $_SERVER['REQUEST_URI'] == '/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/antonio-banderas-tualetnaya-voda-blue-seduction-zhenskaya-50/') {
        echo '<link rel="canonical" href="/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/antonio-banderas-tualetnaya-voda-blue-seduction-zhenskaya-50/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/benetton-tualetnaya-voda-united-dreams-love-yourself-zhen-30-ml/' || $_SERVER['REQUEST_URI'] == '/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/benetton-united-dreams-love-yourself-30ml/') {
        echo '<link rel="canonical" href="/catalog/parfyumeriya/parfyumeriya-dlya-zhenshchin/benetton-united-dreams-love-yourself-30ml/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/pakety-dlya-musora/pakety-dlya-musora-khozyayushka-35l-30sht/' || $_SERVER['REQUEST_URI'] == '/catalog/chistota-v-dome/khozyaystvennye-tovary/pakety-dlya-musora/khozyayushka-pakety-dlya-musora-35-l-30-sht/') {
        echo '<link rel="canonical" href="/catalog/chistota-v-dome/khozyaystvennye-tovary/pakety-dlya-musora/khozyayushka-pakety-dlya-musora-35-l-30-sht/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-litsom/krem-dlya-litsa/chistaya-liniya-dnevnoy-fito-krem-dlya-litsa-ot-45-let-arnika-i-zhimolost/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-za-litsom/krem-dlya-litsa/chistaya-liniya-dnevnoy-fito-krem-dlya-litsa-ot-45-let-arnika-i-zhimolost-45-ml/') {
        echo '<link rel="canonical" href="/catalog/ukhod-za-litsom/krem-dlya-litsa/chistaya-liniya-dnevnoy-fito-krem-dlya-litsa-ot-45-let-arnika-i-zhimolost-45-ml/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/dove-hair-therapy-shampun-intensivnoe-vosstanovlenie8235/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/dove-hair-therapy-shampun-intensivnoe-vosstanovlenie/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/shampun-quot-dove-hair-therapy-quot-intensivnoe-vosstanovlenie/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/shampuni-dlya-volos/dove-hair-therapy-shampun-intensivnoe-vosstanovlenie8235/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/shampun-quot-dove-hair-therapy-quot-obem-i-vosstanovlenie/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/shampun-quot-dove-hair-therapy-quot-obem-i-vosstanovlenie989/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/shampuni-dlya-volos/shampun-quot-dove-hair-therapy-quot-obem-i-vosstanovlenie/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/shampun-quot-dove-advanced-hair-series-quot-pitayushchiy-ukhod/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/shampun-quot-dove-hair-therapy-quot-pitayushchiy-ukhod/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/shampuni-dlya-volos/shampun-quot-dove-hair-therapy-quot-pitayushchiy-ukhod/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/dove-hair-therapy-shampun-protiv-sekushchikhsya-konchikov/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/dove-hair-therapy-shampun-protiv-sekushchikhsya-konchikov8237/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/shampuni-dlya-volos/dove-hair-therapy-shampun-protiv-sekushchikhsya-konchikov/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/fructis-shampun-gustye-i-roskoshnye/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/fructis-shampun-gustye-i-roskoshnye8255/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/shampuni-dlya-volos/fructis-shampun-gustye-i-roskoshnye/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/fructis-shampun-rost-vo-vsyu-silu-ukreplyayushchiy8257/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/fructis-shampun-rost-vo-vsyu-silu-ukreplyayushchiy/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/shampuni-dlya-volos/fructis-shampun-rost-vo-vsyu-silu-ukreplyayushchiy/"/>';
    }
    if ($_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/fructis-shampun-troynoe-vosstanovlenie-ukreplyayushchiy8263/' || $_SERVER['REQUEST_URI'] == '/catalog/ukhod-dlya-volos/shampuni-dlya-volos/fructis-shampun-troynoe-vosstanovlenie-ukreplyayushchiy/') {
        echo '<link rel="canonical" href="/catalog/ukhod-dlya-volos/shampuni-dlya-volos/fructis-shampun-troynoe-vosstanovlenie-ukreplyayushchiy8263/"/>';
    }
    ?>

    <? include("/home/bitrix/www/local/templates/all.bh.by/noindex_page.php"); ?>


</head>
<body>
<noscript><img src="https://vk.com/rtrg?p=VK-RTRG-389496-b2SX6" style="position:fixed; left:-999px;" alt=""/></noscript>
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

    ym(52969687, "init", {
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true,
        ecommerce: "dataLayer"
    });
</script>
<noscript><img height="1" width="1" style="display:none"

               src="https://www.facebook.com/tr?id=3901255...pt=1";

    /></noscript>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/52969687" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->


<? $APPLICATION->ShowPanel() ?>
<?
$cur_page = $APPLICATION->GetCurPage(false);
$arrPage = explode('/', $cur_page);

?>
<? global $USER;
if (!$USER->IsAuthorized()): ?>
    <? //<style>.js-favorites-lnk{display: none !important;}</style>*/?>
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
<? if (!in_array(10, $USER->GetUserGroupArray()) && !in_array(11, $USER->GetUserGroupArray())): ?>
    <style>
        .gmi-nds {
            display: none !important;
        }
    </style>
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
									"top-all.bh", 
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
                    <!-- BEAUTY HOUSE -->
                    <a class="logo-small" href="/" style="display: none">
                        BEAUTY HOUSE
                    </a>
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
                        <? global $USER;
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
                            <? $APPLICATION->IncludeComponent(
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
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?
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
                    <a class="logo logo_white" href="/"><img src="/local/templates/.default/images/logo-small.svg"
                                                             alt=""></a>
                    <? /*<div class="right">
							<div class="h__search">
								<?$APPLICATION->IncludeComponent(
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
);?>
							</div>
							<a class="mobile-search" href="/catalog/?q"></a>
							<?global $USER;if($USER->IsAuthorized()):?>
								<a class="login login_white" href="/personal/"></a>
							<?else:?>
								<a class="login login_white" data-fancybox data-src="#auth-popup" href="javascript:;"></a>
							<?endif;?>
							<a class="favorites favorites_white" href="/personal/favorites/"></a>
							<div class="cart-wrp">
								<?$APPLICATION->IncludeComponent(
									"bitrix:sale.basket.basket.line",
									"main",
									array(
										"COMPONENT_TEMPLATE" => "main",
										"HIDE_ON_BASKET_PAGES" => "N",
										"PATH_TO_AUTHORIZE" => "",
										"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
										"PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
										"PATH_TO_PERSONAL" => SITE_DIR."personal/",
										"PATH_TO_PROFILE" => SITE_DIR."personal/",
										"PATH_TO_REGISTER" => SITE_DIR."login/",
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
                                "MENU_CACHE_TIME" => "3600000000",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "ROOT_MENU_TYPE" => "topcatalog",
                                "USE_EXT" => "N",
                                "COMPONENT_TEMPLATE" => "top_main_catalog"
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
                        <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", "main", array(
                            "PATH" => "",
                            "SITE_ID" => "s1",
                            "START_FROM" => "0"
                        ),
                            false,
                            array(
                                "ACTIVE_COMPONENT" => "Y"
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
                if ($APPLICATION->GetCurPage(false) === '/aktsii/') include($_SERVER["DOCUMENT_ROOT"] . "/local/include/sale.php");
                if ($APPLICATION->GetCurPage(false) === '/populyarnye/') include($_SERVER["DOCUMENT_ROOT"] . "/local/include/popular.php");
                if ($APPLICATION->GetCurPage(false) === '/novinki/') include($_SERVER["DOCUMENT_ROOT"] . "/local/include/new.php");
                if ($APPLICATION->GetCurPage(false) === '/podarki/') include($_SERVER["DOCUMENT_ROOT"] . "/local/include/gift.php");
                //if($APPLICATION->GetCurPage(false) === '/brendy/') 	include($_SERVER["DOCUMENT_ROOT"]."/include/brands.php");
                ?>

