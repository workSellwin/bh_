<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;


?>
<style>
    .box-gift-img2{
        display: block;
    }
    .box-gift-img1{
        display: block;
    }
</style>

<?

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */
$dbBasketItems = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID" => "ASC"
    ),
    array(
        "LID" => $arResult["ORDER"]["LID"],
        "ORDER_ID" =>$arResult["ORDER"]["ID"],
    ),
    false,
    false,
    array("ID", 'NAME', 'PRICE', 'CURRENCY', 'QUANTITY', )
);
$prod = [];
$all_prise = 0;
while ($arItems = $dbBasketItems->Fetch())
{
    $prod[$arItems['ID']]['id'] = $arItems['ID'];
    $prod[$arItems['ID']]['name'] = $arItems['NAME'];
    $prod[$arItems['ID']]['price'] = $arItems['PRICE'] * $arItems['QUANTITY'];
    $prod[$arItems['ID']]['quantity'] = $arItems['QUANTITY'];
    $all_prise +=$arItems['PRICE'];

}
$prod = array_values($prod);




if ($arParams["SET_TITLE"] == "Y")
{
	$APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));
}
?>

<? if (!empty($arResult["ORDER"])): ?>

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=Loc::getMessage("SOA_ORDER_SUC", array(
					"#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"],
					"#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]
				))?>
				<?/* if (!empty($arResult['ORDER']["PAYMENT_ID"])): ?>
					<?=Loc::getMessage("SOA_PAYMENT_SUC", array(
						"#PAYMENT_ID#" => $arResult['PAYMENT'][$arResult['ORDER']["PAYMENT_ID"]]['ACCOUNT_NUMBER']
					))?>
				<? endif */?>
				<br /><br />
				<?=Loc::getMessage("SOA_ORDER_SUC1", array("#LINK#" => $arParams["PATH_TO_PERSONAL"]))?>
				<br /><br />
				<p>В случае возникновения трудностей с оформлением заказа на сайте, можете обращаться к нашим менеджерам по телефону: <b>7577</b></p>
				<br>
				График работы:<br>
				пн. - пт. – с 10:30 до 18:00<br>
				сб,  вс. – выходной

			</td>
		</tr>
	</table>

	<?
    if(isset($_REQUEST['ORDER_ID']) && !empty($_REQUEST['ORDER_ID'])){
        $APPLICATION->IncludeComponent(
            "bh.by:analytics_order",
            "main",
            array(
                "ORDER_ID" => isset($_REQUEST['ORDER_ID']) && !empty($_REQUEST['ORDER_ID']) ? $_REQUEST['ORDER_ID'] : '',
            ),
            false
        );
    }
	if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y')
	{
		if (!empty($arResult["PAYMENT"]))
		{
			foreach ($arResult["PAYMENT"] as $payment)
			{
				if ($payment["PAID"] != 'Y')
				{
					if (!empty($arResult['PAY_SYSTEM_LIST'])
						&& array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
					)
					{
						$arPaySystem = $arResult['PAY_SYSTEM_LIST'][$payment["PAY_SYSTEM_ID"]];

						if (empty($arPaySystem["ERROR"]))
						{
							?>
							<br /><br />

							<table class="sale_order_full_table">
								<tr>
									<td class="ps_logo">
										<div class="pay_name"><?=Loc::getMessage("SOA_PAY") ?></div>
										<?=CFile::ShowImage($arPaySystem["LOGOTIP"], 100, 100, "border=0\" style=\"width:100px\"", "", false) ?>
										<div class="paysystem_name"><?=$arPaySystem["NAME"] ?></div>
										<br/>
									</td>
								</tr>
								<tr>
									<td>
										<? if (strlen($arPaySystem["ACTION_FILE"]) > 0 && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y"): ?>
											<?
											$orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
											$paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
											?>
											<script>
												window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
											</script>
										<?=Loc::getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&PAYMENT_ID=".$paymentAccountNumber))?>
										<? if (CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF']): ?>
										<br/>
											<?=Loc::getMessage("SOA_PAY_PDF", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&pdf=1&DOWNLOAD=Y"))?>
										<? endif ?>
										<? else: ?>
											<?=$arPaySystem["BUFFERED_OUTPUT"]?>
										<? endif ?>
									</td>
								</tr>
							</table>

							<?
						}
						else
						{
							?>
							<span style="color:red;"><?=Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
							<?
						}
					}
					else
					{
						?>
						<span style="color:red;"><?=Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
						<?
					}
				}
			}
		}
	}
	else
	{
		?>
		<br /><strong><?=$arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR']?></strong>
		<?
	}
	?>

<? else: ?>

	<b><?=Loc::getMessage("SOA_ERROR_ORDER")?></b>
	<br /><br />

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=Loc::getMessage("SOA_ERROR_ORDER_LOST", array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
				<?=Loc::getMessage("SOA_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
	</table>

<? endif ?>




<script type="text/javascript">
    $(document).ready(function () {
        var products =<?echo CUtil::PhpToJSObject($prod)?>;//Преобразует массив PHP в js
        dataLayer.push({
            "ecommerce": {
                "purchase": {
                    "actionField": {
                        "id": "<?=$arResult["ORDER"]["ID"]?>"
                    },
                    "products": products
                }
            }
        });

        //СБОР ДАННЫХ ДЛЯ E-COMMERCE С ПОМОЩЬЮ WEB API
        carrotquest.track('$order_completed', {
            "$order_id": '<?=$arResult["ORDER"]["ID"]?>',
            "$order_id_human": '<?=$arResult["ORDER"]["ID"]?>',
            "$order_amount": '<?=$all_prise?>'
        });
        carrotquest.identify([
            {op: "add", key: "$orders_count", value: 1},
            {op: "add", key: "$revenue", value: '<?=$all_prise?>'},
            {op: "update_or_create", key: "$last_payment", value: '<?=$all_prise?>'}
        ]);
        //---------------------------------------------------------

    });
</script>
