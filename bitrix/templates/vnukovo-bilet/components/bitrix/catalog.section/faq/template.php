<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<? //trace($arResult)?>
<div class="catalog-section clearfix">
	<h2 class="caption"><a href="<?=$arResult['SECTION_PAGE_URL']?>"><?= GetMessage('FAQ_CAPTION')?></a></h2>
	<div class="sect_text">
		<ul class="catalog-section-elements clearfix">
		<? foreach($arResult["ITEMS"] as $cell=>$arElement): ?>
		<li style="width:<?= floor(100/$arParams["LINE_ELEMENT_COUNT"]) ?>%;"><a href="<?= $arElement["DETAIL_PAGE_URL"] ?>"><?= $arElement["NAME"] ?></a></li>
		<? endforeach; ?>
		</ul>
		<a class="elements-list-link" href="<?=$arResult['SECTION_PAGE_URL']?>"><?=GetMessage('FAQ_ALL_ELEMENTS')?></a>
	</div>
</div>
