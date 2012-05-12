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
	<div class="tmp"></div>
	<div id="header" class="clearfix">
		<div class="wrap clearfix">
			<div id="logo"><a href="<?= SITE_DIR ?>"></a></div>
		</div>
	</div>
	<div id="page" class="clearfix">
		<div class="wrap">

	<!-- site_closed -->
	<div class="site-closed">
		<h2 class="title">
			<?=GetMessage('SITE_CLOSED_TITLE') ?>
		</h2>
		<div class="description">
			<?=GetMessage('SITE_CLOSED_DESC') ?>
		</div>
	</div>
	<!-- /site_closed -->
	
			<div id="footer" class="clearfix">
			<? $APPLICATION->IncludeFile("copyright.php"); ?>
			</div>
		</div>
	</div>
</div>
</body>
</html>
