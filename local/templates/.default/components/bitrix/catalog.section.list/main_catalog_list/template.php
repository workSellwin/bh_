<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
$this->setFrameMode(true); ?>
<? //s1 = 3 ; s2 = 4?>


<?
$redirect = [
    '3' => [
        'SITE' => 's1',
        'HOST' => 'bh.by'
    ],
    '4' => [
        'SITE' => 's2',
        'HOST' => 'all.bh.by'
    ],
];
?>

<?// $arResult['SECTIONS'] = getChilds($arResult['SECTIONS']) ?>

    <div class="container my-list-catalog">
        <div class="row">
            <div class=" cl">
                <nav class="cl">
                    <? foreach ($arResult['SECTIONS'] as $arItems): ?>
                        <?if($arItems['UF_HIDE_CATALOG'] != 1):?>
                        <div class="f__menu-item ">
                            <div class="f__menu-ttl">
                                <a href="<?=$_SERVER['REQUEST_SCHEME']?>://<?=$redirect[$arItems['UF_SITE'][0]]['HOST'] . $arItems['SECTION_PAGE_URL'] ?>"><?= $arItems['NAME'] ?></a>
                            </div>
                            <? foreach ($arItems['CHILD'] as $item): ?>
                                <?if($item['UF_HIDE_CATALOG'] != 1):?>
                                    <a class="f__menu-lnk " href="<?=$_SERVER['REQUEST_SCHEME']?>://<?=$redirect[$arItems['UF_SITE'][0]]['HOST'] . $item['SECTION_PAGE_URL'] ?>"><?= $item['NAME'] ?></a>
                                <?endif;?>
                            <? endforeach; ?>
                        </div>
                    <?endif;?>
                    <? endforeach; ?>
                </nav>
            </div>
        </div>
    </div>