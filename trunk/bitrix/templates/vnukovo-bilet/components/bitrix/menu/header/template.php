<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if (!empty($arResult)):?>
<div class="header_links">
	<a href="<?= SITE_DIR ?>" class="home" title="<?= GetMessage('HEADER_LINKS_HOME') ?>"></a>
<? foreach($arResult as $arItem):?>
	<a<?= $arItem["SELECTED"]? ' class="selected"' : ''?> href="<?=$arItem[1]?>"><?=$arItem[0]?></a>
<? endforeach; ?>
</div>
<? endif; ?>