<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @var array $arUrls */

/** @var array $arHeaders */

use Bitrix\Sale\DiscountCouponsManager;

// if (!empty($arResult["ERROR_MESSAGE"]))
// 	ShowError($arResult["ERROR_MESSAGE"]);

$bDelayColumn = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn = false;
$bPriceType = false;
$prod_basket_id = [];

if ($normalCount > 0):
    ?>
    <div id="basket_items_list" xmlns="http://www.w3.org/1999/html">
        <div class="bx_ordercart_order_table_container">
            <table id="basket_items">
                <thead>
                <tr>
                    <?
                    foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
                        $arHeaders[] = $arHeader["id"];

                        // remember which values should be shown not in the separate columns, but inside other columns
                        if (in_array($arHeader["id"], array("TYPE"))) {
                            $bPriceType = true;
                            continue;
                        } elseif ($arHeader["id"] == "PROPS") {
                            $bPropsColumn = true;
                            continue;
                        } elseif ($arHeader["id"] == "DELAY") {
                            $bDelayColumn = true;
                            continue;
                        } elseif ($arHeader["id"] == "DELETE") {
                            $bDeleteColumn = true;
                            continue;
                        } elseif ($arHeader["id"] == "WEIGHT") {
                            $bWeightColumn = true;
                        }

                        if ($arHeader["id"] == "NAME"):
                            ?>
                            <td class="item" colspan="2" id="col_<?= $arHeader["id"]; ?>">
                        <?
                        elseif ($arHeader["id"] == "PRICE"):
                            ?>
                            <td class="price" id="col_<?= $arHeader["id"]; ?>">
                        <?
                        else:
                            ?>
                            <td class="custom" id="col_<?= $arHeader["id"]; ?>">

                        <?
                        endif;
                        ?>
                        <?= $arHeader["name"]; ?>
                        </td>
                        <?//TODO добавляем НДС к цене 77777
                        ?>
                        <?
                        if ($arResult['ITEMS']['AnDelCanBuy'][0]['PRICE_TYPE_ID'] == 2 && $arHeader["id"] == 'SUM'):?>
                            <td class="custom gmi-nds" id="col_<?= $arHeader["id"]; ?>_nds">Сумма с НДС</td>
                        <? endif; ?>
                    <?
                    endforeach;

                    if ($bDeleteColumn || $bDelayColumn):
                        ?>
                        <td class="custom"></td>
                    <?
                    endif;
                    ?>
                </tr>
                </thead>

                <tbody>
                <?
                $skipHeaders = array('PROPS', 'DELAY', 'DELETE', 'TYPE');

                foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):

                    $prod_basket_id[$arItem["PRODUCT_ID"]]['ID'] = $arItem["PRODUCT_ID"];
                    $prod_basket_id[$arItem["PRODUCT_ID"]]['BASKET_PRODUCT_ID'] = $arItem["ID"];
                    $prod_basket_id[$arItem["PRODUCT_ID"]]['DISCOUNT_PRICE'] = $arItem["DISCOUNT_PRICE_PERCENT"];
                    $prod_basket_id[$arItem["PRODUCT_ID"]]['PRISE'] = $arItem["SUM_VALUE"];

                    if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"):
                        ?>
                        <tr id="<?= $arItem["ID"] ?>"
                            data-item-name="<?= $arItem["NAME"] ?>"
                            data-item-brand="<?= $arItem[$arParams['BRAND_PROPERTY'] . "_VALUE"] ?>"
                            data-item-price="<?= $arItem["PRICE"] ?>"
                            data-item-currency="<?= $arItem["CURRENCY"] ?>"
                        >
                            <?
                            foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):

                                if (in_array($arHeader["id"], $skipHeaders)) // some values are not shown in the columns in this template
                                    continue;

                                if ($arHeader["name"] == '')
                                    $arHeader["name"] = GetMessage("SALE_" . $arHeader["id"]);

                                if ($arHeader["id"] == "NAME"):
                                    ?>
                                    <td class="itemphoto">
                                        <div class="bx_ordercart_photo_container">
                                            <?
                                            if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0):
                                                $url = $arItem["PREVIEW_PICTURE_SRC"];
                                            elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0):
                                                $url = $arItem["DETAIL_PICTURE_SRC"];
                                            else:
                                                $url = $templateFolder . "/images/no_photo.png";
                                            endif;

                                            if (strlen($arItem["DETAIL_PAGE_URL"]) > 0): ?><a
                                                    href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?
                                                endif;
                                                ?>
                                                <div class="bx_ordercart_photo"
                                                     style="background-image:url('<?= $url ?>')"></div>
                                                <?
                                                if (strlen($arItem["DETAIL_PAGE_URL"]) > 0): ?></a><?
                                        endif;
                                        ?>
                                        </div>
                                        <?
                                        if (!empty($arItem["BRAND"])):
                                            ?>
                                            <div class="bx_ordercart_brand">
                                                <img alt="" src="<?= $arItem["BRAND"] ?>"/>
                                            </div>
                                        <?
                                        endif;
                                        ?>
                                    </td>
                                    <td class="item">
                                        <h2 class="bx_ordercart_itemtitle">
                                            <?
                                            if (strlen($arItem["DETAIL_PAGE_URL"]) > 0): ?><a
                                                    href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?
                                                endif;
                                                ?>
                                                <?= $arItem["NAME"] ?>
                                                <?
                                                if (strlen($arItem["DETAIL_PAGE_URL"]) > 0): ?></a><?
                                        endif;
                                        ?>
                                        </h2>
                                        <?/*<div class="bx_ordercart_itemart">
										<?
										if ($bPropsColumn):
											foreach ($arItem["PROPS"] as $val):

												if (is_array($arItem["SKU_DATA"]))
												{
													$bSkip = false;
													foreach ($arItem["SKU_DATA"] as $propId => $arProp)
													{
														if ($arProp["CODE"] == $val["CODE"])
														{
															$bSkip = true;
															break;
														}
													}
													if ($bSkip)
														continue;
												}

												echo htmlspecialcharsbx($val["NAME"]).":&nbsp;<span>".$val["VALUE"]."</span><br/>";
											endforeach;
										endif;
										?>
									</div>
									<?
									if (is_array($arItem["SKU_DATA"]) && !empty($arItem["SKU_DATA"])):
										$propsMap = array();
										foreach ($arItem["PROPS"] as $propValue)
										{
											if (empty($propValue) || !is_array($propValue))
												continue;
											$propsMap[$propValue['CODE']] = (isset($propValue['~VALUE']) ? $propValue['~VALUE'] : $propValue['VALUE']);
										}
										unset($propValue);

										foreach ($arItem["SKU_DATA"] as $propId => $arProp):
											$selectedIndex = 0;
											// if property contains images or values
											$isImgProperty = false;
											if (!empty($arProp["VALUES"]) && is_array($arProp["VALUES"]))
											{
												$counter = 0;
												foreach ($arProp["VALUES"] as $id => $arVal)
												{
													$counter++;
													if (isset($propsMap[$arProp['CODE']]))
													{
														if ($propsMap[$arProp['CODE']] == $arVal['NAME'] || $propsMap[$arProp['CODE']] == $arVal['XML_ID'])
															$selectedIndex = $counter;
													}
													if (!empty($arVal["PICT"]) && is_array($arVal["PICT"])
														&& !empty($arVal["PICT"]['SRC']))
													{
														$isImgProperty = true;
													}
												}
												unset($counter);
											}
											$countValues = count($arProp["VALUES"]);
											$full = ($countValues > 5) ? "full" : "";

											$marginLeft = 0;
											if ($countValues > 5 && $selectedIndex > 5)
												$marginLeft = ((5 - $selectedIndex)*20).'%';

											if ($isImgProperty): // iblock element relation property
											?>
												<div class="bx_item_detail_scu_small_noadaptive <?=$full?>">
													<span class="bx_item_section_name_gray">
														<?=htmlspecialcharsbx($arProp["NAME"])?>:
													</span>
													<div class="bx_scu_scroller_container">

														<div class="bx_scu">
															<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>"
																style="width: 200%; margin-left: <?=$marginLeft; ?>"
																class="sku_prop_list"
																>
																<?
																$counter = 0;
																foreach ($arProp["VALUES"] as $valueId => $arSkuValue):
																	$counter++;
																	$selected = ($selectedIndex == $counter ? ' bx_active' : '');
																?>
																	<li style="width:10%;"
																		class="sku_prop<?=$selected?>"
																		data-sku-selector="Y"
																		data-value-id="<?=$arSkuValue["XML_ID"]?>"
																		data-sku-name="<?=htmlspecialcharsbx($arSkuValue["NAME"]); ?>"
																		data-element="<?=$arItem["ID"]?>"
																		data-property="<?=$arProp["CODE"]?>"
																	>
																		<a href="javascript:void(0)" class="cnt"><span class="cnt_item" style="background-image:url(<?=$arSkuValue["PICT"]["SRC"];?>)"></span></a>
																	</li>
																<?
																endforeach;
																unset($counter);
																?>
															</ul>
														</div>

														<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
														<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
													</div>

												</div>
											<?
											else:
											?>
												<div class="bx_item_detail_size_small_noadaptive <?=$full?>">
													<span class="bx_item_section_name_gray">
														<?=htmlspecialcharsbx($arProp["NAME"])?>:
													</span>
													<div class="bx_size_scroller_container">
														<div class="bx_size">
															<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>"
																style="width: 200%; margin-left: <?=$marginLeft; ?>;"
																class="sku_prop_list"
																>
																<?
																if (!empty($arProp["VALUES"]))
																{
																	$counter = 0;
																	foreach ($arProp["VALUES"] as $valueId => $arSkuValue):
																		$counter++;
																		$selected = ($selectedIndex == $counter ? ' bx_active' : '');
																	?>
																		<li style="width:10%;"
																			class="sku_prop<?=$selected?>"
																			data-sku-selector="Y"
																			data-value-id="<?=($arProp['TYPE'] == 'S' && $arProp['USER_TYPE'] == 'directory' ? $arSkuValue['XML_ID'] : htmlspecialcharsbx($arSkuValue['NAME'])); ?>"
																			data-sku-name="<?=htmlspecialcharsbx($arSkuValue["NAME"]); ?>"
																			data-element="<?=$arItem["ID"]?>"
																			data-property="<?=$arProp["CODE"]?>"
																		>
																			<a href="javascript:void(0)" class="cnt"><?=htmlspecialcharsbx($arSkuValue["NAME"]); ?></a>
																		</li>
																	<?
																	endforeach;
																	unset($counter);
																}
																?>
															</ul>
														</div>
														<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
														<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
													</div>

												</div>
											<?
											endif;
										endforeach;
									endif;
									*/
                                        ?>
                                    </td>
                                <?
                                elseif ($arHeader["id"] == "QUANTITY"):
                                    $main_ratio = 1;
                                    ?>
                                    <?
                                    $ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 1;
                                    $useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
                                    $useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");
                                    ?>
                                    <td class="custom">
                                        <div class="quantity">
                                            <a href="javascript:void(0);" class="minus"
                                               onclick="setQuantity(<?= $arItem["ID"] ?>, <?= $ratio ?>, 'down', <?= $useFloatQuantityJS ?>);"></a>

                                            <input
                                                    type="text"
                                                    size="3"
                                                    id="QUANTITY_INPUT_<?= $arItem["ID"] ?>"
                                                    name="QUANTITY_INPUT_<?= $arItem["ID"] ?>"
                                                    maxlength="18"
                                                    style="max-width: 50px"
                                                    value="<?= $arItem["QUANTITY"] ?>"
                                                    onchange="updateQuantity('QUANTITY_INPUT_<?= $arItem["ID"] ?>', '<?= $arItem["ID"] ?>', <?= $ratio ?>, <?= $useFloatQuantityJS ?>)"
                                            >
                                            <a href="javascript:void(0);" class="plus"
                                               onclick="setQuantity(<?= $arItem["ID"] ?>, <?= $ratio ?>, 'up', <?= $useFloatQuantityJS ?>);"></a>
                                        </div>
                                        <input type="hidden" id="QUANTITY_<?= $arItem['ID'] ?>"
                                               name="QUANTITY_<?= $arItem['ID'] ?>" value="<?= $arItem["QUANTITY"] ?>"/>
                                        <?/*<span><?=$arHeader["name"]; ?>:</span>
									<div class="centered">
										<table cellspacing="0" cellpadding="0" class="counter">
											<tr>
												<td>
													<?
													$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
													$useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
													$useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");
													?>
													<input
														type="text"
														size="3"
														id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
														name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
														maxlength="18"
														style="max-width: 50px"
														value="<?=$arItem["QUANTITY"]?>"
														onchange="updateQuantity('QUANTITY_INPUT_<?=$arItem["ID"]?>', '<?=$arItem["ID"]?>', <?=$ratio?>, <?=$useFloatQuantityJS?>)"
													>
												</td>
												<?
												if (!isset($arItem["MEASURE_RATIO"]))
												{
													$arItem["MEASURE_RATIO"] = 1;
												}

												if (
													floatval($arItem["MEASURE_RATIO"]) != 0
												):
												?>
													<td id="basket_quantity_control">
														<div class="basket_quantity_control">
															<a href="javascript:void(0);" class="plus" onclick="setQuantity(<?=$arItem["ID"]?>, <?=$arItem["MEASURE_RATIO"]?>, 'up', <?=$useFloatQuantityJS?>);"></a>
															<a href="javascript:void(0);" class="minus" onclick="setQuantity(<?=$arItem["ID"]?>, <?=$arItem["MEASURE_RATIO"]?>, 'down', <?=$useFloatQuantityJS?>);"></a>
														</div>
													</td>
												<?
												endif;
												if (isset($arItem["MEASURE_TEXT"]))
												{
													?>
														<td style="text-align: left"><?=htmlspecialcharsbx($arItem["MEASURE_TEXT"])?></td>
													<?
												}
												?>
											</tr>
										</table>
									</div>
									<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
									*/
                                        ?>
                                    </td>
                                <?
                                elseif ($arHeader["id"] == "PRICE"):
                                    ?>
                                    <td class="price">

                                        <div class="current_price" id="current_price_<?= $arItem["ID"] ?>">
                                            <?= $arItem["PRICE_FORMATED"] ?>
                                        </div>
                                        <div class="old_price" id="old_price_<?= $arItem["ID"] ?>">
                                            <?
                                            if (floatval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0):?>
                                                <?= $arItem["FULL_PRICE_FORMATED"] ?>
                                            <? endif; ?>
                                        </div>

                                        <?
                                        if ($bPriceType && strlen($arItem["NOTES"]) > 0):?>
                                            <div class="type_price"><?= GetMessage("SALE_TYPE") ?></div>
                                            <div class="type_price_value"><?= $arItem["NOTES"] ?></div>
                                        <? endif; ?>
                                    </td>
                                <?
                                elseif ($arHeader["id"] == "DISCOUNT"):
                                    ?>
                                    <td class="custom">
                                        <?/*<span><?=$arHeader["name"]; ?>:</span>*/
                                        ?>
                                        <div id="discount_value_<?= $arItem["ID"] ?>"><?= $arItem["DISCOUNT_PRICE_PERCENT_FORMATED"] ?></div>
                                    </td>
                                <?
                                elseif ($arHeader["id"] == "WEIGHT"):
                                    ?>
                                    <td class="custom">
                                        <span><?= $arHeader["name"]; ?>:</span>
                                        <?= $arItem["WEIGHT_FORMATED"] ?>
                                    </td>
                                <?
                                else:
                                    ?>
                                    <td class="<?= ($arHeader["id"] == "SUM") ? "sum" : "custom" ?>">
                                        <?/*<span><?=$arHeader["name"]; ?>:</span>*/
                                        ?>
                                        <?
                                        if ($arHeader["id"] == "SUM"):
                                        ?>
                                        <div id="sum_<?= $arItem["ID"] ?>">
                                            <?
                                            endif;

                                            echo $arItem[$arHeader["id"]];

                                            if ($arHeader["id"] == "SUM"):
                                            ?>
                                        </div>
                                    <?
                                    endif;
                                    ?>
                                    </td>
                                    <?//TODO добавляем НДС к цене 77777
                                    ?>
                                    <?
                                    if ($arItem['PRICE_TYPE_ID'] == 2 && $arHeader["id"] == 'SUM'):?>
                                        <?
                                        unset($sumProductNds);
                                        $nds = Mitlab::getProductNds($arItem['PRODUCT_ID']);
                                        $sumProductNds = $arItem['SUM_VALUE'] / 100 * $nds + $arItem['SUM_VALUE'];
                                        $arResult['ALL_SUM_NDS'] += $sumProductNds;
                                        ?>
                                        <td class="sum gmi-nds">
                                            <div id="sum_nds_<?= $arItem["ID"] ?>" data-sum-nds="<?= $sumProductNds ?>"
                                                 data-nds="<?= $nds ?>">
                                                <?= CurrencyFormat($sumProductNds, $arItem['CURRENCY']); ?>
                                            </div>
                                        </td>
                                    <? endif; ?>
                                <?
                                endif;
                            endforeach;

                            if ($bDelayColumn || $bDeleteColumn):
                                ?>
                                <td class="control">
                                    <a class="del-cart js-btn-basket-link"
                                       href="<?= str_replace("#ID#", $arItem["ID"], $arUrls["delete"]) ?>"
                                       onclick="return deleteProductRow(this)">
                                    </a>
                                </td>
                            <?
                            endif;
                            ?>
                        </tr>
                    <?
                    endif;
                endforeach;
                ?>
                </tbody>
            </table>
            <?/*<span id="spike-nail"></span>
		<script>
			$( document ).ready(function() {
				setTimeout(function(){
					$("#spike-nail").click();
				},5000);
			});
		</script>*/
            ?>
        </div>
        <input type="hidden" id="column_headers" value="<?= htmlspecialcharsbx(implode($arHeaders, ",")) ?>"/>
        <input type="hidden" id="offers_props"
               value="<?= htmlspecialcharsbx(implode($arParams["OFFERS_PROPS"], ",")) ?>"/>
        <input type="hidden" id="action_var" value="<?= htmlspecialcharsbx($arParams["ACTION_VARIABLE"]) ?>"/>
        <input type="hidden" id="quantity_float" value="<?= ($arParams["QUANTITY_FLOAT"] == "Y") ? "Y" : "N" ?>"/>
        <input type="hidden" id="price_vat_show_value"
               value="<?= ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N" ?>"/>
        <input type="hidden" id="hide_coupon" value="<?= ($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N" ?>"/>
        <input type="hidden" id="use_prepayment" value="<?= ($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N" ?>"/>
        <input type="hidden" id="auto_calculation" value="<?= ($arParams["AUTO_CALCULATION"] == "N") ? "N" : "Y" ?>"/>



        <?
        global $PRICE_GIFTS_ALL;
        $PRICE_GIFTS_ALL = $arResult["allSum"];
        global $PROD_BASKET_ID;
        $PROD_BASKET_ID = $prod_basket_id;

        ?>


        <div class="bx_ordercart_order_pay">


            <div class="bx_ordercart_order_pay_left" id="coupons_block">
                <?
                if ($arParams["HIDE_COUPON"] != "Y") {
                    ?>
                    <div class="bx_ordercart_coupon">
                    <span><?= GetMessage("STB_COUPON_PROMT") ?></span><input type="text" id="coupon" name="COUPON"
                                                                             value="" onchange="enterCoupon();">&nbsp;<a
                            class="bx_bt_button bx_big" href="javascript:void(0)" onclick="enterCoupon();"
                            title="<?= GetMessage('SALE_COUPON_APPLY_TITLE'); ?>"><?= GetMessage('SALE_COUPON_APPLY'); ?></a>
                    </div><?
                    if (!empty($arResult['COUPON_LIST'])) {
                        foreach ($arResult['COUPON_LIST'] as $oneCoupon) {
                            $couponClass = 'disabled';
                            switch ($oneCoupon['STATUS']) {
                                case DiscountCouponsManager::STATUS_NOT_FOUND:
                                case DiscountCouponsManager::STATUS_FREEZE:
                                    $couponClass = 'bad';
                                    break;
                                case DiscountCouponsManager::STATUS_APPLYED:
                                    $couponClass = 'good';
                                    break;
                            }
                            ?>
                            <div class="bx_ordercart_coupon">
                                <input disabled readonly type="text" name="OLD_COUPON[]" value="<?= htmlspecialcharsbx($oneCoupon['COUPON']); ?>" class="<? echo $couponClass; ?>">
                            <span class="<? echo $couponClass; ?>" data-coupon="<? echo htmlspecialcharsbx($oneCoupon['COUPON']); ?>"></span>
                                <?if($couponClass == 'disabled' && $oneCoupon['COUPON'] == 'LOVEBH'):?>
                                    <div class="message-text-cuopon">Внимание! Данный промокод дает скидку только на товары сайта bh.by (без учета акционных товаров) и не применяется для данного набора корзины</div>
                                <?endif;?>

                                <div class="bx_ordercart_coupon_notes"><?
                                if (isset($oneCoupon['CHECK_CODE_TEXT'])) {
                                    echo(is_array($oneCoupon['CHECK_CODE_TEXT']) ? implode('<br>', $oneCoupon['CHECK_CODE_TEXT']) : $oneCoupon['CHECK_CODE_TEXT']);
                                }
                                ?>
                            </div>
                            </div>
                            <?
                        }
                        unset($couponClass, $oneCoupon);
                    }
                } else {
                    ?>&nbsp;<?
                }
                ?>
            </div>


            <div class="bx_ordercart_order_pay_right">

                <table class="bx_ordercart_order_sum">
                    <?
                    if ($bWeightColumn && floatval($arResult['allWeight']) > 0):?>
                        <tr>
                            <td class="custom_t1"><?= GetMessage("SALE_TOTAL_WEIGHT") ?></td>
                            <td class="custom_t2" id="allWeight_FORMATED"><?= $arResult["allWeight_FORMATED"] ?>
                            </td>
                        </tr>
                    <? endif; ?>
                    <?
                    if ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y"):?>
                        <tr>
                            <td><?
                                echo GetMessage('SALE_VAT_EXCLUDED') ?></td>
                            <td id="allSum_wVAT_FORMATED"><?= $arResult["allSum_wVAT_FORMATED"] ?></td>
                        </tr>
                        <?
                        $showTotalPrice = (float)$arResult["DISCOUNT_PRICE_ALL"] > 0;
                        ?>
                        <tr style="display: <?= ($showTotalPrice ? 'table-row' : 'none'); ?>;">
                            <td class="custom_t1"></td>
                            <td class="custom_t2" style="text-decoration:line-through; color:#828282;"
                                id="PRICE_WITHOUT_DISCOUNT">
                                <?= ($showTotalPrice ? $arResult["PRICE_WITHOUT_DISCOUNT"] : ''); ?>
                            </td>
                        </tr>
                        <?
                        if (floatval($arResult['allVATSum']) > 0):
                            ?>
                            <tr>
                                <td><?
                                    echo GetMessage('SALE_VAT') ?></td>
                                <td id="allVATSum_FORMATED"><?= $arResult["allVATSum_FORMATED"] ?></td>
                            </tr>
                        <?
                        endif;
                        ?>
                        <tr class="gmi-nds hide">
                            <td>Сумма с НДС:</td>
                            <td id="allVATSum_FORMATED_NDS"
                                data-sum-value="<?= $arResult['ALL_SUM_NDS'] ?>"><?= CurrencyFormat($arResult['ALL_SUM_NDS'], $arResult['CURRENCY']) ?></td>
                        </tr>
                    <? endif; ?>
                    <tr style="display: none;">
                        <td class="fwb"><?= GetMessage("SALE_TOTAL") ?></td>
                        <td class="fwb"
                            id="allSum_FORMATED"><?= str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"]) ?></td>
                    </tr>
                    <?
                    $display = 'none';
                    if (!$arResult['COUPON'] and !$_REQUEST['coupon']) {
                        $display = $arResult["allSum"] < 30 ? "block" : "";
                    }
                    ?>
                    <tr class="min-sum-order" style="display:<?= $display ?>">
                        <?
                        $deliveryPrice = (SITE_ID == 's1') ? '30' : '30';
                        ?>



                            <?$sum = str_replace('.', 'руб. ',  30-$arResult["allSum"])?>
                            <td class="fwb" style="font-size: 27px">До бесплатной доставки осталось: <span class="sum"><?=30-$arResult["allSum"]?> руб.</span> (Минск) <br>
                                Cтоимость доставки: 5 руб.</td>

                            <?/*<td class="fwb">При сумме заказа от <?= $deliveryPrice ?> руб. доставка БЕСПЛАТНАЯ<br>При сумме
                                заказа менее <?= $deliveryPrice ?> руб. cтоимость доставки 5 руб.
                            </td>*/?>
                        

                    </tr><!-- Минимальная сумма заказа 30 руб. -->
                </table>
                <div style="clear:both;"></div>
            </div>

            <?/*if($USER->IsAdmin()):*/?><!--
                <?/*if(SITE_ID == 's1'):*/?>
                    <div id="gifts_s1">
                        <?/*$APPLICATION->IncludeComponent(
                "bh:sale.gifts.list",
                ".default",
                        array(
                            "COMPONENT_TEMPLATE" => ".default",
                            "PRICE" => $arResult["allSum"],
                            "IBLOCK_GIFTS_ID" => "30",
                            "THIS_ID" => $arResult['ID'],
                            'PROD_BASKET_ID' => $prod_basket_id,
                        ),
                        false
                        ); */?>
                    <div>
                <?/*endif;*/?>
            --><?/*endif;*/?>



            <div style="clear:both;"></div>

            <div class="bx_ordercart_order_pay_center" style="display: none;">

                <?
                if ($arParams["USE_PREPAYMENT"] == "Y" && strlen($arResult["PREPAY_BUTTON"]) > 0):?>
                    <?= $arResult["PREPAY_BUTTON"] ?>
                    <span><?= GetMessage("SALE_OR") ?></span>
                <? endif; ?>
                <?
                if ($arParams["AUTO_CALCULATION"] != "Y") {
                    ?>
                    <a href="javascript:void(0)" onclick="updateBasket();"
                       class="checkout refresh"><?= GetMessage("SALE_REFRESH") ?></a>
                    <?
                }
                ?>
                <a href="javascript:void(0)" onclick="checkOut();" class="checkout"><?= GetMessage("SALE_ORDER") ?></a>
            </div>
        </div>
    </div>
    <?
    CJSCore::Init(array('currency', 'fx'));
    $currencyFormat = CCurrencyLang::GetFormatDescription($arResult['CURRENCY']);
    ?>
    <script >
        BX.Currency.setCurrencyFormat('<?=$arResult['CURRENCY']?>', <? echo CUtil::PhpToJSObject($currencyFormat, false, true); ?>);
    </script>
