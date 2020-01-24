<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$this->IncludeLangFile('template.php');

$cartId = $arParams['cartId'];

require(realpath(dirname(__FILE__)).'/top_template.php');

if ($arParams["SHOW_PRODUCTS"] == "Y" && $arResult['NUM_PRODUCTS'] > 0)
{
?>

	<div class="h__cart cl" data-role="basket-item-list">

		<div id="<?=$cartId?>products" class="bx-basket-item-list-container h__cart-inner">
			<div class="h__items-prod">
				<?foreach ($arResult["CATEGORIES"] as $category => $items):
					if (empty($items))
						continue;
					?>
					<?foreach ($items as $v):?>
						<div class="item-prod">
							<div class="h__cart-del js-btn-basket-link" onclick="<?=$cartId?>.removeItemFromCart(<?=$v['ID']?>)" title="<?=GetMessage("TSB1_DELETE")?>"></div>
							<a class="h__cart-prod" href="<?=$v["DETAIL_PAGE_URL"]?>">
								<?if ($arParams["SHOW_IMAGE"] == "Y"):?>
									<span class="h__cart-prod-img">
										<img src="<?=(!empty($v["PICTURE_SRC"]))?$v["PICTURE_SRC"]:SITE_TEMPLATE_PATH."/images/no_photo_mini.png"?>" alt="<?//=$v["NAME"]?>">
									</span>
								<?endif?>
								<span class="h__cart-info">
									<span class="h__cart-name"><?=$v["NAME"]?></span>
									<span class="h__cart-price">
										<?if (true):?>
											<?if ($arParams["SHOW_PRICE"] == "Y"):?>
												<span><?=$v["PRICE_FMT"]?></span>
												<?if ($v["FULL_PRICE"] != $v["PRICE_FMT"]):?>
													<span class="bx-basket-item-list-item-price-old"><?=$v["FULL_PRICE"]?></span>
												<?endif?>
											<?endif?>
											<?if ($arParams["SHOW_SUMMARY"] == "Y"):?>
												<span class="bx-basket-item-list-item-price-summ">
													<?=$v["QUANTITY"]?> <?=$v["MEASURE_NAME"]?> <?=GetMessage("TSB1_SUM")?> <?=$v["SUM"]?>
												</span>
											<?endif?>
										<?endif?>
									</span>
								</span>
							</a>
						</div>
					<?endforeach?>
				<?endforeach?>
			</div>
			<a class="btn btn_black" href="<?=$arParams["PATH_TO_BASKET"]?>">Перейти в корзину</a>
		</div>
	</div>
	<script>
		BX.ready(function(){
			<?=$cartId?>.fixCart();
			$('.h__items-prod').mCustomScrollbar();
		});
	</script>
<?
}