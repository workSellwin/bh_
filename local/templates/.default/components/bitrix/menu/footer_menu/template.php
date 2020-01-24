<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true); ?>
<?/* if (!empty($arResult)): ?>
    <nav class="f__menu cl">
        <?
        $previousLevel = 0;
        foreach ($arResult

        as $arItem): ?>
        <? if ($arItem['TEXT'] == 'Парфюмерия' && $_SERVER['HTTP_HOST'] == 'all.bh.by'): ?>
        <noindex>
            <? endif;
            ?>
            <? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
                <?= str_repeat("</div>", ($previousLevel - $arItem["DEPTH_LEVEL"])); ?>
            <? endif ?>
            <? if ($arItem['TEXT'] == 'Покупателям' && $_SERVER['HTTP_HOST'] == 'all.bh.by'): ?>
        </noindex>
    <? endif;
    ?>
        <? if ($arItem["IS_PARENT"]): ?>

        <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
        <div class="f__menu-item <?= ($arItem["PARAMS"]["UF_HIDE_MENU"]) ? "hide" : "" ?>">
            <div class="f__menu-ttl"><a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a></div>

            <? else: ?>
            <li<? if ($arItem["SELECTED"]): ?> class="item-selected <?= ($arItem["PARAMS"]["UF_HIDE_MENU"]) ? "hide" : "" ?>"<? endif ?>>
                <a href="<?= $arItem["LINK"] ?>" class="parent"><?= $arItem["TEXT"] ?></a>
                <ul>
                    <? endif ?>

                    <? else: ?>

                        <? if ($arItem["PERMISSION"] > "D"): ?>

                            <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
                                <div class="f__menu-item f__menu-ttl <?= ($arItem["PARAMS"]["UF_HIDE_MENU"]) ? "hide" : "" ?>">
                                    <a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a></div>
                            <? else: ?>
                                <a class="f__menu-lnk <?= ($arItem["PARAMS"]["UF_HIDE_MENU"]) ? "hide" : "" ?>"
                                   href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
                            <? endif ?>

                        <? else: ?>

                            <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
                                <div class="f__menu-item f__menu-ttl <?= ($arItem["PARAMS"]["UF_HIDE_MENU"]) ? "hide" : "" ?>">
                                    <a href=""
                                       title="<?= GetMessage("MENU_ITEM_ACCESS_DENIED") ?>"><?= $arItem["TEXT"] ?></a>
                                </div>
                            <? else: ?>
                                <a class="f__menu-lnk <?= ($arItem["PARAMS"]["UF_HIDE_MENU"]) ? "hide" : "" ?>" href=""
                                   title="<?= GetMessage("MENU_ITEM_ACCESS_DENIED") ?>"><?= $arItem["TEXT"] ?></a>
                            <? endif ?>

                        <? endif ?>

                    <? endif ?>

                    <? $previousLevel = $arItem["DEPTH_LEVEL"]; ?>

                    <? endforeach ?>

                    <? if ($previousLevel > 1): ?>
                        <?= str_repeat("</div>", ($previousLevel - 1)); ?>
                    <? endif ?>


    </nav>
<? endif */?>


    <? $arResult = getChilds($arResult); ?>
    <? if (!empty($arResult)): ?>
        <nav class="f__menu cl">



            <?foreach ($arResult as $arItems):?>
                <?if(!$arItems['PARAMS']['UF_HIDE_MENU']):?>
                    <div class="f__menu-item ">
                        <div class="f__menu-ttl">
                            <a href="<?=$arItems['LINK']?>"><?=$arItems['TEXT']?></a>
                        </div>
                        <?if(!empty($arItems['CHILD'])):?>
                            <?foreach ($arItems['CHILD'] as $item):?>
                                <?if(!$item['PARAMS']['UF_HIDE_MENU']):?>
                                    <a class="f__menu-lnk " href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
                                <?endif;?>
                            <?endforeach;?>
                        <?endif;?>
                    </div>
                <?endif;?>
            <?endforeach;?>
        </nav>
    <? endif ?>