<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"DISPLAY_DISCOUNT" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_DISCOUNT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
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
);
?>
