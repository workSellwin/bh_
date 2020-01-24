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

array_walk($arResult["ITEMS"], function (&$item) {
    if($img=$item['PROPERTIES']['IMG']['~VALUE']){
        $item['PREVIEW_PICTURE']['SRC']=$img;
    }
});

$arResult['ELEM'] = [];
foreach ($arResult["ITEMS"] as $val) {
    $arResult['ELEM'][$val['PROPERTIES']['POSITION']['VALUE']] = $val;
}
ksort($arResult['ELEM']);
unset($arResult["ITEMS"]);

?>
<div style="clear: both"></div>


    <section class="banner-section-index">
        <? $img = CFile::ResizeImageGet($arResult['ELEM'][1]['PREVIEW_PICTURE']['ID'], array('width' => 1840, 'height' => 150), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
        <div class="prod-item rad-img-1"
             style=" margin-left: 0; padding: unset; <?= $arResult['ELEM'][1]['PROPERTIES']['LINK']['VALUE'] ? 'cursor: pointer;' : '' ?>" <?= $arResult['ELEM'][1]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $arResult['ELEM'][1]['PROPERTIES']['LINK']['VALUE'] . '\'"' : '' ?>>
            <a class="btn-more btn-more-2" href="<?=$arResult['ELEM'][1]['PROPERTIES']['LINK']['VALUE']?>"></a>
            <span><img class="" src="<?= $img['src'] ?>"></span>
            <span class="plashka"></span>
        </div>
        <div class="">
            <? $i = 1;
            $j = 1;
            $b = 1;
            $img = false;
            foreach ($arResult['ELEM'] as $value):?>
                <?


                if ($i > 1 && $i <= 3):

                    //PR($value['PROPERTIES']['LINK']['VALUE']);
                    $img = CFile::ResizeImageGet($value['PREVIEW_PICTURE']['ID'], array('width' => 898, 'height' => 250), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>

                    <?
                    if ($j == 1):?>
                        <div class="baner-img rad-img-3" style="display: flex; justify-content: space-between!important; height: 100%;">
                    <? endif; ?>


                    <div class="prod-item"
                         style="padding: unset; <?= $value['PROPERTIES']['LINK']['VALUE'] ? 'cursor: pointer;' : '' ?>" <?= $value['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $value['PROPERTIES']['LINK']['VALUE'] . '\'"' : '' ?>>
                        <a class="btn-more btn-more-2" href="<?=$value['PROPERTIES']['LINK']['VALUE']?>"></a>
                        <span><img class="" src="<?= $img['src'] ?>"></span>
                        <span class="plashka"></span>
                    </div>

                    <?
                    if ($j == 2):?>
                        </div>
                    <? endif; ?>

                    <?
                    $j++;

                elseif ($i > 3 && $i <= 7):

                    if ($b == 1):?>
                        <div class="baner-img rad-img-3" style="display: flex; justify-content: space-between!important; height: 100%;">
                    <? endif; ?>


                    <? $img = CFile::ResizeImageGet($value['PREVIEW_PICTURE']['ID'], array('width' => 574, 'height' => 200), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
                    <div class="prod-item"
                         style="padding: unset;  <?= $value['PROPERTIES']['LINK']['VALUE'] ? 'cursor: pointer;' : '' ?>" <?= $value['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $value['PROPERTIES']['LINK']['VALUE'] . '\'"' : '' ?>>
                        <a class="btn-more btn-more-2" href="<?=$value['PROPERTIES']['LINK']['VALUE']?>"></a>
                        <span><img class="" src="<?= $img['src'] ?>"></span>
                        <span class="plashka"></span>
                    </div>

                    <?
                    if ($j == 2):?>
                        </div>
                    <? endif; ?>

                    <? $b++; ?>
                <? endif; ?>

                <?
                $i++;
            endforeach; ?>
        </div>
    </section>



<?/*


<section class="home-banners">
    <?//PR(html_entity_decode($arResult['ELEM'][1]['PROPERTIES']['LINK']['VALUE']), ENT_HTML5);?>
    <div class="row">
        <div class="col-md-20-4">

            <?if($arResult['ELEM'][1]['PROPERTIES']['TARGET']['VALUE'] == 'Да'):?>
                <div class="prod-item" <?= $arResult['ELEM'][1]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="window.open(\'' . $arResult['ELEM'][1]['PROPERTIES']['LINK']['VALUE'] . '\')" style="cursor: pointer;"' : '' ?>>
                    <a class="btn-more btn-more-2" href="#">

                    </a>
                    <span>
                        <img src="<?= $arResult['ELEM'][1]['PREVIEW_PICTURE']['SRC'] ?>" alt="скидки">
                    </span>
                    <span class="plashka"></span>
                </div>
            <?else:?>
                <div class="prod-item" <?= $arResult['ELEM'][1]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $arResult['ELEM'][1]['PROPERTIES']['LINK']['VALUE'] . '\'" style="cursor: pointer;"' : '' ?>>
                    <a class="btn-more btn-more-2" <?= $arResult['ELEM'][1]['PROPERTIES']['LINK']['VALUE'] ? 'href="' . $arResult['ELEM'][1]['PROPERTIES']['LINK']['VALUE'] . '"' : '' ?>>

                    </a>
                    <span>
                        <img src="<?= $arResult['ELEM'][1]['PREVIEW_PICTURE']['SRC'] ?>" alt="скидки">
                    </span>
                    <span class="plashka"></span>
                </div>
            <?endif;?>

        </div>
        <div class="col-md-20-1" style="float: right;">
            <div class="col-xs-25-2 col-sm-25-1  col-md-6">

                <?if($arResult['ELEM'][2]['PROPERTIES']['TARGET']['VALUE'] == 'Да'):?>
                    <div class="prod-item" <?= $arResult['ELEM'][2]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="window.open(\'' . $arResult['ELEM'][2]['PROPERTIES']['LINK']['VALUE'] . '\')" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" href="#">

                        </a>
                        <span>
                            <img class="img-responsive" src="<?= $arResult['ELEM'][2]['PREVIEW_PICTURE']['SRC'] ?>">
                        </span>
                        <span class="plashka"></span>
                    </div>
                <?else:?>
                    <div class="prod-item" <?= $arResult['ELEM'][2]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $arResult['ELEM'][2]['PROPERTIES']['LINK']['VALUE'] . '\'" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" <?= $arResult['ELEM'][2]['PROPERTIES']['LINK']['VALUE'] ? 'href="' . $arResult['ELEM'][2]['PROPERTIES']['LINK']['VALUE'] . '"' : '' ?>>

                        </a>
                        <span>
                                <img class="img-responsive" src="<?= $arResult['ELEM'][2]['PREVIEW_PICTURE']['SRC'] ?>">
                            </span>
                        <span class="plashka"></span>
                    </div>
                <?endif;?>
            </div>
            <div class="col-xs-25-2 col-sm-25-1  col-md-6">

                <?if($arResult['ELEM'][3]['PROPERTIES']['TARGET']['VALUE'] == 'Да'):?>

                    <div class="prod-item" <?= $arResult['ELEM'][3]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="window.open(\'' . $arResult['ELEM'][3]['PROPERTIES']['LINK']['VALUE'] . '\')" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" href="#">

                        </a>
                        <span>
                            <img class="img-responsive" src="<?= $arResult['ELEM'][3]['PREVIEW_PICTURE']['SRC'] ?>">
                        </span>
                        <span class="plashka"></span>
                    </div>

                <?else:?>

                    <div class="prod-item" <?= $arResult['ELEM'][3]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $arResult['ELEM'][3]['PROPERTIES']['LINK']['VALUE'] . '\'" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" <?= $arResult['ELEM'][3]['PROPERTIES']['LINK']['VALUE'] ? 'href="' . $arResult['ELEM'][3]['PROPERTIES']['LINK']['VALUE'] . '"' : '' ?>>

                        </a>
                        <span>
                            <img class="img-responsive" src="<?= $arResult['ELEM'][3]['PREVIEW_PICTURE']['SRC'] ?>">
                        </span>
                        <span class="plashka"></span>
                    </div>

                <?endif;?>
            </div>
            <div class="col-xs-25-2 col-sm-25-1 col-md-6">

                <?if($arResult['ELEM'][4]['PROPERTIES']['TARGET']['VALUE'] == 'Да'):?>

                    <div class="prod-item" <?= $arResult['ELEM'][4]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="window.open(\'' . $arResult['ELEM'][4]['PROPERTIES']['LINK']['VALUE'] . '\')" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" href="#">

                        </a>
                        <span>
                            <img class="img-responsive" src="<?= $arResult['ELEM'][4]['PREVIEW_PICTURE']['SRC'] ?>">
                        </span>
                        <span class="plashka"></span>
                    </div>

                <?else:?>

                    <div class="prod-item" <?= $arResult['ELEM'][4]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $arResult['ELEM'][4]['PROPERTIES']['LINK']['VALUE'] . '\'" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" <?= $arResult['ELEM'][4]['PROPERTIES']['LINK']['VALUE'] ? 'href="' . $arResult['ELEM'][4]['PROPERTIES']['LINK']['VALUE'] . '"' : '' ?>>

                        </a>
                        <span>
                            <img class="img-responsive" src="<?= $arResult['ELEM'][4]['PREVIEW_PICTURE']['SRC'] ?>">
                        </span>
                        <span class="plashka"></span>
                    </div>

                <?endif;?>

            </div>
            <div class="col-xs-25-2 col-sm-25-1  col-md-6">

                <?if($arResult['ELEM'][5]['PROPERTIES']['TARGET']['VALUE'] == 'Да'):?>
                    <div class="prod-item" <?= $arResult['ELEM'][5]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="window.open(\'' . $arResult['ELEM'][5]['PROPERTIES']['LINK']['VALUE'] . '\')" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" href="#">

                        </a>
                        <span>
                            <img class="img-responsive" src="<?= $arResult['ELEM'][5]['PREVIEW_PICTURE']['SRC'] ?>">
                        </span>
                        <span class="plashka"></span>
                    </div>
                <?else:?>
                    <div class="prod-item" <?= $arResult['ELEM'][5]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $arResult['ELEM'][5]['PROPERTIES']['LINK']['VALUE'] . '\'" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" <?= $arResult['ELEM'][5]['PROPERTIES']['LINK']['VALUE'] ? 'href="' . $arResult['ELEM'][5]['PROPERTIES']['LINK']['VALUE'] . '"' : '' ?>>

                        </a>
                        <span>
                                <img class="img-responsive" src="<?= $arResult['ELEM'][5]['PREVIEW_PICTURE']['SRC'] ?>">
                            </span>
                        <span class="plashka"></span>
                    </div>
                <?endif;?>


            </div>
        </div>
        <div class="col-md-20-4">
            <div class="col-sm-3">
                <div class="col-xs-3">

                    <?if($arResult['ELEM'][6]['PROPERTIES']['TARGET']['VALUE'] == 'Да'):?>

                        <div class="prod-item" <?= $arResult['ELEM'][6]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="window.open(\'' . $arResult['ELEM'][6]['PROPERTIES']['LINK']['VALUE'] . '\')" style="cursor: pointer;"' : '' ?>>
                            <a class="btn-more btn-more-2" href="#">

                            </a>
                            <span>
                                <img class="img-responsive" src="<?= $arResult['ELEM'][6]['PREVIEW_PICTURE']['SRC'] ?>">
                            </span>
                            <span class="plashka"></span>
                        </div>

                    <?else:?>

                        <div class="prod-item" <?= $arResult['ELEM'][6]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $arResult['ELEM'][6]['PROPERTIES']['LINK']['VALUE'] . '\'" style="cursor: pointer;"' : '' ?>>
                            <a class="btn-more btn-more-2" <?= $arResult['ELEM'][6]['PROPERTIES']['LINK']['VALUE'] ? 'href="' . $arResult['ELEM'][6]['PROPERTIES']['LINK']['VALUE'] . '"' : '' ?>>

                            </a>
                            <span>
                                    <img class="img-responsive" src="<?= $arResult['ELEM'][6]['PREVIEW_PICTURE']['SRC'] ?>">
                                </span>
                            <span class="plashka"></span>
                        </div>

                    <?endif;?>


                    <?if($arResult['ELEM'][7]['PROPERTIES']['TARGET']['VALUE'] == 'Да'):?>

                        <div class="prod-item" <?= $arResult['ELEM'][7]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="window.open(' . $arResult['ELEM'][7]['PROPERTIES']['LINK']['VALUE'] . '\')" style="cursor: pointer;"' : '' ?>>
                            <a class="btn-more btn-more-2" href="#">

                            </a>
                            <span>
                                <img class="img-responsive" src="<?= $arResult['ELEM'][7]['PREVIEW_PICTURE']['SRC'] ?>">
                            </span>
                            <span class="plashka"></span>
                        </div>

                    <?else:?>

                        <div class="prod-item" <?= $arResult['ELEM'][7]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $arResult['ELEM'][7]['PROPERTIES']['LINK']['VALUE'] . '\'" style="cursor: pointer;"' : '' ?>>
                            <a class="btn-more btn-more-2" <?= $arResult['ELEM'][7]['PROPERTIES']['LINK']['VALUE'] ? 'href="' . $arResult['ELEM'][7]['PROPERTIES']['LINK']['VALUE'] . '"' : '' ?>>

                            </a>
                            <span>
                                    <img class="img-responsive" src="<?= $arResult['ELEM'][7]['PREVIEW_PICTURE']['SRC'] ?>">
                                </span>
                            <span class="plashka"></span>
                        </div>

                    <?endif;?>


                </div>
                <div class="col-xs-3">

                    <?if($arResult['ELEM'][8]['PROPERTIES']['TARGET']['VALUE'] == 'Да'):?>

                        <div class="prod-item" <?= $arResult['ELEM'][8]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="window.open(\'' . $arResult['ELEM'][8]['PROPERTIES']['LINK']['VALUE'] . '\')" style="cursor: pointer;"' : '' ?>>
                            <a class="btn-more btn-more-2" href="#">

                            </a>
                            <span>
                                <img class="img-responsive" src="<?= $arResult['ELEM'][8]['PREVIEW_PICTURE']['SRC'] ?>">
                            </span>
                            <span class="plashka"></span>
                        </div>
                    <?else:?>
                        <div class="prod-item" <?= $arResult['ELEM'][8]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $arResult['ELEM'][8]['PROPERTIES']['LINK']['VALUE'] . '\'" style="cursor: pointer;"' : '' ?>>
                            <a class="btn-more btn-more-2" <?= $arResult['ELEM'][8]['PROPERTIES']['LINK']['VALUE'] ? 'href="' . $arResult['ELEM'][8]['PROPERTIES']['LINK']['VALUE'] . '"' : '' ?>>

                            </a>
                            <span>
                                    <img class="img-responsive" src="<?= $arResult['ELEM'][8]['PREVIEW_PICTURE']['SRC'] ?>">
                                </span>
                            <span class="plashka"></span>
                        </div>
                    <?endif;?>

                </div>
            </div>
            <div class="col-xs-6 col-sm-3">


                <?if($arResult['ELEM'][9]['PROPERTIES']['TARGET']['VALUE'] == 'Да'):?>

                    <div class="prod-item" <?= $arResult['ELEM'][9]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="window.open(\'' . $arResult['ELEM'][9]['PROPERTIES']['LINK']['VALUE'] . '\')" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" href="#">

                        </a>
                        <span>
                            <img class="img-responsive" src="<?= $arResult['ELEM'][9]['PREVIEW_PICTURE']['SRC'] ?>">
                        </span>
                        <span class="plashka"></span>
                    </div>
                <?else:?>
                    <div class="prod-item" <?= $arResult['ELEM'][9]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $arResult['ELEM'][9]['PROPERTIES']['LINK']['VALUE'] . '\'" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" <?= $arResult['ELEM'][9]['PROPERTIES']['LINK']['VALUE'] ? 'href="' . $arResult['ELEM'][9]['PROPERTIES']['LINK']['VALUE'] . '"' : '' ?>>

                        </a>
                        <span>
                                <img class="img-responsive" src="<?= $arResult['ELEM'][9]['PREVIEW_PICTURE']['SRC'] ?>">
                            </span>
                        <span class="plashka"></span>
                    </div>
                <?endif;?>

                <?if($arResult['ELEM'][10]['PROPERTIES']['TARGET']['VALUE'] == 'Да'):?>
                    <div class="prod-item" <?= $arResult['ELEM'][10]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="window.open(\'' . $arResult['ELEM'][10]['PROPERTIES']['LINK']['VALUE'] . '\')" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" href="#">

                        </a>
                        <span>
                            <img class="img-responsive" src="<?= $arResult['ELEM'][10]['PREVIEW_PICTURE']['SRC'] ?>">
                        </span>
                        <span class="plashka"></span>
                    </div>
                <?else:?>
                    <div class="prod-item" <?= $arResult['ELEM'][10]['PROPERTIES']['LINK']['VALUE'] ? 'onclick="location=\'' . $arResult['ELEM'][10]['PROPERTIES']['LINK']['VALUE'] . '\'" style="cursor: pointer;"' : '' ?>>
                        <a class="btn-more btn-more-2" <?= $arResult['ELEM'][10]['PROPERTIES']['LINK']['VALUE'] ? 'href="' . $arResult['ELEM'][10]['PROPERTIES']['LINK']['VALUE'] . '"' : '' ?>>

                        </a>
                        <span>
                                <img class="img-responsive" src="<?= $arResult['ELEM'][10]['PREVIEW_PICTURE']['SRC'] ?>">
                            </span>
                        <span class="plashka"></span>
                    </div>
                <?endif;?>
            </div>
        </div>
    </div>
</section>



<div style="clear: both"></div>

*/?>