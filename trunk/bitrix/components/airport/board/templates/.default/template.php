<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<div class="airport-board">
<? //trace($arResult) ?>

	<? if ( !empty($arResult['AIRPORTS_LIST']) && $arResult['SHOW_AIRPORTS_FILTER'] == 'Y' ): ?>
	<ul class="sub_menu clearfix">
	<? foreach($arResult['AIRPORTS_LIST'] as $arItem): ?>
		<li<?= $arItem['SELECTED'] == 'Y' ? '  class="selected"' : '' ?>><a class="block" href="?airport=<?= $arItem['CODE'] ?>"><?= $arItem['NAME'] ?></a></li>
	<? endforeach; ?>
	</ul>
	<? endif; ?>
	<div class="sect_text sect_board">
	<? foreach( $arResult['FLIGHTS'] as $type => $flights ): ?>
		<? if ( $flights['ERROR']['CODE']) : ?>
			<?= $flights['ERROR']['CODE'] . ': ' . $flights['ERROR']['MESSAGE'] ?>
		<? else: ?>
		<div class="update-time"><?= GetMessage('AIRPORT_BOARD_UPDATED') ?>&nbsp;<?= ConvertTimeStamp(false, "FULL") /*FormatDate("isago", getmicrotime())*/ ?></div>
		<h3><?= GetMessage('AIRPORT_BOARD_'.$type.'_HEADING') ?></h3>
		<table class="board <?= strtolower($type) ?>">
			<thead>
				<tr>
					<th class="terminal">&nbsp;</th>
					<th class="company"><?= GetMessage('AIRPORT_BOARD_AIRCOMPANY') ?></th>
					<th class="flight"><?= GetMessage('AIRPORT_BOARD_FLIGHT') ?></th>
					<th class="route"><?= GetMessage('AIRPORT_BOARD_ROUTE') ?></th>
					<th class="time"><?= GetMessage('AIRPORT_BOARD_TIME') ?> <div class="subtitle"><?= GetMessage('AIRPORT_BOARD_TIME_PLANNED') ?></div></th>
					<th class="time"><?= GetMessage('AIRPORT_BOARD_TIME') ?> <div class="subtitle"><?= GetMessage('AIRPORT_BOARD_TIME_ESTIMATED') ?></div></th>
					<th class="time"><?= GetMessage('AIRPORT_BOARD_TIME') ?> <div class="subtitle"><?= GetMessage('AIRPORT_BOARD_TIME_ACTUAL') ?></div></th>
					<th class="state"><?= GetMessage('AIRPORT_BOARD_STATE') ?></th>
				</tr>
			</thead>
			<tbody>
			<? $n = 0; ?>
			<? foreach ( $flights['FLIGHTS'] as $flight ): ?>
			<? $n++; ?>
			<? $class = floor($n/2) == $n/2 ? 'even' : 'odd' ?>
			<tr class=" <?= strtolower($type) ?> terminal_<?= strtolower($flight['TERMINAL']) ?> <?= $class ?>">
				<td class="terminal"><?= $flight['TERMINAL'] ?></td>
				<td class="company"><?= $flight['AK_NAME'] ?></td>
				<td class="flight"><?= $flight['FLIGHT']['AK_CODE'] ?>&nbsp;-&nbsp;<?= $flight['FLIGHT']['NUMBER'] ?></td>
				<td class="route"><?= $type == 'INBOUND' ? $flight['DEPARTURE'] : $flight['ARRIVAL'] ?></td>
				<td class="time"><?= $flight['TIME']['PLANNED']['TIME'] ?></td>
				<td class="time"><?= $flight['TIME']['ESTIMATED']['TIME'] ?></td>
				<td class="time"><?= $flight['TIME']['ACTUAL']['TIME'] ?></td>
				<td class="state state_<?= strtolower($flight['STATUS']['CODE']) ?>"><?= $flight['STATUS']['NAME'] ?></td>
			<? endforeach; ?>
			</tbody>
		</table>
		<? endif; ?>
	<? endforeach; ?>
	</div>
	<? /* trace($arResult["FLIGHTS"]["INBOUND"]["ERROR"]["CODE"]) ?>
	<div>Время жизни кеша: <?= $arParams["CACHE_TIME"] ?> c</div>
	<div>Прилетающих рейсов: <?= count($arResult["FLIGHTS"]["INBOUND"]["FLIGHTS"]) ?></div>
	<div>Вылетающих рейсов: <?= count($arResult["FLIGHTS"]["OUTBOUND"]["FLIGHTS"]) ?></div>
	*/ ?>
</div>
