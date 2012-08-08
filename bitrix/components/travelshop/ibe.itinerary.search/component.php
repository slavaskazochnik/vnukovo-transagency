<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/*************************************************************************
	Init
*************************************************************************/
if ( !CModule::IncludeModule("ibe" )) {
	ShowError( GetMessage("IBE_MODULE_NOT_INSTALL") );
	return;
}

/*************************************************************************
	Processing of received parameters
*************************************************************************/


/*************************************************************************
	Body
*************************************************************************/
//trace($arParams);

$arResult["GET_ITINERARY_URL"] = $componentPath . "/get_itinerary.php";

$this->IncludeComponentTemplate();

//trace($result);
?>
