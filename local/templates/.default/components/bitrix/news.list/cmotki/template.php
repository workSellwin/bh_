<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="related-wrp">
    <div class="related__ttl">С этим товаром покупают</div>
    <div class="related" data-entity="container-OQ3k9P">
<? foreach ($arResult["ITEMS"] as $arItem): ?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>

    <!-- items-container -->
    <div class="related__col" data-entity="items-row" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
        <div class="related__item-container" id="bx_3966226736_8527_362ce596257894d11ab5c1d73d13c755" data-entity="item" style="height: auto;">
            <div class="related__item">
                <a id="favorites_8527" class="favorites-lnk js-favorites-lnk" data-ajax="/ajax/add_favorites.php"
                   data-id="8527" data-status="Y" href="#"></a>

                <span class="prod-status">
                    <span class="prod-status__item prod-status__item-describe " style="background-color: #f42561"
                          id="bx_3966226736_8527_362ce596257894d11ab5c1d73d13c755_dsc_perc">-10%
                    </span>
                </span>

                <a class="product-item-image-wrapper related__img"
                   href="<?=$arItem['DETAIL_PAGE_URL']?>"
                   title="картинка Kerastase Elixir Ultime Уход 200 мл " data-entity="image-wrapper">
                        <span class="product-item-image-slider-slide-container slide"
                              id="bx_3966226736_8527_362ce596257894d11ab5c1d73d13c755_pict_slider" style="display: none;"
                              data-slider-interval="3000" data-slider-wrap="true">
                        </span>
                    <span id="bx_3966226736_8527_362ce596257894d11ab5c1d73d13c755_pict" style="display: ;">
                            <img src="/upload/iblock/95b/95b1cfd9f288bd1360d397a036d6cf24.png"
                                 alt="Kerastase Elixir Ultime Уход 200 мл">
                        </span>
                    <span class="product-item-image-alternative"
                          id="bx_3966226736_8527_362ce596257894d11ab5c1d73d13c755_secondpict"
                          style="background-image: url(/upload/iblock/061/061f0ab90012c203d9a256d45ce23958.png); display: ;">
                        </span>
                    <div class="product-item-image-slider-control-container"
                         id="bx_3966226736_8527_362ce596257894d11ab5c1d73d13c755_pict_slider_indicator"
                         style="display: none;">
                    </div>
                </a>

                <a class="related__name" title="Kerastase Elixir Ultime Уход 200 мл"
                   href="/catalog/professionalnyy-ukhod-za-volosami/ukhod-za-volosami/l-oreal-kerastase-elixir-ultime-molochko-200-ml/"><?=$arItem['NAME']?></a>
                <div class="related__hiden">
                    <div class="prod-price" data-entity="price-block">
                        <div class="prod-price__old"
                             id="bx_3966226736_8527_362ce596257894d11ab5c1d73d13c755_price_old">
                            96.50 руб.
                        </div>
                        <div class="prod-price__new" id="bx_3966226736_8527_362ce596257894d11ab5c1d73d13c755_price">
                            86.85 руб.
                        </div>
                    </div>
                    <div class="" data-entity="buttons-block">
                        <div class="product-item-button-container product_id_8527"
                             id="bx_3966226736_8527_362ce596257894d11ab5c1d73d13c755_basket_actions">
                            <a class="btn btn_ico js-btn-basket 2"
                               id="bx_3966226736_8527_362ce596257894d11ab5c1d73d13c755_buy_link"
                               href="javascript:void(0)" rel="nofollow">
                                В корзину </a>
                            <span class="hide btn btn_in_basket btn_ico">Товар в корзине</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?//PR($arItem)?>
<? endforeach; ?>
        <!-- items-container -->
    </div>
</div>




