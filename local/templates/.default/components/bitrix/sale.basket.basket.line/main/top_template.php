<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global string $cartId
 */
$compositeStub = (isset($arResult['COMPOSITE_STUB']) && $arResult['COMPOSITE_STUB'] == 'Y');
?>
<a class="cart <?=(!empty($arParams["MAIN_WHITE"]))?"cart_white":""?>" href="<?=$arParams["PATH_TO_BASKET"]?>">
	<span class="cart-num">
		<span class="span"><?=$arResult["NUM_PRODUCTS"]?></span>
	</span>
</a>
<?
	if(empty($arParams["MAIN_WHITE"])){
		foreach ($arResult["CATEGORIES"]["READY"] as $value){
			$arElementsBasket[] = $value["PRODUCT_ID"];
		}
		//GmiPrint($arParams);
		?>
			<script>
				var arElementsBasket = <?=CUtil::PhpToJSObject ($arElementsBasket)?>;
				<?if($arParams["AJAX"] == "Y"):?>
					showBtnInBasket();
				<?endif;?>
			</script>
		<?
	}
?>