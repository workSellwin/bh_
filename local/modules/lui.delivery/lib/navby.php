<?php


namespace Lui\Delivery;

use SoapClient;

include_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/lui.delivery/class/good.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/lui.delivery/class/cost.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/lui.delivery/class/point.php';

class NavBy
{
    protected $arOrders = [];
    /**
     * @var \SoapClientDebug
     */
    protected $client;
    protected $responce = [];

    protected $login;
    protected $password;
    protected $wsdl;
    protected $debug;

    public function __construct(array $arOrders)
    {
       /* $arOrders['ORDERS'] = array_filter($arOrders['ORDERS'], function ($e) {
            if (!isset($e['CONFIG']['ERROR'])) {
                return true;
            }
            return $e['CONFIG']['ERROR'] == 'N' ? true : false;
        });*/
        $this->arOrders = $arOrders;
    }


    public function sends()
    {
        if (!empty($this->arOrders['ORDERS'])) {
            $this->connect();
            foreach ($this->arOrders['ORDERS'] as $arOrder) {
                $this->send($arOrder);
            }
        }
    }

    protected function send($arOrder)
    {
        $xml = $this->GetObSend($arOrder);
        $res = $this->SoapAddPoint($xml);
        if (is_object($res) and isset($res->faultstring)) {
            $this->responce[] = [$res->faultstring,$arOrder['ROW']];
        }else{
            $this->responce[]=$res;
        }
    }

    public function GetResponce()
    {
        return $this->responce;
    }


    protected function GetObSend($arOrder)
    {
        $ob = new \point();
        $xml = $ob->SetPoint($arOrder);
        return $xml;
    }

    public function connect()
    {
        $options = [
            'login' => $this->login,
            'password' => $this->password,
            'trace' => true,
            'exceptions' => false
        ];
        $this->client = new SoapClientDebug($this->wsdl, $options);
    }

    public function setSoapConfig($login, $password, $wsdl)
    {
        $this->login = $login;
        $this->password = $password;
        $this->wsdl = $wsdl;
    }

    protected function SoapAddPoint($xml)
    {
        $this->client->SetXml($xml);
        $res = $this->client->addPoint([]);
        return $res;
    }
}

class SoapClientDebug extends \SoapClient
{
    protected $xmlSoap;

    public function SetXml($xml)
    {
        $this->xmlSoap = $xml;
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $request = $this->xmlSoap;
        // PR(htmlspecialchars($request));
        // PR([$request, $location, $action, $version, $one_way]);
        // Add code to inspect/dissect/debug/adjust the XML given in $request here

        // Uncomment the following line, if you actually want to do the request
        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }
}

