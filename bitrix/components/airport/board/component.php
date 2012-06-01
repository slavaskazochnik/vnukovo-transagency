<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/*************************************************************************
	Init
*************************************************************************/
// ������ ����������
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

// �� ���������� ������ ����������
$showAirportFilter = false;

/*************************************************************************
	Processing of received parameters
*************************************************************************/
// ����� ����� ����
if ( !isset($arParams["CACHE_TIME"]) )
	$arParams["CACHE_TIME"] = 300;

// ���������� ����� ������ ����������
if ( !is_array($arParams["SHOW_AIRPORTS"]) )
	$arParams["SHOW_AIRPORTS"] = Array("-");

$arParams["SHOW_WORKFLOW"] = $_REQUEST["show_workflow"]=="Y";
if ( $arParams["SHOW_WORKFLOW"] )
	$arParams["CACHE_TIME"] = 0;

// ������������ ����������� ������ ��� �����
$arParams["DATA_PROVIDER_PATH"] = trim($arParams["DATA_PROVIDER_PATH"]);
if ( strlen($arParams["DATA_PROVIDER_PATH"]) <= 0 )
  $arParams["DATA_PROVIDER_PATH"] = $_SERVER['DOCUMENT_ROOT']."/bitrix/components/airport/board/data-provider/";

// ������� ��������
$arParams["SELECTED_AIRPORT"] = ToUpper(trim($_REQUEST["airport"]));
if ( in_array("-", $arParams["SHOW_AIRPORTS"]) ) // ���� � ���������� ���������� ������� ��� ���������
{
  $showAirportFilter = true; // ���������� ����� ����������
  $airportList = $airportListCfg;
  foreach ( $airportList as &$airport ) // ���� ���������� � ������� �������� ���� � ������ ����������
  {
    if ( $arParams["SELECTED_AIRPORT"] == $airport["CODE"] )
    {
      $airport["SELECTED"] = "Y";
      $currentAirportFound = true;
      $currentAirportName = $airport["CODE"];
      break;
    }
  }
  if ( !$currentAirportFound ) // ���� �������� �� ������
  {
    $arParams["SELECTED_AIRPORT"] = $airportList[0]["CODE"];
    $airportList[0]["SELECTED"] = "Y";
    $currentAirportName = $airportList[0]["CODE"];
  }
} elseif ( count($arParams["SHOW_AIRPORTS"]) > 1 ) { // ���� � ���������� ���������� ������� ����� ������ ���������
    $showAirportFilter = true; // ���������� ����� ����������
    foreach ( $airportListCfg as $airport ) // ��������� ������ ����������
    {
      if ( in_array($airport["CODE"], $arParams["SHOW_AIRPORTS"]) ) {
        $airportList[] = $airport;
      }
    }
    foreach ( $airportList as &$airport ) // ���� ���������� � ������� �������� ���� � ������ ����������
    {
      if ( $arParams["SELECTED_AIRPORT"] == $airport["CODE"] )
      {
        $airport["SELECTED"] = "Y";
        $currentAirportFound = true;
        $currentAirportName = $airport["CODE"];
        break;
      }
    }
    if ( !$currentAirportFound ) // ���� �������� �� ������
    {
      $arParams["SELECTED_AIRPORT"] = $airportList[0]["CODE"];
      $airportList[0]["SELECTED"] = "Y";
      $currentAirportName = $airportList[0]["CODE"];
    }
} elseif ( count($arParams["SHOW_AIRPORTS"]) == 1 ) { // ���� � ���������� ���������� ������ ���� ��������
  $showAirportFilter = false; // ���������� ����� ����������
  foreach ( $airportListCfg as &$airport ) // ��������� ������ ����������
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
  $arResult["SHOW_AIRPORTS_FILTER"] = $showAirportFilter ? "Y" : "N"; // ���������� ������ ����������
  $arResult["AIRPORTS_LIST"] = $airportList; // ������ ����������
  $arResult["FLIGHTS"] = Array(); // ������ ������

  require_once( $arParams["DATA_PROVIDER_PATH"] . ToLower($arParams["SELECTED_AIRPORT"]) . ".php" );

  $arResult["FLIGHTS"] = CAirportBoard::GetBoard();

  if ( intval($arResult["FLIGHTS"]["INBOUND"]["ERROR"]["CODE"]) || intval($arResult["FLIGHTS"]["OUTBOUND"]["ERROR"]["CODE"]) ) // ���� ��������� ������ ��� ��������� ������ ��� �����
  {
    $this->AbortResultCache(); // ���������� ���
  } elseif ( !count($arResult["FLIGHTS"]["INBOUND"]["FLIGHTS"]) || !count($arResult["FLIGHTS"]["OUTBOUND"]["FLIGHTS"]) ) // ���� �������� ������ ������ ��� �����
  {
    $this->AbortResultCache(); // ���������� ���
    $arResult["FLIGHTS"]["INBOUND"]["ERROR"]["CODE"] = "1";
    $arResult["FLIGHTS"]["OUTBOUND"]["ERROR"]["CODE"] = "1";
    $arResult["FLIGHTS"]["INBOUND"]["ERROR"]["MESSAGE"] = str_replace("#AIRPORT#", $currentAirportName, GetMessage("AIRPORT_BOARD_NO_FLIGHTS"));
    $arResult["FLIGHTS"]["OUTBOUND"]["ERROR"]["MESSAGE"] = str_replace("#AIRPORT#", $currentAirportName, GetMessage("AIRPORT_BOARD_NO_FLIGHTS"));
  }

  $this->IncludeComponentTemplate();
}

//trace($result);
?>
