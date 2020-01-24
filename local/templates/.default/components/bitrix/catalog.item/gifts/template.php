<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;
$this->setFrameMode(true);
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogProductsViewedComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

?>

<?

if (isset($arResult['ITEM'])) {?>
    <div  class="prod-item <?=$arParams['GIFTS']['ACTIVE'] == 'N' ? 'prod-item--disabled' : '' ?> <?=(isset($arResult['SCALABLE']) && $arResult['SCALABLE'] === 'Y' ? ' product-item-scalable-card' : '')?>"
        id="<?=$arResult['ITEM']['ID']?>"
            <?=$arParams['PROD_BASKET_ID'][$arResult['ITEM']['ID']] ? 'style="border: 1px solid #000000"' : '' ?>
          data-entity="item">
        <div class="prod-item__elem">
            <div class="prod-item__elem--img">
                <img src="<?=$arResult['ITEM']['PREVIEW_PICTURE']['SRC']?>">
            </div>
            <div class="prod-item-box">
                <div class="prod-item-box__text">
                    <p class="related__name"><?=$arResult['ITEM']['NAME']?></p>
                </div>
                <div class="prod-item-box__radio">
                    <input type="checkbox"
                           onclick="giftsClick(this)"
                           name="GIFTS"
                           value="<?=$arResult['ITEM']['ID']?>"
                        <?=$arParams['PROD_BASKET_ID'][$arResult['ITEM']['ID']] ? 'checked="checked"' : '' ?>
                        <?=$arParams['GIFTS']['ACTIVE'] == 'N' ? 'disabled' : '' ?>
                    >
                </div>
            </div>
        </div>
        <p class="related__learn-more"><a href="<?=$arResult['ITEM']['DETAIL_PAGE_URL']?>">Узнать больше</a></p>
    </div>
<?}?>


