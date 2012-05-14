<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if( defined("SHOW_404") || SHOW_404 == "Y") { $APPLICATION->IncludeFile("404.php"); return; }
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/ibe/classes/ibe/utils.php');
IncludeTemplateLangFile(__FILE__);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
<meta http-equiv="Content-Type"	content="text/html; charset=<?=LANG_CHARSET ?>" />
<? $APPLICATION->ShowMeta("keywords")?>
<? $APPLICATION->ShowMeta("description")?>

<title><?=$arLang["SITE_NAME"] ? $arLang["SITE_NAME"]." &#151; " : "" ?><? $APPLICATION->ShowTitle(); ?></title>

<link type="image/x-icon" href="/favicon.ico" rel="icon" />
<link type="image/ico" href="/favicon.ico" rel="SHORTCUT ICON" />

<? $APPLICATION->ShowCSS() ?>
<? define("__JQUERY_JS", true); ?>
<?=CIBECacheControl::RenderJSLink('/bitrix/templates/'.SITE_TEMPLATE_ID.'/js/jquery-1.5.1.min.js'); ?>
<? define("__BROWSER_JS", true); ?>
<?=CIBECacheControl::RenderJSLink('/bitrix/templates/'.SITE_TEMPLATE_ID.'/js/jquery.browser-2.3.min.js'); ?>
<? define("__TOOLTIP_JS", true); ?>
<?=CIBECacheControl::RenderJSLink('/bitrix/templates/'.SITE_TEMPLATE_ID.'/js/jquery.tooltip-1.3.js'); ?>

<? method_exists($APPLICATION, 'ShowHeadStrings') ? $APPLICATION->ShowHeadStrings() : ''?>
<? method_exists($APPLICATION, 'ShowHeadScripts') ? $APPLICATION->ShowHeadScripts() : ''?>

<script type="text/javascript">
// <![CDATA[
function tooltip(selector) {
  var titles = ('undefined' === typeof selector) ? $('[title]') : selector.find('[title]');
  if (titles.length) {
    titles.tooltip({
      bodyHandler: function() {
        return $('<div class="arr"></div><div class="inner">'.concat(this.tooltipText, '</div>'));
      },
      showURL: false,
      track: true,
      top: 20,
      left: -75,
      width: 160,
      fixPNG: true
    });
  }
}
// ]]>
</script>
</head>

<body <?=$APPLICATION->GetCurUri() == SITE_DIR."index.php" || $APPLICATION->GetCurUri() == SITE_DIR ? ' id="home"' : '' ?>>
<div id="panel" class="noprint">
	<? $APPLICATION->ShowPanel();?>
</div>
<div id="layout">
	<div class="tmp"></div>
	<div id="header" class="clearfix">
		<a class="main_help" href="#"></a>
		<div class="wrap clearfix">
			<div id="logo"><a href="<?= SITE_DIR ?>"></a></div>
			<? $APPLICATION->IncludeComponent(
				"bitrix:menu",
				"header",
				Array(
					"ROOT_MENU_TYPE" => "header",
					"MAX_LEVEL" => "1"
				)
			); ?>	
		</div>
	</div>
	<div id="main_navigation" class="clearfix">
		<div class="wrap">
		<? $APPLICATION->IncludeComponent(
			"bitrix:menu",
			"main_menu",
			Array(
				"ROOT_MENU_TYPE" => "top",
				"MAX_LEVEL" => "1"
			)
		); ?>
		</div>
	</div>
	<div id="page" class="clearfix">
		<div class="wrap">
			<div id="content" class="clearfix">
			<? $APPLICATION->ShowProperty("CONTENT_PREFACE"); ?>