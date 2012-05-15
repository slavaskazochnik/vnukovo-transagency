<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$USE_AUTOCOMPLETE = ( !count($arResult['points']) && true == $arParams["USE_AUTOCOMPLETE"] ) ; // »спользовать автозаполнение, если используютс€ пол€ дл€ ввода пунктов и разрешено автозаполнение
$USE_JQUERY_UI = true;

require_once(dirname(__FILE__).'/tools.php');

$APPLICATION->AddHeadString(CIBECacheControl::RenderJSLink("/bitrix/js/ibe/tools.js"));
$APPLICATION->AddHeadString(CIBECacheControl::RenderJSLink("/bitrix/js/ibe/formtools.js"));

$frontofficeHelper = new frontofficeHelper();

if ( isset($arResult["processor"]) ) {
    $APPLICATION->SetPageProperty("TravelShopBookingCurrentStage", ToUpper($arResult["processor"]));
	$APPLICATION->SetPageProperty("HIDE_TEXT_FIELD", 'Y');
	if ( $arResult["processor"] != 'form_order' ) {
		$APPLICATION->SetPageProperty("HIDE_RIGHT_COLLUMN", true );
		$APPLICATION->IncludeComponent(
			"travelshop:ibe.bookstage",
			"",
			Array(
				"COMPONENT" => "travelshop:ibe.frontoffice",
				"IBE_AJAX_MODE" => "N",
				"SHOW_STAGE_SERVICES" => "N"
			)
		);
	}
}

if(isset($arResult["processor"])) { ?>
<? //$arResult["processor"]?>
<div id="ts_ag_reservation_curtain">
<? if ( $arParams[ "~IBE_AJAX_MODE" ] == "Y" && !$arResult[ "~IS_AJAX_MODE" ] ) : // компонент в Ajax-режиме и это не жестко предопределенный Ajax-вызов ?>
<? 
// если это загрузка всей страницы, то подключаем базовые скрипты
if ( !CIBEAjax::IsAjaxMode() ) { 
  $APPLICATION->IncludeComponent(
    "travelshop:ibe.ajax",
    ""
  );
}
?>
<script type="text/javascript">
// <![CDATA[
ibe_ajax.default_areas_to_update = "#ts_ag_reservation_container,#ts_ag_reservation_stages_container,#ts_basket_container,#ts_ag_personal_menu_container";
ibe_ajax.on_before_post = function () { 
  $( '#ts_ag_reservation_curtain' ).fadeTo( 200, 0.1 ); 
  $( '.common-error' ).hide();
};
ibe_ajax.on_after_post = function () { 
  $('#ts_ag_reservation_curtain').fadeTo(200, 1); 
};
ibe_ajax.on_post_error = function ( textStatus, errorThrown ) { 
  alert( "Connection error (" + textStatus + ")" );
  $('#ts_ag_reservation_curtain').fadeTo(200, 1);
};
// ]]>
</script>
<? endif; // $arParams[ "~IBE_AJAX_MODE" ] == "Y" ?>

<span id="ts_ag_reservation_container">
<?
$bOutputStarted = CIBEAjax::StartArea( "#ts_ag_reservation_container" );

// ¬ыводим логотипы авиакомпаний
if ($arResult['LOGOS']){
  $logoStyles = ''; 
  $logoStyles .= '<style type="text/css" scoped="scoped">';
  foreach ($arResult['LOGOS'] as $arCompany){
    $logoStyles .=  ' .logo-small-'.$arCompany['IATACODE'].'{background-image:url('.$arCompany['LINK']['SMALL'].');}'.
            ' .logo-normal-'.$arCompany['IATACODE'].'{background-image:url('.$arCompany['LINK']['NORMAL'].');}';
  } 
  $logoStyles .= '</style>';
  $APPLICATION->AddHeadString($logoStyles, true);
}
?>
<? if(!$arResult['~PRINT']): // не выводить при печати ?>
<div id="<?= $arResult[ "~IS_AJAX_MODE" ] ? "ts_ag_reservation_ajax" : "ts_ag_reservation" ?>" class="<?= $arResult["processor"] ?>_wrapper clearfix">
	<? if ( $arResult["processor"] != 'form_order' &&  $arResult["processor"] != 'order' && $arResult["processor"] != 'progress') : ?>
	<div class="ts_sect_left">
	<? $APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "sect", 
			"AREA_FILE_SUFFIX" => "system_ts", 
			"EDIT_MODE" => "html", 
			"EDIT_TEMPLATE" => "standart.php",
			"AREA_FILE_RECURSIVE" => "N" 
		)
	); ?>
	</div>
	<div class="ts_sect_right">
	<? endif; ?>
