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
//$this->setFrameMode(true);
?>
<div class="contacts">
	<?if(!empty($arResult["ADDRESS"])):?>
		<div class="contacts__item contacts__address">
			<div class="contacts__ttl">Наш адрес:</div>
			<?=$arResult["ADDRESS"]?>
		</div>
	<?endif;?>
	<?if(!empty($arResult["FAX"])):?>
		<div class="contacts__item contacts__fax">
			<div class="contacts__ttl">Телефон-факс:</div>
			<?foreach($arResult["FAX"] as $arItem):?>
				<a href="tel:<?=$arItem?>"><?=$arItem?></a><br>
			<?endforeach;?>
		</div>
	<?endif;?>
	<?if(!empty($arResult["PHONE"])):?>
		<div class="contacts__item contacts__phone">
			<div class="contacts__ttl">Телефон:</div>
			<?foreach($arResult["PHONE"] as $arItem):?>
				<a href="tel:<?=$arItem?>"><?=$arItem?></a><br>
			<?endforeach;?>
		</div>
	<?endif;?>
	<?if(!empty($arResult["EMAIL"])):?>
		<div class="contacts__item contacts__email">
			<div class="contacts__ttl">Email:</div>
			<?foreach($arResult["EMAIL"] as $arItem):?>
				<a href="mailto:<?=$arItem?>"><?=$arItem?></a><br>
			<?endforeach;?>
		</div>
	<?endif;?>
</div>