<?
else:
    ?>
    <div id="basket_items_list">
        <?/*<table>
		<tbody>
			<tr>
				<td style="text-align:center">
					<div class=""><?=GetMessage("SALE_NO_ITEMS");?></div>
				</td>
			</tr>
		</tbody>
	</table>*/
        ?>
    </div>
<?
endif;

if ($_REQUEST['DEBUG'] == 'Y') {
    $arFull=GetDebugData($arResult['FULL_DISCOUNT_LIST']);
    $arThis=GetDebugData($arResult['APPLIED_DISCOUNT_LIST']);
    echo "<h2>Применились</h2>";
    ShowTableDebug($arThis);
    echo "<h2>Все</h2>";
    ShowTableDebug($arFull);
}


function ShowTableDebug($arDebug)
{
    $arDebug = array_values($arDebug);
    echo "<table class='table'>";
    foreach ($arDebug[0] as $td => $v) {
        echo "<th>{$td}</th>";
    }
    echo "</tr>";
    foreach ($arDebug as $tr) {
        echo "<tr>";
        foreach ($tr as $td) {
            echo "<td>{$td}</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function GetDebugData($data)
{
    $arDebug = [];
    foreach ($data as $d) {

        $arDebug[] = [
            'NAME' => $d['NAME'],
            'LID' => $d['LID'],
            'ID' => $d['ID'],
            'PRIORITY' => $d['PRIORITY'],
            'SORT' => $d['SORT'],
            'LAST_DISCOUNT' => $d['LAST_DISCOUNT'],
            'LAST_LEVEL_DISCOUNT' => $d['LAST_LEVEL_DISCOUNT'],
        ];
    }
    return $arDebug;
}
