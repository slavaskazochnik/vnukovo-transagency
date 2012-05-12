<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"DISPLAY_CLASS" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_CLASS"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_COMPANY" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_COMPANY"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_DIRECT" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_DIRECT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_MATRIX" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_MATRIX"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_CURRENCY" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_CURRENCY"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"DISPLAY_TARRIFS" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_TARIFFS"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PAYMENT" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_PAYMENT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"JQ_CALENDAR_NUMBER_OF_MONTHS" => Array(
		"NAME" => GetMessage("JQ_CALENDAR_NUMBER_OF_MONTHS"),
		"TYPE" => "LIST",
    "ADDITIONAL_VALUES" => "N",
    "REFRESH" => "N",
    "VALUES" => array(
      '1' => 1,
      '2' => 2,
      '3' => 3,
      '6' => 6
    )
	),
	"JQ_CALENDAR_STEP_MONTHS" => Array(
		"NAME" => GetMessage("JQ_CALENDAR_STEP_MONTHS"),
		"TYPE" => "LIST",
    "ADDITIONAL_VALUES" => "N",
    "REFRESH" => "N",
    "VALUES" => array(
      '1' => 1,
      '2' => 2,
      '3' => 3,
      '6' => 6
    )
	),
  "JQ_CALENDAR_SHOW_OTHER_MONTHS" => Array(
		"NAME" => GetMessage("JQ_CALENDAR_SHOW_OTHER_MONTHS"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
  "JQ_CALENDAR_SELECT_OTHER_MONTHS" => Array(
		"NAME" => GetMessage("JQ_CALENDAR_SELECT_OTHER_MONTHS"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR" => Array(
		"NAME" => GetMessage("JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
);
?>
