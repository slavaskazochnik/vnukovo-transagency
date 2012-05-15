<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"]
	),
	$component
);
?>
<? if(CModule::IncludeModule("iblock") && ($arIBlock = GetIBlock($arParams['IBLOCK_ID'], $arParams['~IBLOCK_TYPE']))) : ?>
<div class="sect_text sect_catalog">
<? //trace($arIBlock);?>
	<? if ( $arIBlock['PICTURE'] ) : ?>
	<img class="iblock-pic" src="<?=$arIBlock['PICTURE']?>" alt="<?=$arIBlock['NAME']?>"  />
	<? endif; ?>
	<? if ( $arIBlock['DESCRIPTION'] ) : ?>
	<?= $arIBlock['DESCRIPTION'] ?>
	<? endif; ?>
</div>
<? endif; ?>
