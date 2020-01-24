<?php

class SmsNotification
{
    private $startOrderDate = false;
    /**
     * @var [] Bh\Sms\SmsRestricts $restricts
     */
    private $restricts = [];
    private $arBuyers = [];
    private $arOrders = [];
    private $orderLimit = 100000;
    private $productIds = [];
    private $queue = [];
    private $fUsers = [];
    private $obSmsService = false;
    private $author = "BH.BY";
    private $messageTemplate = [
        "название товара" => "NAME",
        "р." => "PRICE",
    ];

    public function __construct()
    {
        global $DB;

        if(!\CModule::IncludeModule('mlife.smsservices')){
            throw new \Error("empty module 'mlife.smsservices'");
        }
        if(!\CModule::IncludeModule('sale')){
            throw new \Error("empty module 'sale'");
        }
        if(!\CModule::IncludeModule('iblock')){
            throw new \Error("empty module 'iblock'");
        }
        if(!\CModule::IncludeModule('catalog')){
            throw new \Error("empty module 'catalog'");
        }
        $this->obSmsService = new \CMlifeSmsServices();

        $restricts = $this->getRestricts();

        $days = 0;

        foreach($restricts as $restrict){
//if(!$key){
//    continue;
//}
            $restrict['DAYS'] = intval($restrict['DAYS']);

            if( $restrict['DAYS'] == 0 ){
                $restrict['DAYS'] = 100;
            }
            elseif( $restrict['DAYS'] > 360 ){
                $restrict['DAYS'] = 360;
            }

            if($days < $restrict['DAYS']){
                $days = $restrict['DAYS'];
            }

            $this->setRestrictsNew($restrict);
        }

        //$days = $days - 30;//месяц сверху

        $this->startOrderDate = date($DB->DateFormatToPHP(\CSite::GetDateFormat("SHORT")), time() - 86400 * $days);
//        $this->setRestricts(
//            0,
//            $day,
//            '<200',
//            ['SELECTED' => ["ALL"], 'EXCEPTION' => ["тушь для ресниц"]],
//            "Пришло время оформить заказ на #название товара# всего за #р.#"
//        );

        $ordersData = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/local/include/sms-orders.txt');
        $buyersData = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/local/include/sms-users.txt');

        if($ordersData){
            $this->arOrders = unserialize($ordersData);
        }

        if($buyersData){
            $this->arBuyers = unserialize($buyersData);
        }
    }

    private function getRestricts(){

        $restricts = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/local/include/restricts.txt');

        if($restricts){
            $restricts = unserialize($restricts);
        }
        else{
            $restricts = [];
        }
        return $restricts;
    }

    private function setRestricts($section = false, $days = false, $volume = false, $productType = false, $template = false){
        $restrict = new SmsRestricts($section, $days, $volume, $productType, $template);
        $this->restricts[$restrict->getId()] = $restrict;
    }

    private function setRestrictsNew($restrictOpt){
        $restrict = new SmsRestricts($restrictOpt['SECTION'], $restrictOpt['DAYS'], $restrictOpt['VOLUME'], $restrictOpt['PRODUCT_TYPE'], $restrictOpt['TEMPLATE']);
        $this->restricts[$restrict->getId()] = $restrict;
    }

    public function smsSending(){//начать рассылку

        $subscribers = $this->getSubscribersQueue();
//pr($subscribers);
        foreach($subscribers as $subscriber){
            $this->send($subscriber);
        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/include/sms-orders.txt', serialize($this->arOrders));
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/include/sms-users.txt', serialize($this->arBuyers));
    }

    public function getCount(){

        $this->orderLimit = 100000;
        $subscribers = $this->getSubscribersQueue();
        //pr($subscribers);
        return count($subscribers);
    }

    private function send(Subscriber $subscriber){//отправка смс абоненту

        $phone = $subscriber->getPhone();
        $phone = $this->preparePhone($phone);
        $mess = $subscriber->getMessage();

        if($this->checkMessage($mess) && !empty($phone) ){

            $this->arOrders[] = $subscriber->getOrder();
            $this->arBuyers[$subscriber->getBuyer()] = time();

            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/sending_sms.txt',
                print_r([
                    'телефон' => $subscriber->getPhone(),
                    'сообщение' => $subscriber->getMessage(),
                    'номер заказа' => $subscriber->getOrder()
                    ], true), FILE_APPEND );
            //$arSend = $this->obSmsService->sendSms($subscriber->getPhone(), $subscriber->getMessage(), 0, $this->author);
//        if ($arSend->error) {
//            AddMessage2Log('\n\n\n Заказ №' . $ID . ' Ошибка отправки смс: ' . $arSend->error . ', код ошибки: ' . $arSend->error_code);
//        } else {
//            AddMessage2Log('\n\n\n Заказ №' . $ID . ' Сообщение успешно отправлено, Стоимость рассылки:' . $arSend->cost . ' руб.');
//        }
        }
        else{
            //
        }
    }

