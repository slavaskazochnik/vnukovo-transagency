<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Список аэропортов
$arAirports = Array(
  "VKO" => GetMessage("AIRPORT_BOARD_SHOW_VNUKOVO"),
  "DME" => GetMessage("AIRPORT_BOARD_SHOW_DOMODEDOVO"),
  "LED" => GetMessage("AIRPORT_BOARD_SHOW_PULKOVO"),
  "SVO" => GetMessage("AIRPORT_BOARD_SHOW_SHEREMETEVO"),
);

$arComponentParameters = array(
	"GROUPS" => array(
		"AIRPORTS" => array(
			"NAME" => GetMessage("AIRPORT_BOARD_AIRPORTS_GROUP"),
		),
	),
	"PARAMETERS" => array(
		"SHOW_AIRPORTS" =>array(
			"PARENT" => "AIRPORTS",
			"NAME" => GetMessage("AIRPORT_BOARD_SHOW_AIRPORTS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"DEFAULT" => "-",
			"VALUES" => array_merge(Array("-" => GetMessage("AIRPORT_BOARD_SHOW_ALL")), $arAirports),
		),
		"CACHE_TIME"  =>  Array("DEFAULT" => 300),
	),
);
?>
