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
?>

<? foreach ($arResult['ACTION'] as $key => $val):?>

    <?if($key == 'ACTION_PERSON' || $key == 'ACTION_DELIVERY'):
        $ya_id = SITE_ID == 's1' ? 45947409 : 52969687 ?>
        <script type="text/javascript">
            $(document).ready(function () {
                setTimeout(function() {
                    window.yaCounter<?=$ya_id?>.reachGoal('<?=$val?>');
                    ga('send', 'event', '<?=$val?>', 'Press');
                }, 1000);
            });
        </script>
    <?endif;?>

<?endforeach;?>