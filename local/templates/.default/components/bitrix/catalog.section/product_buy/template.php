<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */

$this->setFrameMode(true);
//$this->addExternalCss('/bitrix/css/main/bootstrap.css');

if (!empty($arResult['NAV_RESULT']))
{
	$navParams =  array(
		'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
		'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
		'NavNum' => $arResult['NAV_RESULT']->NavNum
	);
}
else
{
	$navParams = array(
		'NavPageCount' => 1,
		'NavPageNomer' => 1,
		'NavNum' => $this->randString()
	);
}

$showTopPager = false;
$showBottomPager = false;
$showLazyLoad = false;

if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $navParams['NavPageCount'] > 1)
{
	$showTopPager = $arParams['DISPLAY_TOP_PAGER'];
	$showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];
	$showLazyLoad = $arParams['LAZY_LOAD'] === 'Y' && $navParams['NavPageNomer'] != $navParams['NavPageCount'];
}

$templateLibrary = array('popup', 'ajax', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

$positionClassMap = array(
	'left' => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right' => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top' => 'product-item-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$arParams['~MESS_BTN_BUY'] = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_SHOW_MAX_QUANTITY'] = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCS_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW');

$generalParams = array(
	'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
	'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
	'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
	'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
	'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
	'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
	'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
	'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
	'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
	'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
	'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
	'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
	'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
	'COMPARE_PATH' => $arParams['COMPARE_PATH'],
	'COMPARE_NAME' => $arParams['COMPARE_NAME'],
	'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
	'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
	'LABEL_POSITION_CLASS' => $labelPositionClass,
	'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
	'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
	'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
	'~BASKET_URL' => $arParams['~BASKET_URL'],
	'~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
	'~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
	'~COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
	'~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
	'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
	'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
	'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
	'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
	'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
	'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
	'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE']
);

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($navParams['NavNum']));
$containerName = 'container-'.$navParams['NavNum'];

?>

<style>
    .related__col .cmotka-plus {
        position: absolute;
        font-size: 35px;
        z-index: 9; top: 82px;
    }
    .related__col .cmotka-ravno {
        position: absolute;
        font-size: 35px;
        z-index: 9;
        top: 82px;
        right: 0;
    }
    .related__col .cmotka-btn {
        position: absolute;
        z-index: 9;
    }
    .related__col .cmotka-text {
        position: absolute;
        z-index: 9;
        top: 60px;
        right: -263px;
        font-size: 15px;
        width: 250px;
    }
    .cmotka-btn-span {
        position: absolute;
        z-index: 9;
        top: 90px;
        display: none;
        right: -208px;
    }
    .cmotka-text .star {
        position: relative;
        top: -5px;
        margin-right: 5px;
    }
</style>


<div class="related-wrp">
	<div class="related__ttl"><?=$arParams["TITLE"]?>:</div>
	<div class="related" data-entity="<?=$containerName?>">
		<?
		if (!empty($arResult['ITEMS']) && !empty($arResult['ITEM_ROWS']))
		{
			$areaIds = array();

			foreach ($arResult['ITEMS'] as $item)
			{
				$uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
				$areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
				$this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
				$this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
			}
			?>
			<!-- items-container -->
			<?$i = 1;
			foreach ($arResult['ITEM_ROWS'] as $rowData)
			{
			    if($i<=2) {
                    $rowItems = array_splice($arResult['ITEMS'], 0, $rowData['COUNT']); ?>

                    <div class="related__col"  data-entity="items-row" style="position: relative">
                        <?if($i == 2):?>
                            <span class="cmotka-plus">+</span>

                        <?endif?>
                        <?$item = reset($rowItems);
                        $APPLICATION->IncludeComponent(
                            'bitrix:catalog.item',
                            'product_buy',
                            array(
                                'RESULT' => array(
                                    'ITEM' => $item,
                                    'AREA_ID' => $areaIds[$item['ID']],
                                    'TYPE' => $rowData['TYPE'],
                                    'BIG_LABEL' => 'N',
                                    'BIG_DISCOUNT_PERCENT' => 'N',
                                    'BIG_BUTTONS' => 'N',
                                    'SCALABLE' => 'N'
                                ),
                                'PARAMS' => $generalParams
                                    + array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
                            ),
                            $component,
                            array('HIDE_ICONS' => 'Y')
                        );
                        ?>
                        <?if($i == 2):?>
                            <span class="cmotka-ravno" style="">=</span>

                                <p class="cmotka-text">Получите скидку <span class="dic_smotka">15</span> %<br>
                                    <?/*<span style="font-size: 35px" class="summa_smotka"></span><br>*/?>
                                    <span>на один из продуктов</span><br>
                                    <a  class="btn btn_ico cmotka-btn"  href="javascript:void(0)" data-dic-id="<?=$arParams['DIC_ID']?>" data-prod-id1="<?=$arParams['ARRAY_PRODUCT_ID'][0]?>" data-prod-id2="<?=$arParams['ARRAY_PRODUCT_ID'][1]?>" rel="nofollow">В корзину </a>
                                    <span style="display: none; right: 0; position: relative;top: 30px;" class="btn btn_in_basket btn_ico cmotka-btn-span">Товары в корзине</span>
                                    </span>
                                    <br>
                                    <span style="right: 0; position: relative;top: 55px; color: red" class=""><span class="star">*</span>Скидка не суммируется<br> со скидкой на бренд</span>
                                </p>


                        <?endif?>
                    </div>
                    <?

                }
                $i++;
			}
			unset($generalParams, $rowItems);
			?>
			<!-- items-container -->
			<?
		}
		else
		{
			// load css for bigData/deferred load
			$APPLICATION->IncludeComponent(
				'bitrix:catalog.item',
				'',
				array(),
				$component,
				array('HIDE_ICONS' => 'Y')
			);
		}
		?>
	</div>
