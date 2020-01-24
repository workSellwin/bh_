<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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





<?if(!empty($arResult["ITEMS"])):?>
    <div class="container">
        <div class="main__ttl">Новинки</div>
        <div class="instagram-box">
            <div class="brend-novinki-slider-container owl-carousel-inst clearfix" style="display: flex;">
                <?foreach ($arResult["ITEMS"] as $key => $val):
                    PR($val);
                    $this->AddEditAction($val['ID'], $val['EDIT_LINK'], CIBlock::GetArrayByID($val["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($val['ID'], $val['DELETE_LINK'], CIBlock::GetArrayByID($val["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <div class="l-span-3" id="<?=$this->GetEditAreaId($val['ID']);?>">
                        <div class="instagram-card">

                        </div>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </div>
<?endif?>

<script>
    $(".brend-novinki-slider-container").slick({
        autoplay: !0,
        autoplaySpeed: 6e3,
        slidesToShow: 3,
        slidesToScroll: 1,
        speed: 800,
        pauseOnFocus: !1,
        dots: !1,
        arrows: !0,
        responsive: [{breakpoint: 1125, settings: {slidesToShow: 3, slidesToScroll: 1}}, {
            breakpoint: 769,
            settings: {slidesToShow: 2, slidesToScroll: 1}
        }, {breakpoint: 640, settings: {slidesToShow: 1, slidesToScroll: 1}}]
    })

</script>
