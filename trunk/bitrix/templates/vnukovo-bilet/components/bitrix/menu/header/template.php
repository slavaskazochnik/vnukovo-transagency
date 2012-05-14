<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if (!empty($arResult)):?>
<div class="header_links">
	<a href="<?= SITE_DIR ?>" class="home" title="<?= GetMessage('HEADER_LINKS_HOME') ?>"></a>
<? foreach($arResult as $arItem):?>
	<? if ($arItem["PERMISSION"] > "D" && $arItem["DEPTH_LEVEL"] <= $arParams['MAX_LEVEL'] ): ?>
		<a<?= $arItem["SELECTED"]? ' class="selected"' : ''?> href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
	<? endif ?>
<? endforeach; ?>
</div>
<? endif; ?>