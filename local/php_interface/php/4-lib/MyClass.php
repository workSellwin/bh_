<?php

class MyClass
{
    // создаем обработчик события "OnBeforeIBlockElementUpdate"
    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {

        if ($arFields["IBLOCK_ID"] == 3) {
            CModule::IncludeModule("iblock");

            $db_props = CIBlockElement::GetProperty(3, $arFields["ID"], array("sort" => "asc"), Array("CODE" => "CML2_LINK"));
            if ($ar_props = $db_props->Fetch())
                $product = IntVal($ar_props["VALUE"]);
            else
                $product = false;


            if ($product) {
                $db_old_groups = CIBlockElement::GetElementGroups($product, true);
                $ar_new_groups = array();
                while ($ar_group = $db_old_groups->Fetch())
                    $ar_new_groups[] = $ar_group["ID"];
            }

            $nav = CIBlockSection::GetNavChain(false, $ar_new_groups[0]);
            if ($arSectionPath = $nav->GetNext()) {

                CIBlockElement::SetPropertyValuesEx($arFields["ID"], $arFields["IBLOCK_ID"], array('SECTION_MAIN' => $arSectionPath['NAME']));

            }

        }

    }
}