    private function checkMessage($mess){

        if(!is_string($mess)){
            return false;
        }
        elseif(mb_strlen($mess) < 10){
            return false;
        }
        return true;
    }

    private function preparePhone($phone){

        preg_match("#\d{0,3}(\d{9}$)#", $phone, $matches);

        if( count($matches) && isset($matches[1]) ){
            return '+375' . $matches[1];
        }
        return false;
    }

    private function getConfig(){//получить ограничения, шаблоны и т.д.

    }

    private function getSubscribersQueue(){//получить абонентов

        $arOrders = $this->getOrders();

        if($arOrders == false){
            return [];
        }
        $arBasketItems = $this->getBasketItems($arOrders);
        $arPhones = $this->getUsersPhone();
        $this->productIds = $this->filterProducts($arBasketItems);
        $this->createQueue($arBasketItems['BASKET_ITEMS'], $arPhones);

        return $this->queue;
    }

    private function getOrders(){

        //pr($this->getExceptionFUser());//die();
        $filter = [
            "PERSON_TYPE_ID" => 1,
            "!ID" => $this->getExceptionOrderId(),
            '>DATE_INSERT' => $this->startOrderDate,
            "STATUS_ID" => ["ok", "F"],
            "!USER_ID" => $this->getExceptionFUser()
        ];

        $res = \Bitrix\Sale\Order::getList([
            'filter' => $filter,
            'limit' => $this->orderLimit,
            'order' => 'DATE_INSERT',
            'select' => ['ID', 'DATE_INSERT', 'USER_ID', 'STATUS_ID']
        ]);

        $orders = [];
        $orderIds = [];

        while( $orderFields = $res->fetch() ){
            //pr($orderFields);die();
            $orders[$orderFields['ID']] = $orderFields;
            $orderIds[] = $orderFields['ID'];
        }
//        pr($filter);
//        pr($orders);
//        die();
        return count($orderIds) ? ["ORDERS" => $orders, "ORDERS_ID" => $orderIds] : false;
    }

    private function getBasketItems($arOrders){

        $res = \Bitrix\Sale\Basket::getList([
            'filter' => ['ORDER_ID' => $arOrders['ORDERS_ID'], 'QUANTITY' => 1],
            //'limit' => 10,
            'order' => 'ORDER_ID',
            'select' => ['ID', 'ORDER_ID', 'FUSER_ID', 'PRICE', 'DATE_INSERT', 'PRODUCT_ID', 'NAME'],
        ]);

        $productIds = [];
        $productsInsertData = [];
        $basketItems = [];

        while( $basketFields = $res->fetch() ){

            $this->fUsers[$basketFields['FUSER_ID']] = $arOrders['ORDERS'][$basketFields['ORDER_ID']]['USER_ID'];
            $productIds[$basketFields['PRODUCT_ID']] = false;
            $productsInsertData[$basketFields['PRODUCT_ID']] = $basketFields['DATE_INSERT'];
            $arPrice = CCatalogProduct::GetOptimalPrice(//актуальные цены
                $basketFields['PRODUCT_ID'],
                1,
                CUser::GetUserGroup($this->fUsers[$basketFields['FUSER_ID']])
            );
            $basketFields['PRICE'] = $arPrice['DISCOUNT_PRICE'];
            $basketItems[$basketFields['FUSER_ID']][$basketFields['ORDER_ID']][$basketFields['PRODUCT_ID']] = $basketFields;
        }

        return ['BASKET_ITEMS' => $basketItems, 'PRODUCTS_ID' => $productIds, "PRODUCTS_INSERT_DATA" => $productsInsertData];
    }