<? endif; ?>
<? // ≈сли есть ошибка - нужно вывести
if (isset($arResult['display_error']) && strlen($arResult['display_error'])) {
  // ѕрисутствует структурированный вариант
  if (isset($arResult['ERROR'])) {
    $arError = array();

    foreach ($arResult['ERROR'] as $error) {
      $arError[] = ( 0 !== $error['CODE'] && GetMessage( 'TS_'.$error['TYPE'].'_ERROR_'.$error['CODE'] ) ? GetMessage( 'TS_'.$error['TYPE'].'_ERROR_'.$error['CODE'] ) : $error['TEXT']);
    }

    ShowError(implode('<br /><br />', $arError));
  }
  else {
	ERROR_OUTPUT($arResult['display_error']);
  }
}

	if (isset($_POST['actions']) && $_POST['actions'] == "update_personal") {
		$pass_count  = $GLOBALS['COMPONENT_SESSION']['choose_trip']['adult'] + $GLOBALS['COMPONENT_SESSION']['choose_trip']['child'] + $GLOBALS['COMPONENT_SESSION']['choose_trip']['infant'];
		for($i = 0; $i < $pass_count; $i++)
		{
			if(isset($_POST['PSGRDATA_FFAK_VISIBLED_'.$i]))
			{
				$_SESSION['psgr_ffak_visibled'][$i] = $_POST['PSGRDATA_FFAK_VISIBLED_'.$i] == "true";
			}
		}
	}

	if (file_exists($_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".SITE_TEMPLATE_ID."/components/".str_replace(":","/",$component->GetName())."/".$this->GetName()."/".$arResult[ "processor" ].".php")) {
		require($_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".SITE_TEMPLATE_ID."/components/".str_replace(":","/",$component->GetName())."/".$this->GetName()."/".$arResult[ "processor" ].".php");

	} elseif (file_exists($_SERVER["DOCUMENT_ROOT"].$templateFolder."/".$arResult[ "processor" ].".php")){
		require( $_SERVER["DOCUMENT_ROOT"].$templateFolder."/".$arResult[ "processor" ].".php" );

	} elseif(file_exists($_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/components/travelshop/ibe.frontoffice/templates/.default/".$arResult[ "processor" ].".php")) {
		require( $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/components/travelshop/ibe.frontoffice/templates/.default/".$arResult[ "processor" ].".php" );

	}
  ?>

<? } ?>
<? if (!$arResult['~PRINT']): // не выводить при печати ?>
	<? if ( $arResult["processor"] != 'form_order' &&  $arResult["processor"] != 'order' && $arResult["processor"] != 'progress') : ?>
	</div>
	<? endif; ?>
<!-- окно всплывающей подсказки -->
<div id="popup-help" class="highslide-html-content">
	<div class="highslide-overlay controlbar clearfix">
		<a class="highslide-move" href="#" onclick="return false;"><span class="title">&nbsp;</span></a>
		<a onclick="return hs.close(this)" class="close" href="#"></a>
	</div>
	<div class="highslide-body"></div>
</div>
<script type="text/javascript">
// <![CDATA[
hs.graphicsDir = '/images/highslide/';
hs.outlineType = 'drop-shadow';
hs.showCredits = false;
hs.loadingText = '<?=GetMessage('tools_lib_loading') ?>';

hs.Expander.prototype.onBeforeExpand = function(sender) {
	$('.'+sender['wrapperClassName']+' .title').text(sender['captionText']);
	$('#'+sender['contentId']+' .highslide-move').width($('#'+sender['contentId']+' .highslide-overlay').width()-25);
}

$(document).ready(function() {
  var timer = setTimeout(function() {
<? if ('Y' == $arParams['~IBE_AJAX_MODE']): ?>
    initArrowBlock();

<? endif; ?>
    if ($('#carrier_matrix').length) {
      resizeAllowedNow = false;
      rebuildCarrierMatrix();
      resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
    }
  }, 1);
});

$(window).resize(function() {
  if ($('#carrier_matrix').length) {
    rebuildCarrierMatrix();
  }
});
// ]]>
</script>
<? endif; ?>
<? if(!$arResult['~PRINT']): // не выводить при печати ?>
</div>
<? endif; ?>
<?
if ( $bOutputStarted ) {
  CIBEAjax::EndArea();
}
?>
</span>
</div>