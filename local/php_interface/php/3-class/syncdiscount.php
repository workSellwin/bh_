<?php

class SyncDiscount
{
    protected $THIS_SITE;
    protected $OTHER_SITE;

    /**
     * SyncDiscount constructor.
     */
    public function __construct()
    {
        CModule::IncludeModule("sale");
        Cmodule::IncludeModule('catalog');
        $this->FUSER_ID = \Bitrix\Sale\Fuser::getId();
        $this->THIS_SITE = SITE_ID;
        $this->OTHER_SITE = SITE_ID == 's1' ? 's2' : 's1';
    }


}