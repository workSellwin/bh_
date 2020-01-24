<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true); ?>
<? //$arResult = getChilds($arResult)?>

<div class="container my-list-catalog">
    <div class="catalog cl">
        <div class="department-card-container">
            <? if (!empty($arResult)): ?>
                <? foreach ($arResult as $key => $arItem): ?>
                    <div class="department-card">
                        <div class="<?= !empty($arItem['CHILD']) ? 'flipper' : '' ?>" data-url-section="<?=$arItem['LINK']?>">
                            <div class="department-card-front">
                                <span><?= $arItem['TEXT'] ?></span>
                                <!--<span>150 000 товаров</span>-->
                                <div>
                                    <img src="<?= $arItem['IMG']['CATALOG_IMG'] ? $arItem['IMG']['CATALOG_IMG'] : '/upload/resize_cache/iblock/fb5/283_346_1/fb5d1414a255c21931c8c9779d644d37.jpg' ?>">
                                </div>
                            </div>
                            <? if (!empty($arItem['CHILD'])): ?>
                                <div class="department-card__transparent-top"></div>
                            <? endif; ?>
                            <div class="department-card-back">
                                <? if (!empty($arItem['CHILD'])): ?>
                                    <ul>
                                        <? foreach ($arItem['CHILD'] as $item): ?>
                                            <li><a href="<?= $item['SECTION_PAGE_URL'] ?>"><?= $item['NAME'] ?></a></li>
                                        <? endforeach ?>
                                    </ul>
                                <? endif ?>
                            </div>
                            <div class="department-card__transparent-bottom"></div>
                        </div>
                    </div>
                <? endforeach ?>
            <? endif ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.department-card').click(function () {
            if(!$(this).children().hasClass("flipper")){
                var url = $(this).children().attr('data-url-section');
                location=url;
            }
        })
    });
</script>