    /**
     * @param array $basketItems
     * @param array $arPhones
     */
    private function createQueue(array $basketItems, array $arPhones ){

        foreach( $basketItems as $fUserId => $arFUserOrders ){//уровень F_USER

            foreach($arFUserOrders as $orderId => $basketItems){

                foreach($basketItems as $productId => $basketItem){//удалить все, что не прошли условия

                    if(!$this->productIds[$productId]){
                        unset($basketItems[$productId]);
                    }
                }
                unset($basketItem);

                if(count($basketItems)){

                    $maxPrice = 0;
                    $maxPriceProductId = false;

                    foreach($basketItems as $basketItem){
                        if($basketItem['PRICE'] > $maxPrice){
                            $maxPrice = $basketItem['PRICE'];
                            $maxPriceProductId = $basketItem['PRODUCT_ID'];
                        }
                    }
                    //$this->updateFUserLastSend($fUserId);
                    //$this->updateOrderLastSend($orderId);
                    $template = $this->restricts[$this->productIds[$maxPriceProductId]]->getTemplate();
                    $phone = $arPhones[$this->fUsers[$fUserId]];

                    if( !empty($phone) ) {
//                        pr($orderId);
//                        pr($this->fUsers[$fUserId]);
//                        die();
                        $this->addQueue(
                            $fUserId,
                            $this->fUsers[$fUserId],
                            $maxPriceProductId,
                            $phone,
                            $this->getMessage($template, $basketItem),
                            $orderId
                        );
                        $this->delFUser($fUserId);
                    }
                    break;
                }
            }
        }
    }

    private function getMessage($template, $basketItem){

        foreach($this->messageTemplate as $key => $val){

            if($val == 'NAME'){
                $val = '"' . $basketItem[$val] . '"';
                $template = str_replace('#'. $key . '#', $val, $template);
            }
            elseif($val == 'PRICE'){
                $val = round($basketItem[$val], 2) . ' руб.';
                $template = str_replace('#'. $key . '#', $val, $template);
            }
            elseif( !empty($basketItem[$val]) ){
                $template = str_replace('#'. $key . '#', $basketItem[$val], $template);
            }
        }
        return $template;
    }

    private function delFUser($fUserId){
        unset($this->fUsers[$fUserId]);
    }

    private function getUsersPhone(){

        if( count($this->fUsers) ){

            $by = "timestamp_x";
            $order = "desc";
            $strUserIds = '';

            foreach( $this->fUsers as $userId ){

                if($strUserIds === ''){
                    $strUserIds .= $userId;
                }
                else{
                    $strUserIds .= ' | ' . $userId;
                }
            }

            $rsUsers  = \CUser::GetList($by, $order, ["ID" => $strUserIds, "!PERSONAL_PHONE" => false]);
            $usersPhone = [];

            while($fields = $rsUsers->fetch()){

                if(!empty($fields['PERSONAL_PHONE'])){
                    $usersPhone[$fields['ID']] =  str_replace([' ', '+'],['', ''],$fields['PERSONAL_PHONE']);
                }
            }
            return $usersPhone;
        }

        return [];
    }

    private function updateFUserLastSend($fUserId){

    }

    private function updateOrderLastSend($orderId){

    }

    private function addQueue($fUserId, $userId, $productId, $phone, $smsTemplate, $bindOrder){
        $this->queue[] = new Subscriber( $fUserId, $userId, $productId, $phone, $smsTemplate, $bindOrder);
    }

    private function filterProducts($basketItems){//фильтр товаров по заданному ограничению

        if( !is_array($this->restricts) && !count($this->restricts) ){
            return $basketItems['PRODUCTS_ID'];
        }

        $res = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => 2,
                "ID" => array_keys($basketItems['PRODUCTS_ID']),
                "!PROPERTY_productype" => "набор",
                "!PROPERTY_SCOPE" => false
            ],
            false,
            false,
            ['ID', 'NAME', 'IBLOCK_SECTION_ID', "PROPERTY_productype", "PROPERTY_SCOPE"]
        );

        while( $elFields = $res->fetch() ){
            foreach($this->restricts as $restrict){
                $elFields["DATA_INSERT"] = $basketItems['PRODUCTS_INSERT_DATA'][$elFields["ID"]];

                if($restrict->check($elFields)){
                    $basketItems['PRODUCTS_ID'][$elFields['ID']] = $restrict->getId();
                    //break;
                }
            }
        }

        return $basketItems['PRODUCTS_ID'];
    }

    private function getExceptionFUser(){

        $exceptionBuyers = [];

        if(count($this->arBuyers)){

            foreach($this->arBuyers as $buyerId => $time){
                if($time + 604800 > time() ){
                    $exceptionBuyers[] = $buyerId;
                }
            }
        }
       // pr($exceptionBuyers);die();
        return $exceptionBuyers;//вернуть массив FUSER, которые получали sms менее недели назад
    }

    private function getExceptionOrderId(){

        return $this->arOrders;//вернуть массив ID заказов, на которые получали sms
    }
}

