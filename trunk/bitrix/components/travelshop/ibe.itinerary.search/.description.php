<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IBE_ITINERARY_SEARCH_NAME"),
	"DESCRIPTION" => GetMessage("IBE_ITINERARY_SEARCH_DESCRIPTION"),
	"ICON" => "/images/search.gif",
	"CACHE_PATH" => "Y",
	"PATH" => array(
      "ID" => "travelshop",
      "NAME" => GetMessage('TAIS_TRAVELSHOP'),
      "CHILD" => array(
        "ID" => "booking",
			  "NAME" => GetMessage('BOOKING'),
		  ),
   ),
);

?>