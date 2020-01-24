<?php
function sendOrderReport()
{
    $ordersReport = new ordersReport('e.gisak@sellwin.by, d.maznyak@sellwin.by'); //'e.gisak@sellwin.by, d.maznyak@sellwin.by'
    $time = time() - 3600 * 24 * 7; //две недели
    $ordersReport->getFileOrdersReport($time);
    return "sendOrderReport();";
}

function sendOrderReportInterval($DATE)
{
    $ordersReport = new ordersReport('yauheni_4@mail.ru');
    $DATE_TO = $DATE;
    $ordersReport->getFileOrdersReportInterval($DATE_TO);
    $DATE_TO = $ordersReport->date_from;
    $DATE_TO = date('d.m.Y', strtotime($DATE_TO . ' + 1 days'));
    if ($DATE_TO <= date('d.m.Y')) {
        return "sendOrderReportInterval('{$DATE_TO}');";
    } else {
        return false;
    }
}

/**
 * Агент отчистки устаревшего кеша
 *
 * @return string
 */
function AgentBXClearCache()
{
    BXClearCache(true, "/iblock/");
    $staticHtmlCache = \Bitrix\Main\Data\StaticHtmlCache::getInstance();
    $staticHtmlCache->deleteAll();
    return 'AgentBXClearCache();';
}
