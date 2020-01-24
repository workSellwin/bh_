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
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

if($arResult["NavPageNomer"] < $arResult["nEndPage"]):?>
        <div class="bottom-block ajax-pagi" id="ts-pager-container">
			<a
					id="ts-pager-text"
					href="javascript:;"
					title="<?=Loc::getMessage("T_REVIEWS_MORE")?>"
					class="btn btn_border"
			><?=Loc::getMessage("T_PAGI_MORE")?></a>
		</div>
<?endif?>
<script>
var pager = new Pager(<?=Bitrix\Main\Web\Json::encode($arResult)?>);
	BX.message({
		'T_PAGI_MORE': '<?=Loc::getMessage("T_PAGI_MORE")?>',
		'T_PAGI_LOADING': '<?=Loc::getMessage("T_PAGI_LOADING")?>'
	});
</script>