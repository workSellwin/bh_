<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<?
	$cur_page=$APPLICATION->GetCurPage(false);
	$arrPage=explode('/',$cur_page);
?>
<?if (!empty($arResult)):?>
<div class="catalog-left__item">
	<div class="js-left-nav-btn">Сортировка по разделам<span></span></div>
	<nav class="left-menu js-left-nav">
		<?
		$previousLevel = 0;
		foreach($arResult as $arItem):?>
			<?if(strpos($arItem["LINK"],"/" . $arrPage[2] . "/") ):?>
				<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
					<?=str_repeat("</div></div>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
				<?endif?>

				<?if ($arItem["IS_PARENT"]):?>

					<?if ($arItem["DEPTH_LEVEL"] == 2):?>
						<div class="left-menu__item <?=($arItem["PARAMS"]["UF_HIDE_MENU"])?"hide":""?>" >
							<a class="left-menu__lnk <?=($arItem["SELECTED"])?"left-menu__lnk_active":""?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
							<div class="left-submenu">
					<?elseif($arItem["DEPTH_LEVEL"] != 1):?>
						<div class="left-menu__item <?=($arItem["PARAMS"]["UF_HIDE_MENU"])?"hide":""?>">
							<a class="left-menu__lnk <?=($arItem["SELECTED"])?"left-menu__lnk_active":""?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
							<div class="left-submenu">
					<?endif?>

				<?else:?>
					<?if ($arItem["DEPTH_LEVEL"] == 2):?>
						<div class="left-menu__item <?=($arItem["PARAMS"]["UF_HIDE_MENU"])?"hide":""?>"><a class="left-menu__lnk no-ico <?=($arItem["SELECTED"])?"left-menu__lnk_active":""?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></div>
					<?elseif($arItem["DEPTH_LEVEL"] != 1):?>
						<a class="left-submenu__lnk <?=($arItem["SELECTED"])?"left-submenu__lnk_active":""?> <?=($arItem["PARAMS"]["UF_HIDE_MENU"])?"hide":""?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
					<?endif?>
				<?endif?>
				<?$previousLevel = $arItem["DEPTH_LEVEL"];?>
			<?endif;?>
		<?endforeach?>

		<?if ($previousLevel > 4)://close last item tags?>
			<?=str_repeat("</div></div>", ($previousLevel-1) );?>
		<?endif?>
	</nav>
</div>
<?endif?>