<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if (!empty($arResult)):?>
<table class="top_menu">
	<tr>
<? foreach($arResult as $arItem):?>
	<? if ($arItem["PERMISSION"] > "D"): ?>
		<td<?= $arItem["SELECTED"]? ' class="selected"' : ''?>><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></td>
	<? endif ?>
<? endforeach; ?>
	</tr>
</table>
<? endif; ?>