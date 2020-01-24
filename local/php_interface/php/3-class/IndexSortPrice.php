<?php

/**
 *
 * $ob = new IndexSortPrice();
 * $ob->UpdatePageReIndex($_REQUEST['PAGE']);
 *
 * Class IndexSortPrice
 */
class IndexSortPrice
{
    /**
     * @var int
     */
    protected $IBLOCK_ID = 2;
    /**
     * @var array
     */
    protected $arPriceGroup = [];
    /**
     * @var array
     */
    protected $arID = [];
    /**
     * @var int
     */
    protected $nPageSize = 100;

    /**
     * IndexSortPrice constructor.
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        \Bitrix\Main\Loader::includeModule("sale");
        \Bitrix\Main\Loader::includeModule("catalog");
        \Bitrix\Main\Loader::includeModule("iblock");
        $this->arPriceGroup = [
            '2' => 'PRICE_SORT_2',
            '10' => 'PRICE_SORT_10',
            '14' => 'PRICE_SORT_14',
        ];
    }

    /**
     * @param bool $page
     */
    public function reIndex($page = false)
    {
        $this->GetAllID($page);
        foreach ($this->arID as $id) {
            $this->UpdateSortIndexPrice($id, $this->GetItemPrice($id));
        }
    }

    /**
     * @param $page
     * @param bool $show
     * @return bool
     */
    public function UpdatePageReIndex($page, $show = true)
    {
        $count = $this->GetCountPage();
        $allPage = ceil($count / $this->nPageSize);
        if ($page > $allPage) {
            return false;
        }
        $this->reIndex($page);
        if ($show) {
            $this->ShowNextPage($allPage, ++$page);
        }
        return true;
    }

    /**
     * @return string
     */
    public static function AddAgent()
    {
        \CAgent::AddAgent(
            "\IndexSortPrice::Agent(0);",
            "main",
            "N",
            60,
            "",
            "Y",
            "",
            30);
        return "\IndexSortPrice::AddAgent();";
    }

    /**
     * @param $page
     * @return string
     * @throws \Bitrix\Main\LoaderException
     */
    public static function Agent($page)
    {
        $ob = new self();
        if ($ob->UpdatePageReIndex($page, false)) {
            $page++;
            return "\IndexSortPrice::Agent({$page});";
        }
    }

    /**
     * @param $all
     * @param $page
     */
    public function ShowNextPage($all, $page)
    {
        $page2 = $page - 1;
        echo <<<HTML
                <form method="get">
                <h3>Страница {$page2} из {$all}</h3>
                    <input type="hidden" name="PAGE" value="{$page}">
                    <input type="submit" id="luiwadjogs-submit-price">
                </form>
                <script>
                    $(document).ready(function() {
                      $('#luiwadjogs-submit-price').trigger('click');
                    });
                </script>
HTML;
    }

    /**
     * @return int
     */
    public function GetCountPage()
    {
        return count($this->GetArAllId());
    }

    /**
     * @param $id
     * @return array
     */
    public function GetItemPrice($id)
    {
        $arResult = [];
        foreach ($this->GetPricesGroup() as $idGroup => $property) {
            $arPrice = $this->GetOptimalPrice($id, [$idGroup]);
            $arResult[$property] = $arPrice[0];
            $arResult[$property . '_DISCOUNT'] = $arPrice[1];
        }
        return $arResult;
    }

    /**
     * @param bool $page
     */
    public function GetAllID($page = false)
    {
        $this->arID = $this->GetArAllId($page);
    }


    /**
     * @param bool $page
     * @return array
     */
    public function GetArAllId($page = false)
    {
        $arResult = [];
        $nav = false;
        if ($page !== false) {
            $nav = [
                'iNumPage' => $page,
                'nPageSize' => $this->nPageSize,
            ];
        }
        $res = CIBlockElement::getList(
            ['ID' => 'ASC'],
            ['ACTIVE' => 'Y', 'IBLOCK_ID' => $this->IBLOCK_ID],
            false,
            $nav,
            array('ID', 'NAME')
        );
        while ($row = $res->getNext()) {
            $arResult[] = $row['ID'];
        }
        return $arResult;
    }

    /**
     * @return array
     */
    public function GetPricesGroup()
    {
        return $this->arPriceGroup;
    }

    /**
     * @param $intProductID
     * @param $arUserGroups
     * @return array
     */
    public function GetOptimalPrice($intProductID, $arUserGroups)
    {
        $arPrice = \CCatalogProduct::GetOptimalPrice($intProductID, 1, $arUserGroups, 'N', 's1');
        $discount =  $arPrice['RESULT_PRICE']['PERCENT'];
        return [$arPrice['RESULT_PRICE']['DISCOUNT_PRICE'], $discount];
    }

    /**
     * @param $id
     * @param $update
     */
    public function UpdateSortIndexPrice($id, $update)
    {
        \CIBlockElement::SetPropertyValuesEx($id, $this->IBLOCK_ID, $update);
    }

}
