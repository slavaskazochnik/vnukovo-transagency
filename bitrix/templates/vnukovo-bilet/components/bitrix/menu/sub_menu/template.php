<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if (!empty($arResult)):?>
<ul class="sub_menu clearfix">
<? foreach($arResult as $arItem):?>
	<? if ($arItem["PERMISSION"] > "D" && $arItem["DEPTH_LEVEL"] <= $arParams['MAX_LEVEL'] ): ?>
		<li<?= $arItem["SELECTED"]? '  class="selected"' : ''?>><a class="block" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
	<? endif ?>
<? endforeach; ?>
</ul>
<? endif; ?>