<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<? trace($arResult["FLIGHTS"]["INBOUND"]["ERROR"]["CODE"]) ?>
<div class="airport-board">
<div>����� ����� ����: <?= $arParams["CACHE_TIME"] ?> c</div>
<div>����������� ������: <?= count($arResult["FLIGHTS"]["INBOUND"]["FLIGHTS"]) ?></div>
<div>���������� ������: <?= count($arResult["FLIGHTS"]["OUTBOUND"]["FLIGHTS"]) ?></div>
</div>