</div>
<?
if ($showLazyLoad)
{
	?>
	<div class="row bx-<?=$arParams['TEMPLATE_THEME']?>">
		<div class="btn btn-default btn-lg center-block" style="margin: 15px;"
			data-use="show-more-<?=$navParams['NavNum']?>">
			<?=$arParams['MESS_BTN_LAZY_LOAD']?>
		</div>
	</div>
	<?
}

if ($showBottomPager)
{
	?>
	<div data-pagination-num="<?=$navParams['NavNum']?>">
		<!-- pagination-container -->
		<?=$arResult['NAV_STRING']?>
		<!-- pagination-container -->
	</div>
	<?
}
/*
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, 'catalog.section');
$signedParams = $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section');*/
?>
<!--<script>
	BX.message({
		BTN_MESSAGE_BASKET_REDIRECT: '<?/*=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')*/?>',
		BASKET_URL: '<?/*=$arParams['BASKET_URL']*/?>',
		ADD_TO_BASKET_OK: '<?/*=GetMessageJS('ADD_TO_BASKET_OK')*/?>',
		TITLE_ERROR: '<?/*=GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR')*/?>',
		TITLE_BASKET_PROPS: '<?/*=GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS')*/?>',
		TITLE_SUCCESSFUL: '<?/*=GetMessageJS('ADD_TO_BASKET_OK')*/?>',
		BASKET_UNKNOWN_ERROR: '<?/*=GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR')*/?>',
		BTN_MESSAGE_SEND_PROPS: '<?/*=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS')*/?>',
		BTN_MESSAGE_CLOSE: '<?/*=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE')*/?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?/*=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE_POPUP')*/?>',
		COMPARE_MESSAGE_OK: '<?/*=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_OK')*/?>',
		COMPARE_UNKNOWN_ERROR: '<?/*=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')*/?>',
		COMPARE_TITLE: '<?/*=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_TITLE')*/?>',
		PRICE_TOTAL_PREFIX: '<?/*=GetMessageJS('CT_BCS_CATALOG_PRICE_TOTAL_PREFIX')*/?>',
		RELATIVE_QUANTITY_MANY: '<?/*=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])*/?>',
		RELATIVE_QUANTITY_FEW: '<?/*=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])*/?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?/*=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')*/?>',
		BTN_MESSAGE_LAZY_LOAD: '<?/*=$arParams['MESS_BTN_LAZY_LOAD']*/?>',
		BTN_MESSAGE_LAZY_LOAD_WAITER: '<?/*=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_LAZY_LOAD_WAITER')*/?>',
		SITE_ID: '<?/*=SITE_ID*/?>'
	});
	var <?/*=$obName*/?> = new JCCatalogSectionComponent({
		siteId: '<?/*=CUtil::JSEscape(SITE_ID)*/?>',
		componentPath: '<?/*=CUtil::JSEscape($componentPath)*/?>',
		navParams: <?/*=CUtil::PhpToJSObject($navParams)*/?>,
		deferredLoad: false, // enable it for deferred load
		initiallyShowHeader: '<?/*=!empty($arResult['ITEM_ROWS'])*/?>',
		bigData: <?/*=CUtil::PhpToJSObject($arResult['BIG_DATA'])*/?>,
		lazyLoad: !!'<?/*=$showLazyLoad*/?>',
		loadOnScroll: !!'<?/*=($arParams['LOAD_ON_SCROLL'] === 'Y')*/?>',
		template: '<?/*=CUtil::JSEscape($signedTemplate)*/?>',
		ajaxId: '<?/*=CUtil::JSEscape($arParams['AJAX_ID'])*/?>',
		parameters: '<?/*=CUtil::JSEscape($signedParams)*/?>',
		container: '<?/*=$containerName*/?>'
	});
</script>-->


<script>
    $(document).ready(function(){

        $('body').on('click', '.cmotka-btn', function () {
            var prod_id_1 =  $(this).attr('data-prod-id1');
            var prod_id_2 =  $(this).attr('data-prod-id2');
            $.post(
                "/ajax/add_basket_smotki.php",
                {
                    prod_id_1: prod_id_1,
                    prod_id_2: prod_id_2
                },
                onAjaxSuccess
            );

            function onAjaxSuccess(data)
            {
                console.log(data);
                $('.cmotka-btn').hide();
                $('.cmotka-btn-span').show();
            }
        });

        var price_smotki = 0;
        var discount_smotka = $('.catalog-element-popup-element').text();
        var dic_id = $('.cmotka-btn').attr('data-dic-id');
        discount_smotka = discount_smotka.replace('скидку', '');
        discount_smotka = discount_smotka.replace('%', '');
        discount_smotka = +discount_smotka.trim();

        $("#BOX_PRODUCT_BUY_JS .prod-price__new").each(function(){
            if($(this).attr('data-id') == dic_id){
                console.log(dic_id);
                var p_s = +$(this).text().replace('руб.', '').trim();
                p_s = p_s / 100 * (100 - discount_smotka);
                price_smotki += p_s;
            }else{
                price_smotki += +$(this).text().replace('руб.', '').trim();
            }
        });
        price_smotki= price_smotki.toFixed(2);

        $('.dic_smotka').text(discount_smotka);
        $('.summa_smotka').text(price_smotki+' руб.');

    });
</script>