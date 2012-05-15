<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<div class="catalog-top clearfix">
	<? //trace($arResult) ?>
	<? //trace($arParams) ?>

<? if(CModule::IncludeModule("iblock") && ($arIBlock = GetIBlock($arParams['IBLOCK_ID'], $arParams['~IBLOCK_TYPE']))) {
	$iBlockCaption = $arIBlock['NAME'] && $arIBlock['LIST_PAGE_URL'] ? '<a href="' . $arIBlock['LIST_PAGE_URL'] . '">' . $arIBlock['NAME'] . '</a>' : '' ;
	$iBlockCaption = $iBlockCaption ? '<h2 class="caption">' . $iBlockCaption . '</h2>' : '';
	
	$iBlockLink = $arIBlock['LIST_PAGE_URL'] ? '<a class="elements-list-link" href="' . $arIBlock['LIST_PAGE_URL'] . '">' . GetMessage('CATALOG_ALL_ELEMENTS') . $arIBlock['ELEMENTS_NAME'] . '</a>' : '' ;
} ?>
	<?= $iBlockCaption ?>
	<div class="sect_text">
		<ul class="catalog-top-elements clearfix">
			<? foreach($arResult["ROWS"] as $arItems): ?>
				<? foreach($arItems as $arElement): ?>
					<? if(is_array($arElement)): ?>
					<li style="width:<?= $arResult["TD_WIDTH"] ?>;">
						<a href="<?= $arElement["DETAIL_PAGE_URL"] ?>"><?= $arElement["NAME"] ?></a>
					</li>
					<? endif; ?>
				<? endforeach ?>
			<? endforeach ?>
		</ul>
		<?=$iBlockLink ?>
	</div>
</div>
