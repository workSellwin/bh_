<?php

function ShowLabelFavourably($type, $prop)
{
    $prop = array_column($prop, '~VALUE', 'CODE');
    $FAVOURABLE = $prop['FAVOURABLE'];
    if ($FAVOURABLE) {
        switch ($type) {
            case  'element':
                echo <<<LABEL
            <span   title="Выгодно">
                <span style="background-color: #8856e8;" class="prod-status__item prod-status__item-describe">Выгодно</span>
            </span>
LABEL;
                break;

            case 'element_img':
                echo <<<LABEL
        <span title="Выгодно" class="prod-status__item" style="background-color: #ffff; width: 58px;"><img src="/local/templates/.default/images/Untitled-1.png"></span>
LABEL;

                break;


            case 'catalog.item:cat_new_views':
                echo <<<LABEL
        <span title="Выгодно" class="prod-status__item " style="background-color: #8856e8;">Выгодно</span>
LABEL;
                break;

            case 'catalog.item:cat_new_views_img':
                echo <<<LABEL
        <span title="Выгодно" class="prod-status__item" style="background-color: #ffff; width: 58px;"><img src="/local/templates/.default/images/Untitled-1.png"></span>
LABEL;

                break;
        }
    }
}
