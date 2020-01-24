<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
$rsUserGmi = CUser::GetByID($GLOBALS['USER']->GetID());
$arUserGmi = $rsUserGmi->Fetch();

if($arUserGmi['UF_CODE_STORE']){
	$calendar = array();
	$request = $arUserGmi['UF_CODE_STORE'];

	$obCache = new CPHPCache();
	if ($obCache->InitCache(86400, $request, '/delivery/'))
	{
		$calendar = $obCache->GetVars();
	}
	elseif ($obCache->StartDataCache()) {
		if (\Bitrix\Main\Loader::includeModule('iblock')) {

			$json = file_get_contents('http://evesell.sellwin.by/eve-adapter-sellwin/deliveryday/json?code='.$request);
			$jsonRes = json_decode($json,true);
			foreach ($jsonRes['data'] as $datum) {
				$calendar[] = $datum['dateDelivery'];
			}

			if (defined('BX_COMP_MANAGED_CACHE')) {
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache('/delivery/');
				$CACHE_MANAGER->RegisterTag('deliveryday');
				$CACHE_MANAGER->EndTagCache();
			}
		}
		$obCache->EndDataCache($calendar);
	}
}
?>

<?if(count($calendar)>0):?>
	<div class="calendar-block flex row">
		<?
		$beginDate = new \Bitrix\Main\Type\DateTime($calendar[0], "d.m.Y");
		$endDate = new \Bitrix\Main\Type\DateTime(end($calendar), "d.m.Y");

		$beginDateYear = $beginDate->format("Y");
		$endDateYear = $endDate->format("Y");

		$beginDateMonth = $beginDate->format("n");
		$endDateMonth = $endDate->format("n");

		for($currentYear = $beginDateYear; $currentYear <= $endDateYear; $currentYear++)
		{
			$firstMonth = ($currentYear == $beginDateYear) ? $beginDateMonth : 1;
			$lastMonth = ($currentYear == $endDateYear) ? $endDateMonth : 12;

			for($month = $firstMonth; $month <= $lastMonth; $month++)
			{
				$time = mktime(0, 0, 0, $month, 1, $currentYear);
				$daysCount = date("t", $time);
				$dayWeek = date("w", $time);
				if($dayWeek == 0) $dayWeek = 7;

				echo '<div class="item">';
				echo '<table class="table">';
				echo '<thead><tr><th colspan="7">
							<div class="item-title flex">
								<button type="button" class="slider-button button-prev hide">&#9668;</button>
								<div class="name">'.\Bitrix\Main\Localization\Loc::getMessage("T_REALTY_MONTH_".$month).'</div>
								<button type="button" class="slider-button button-next hide">&#9658;</button>
							</div>
						</th></tr></thead>';
				echo '<tbody>';
				echo '<tr class="title-day">';
				$j=0;
				while($j < 7){
					echo '<td>'.\Bitrix\Main\Localization\Loc::getMessage("T_DAY_NAME_".$j).'</td>';
					$j++;
				}
				echo '</tr>';
				echo '<tr>';

				if($dayWeek != 1)
				{
					for($i = 1; $i < $dayWeek; $i++)
					{
						echo '<td>&nbsp;</td>';
					}
				}

				for($i = 1; $i <= $daysCount; $i++)
				{
					$time = mktime(0, 0, 0, $month, $i, $currentYear);
					$dayWeek = date("w", $time);
					$date = date("d.m.Y 0:00:00", $time);

					if($dayWeek == 1)
					{
						echo '</tr><tr>';
					}

					echo '<td>';



					if(in_array($date, $calendar))
					{
						echo '<b>'.$i.'</b>';
					}
					else
					{
						echo $i;
					}

					echo '</td>';
				}
				if($dayWeek != 0 )
				{
					for($i = $dayWeek + 1; $i <= 7; $i++)
					{
						echo '<td>&nbsp;</td>';
					}
				}

				echo '</tr>';
				echo '</tbody>';
				echo '</table>';
				echo '</div>';
			}
		}
		?>
	</div>
<?endif;?>
