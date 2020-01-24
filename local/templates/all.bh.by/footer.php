<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>					<?if($APPLICATION->GetDirProperty("templates")=="Y"):?>
						</article>
					<?endif;?>
				<?if($APPLICATION->GetCurPage(false) !== '/' || $arrPage[1]!="catalog"):?>
					</div> <!-- main__container -->
				<?endif;?>
			</div> <!-- main__content -->
		</div> <!-- end main -->

    <div class="container-entry-points">
        <div class="entry-points">
            <a href="/catalog/" class="entry-point-item">
                <div class="image">
                    <img src="/local/templates/.default/images/img4.svg" class="img img">
                </div>
                <div class="name">Каталог</div>
            </a>
            <a href="/brands/" class="entry-point-item">
                <div class="image">
                    <img src="/local/templates/.default/images/img3.svg" class="img img">
                </div>
                <div class="name">Бренды</div>
            </a>
            <a href="/aktsii/" class="entry-point-item">
                <div class="image">
                    <img src="/local/templates/.default/images/img1.svg" class="img img">
                </div>
                <div class="name">Акции</div>
            </a>
            <a href="/catalog/podarki/" class="entry-point-item">
                <div class="image">
                    <img src="/local/templates/.default/images/img2.svg" class="img img">
                </div>
                <div class="name">Идеи подарков</div>
            </a>
        </div>
    </div>

		<footer class="f">
			<div class="container">
				<div class="row">
					<div class="f__nav cl">
						<?$APPLICATION->IncludeComponent(
							"bitrix:menu",
							"footer_menu",
							array(
								"ALLOW_MULTI_SELECT" => "N",
								"CHILD_MENU_TYPE" => "left",
								"DELAY" => "N",
								"MAX_LEVEL" => "3",
								"MENU_CACHE_GET_VARS" => array(
								),
								"MENU_CACHE_TIME" => "3600",
								"MENU_CACHE_TYPE" => "N",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"ROOT_MENU_TYPE" => "bottom",
								"USE_EXT" => "Y",
								"COMPONENT_TEMPLATE" => "footer_menu"
							),
							false
						);?>
						<div class="f__nav-bottom cl">
							<div class="f__time">
								<div class="f__nav-bottom-ttl">Прием и обработка заказов:</div>
								<span>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => "/include/foter-time.php"), false);?>
								</span>
							</div>
							<div class="f__delivery">
								<span>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => "/include/footer-delivery.php"), false);?>
								</span>
							</div>
							<div class="f__payment">
								<div class="f__nav-bottom-ttl">Принимаем к оплате:</div>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => "/include/buy_img.php"), false);?>
							</div>

							<div class="f__social">
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
						</div>
					</div>
					<div class="f__bottom cl">
						<div class="f__bottom-left">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => "/include/copy.php"), false);?>
						</div>
						<div class="f__bottom-right"><a href="http://mitgroup.ru/" rel="nofollow">Разработка сайта: </a>MITGROUP <p>Продвижение сайта - <a href="https://www.seologic.by/">Seologic</a>.</p></div>
					</div>
				</div>
			</div>
			<div class="hide">
				<?$APPLICATION->IncludeComponent(
					"bitrix:menu",
					"hidden",
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
						"ROOT_MENU_TYPE" => "left",
						"USE_EXT" => "N"
					),array('HIDE_ICONS' => 'Y')
				);?>
			</div>
		</footer>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-136972579-1', 'auto');
  ga('send', 'pageview');

</script>
		<script  >
jQuery('.product-item-button-container').click(function(){
	ga('send', 'event', 'knopka1', 'form1');
});

</script>
		<script  >
jQuery('.product-item-detail-buy-button').click(function(){
	ga('send', 'event', 'knopka1', 'form1');
});

</script>

		<script  >
jQuery('.product-item-detail-buy-button').click(function(){
    if(yaCounter45947409)
	    yaCounter45947409.reachGoal('order'); return true;
});

