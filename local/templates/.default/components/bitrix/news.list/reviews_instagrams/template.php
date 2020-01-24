<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();



?>
<?if(!empty($arResult["ITEMS"])):?>
    <div class="container">
        <div class="main__ttl">ОТЗЫВЫ О НАС В ИНСТАГРАМ</div>
        <div class="instagram-box">
            <div class="instagram-slider-container owl-carousel-inst clearfix" style="display: flex;">
                <?foreach ($arResult["ITEMS"] as $key => $val):

                    $this->AddEditAction($val['ID'], $val['EDIT_LINK'], CIBlock::GetArrayByID($val["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($val['ID'], $val['DELETE_LINK'], CIBlock::GetArrayByID($val["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <div class="l-span-3" id="<?=$this->GetEditAreaId($val['ID']);?>">
                        <div class="instagram-card">
                            <iframe class="instagram-media instagram-media-rendered" id="instagram-embed-<?=$key?>" src="https://www.instagram.com/p/<?=$val['PROPERTIES']['ID_INSTA']['VALUE']?>/embed/captioned/?cr=1&amp;v=5&amp;rd=https%3A%2F%2Fmixit.ru&amp;rp=%2F#%7B%22ci%22%3A0%2C%22os%22%3A1584.8799999985204%7D" allowtransparency="true" allowfullscreen="true" frameborder="0" height="1007" data-instgrm-payload-id="instagram-media-payload-<?=$key?>" scrolling="no" style="background: white; max-width: 658px; width: 1px; border-radius: 3px; border: 1px solid rgb(219, 219, 219); box-shadow: none; display: block; margin: 0px 0px 12px; min-width: 300px; padding: 0px;  height: <?=$val['PROPERTIES']['HEIGTH']['VALUE']?>px; margin: 0 auto;"></iframe>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </div>
<?endif?>

<script>
    $(".instagram-slider-container").slick({
        autoplay: !0,
        autoplaySpeed: 6e3,
        slidesToShow: 4,
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
