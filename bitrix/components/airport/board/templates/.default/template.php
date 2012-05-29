<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<? trace($arResult["FLIGHTS"]["INBOUND"]["ERROR"]["CODE"]) ?>
<div class="airport-board">
<div>Время жизни кеша: <?= $arParams["CACHE_TIME"] ?> c</div>
<div>Прилетающих рейсов: <?= count($arResult["FLIGHTS"]["INBOUND"]["FLIGHTS"]) ?></div>
<div>Вылетающих рейсов: <?= count($arResult["FLIGHTS"]["OUTBOUND"]["FLIGHTS"]) ?></div>
</div>