</script>
		<script  >
jQuery('.product-item-button-container').click(function(){
    if(yaCounter45947409)
	    yaCounter45947409.reachGoal('order'); return true;
});

</script>
		<script  >
jQuery('.bx_bt_button.bx_big').click(function(){
    if(yaCounter45947409)
	    yaCounter45947409.reachGoal('kupon');
});

</script>
		<script  >
jQuery('.bx_bt_button.bx_big').click(function(){
	ga('send', 'event', 'Knopka', 'Kupon');
});

</script>

<?// include_once $_SERVER['DOCUMENT_ROOT'].'/local/templates/popup_action2.php'?>

<?//Электронная коммерция?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.js-btn-basket').click(function () {

            var prod_name = $(this).attr('data-prod-name');
            var prod_id = $(this).attr('data-prod-id');
            var prod_price = $(this).parents(".prod-item").find('.prod-price__new').text();
            if (prod_price === undefined) {
                prod_price = $(this).parents(".prod-info").find('.prod__price-current').text();
            }
            prod_price = prod_price.trim();
            prod_price = prod_price.split(' ');

            dataLayer.push({
                "ecommerce": {
                    "add": {
                        "products": [
                            {
                                "id": prod_id,
                                "name": prod_name,
                                "price": prod_price[0],
                                "quantity": 1
                            }
                        ]
                    }
                }
            });
        });

        $('.js-btn-basket-link').click(function () {

            var prod_name = $(this).attr('data-prod-name');
            var prod_id = $(this).attr('data-prod-id');
            var prod_quantity = $(this).parents("tr#" + prod_id).find('input#QUANTITY_' + prod_id).val();
            if (prod_quantity === undefined) {
                prod_quantity = 1;
            }

            dataLayer.push({
                "ecommerce": {
                    "remove": {
                        "products": [
                            {
                                "id": prod_id,
                                "name": prod_name,
                                "quantity": prod_quantity
                            }
                        ]
                    }
                }
            });
        });
    });
</script>



<script >
var google_tag_params = {
ecomm_prodid: "REPLACE_WITH_STRING_VALUE",
ecomm_pagetype: "REPLACE_WITH_STRING_VALUE",
ecomm_totalvalue: "REPLACE_WITH_STRING_VALUE"
};
</script>
<script >
/* <![CDATA[ */
var google_conversion_id = 834546144;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script  src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/834546144/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<?if(!$APPLICATION->GetProperty("prod")) $APPLICATION->AddHeadString('<meta property="og:image" content="https://bh.by/upload/bh_sign.png" />',true);?>
<?
$popup = __DIR__ . "/../.default/include/popup.php";
if(file_exists($popup))
	include $popup;
?>

<?/*
    <!-- BEGIN JIVOSITE CODE {literal} -->
    <script  defer="defer"  type='text/javascript'>
        (function(){ var widget_id = '4PH2b1jgYk';var d=document;var w=window;function l(){
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
            s.src = '//code.jivosite.com/script/widget/'+widget_id
            ; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}
            if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}
            else{w.addEventListener('load',l,false);}}})();
    </script>
    <!-- {/literal} END JIVOSITE CODE -->
    <link href="/jivosite/jivosite.css" rel="stylesheet">
    <script  defer="defer"  src="/jivosite/jivosite.js" ></script>

*/?>


	</body>
</html>
<?if($GLOBALS['description']):?>
<?$APPLICATION->SetPageProperty('description',$GLOBALS['description']);?>
<?endif;?>
<?if($GLOBALS['keywords']):?>
<?$APPLICATION->SetPageProperty('keywords',$GLOBALS['keywords']);?>
<?endif;?>
<?if($GLOBALS['title']):?>
<?$APPLICATION->SetPageProperty('title',$GLOBALS['title']);?>
<?endif;?>
<?if($GLOBALS['h1']):?>
<?$APPLICATION->SetTitle($GLOBALS['h1']);?>
<?endif;?>
