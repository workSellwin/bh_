<?php

/**
 * Class DiscountNeighbour
 * необходимо подключить карты лояльности Купилка Соседей
 */
class DiscountNeighbour
{
    const ADD_GROUP = 22;

    public function __construct()
    {
    }

    public function IsInterval($v)
    {
        // return self::IsInterval2($v) or self::IsInterval3($v);
        return self::IsIntervalNew($v);
    }


    public function IsIntervalNew($v)
    {
        $k = 0;
        foreach (self::IsIntervalArray() as $start => $finish) {
            if ($v >= $start and $v <= $finish) {
                $k++;
            }
        }
        return (bool)$k;
    }

    protected static function IsIntervalArray()
    {
        return [
            //  Купилка
            '948200000000000' => '948200009999999',
            '948200090000000' => '948200099999999',
            //  Ведомость предоставленных штрих-кодов "Моцная картка"
            '9968010000018' => '9968014062005', // 1 30.05.2019 добавили 150 тыс для карт Мастеркард
            '9968020000015' => '9968020600000', // 2
            '9968030000012' => '9968030120000', // 3
            '9968040000019' => '9968040200006', // 4
            '9968050000016' => '9968050250008', // 5
            '9968060000013' => '9968060060000', // 6
            '9968070000010' => '9968070650000', // 7 12.02.2019 добавляли 15 тыс для Альфы
            '9968080000017' => '9968081000009', // 8
            '9968090000014' => '9968091000006', // 9
            '9968100000010' => '9968105000008', // 10
            '9968110000017' => '9968111379983', // 11
            '9968120000014' => '9968121260004', // 12 19.04.19. добавили 6 тыс лоя Белаграпром банка. 30.05.2019 добавили еще 20 тыс
            '9968150000015' => '9968150649993', // 13 добавка 03.06.19
            // новые диапазоны
            '996801000001' => '996801406200',
            '996802000001' => '996802060000',
            '996803000001' => '996803012000',
            '996804000001' => '996804020000',
            '996805000001' => '996805025000',
            '996806000001' => '996806006000',
            '996807000001' => '996807065000',
            '996808000001' => '996808100000',
            '996809000001' => '996809100000',
            '996810000001' => '996810500000',
            '996811000001' => '996811137998',
            '996812000001' => '996812129100',
            '996815000001' => '996815097999',
        ];
    }


    protected static function IsInterval2($v)
    {
        return $v > 948200000000000 and $v < 948200009999999;
    }

    protected static function IsInterval3($v)
    {
        return $v > 948200090000000 and $v < 948200099999999;
    }

    /**
     * @return array
     */
    protected function GetSpec()
    {
        // КОСМО - 21 , shell-minsk - 20  , SELLWIN - 14, B2B - 11
        return [21, 20, 14, 11];
    }

    public function AddGroupUser()
    {
        global $USER;
        $arResult = [];
        if (is_object($USER)) {
            if ($this->isView()) {
                $id = $USER->GetID();
                $arGroups = CUser::GetUserGroup($id);
                $arGroups[] = self::ADD_GROUP;
                CUser::SetUserGroup($id, $arGroups);
                $USER->SetUserGroupArray($arGroups);
                $arResult['STATUS'] = 'OK';
                $arResult['MESSAGE'] = 'Добавили';
            } else {
                $arResult['STATUS'] = 'ERROR';
                $arResult['MESSAGE'] = 'Для Вас уже действуют специальные условия.';
            }
        } else {
            $arResult['STATUS'] = 'ERROR';
            $arResult['MESSAGE'] = 'Для Вас уже действуют специальные условия.';
        }
        return $arResult;
    }

    public function isView()
    {
        global $USER;
        $add = false;
        if (is_object($USER)) {
            $id = $USER->GetID();
            if (!$id) {
                $add = true;
                self::SetKupilka(true);
            }
            $arGroups = CUser::GetUserGroup($id);
            if (
                $id > 0 and
                is_array($arGroups) and
                !array_intersect($this->GetSpec(), $arGroups)
            ) {
                $add = true;
            }
        } else {
            $add = true;
        }
        return $add;
    }

    public static function Action()
    {
        $ob = new self();
        if (isset($_SESSION['ERROR_ORDER_PROP_38'])) {
            unset($_SESSION['ERROR_ORDER_PROP_38']);
        }
        if ($_REQUEST['ORDER_PROP_38'] and $ob->isView()) {
            if ($ob->IsInterval($_REQUEST['ORDER_PROP_38'])) {
                if (!self::GetKupilka()) {
                    $ob->AddGroupUser();
                    $ob->AddPropUser();
                }
            } else {
                $_SESSION['ERROR_ORDER_PROP_38'] = "Неправильный номер карты!";
            }
        }
    }


    public static function ActionUser(&$arFields)
    {
        $ob = new self();
        if ($_REQUEST['ORDER_PROP_38']) {
            if ($ob->IsInterval($_REQUEST['ORDER_PROP_38'])) {
                $arFields['GROUP_ID'][] = $ob::ADD_GROUP;
                $arFields['UF_KUPILKA'] = $_REQUEST['ORDER_PROP_38'];
            }
        }
    }


    /**
     * @return mixed
     */
    public static function GetKupilka()
    {
        return $_SESSION['KUPILKA_ADD_GROUP'];
    }

    /**
     * @param $v
     */
    public static function SetKupilka($v)
    {
        $_SESSION['KUPILKA_ADD_GROUP'] = $v;
    }

    protected function AddPropUser()
    {
        global $USER;
        if (is_object($USER)) {
            $ID = $USER->GetID();
            $user = new CUser;
            $user->Update($ID, ['UF_KUPILKA' => $_REQUEST['ORDER_PROP_38']]);
        }
    }
}
