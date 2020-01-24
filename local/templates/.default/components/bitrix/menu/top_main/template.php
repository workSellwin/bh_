<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>

<?if (!empty($arResult)):?>
	<nav class="main-menu">
		<?
		foreach($arResult as $arItem):
			if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
				continue;
			$class = '';
			switch ($arItem["LINK"]){
				case '/aktsii/' : $class = 'main-lnk_red'; break;
				case '/catalog/podarki/' : $class = 'main-lnk_black'; break;
			}
		?>
			<a class="main-lnk <?=$class?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
			<?/*if($arItem["SELECTED"]):?>
				<li><a href="<?=$arItem["LINK"]?>" class="selected"><?=$arItem["TEXT"]?></a></li>
			<?else:?>
				<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
			<?endif*/?>
		<?endforeach?>
	</nav>
<?endif?>