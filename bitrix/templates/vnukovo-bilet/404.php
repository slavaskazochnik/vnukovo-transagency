<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? IncludeTemplateLangFile(__FILE__); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= LANG_CHARSET;?>" />
<meta name="robots" content="all" />
<? $APPLICATION->ShowMeta("keywords")?>
<? $APPLICATION->ShowMeta("description")?>
<title><? $APPLICATION->ShowTitle()?></title>
<? $APPLICATION->ShowCSS()?>
<? method_exists($APPLICATION->ShowHeadStrings()) ? $APPLICATION->ShowHeadStrings() : ''?>
<? method_exists($APPLICATION->ShowHeadScripts()) ? $APPLICATION->ShowHeadScripts() : ''?>
<link rel="shortcut icon" href="/favicon.ico" />
</head>

<body>
<div id="layout">
	<div id="header" class="clearfix">
		<div class="wrap clearfix">
			<div id="logo"><a href="<?= SITE_DIR ?>"></a></div>
		</div>
	</div>
	<div id="middle" class="clearfix">
		<div class="wrap">
			<div id="content" class="clearfix">
	<!-- 404 -->
	<h1 class="page_title"><?=GetMessage('404_TITLE') ?></h1>
	<div class="error-404 sect_text">
		<div class="description">
			<?=GetMessage('404_DESC') ?><br />
			<?=GetMessage('404_GO_TO') ?> <a href="<?=SITE_DIR?>"><?=GetMessage('404_MAIN_PAGE') ?></a>.
			
		</div>
	</div>
	<!-- /404 -->
			</div>
			<div id="footer" class="clearfix">
			<? $APPLICATION->IncludeFile("copyright.php"); ?>
			</div>
		</div>
	</div>
</div>
</body>
</html>
