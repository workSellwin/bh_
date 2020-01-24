<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if(!$arResult["NavShowAlways"])
{
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}
ob_start();
?>
<div class="bx-pagination">
    <div class="bx-pagination-container">
        <ul>
            <?

            $strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
            $strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
            ?>

            <?
            if($arResult["bDescPageNumbering"] === true):
                $bFirst = true;
                if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                    if($arResult["bSavePage"]):
                        ?>
                        <li class="bx-pag-prev"><a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><span></span></a></li>


                    <?
                    else:
                        if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):
                            ?>
                            <li class="bx-pag-prev"><a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><span></span></a></li>

                        <?
                        else:
                            ?>

                            <li class="bx-pag-prev"><a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><span></span></a></li>
                        <?
                        endif;
                    endif;

                    if ($arResult["nStartPage"] < $arResult["NavPageCount"]):
                        $bFirst = false;
                        if($arResult["bSavePage"]):
                            ?>
                            <li><a class="modern-page-first" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>">1</a></li>
                        <?
                        else:
                            ?>
                            <li><a class="modern-page-first" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a></li>
                        <?
                        endif;
                        if ($arResult["nStartPage"] < ($arResult["NavPageCount"] - 1)):
                            /*?>
                                        <span class="modern-page-dots">...</span>
                            <?*/
                            ?>
                            <li><a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=intVal($arResult["nStartPage"] + ($arResult["NavPageCount"] - $arResult["nStartPage"]) / 2)?>">...</a></li>
                        <?
                        endif;
                    endif;
                endif;
                do
                {
                    $NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;

                    if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                        ?>
                        <li class="bx-active"><span class="<?=($bFirst ? "modern-page-first " : "")?>modern-page-current"><?=$NavRecordGroupPrint?></span></li>
                    <?
                    elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$NavRecordGroupPrint?></a></li>
                    <?
                    else:
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"<?
                            ?> class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$NavRecordGroupPrint?></a></li>
                    <?
                    endif;

                    $arResult["nStartPage"]--;
                    $bFirst = false;
                } while($arResult["nStartPage"] >= $arResult["nEndPage"]);

                if ($arResult["NavPageNomer"] > 1):
                    if ($arResult["nEndPage"] > 1):
                        if ($arResult["nEndPage"] > 2):
                            /*?>
                                    <span class="modern-page-dots">...</span>
                            <?*/
                            ?>
                            <li><a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nEndPage"] / 2)?>">...</a></li>
                        <?
                        endif;
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1"><?=$arResult["NavPageCount"]?></a></li>
                    <?
                    endif;

                    ?>
                    <li class="bx-pag-next"><a class="modern-page-next"href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><span></span></a></li>
                <?
                endif;

            else:
                $bFirst = true;

                if ($arResult["NavPageNomer"] > 1):
                    if($arResult["bSavePage"]):
                        ?>
                        <li class="bx-pag-prev"><a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><span></span></a></li>
                    <?
                    else:
                        if ($arResult["NavPageNomer"] > 2):
                            ?>
                            <li class="bx-pag-prev"><a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><span></span></a></li>
                        <?
                        else:
                            ?>
                            <li class="bx-pag-prev"><a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><span></span></a></li>
                        <?
                        endif;

                    endif;

                    if ($arResult["nStartPage"] > 1):
                        $bFirst = false;
                        if($arResult["bSavePage"]):
                            ?>
                            <li><a class="modern-page-first" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a></li>
                        <?
                        else:
                            ?>
                            <li><a class="modern-page-first" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a></li>
                        <?
                        endif;
                        if ($arResult["nStartPage"] > 2):
                            /*?>
                                        <span class="modern-page-dots">...</span>
                            <?*/
                            ?>
                            <li><a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nStartPage"] / 2)?>">...</a></li>
                        <?
                        endif;
                    endif;
                endif;

                do
                {
                    if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                        ?>
                        <li class="bx-active"><span class="<?=($bFirst ? "modern-page-first " : "")?>modern-page-current"><?=$arResult["nStartPage"]?></span></li>
                    <?
                    elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$arResult["nStartPage"]?></a></li>
                    <?
                    else:
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"<?
                            ?> class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$arResult["nStartPage"]?></a></li>
                    <?
                    endif;
                    $arResult["nStartPage"]++;
                    $bFirst = false;
                } while($arResult["nStartPage"] <= $arResult["nEndPage"]);

                if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
                    if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
                        if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):
                            /*?>
                                    <span class="modern-page-dots">...</span>
                            <?*/
                            ?>
                            <li><a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nEndPage"] + ($arResult["NavPageCount"] - $arResult["nEndPage"]) / 2)?>">...</a></li>
                        <?
                        endif;
                        ?>
                        <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a></li>
                    <?
                    endif;
                    ?>
                    <li class="bx-pag-next"><a class="modern-page-next" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><span></span></a></li>
                <?
                endif;
            endif;
            ?>
        </ul>
    </div>
</div>
<?php
$paging = ob_get_contents();
/*
$paging = preg_replace_callback('/href="([^"]+)"/is', function ($matches) {
    $url = $matches[1];
    $newUrl = '';
    if ($arUrl = parse_url($url)) {
        $newUrl .= $arUrl['path'];
        if (substr($newUrl, -1) != '/') {
            $newUrl .= '/';
        }
        $newUrl = preg_replace('#(page[\d]+/)#is', '', $newUrl);
        parse_str(htmlspecialcharsback($arUrl['query']), $arQuery);
        foreach ($arQuery as $k => $v) {
            if (in_array($k, array('SECTION_CODE'))) {
                unset($arQuery[$k]);
            } elseif (substr($k, 0, 5) == 'PAGEN') {
                $newUrl .= 'page' . intval($v) . '/';
                unset($arQuery[$k]);
            }
        }
        $buildQuery = http_build_query($arQuery, '', '&amp;');
        if (strlen($buildQuery)) {
            $newUrl .= '?' . $buildQuery;
        }
    }
    return 'href="' . $newUrl . '"';
}, $paging);*/
ob_end_clean();
echo $paging;