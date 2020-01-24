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
if(!empty($arResult["ITEMS"])):
?>
    <style>
        span.prod-img{
            margin: 0;
            position: relative;
        }
        .prod-other__slider-item a.btn-more {
            background-color: #000;
            color: #fff;
            font-weight: 400;
            border: 1px solid #000;
            padding: 10px;
            position: absolute;
            left: 36%;
            top: 41%;
            display: none;
            z-index: 10;
        }
        div.prod-item:hover a.btn-more {
            display: block;
        }
        div.prod-item:hover .plashka {
            display: block;
        }
        .viewed-prods-wrp--my a:not(.btn) {
            text-decoration: none;
            color: #fff;
        }
        .viewed-prods-wrp--my {
            margin-bottom: 50px;
        }
        div.prod-item{
            padding: 0;
            position: relative;
        }
        .plashka {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            background-color: rgba(109, 109, 109, 0.24);
            display: none;
        }

        .prod-other__slider-item {
            max-width: 310px!important;
            display: inline-block;
        }


        .prod-other__slider--my {
            display: flex;
            align-items: flex-start;
            flex-wrap: wrap;
            height: 100%;
        }

        .prod-other__slider-item {

            width: 50%;
        }
        @media only screen and (min-width: 1200px) {
            .prod-other__slider .slick-next, .prod-other__slider .slick-prev {
                margin-top: 0;
                top: 180px;
            }
            .viewed-prods-wrp--my img{
                max-height: 320px!important;
            }
            .prod-other__slider-item .prod-img {
                height: 320px!important;
            }
            .prod-other__slider-item {
                display: inline-block!important;
            }
        }
        @media only screen and (min-width: 640px) {
            .prod-other__slider-item {
                display: block;
                margin: 0 auto;
            }
        }






    </style>
    <div class="viewed-prods-wrp viewed-prods-wrp--my">
        <div class="container">
            <div class="main__ttl">Выгодные предложения</div>
            <div class="prod-other__slider--my    "
                 data-entity="<?= $containerName ?>" >
                <? foreach ($arResult["ITEMS"] as $key => $arItem): ?>
                    <?
                    //$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                   // $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    //$file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width'=>300, 'height'=>320), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
                    <div class="prod-other__slider-item" data-entity="items-row">
                        <div class="prod-item " data-entity="item">
                            <a class="btn-more"
                               href="<?=$arItem['PROPERTIES']['LINK']['VALUE'];?>">
                                Перейти
                            </a>
                            <span class="" data-entity="image-wrapper" >
                                <!--<img src="<?/*=$file['src']*/?>">-->
                                <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>">
                            </span>
                            <span class="plashka"></span>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    </div>

    <script>



/*
        $(".prod-other__slider--my").on("init", function () {
            $(".prod-other-wrp .loader").removeClass("show"),
                $(".prod-other__slider--my").removeClass("hide")
        }),
            $(".prod-other__slider--my").slick({
            autoplay: !1,
            autoplaySpeed: 4e3,
            slidesToShow: 4,
            slidesToScroll: 1,
            speed: 800,
            dots: !1,
            arrows: !0,
            responsive: [{breakpoint: 1025, settings: {slidesToShow: 4, slidesToScroll: 4}}, {
                breakpoint: 991,
                settings: {slidesToShow: 3, slidesToScroll: 3}
            }, {breakpoint: 640, settings: {slidesToShow: 2, slidesToScroll: 2}}]
        })
*/
    </script>


<? /*
<div class="brands">
    <div class="container">
        <div class="main__ttl">Выгодные предложения</div>
        <div class="brands__wrp">
            <div class="loader show"></div>
            <div class="brands-carousel carousel hide">
                <?foreach ($arResult["ITEMS"] as $arItem):?>
                    <?
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <div class="brands-carousel__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                        <a href="<?=$arItem['PROPERTIES']['LINK']['VALUE'];?>" title="<?=$arItem['NAME'];?>">
                        <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PICTURE']['TITLE'];?>"><br>
                        <?=$arItem['PREVIEW_TEXT'];?>

                            <span>Подробние</span>
                        </a>
                    </div>
                <?endforeach?>
            </div>
        </div>
    </div>
</div>*/ ?>

<? //PR($arResult["ITEMS"])?>


<?endif;?>