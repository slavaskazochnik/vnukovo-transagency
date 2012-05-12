<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Подключение необходимый css и js файлов
if(!defined("__JQUERY_JS")) {
	define("__JQUERY_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadString(CIBECacheControl::RenderJSLink($templateFolder."/js/jquery-1.5.1.min.js"));
}

if(!defined("__BROWSER_JS")) {
	define("__BROWSER_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadString(CIBECacheControl::RenderJSLink($templateFolder."/js/jquery.browser-2.3.min.js"));
}

if(!defined("__PERCIFORMES_JS")) {
	define("__PERCIFORMES_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadString(CIBECacheControl::RenderJSLink($templateFolder."/js/jquery.perciformes.js"));
}

if(!defined("__SHORTFORM_CALENDAR_CSS")) {
	define("__CALENDAR_CSS", true);
	$GLOBALS["APPLICATION"]->AddHeadString(CIBECacheControl::RenderCSSLink($templateFolder."/css/ui-datepicker.css"));
}

if(!defined("__CALENDAR_JS")) {
	define("__CALENDAR_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadString(CIBECacheControl::RenderJSLink($templateFolder."/js/jquery-ui-1.8.16.custom.min.js"));
}

if(!defined("__SHORTFORM_JS")) {
	define("__SHORTFORM_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadString(CIBECacheControl::RenderJSLink("/bitrix/js/ibe/formtools.js"));
}

if( $USE_AUTOCOMPLETE ) { // Если используется автозаполнение
  if(!defined("__AUTOCOMPLETE_JS")) {
  	define("__AUTOCOMPLETE_JS", true);
  	$GLOBALS["APPLICATION"]->AddHeadString(CIBECacheControl::RenderJSLink($templateFolder."/js/jquery.autocomplete.pack.js"));
  }
  
  if(!defined("__AUTOCOMPLETE_CSS")) {
  	define("__AUTOCOMPLETE_CSS", true);
  	$GLOBALS["APPLICATION"]->AddHeadString(CIBECacheControl::RenderCSSLink($templateFolder."/css/jquery.autocomplete.css"));
  }
}
//-------------------------------------------------------------------------

?>