<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<div class="catalog-element">
<? //trace($arResult)?>
<script type="text/javascript">
// <![CDATA[
$('#el_<?=$arResult['ID']?>').addClass('selected');
$('h1.page_title').replaceWith('<h1 class="page_title"><?=$arResult["NAME"]?></h1>');
// ]]>
</script>
<h2><?=$arResult["NAME"]?></h2>
<? if($arResult["DETAIL_TEXT"]): ?>
	<?= $arResult["DETAIL_TEXT"] ?>
<? elseif($arResult["PREVIEW_TEXT"]): ?>
	<?= $arResult["PREVIEW_TEXT"] ?>
<? endif; ?>

<? /* if(is_array($arResult["SECTION"])): ?>
	<a href="<?= $arResult["SECTION"]["SECTION_PAGE_URL"] ?>"><?= GetMessage("CATALOG_BACK") ?></a>
<? endif */ ?>
</div>
