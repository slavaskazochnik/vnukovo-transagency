<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<div class="catalog-section clearfix">
<script type="text/javascript">
// <![CDATA[
$('#sect_<?=$arResult['ID']?>').addClass('selected');
$('h1.page_title').replaceWith('<h1 class="page_title"><?=$arResult["NAME"]?></h1>');
// ]]>
</script>
	<? //trace($arResult) ?>
	<? //trace($arParams) ?>
	<? $elementsPerCol = ceil( count($arResult["ITEMS"]) / $arParams['LINE_ELEMENT_COUNT'] ); ?>
	<? $colWidth = floor( 100 / $elementsPerCol ); ?>
	
	<? for ( $i = 0; $i < count($arResult["ITEMS"]); $i = $i+$elementsPerCol ): ?> 
	<div class="catalog-section-block" style="width:<?=$colWidth?>%">
		<ul class="catalog-section-elements">
		<? for ( $j = $i ; $j < $i+$elementsPerCol && $j < count($arResult["ITEMS"]) ; $j++ ) : ?>
			<li id="el_<?= $arResult["ITEMS"][$j]["ID"] ?>"><a href="<?= $arResult["ITEMS"][$j]["DETAIL_PAGE_URL"] ?>"><?= $arResult["ITEMS"][$j]["NAME"] ?></a></li>
		<? endfor; ?>
		</ul>
	</div>
	<? endfor; ?>
</div>


<? /*


<table class="data-table" cellspacing="0" cellpadding="0" border="0" width="100%">
	<? foreach($arResult["ITEMS"] as $arElement): ?>
	<tr>
		<td>
			<a href="<?= $arElement["DETAIL_PAGE_URL"] ?>"><?= $arElement["NAME"] ?></a>
			<? if(count($arElement["SECTION"]["PATH"])>0): ?>
				<br />
				<? foreach($arElement["SECTION"]["PATH"] as $arPath): ?>
					/ <a href="<?= $arPath["SECTION_PAGE_URL"] ?>"><?= $arPath["NAME"] ?></a>
				<? endforeach ?>
			<? endif ?>
		</td>
	</tr>
	<? endforeach; ?>
</table>
*/ ?>