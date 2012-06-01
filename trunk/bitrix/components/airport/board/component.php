<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/*************************************************************************
	Init
*************************************************************************/
// Список аэропортов
$airportListCfg = Array();
$airportListCfg[] = Array(
  "CODE"      => "VKO",
  "NAME"      => GetMessage("AIRPORT_BOARD_NAME_VNUKOVO"),
  "CITY"      => GetMessage("AIRPORT_BOARD_CITY_MOSCOW"),
  "SELECTED"  => "N",
);
$airportListCfg[] = Array(
  "CODE"      => "DME",
  "NAME"      => GetMessage("AIRPORT_BOARD_NAME_DOMODEDOVO"),
  "CITY"      => GetMessage("AIRPORT_BOARD_CITY_MOSCOW"),
  "SELECTED"  => "N",
);
$airportListCfg[] = Array(
  "CODE"      => "LED",
  "NAME"      => GetMessage("AIRPORT_BOARD_NAME_PULKOVO"),
  "CITY"      => GetMessage("AIRPORT_BOARD_CITY_PETRESBURG"),
  "SELECTED"  => "N",
);
$airportListCfg[] = Array(
  "CODE"      => "SVO",
  "NAME"      => GetMessage("AIRPORT_BOARD_NAME_SHEREMETEVO"),
  "CITY"      => GetMessage("AIRPORT_BOARD_CITY_MOSCOW"),
  "SELECTED"  => "N",
);

// Не показывать список аэропортов
$showAirportFilter = false;

/*************************************************************************
	Processing of received parameters
*************************************************************************/
// Время жизни кеша
if ( !isset($arParams["CACHE_TIME"]) )
	$arParams["CACHE_TIME"] = 300;

// Показывать табло рейсов аэропортов
if ( !is_array($arParams["SHOW_AIRPORTS"]) )
	$arParams["SHOW_AIRPORTS"] = Array("-");

$arParams["SHOW_WORKFLOW"] = $_REQUEST["show_workflow"]=="Y";
if ( $arParams["SHOW_WORKFLOW"] )
	$arParams["CACHE_TIME"] = 0;

// Расположение поставщиков данных для табло
$arParams["DATA_PROVIDER_PATH"] = trim($arParams["DATA_PROVIDER_PATH"]);
if ( strlen($arParams["DATA_PROVIDER_PATH"]) <= 0 )
  $arParams["DATA_PROVIDER_PATH"] = $_SERVER['DOCUMENT_ROOT']."/bitrix/components/airport/board/data-provider/";

// Текущий аэропорт
$arParams["SELECTED_AIRPORT"] = ToUpper(trim($_REQUEST["airport"]));
if ( in_array("-", $arParams["SHOW_AIRPORTS"]) ) // Если в параметрах компонента выбраны все аэропорты
{
  $showAirportFilter = true; // Показываем выбор аэропортов
  $airportList = $airportListCfg;
  foreach ( $airportList as &$airport ) // Если переданный в запросе аэропорт есть в списке аэропортов
  {
    if ( $arParams["SELECTED_AIRPORT"] == $airport["CODE"] )
    {
      $airport["SELECTED"] = "Y";
      $currentAirportFound = true;
      $currentAirportName = $airport["CODE"];
      break;
    }
  }
  if ( !$currentAirportFound ) // Если аэропорт не найден
  {
    $arParams["SELECTED_AIRPORT"] = $airportList[0]["CODE"];
    $airportList[0]["SELECTED"] = "Y";
    $currentAirportName = $airportList[0]["CODE"];
  }
} elseif ( count($arParams["SHOW_AIRPORTS"]) > 1 ) { // Если в параметрах компонента выбрано более одного аэропорта
    $showAirportFilter = true; // Показываем выбор аэропортов
    foreach ( $airportListCfg as $airport ) // Формируем список аэропортов
    {
      if ( in_array($airport["CODE"], $arParams["SHOW_AIRPORTS"]) ) {
        $airportList[] = $airport;
      }
    }
    foreach ( $airportList as &$airport ) // Если переданный в запросе аэропорт есть в списке аэропортов
    {
      if ( $arParams["SELECTED_AIRPORT"] == $airport["CODE"] )
      {
        $airport["SELECTED"] = "Y";
        $currentAirportFound = true;
        $currentAirportName = $airport["CODE"];
        break;
      }
    }
    if ( !$currentAirportFound ) // Если аэропорт не найден
    {
      $arParams["SELECTED_AIRPORT"] = $airportList[0]["CODE"];
      $airportList[0]["SELECTED"] = "Y";
      $currentAirportName = $airportList[0]["CODE"];
    }
} elseif ( count($arParams["SHOW_AIRPORTS"]) == 1 ) { // Если в параметрах компонента выбран один аэропорт
  $showAirportFilter = false; // Показываем выбор аэропортов
  foreach ( $airportListCfg as &$airport ) // Формируем список аэропортов
  {
    if ( in_array($airport["CODE"], $arParams["SHOW_AIRPORTS"]) )
    {
      $airport["SELECTED"] = "Y";
      $airportList[] = $airport;
    }
  }
  $arParams["SELECTED_AIRPORT"] = $airportList[0]["CODE"];
  $airportList[0]["SELECTED"] = "Y";
  $currentAirportName = $airportList[0]["CODE"];
}

/*************************************************************************
			Work with cache
*************************************************************************/
//trace($arParams);

if ( $this->StartResultCache() )
{
  $arResult["SHOW_AIRPORTS_FILTER"] = $showAirportFilter ? "Y" : "N"; // Показывать список аэропортов
  $arResult["AIRPORTS_LIST"] = $airportList; // Список аэропортов
  $arResult["FLIGHTS"] = Array(); // Список рейсов

  require_once( $arParams["DATA_PROVIDER_PATH"] . ToLower($arParams["SELECTED_AIRPORT"]) . ".php" );

  $arResult["FLIGHTS"] = CAirportBoard::GetBoard();

  if ( intval($arResult["FLIGHTS"]["INBOUND"]["ERROR"]["CODE"]) || intval($arResult["FLIGHTS"]["OUTBOUND"]["ERROR"]["CODE"]) ) // Если произошла ошибка при получении данных для табло
  {
    $this->AbortResultCache(); // сбрасываем кеш
  } elseif ( !count($arResult["FLIGHTS"]["INBOUND"]["FLIGHTS"]) || !count($arResult["FLIGHTS"]["OUTBOUND"]["FLIGHTS"]) ) // Если получили пустые данные для табло
  {
    $this->AbortResultCache(); // сбрасываем кеш
    $arResult["FLIGHTS"]["INBOUND"]["ERROR"]["CODE"] = "1";
    $arResult["FLIGHTS"]["OUTBOUND"]["ERROR"]["CODE"] = "1";
    $arResult["FLIGHTS"]["INBOUND"]["ERROR"]["MESSAGE"] = str_replace("#AIRPORT#", $currentAirportName, GetMessage("AIRPORT_BOARD_NO_FLIGHTS"));
    $arResult["FLIGHTS"]["OUTBOUND"]["ERROR"]["MESSAGE"] = str_replace("#AIRPORT#", $currentAirportName, GetMessage("AIRPORT_BOARD_NO_FLIGHTS"));
  }

  $this->IncludeComponentTemplate();
}

//trace($result);
?>
