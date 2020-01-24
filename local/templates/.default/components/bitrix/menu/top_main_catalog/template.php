<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<?//$arResult = getChilds($arResult)?>
<?if (!empty($arResult)):?>
    <!--start_content-->
    <div class="nav-main-lnks-my">
            <ul class="topmenu">
            <? foreach ($arResult as $arItem): ?>
                <li>
                    <a href="<?= $arItem['LINK'] ?>" class=""><?=$arItem["TEXT"]?>
                        <?if($arItem['CHILD']):?>
                            <span class="fa fa-angle-down"></span>
                        <?endif;?>
                    </a>
                    <? if ($arItem['CHILD']): ?>
                        <ul class="submenu">
                            <? foreach ($arItem['CHILD'] as $val): ?>
                                <li><a href="<?=$val['SECTION_PAGE_URL']?>"><?=$val["NAME"]?></a></li>
                            <? endforeach ?>
                        </ul>
                    <? endif; ?>
                </li>
            <? endforeach ?>
        </ul>
    </div>
<?endif?>

<?//PR($arResult)?>

<?if (!empty($arResult)):?>
<!--start_content-->
	<div class="nav-main-lnks" style="display: none">
		<?
		foreach($arResult as $arItem):
			if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
				continue; ?>
			<a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>

		<?endforeach?>
	</div>
<?endif?>