class Subscriber{

    private $userId = false;
    private $fUserId = false;
    private $phone = false;
    private $message = false;
    private $good = false;
    private $bindOrder = false;

    function __construct($fUser, $userId, $good, $phone, $message, $bindOrder)
    {
        //$this->phone = $phone;
        //$this->smsTemplate = $smsTemplate;

        $this->fUserId = $fUser;
        $this->userId = $userId;
        $this->good = $good;
        $this->phone = $phone;
        $this->message = $message;
        $this->bindOrder = $bindOrder;
    }

    public function setPhone($phone){
        $this->phone = $phone;
    }

    public function getPhone(){
        return $this->phone;
    }
    /**
     * @return bool
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function getBuyer()
    {
        //return $this->fUserId;
        return $this->userId;
    }

    /**
     * @return bool
     */
    public function getOrder()
    {
        return $this->bindOrder;
    }

    /**
     * @param bool $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}

class SmsRestricts{

    private $id = false;
    private $section = false;
    private $days = false;
    private $timestamp = false;
    private $volume = false;
    private $productType = false;
    private $template = "";

    function __construct($section = false, $days = false, $volume = false, $productType = false, $template = false)
    {
        $uniqueStr = serialize($section) . serialize($days) . serialize($volume) . serialize($productType) . serialize($template);
        $this->id = md5($uniqueStr);

        if(is_string($template)){
            $this->template = $template;
        }

        $this->section = intval($section);
        $this->days = intval($days);
        $this->timestamp = time() - ($this->days * 86400);

        if( is_string($volume) && strlen($volume) > 0 ) {
            preg_match('#([<>!=])?(\d+)#', $volume, $matches);

            if (count($matches) && intval($matches[2]) > 0) {
                $this->volume = ["LOGIC" => $matches[1], "VALUE" => $matches[2]];
            } else {
                $this->volume = false;
            }
        }
        else{
            $this->volume = false;
        }
        $this->productType = $productType;
    }

    public function check($arFields){

        if($arFields['DATA_INSERT']->getTimestamp() <= $this->timestamp){
            return false;
        }

        if($this->section > 0 && $arFields['IBLOCK_SECTION_ID'] != $this->section){
            return false;
        }

        if($this->volume !== false) {

            if ($this->volume['LOGIC'] == '=' || $this->volume['LOGIC'] == '') {
                if (intval($arFields['PROPERTY_SCOPE_VALUE']) != $this->volume['VALUE']) {
                    return false;
                }
            } elseif ($this->volume['LOGIC'] == '!') {
                if (intval($arFields['PROPERTY_SCOPE_VALUE']) == $this->volume['VALUE']) {
                    return false;
                }
            } elseif ($this->volume['LOGIC'] == '<') {
                if (intval($arFields['PROPERTY_SCOPE_VALUE']) > $this->volume['VALUE']) {
                    return false;
                }
            } elseif ($this->volume['LOGIC'] == '>') {
                if (intval($arFields['PROPERTY_SCOPE_VALUE']) < $this->volume['VALUE']) {
                    return false;
                }
            }
        }

        if( in_array('ALL', $this->productType["SELECTED"])
            || in_array(trim($arFields['PROPERTY_PRODUCTYPE_VALUE']), $this->productType["SELECTED"]) ){

            if( count($this->productType["EXCEPTION"]) ) {
                foreach ($this->productType["EXCEPTION"] as $exception) {
                    if (trim($exception) == trim($arFields['PROPERTY_PRODUCTYPE_VALUE'])) {
                        return false;
                    }
                }
            }
        }
        else{
            return false;
        }

        return true;
    }

    public function getId(){
        return $this->id;
    }

    public function getTemplate(){
        return $this->template;
    }
}