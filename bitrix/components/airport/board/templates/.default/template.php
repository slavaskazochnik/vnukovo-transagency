<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<div class="board_top">
	<ul class="board-selector clearfix">
		<? foreach( $arResult['FLIGHTS'] as $type => $flights ): ?>
		  <li class="<?= ToLower($type) ?>">
			<?= GetMessage('AIRPORT_BOARD_'.$type) ?>
			<div class="arr"></div>
		  </li>
		<? endforeach; ?>
	</ul>
</div>
<div class="cl"></div>

<div class="airport-board">
<? //trace($arResult) ?>

	<? if ( !empty($arResult['AIRPORTS_LIST']) && $arResult['SHOW_AIRPORTS_FILTER'] == 'Y' ): ?>
	<ul class="sub_menu clearfix">
	<? foreach($arResult['AIRPORTS_LIST'] as $arItem): ?>
		<li<?= $arItem['SELECTED'] == 'Y' ? '  class="selected"' : '' ?> id="<?= strtolower($arItem['CODE']) ?>"><a class="block" href="?airport=<?= $arItem['CODE'] ?>"><?= $arItem['NAME'] ?></a></li>
	<? endforeach; ?>
	</ul>
	<? endif; ?>
	<div class="sect_text sect_board">
	<? foreach( $arResult['FLIGHTS'] as $type => $flights ): ?>
		<? $logoStyles = ''; 
		foreach ( $flights['AK_CODES'] as $ak ) {
			$logoStyles .= '.logo-normal-' . $ak . '{background-image: url("http://images.travelshop.aero/airlines/normal/' . $ak . '.gif");} 
			';
		}
		?>
		<? // $APPLICATION->AddHeadString(	'<style type="text/css">' . $logoStyles . '</style>', true ) ?>
		<style type="text/css"><?=$logoStyles?></style>
	
	  <div class="board <?= ToLower($type) ?>">
		<? if ( $flights['ERROR']['CODE']) : ?>
			<?= $flights['ERROR']['CODE'] . ': ' . $flights['ERROR']['MESSAGE'] ?>
		<? else: ?>
      <? if ( count($flights['FLIGHTS']) ): ?>
        <div class="update-time"><?= GetMessage('AIRPORT_BOARD_UPDATED') ?>&nbsp;<?= ConvertTimeStamp(false, "FULL") /*FormatDate("isago", getmicrotime())*/ ?></div>
        <? /* ?><h3><?= GetMessage('AIRPORT_BOARD_'.$type.'_HEADING') ?></h3><? */ ?>
        <table>
          <thead>
            <tr>
              <th class="company">&nbsp;</th>
              <th class="flight"><?= GetMessage('AIRPORT_BOARD_FLIGHT') ?></th>
              <th class="route"><?= GetMessage('AIRPORT_BOARD_ROUTE') ?></th>
              <th class="time"><?= GetMessage('AIRPORT_BOARD_TIME_PLANNED') ?> <div class="subtitle"><?= GetMessage('AIRPORT_BOARD_TIME') ?></div></th>
              <th class="time"><?= GetMessage('AIRPORT_BOARD_TIME_ESTIMATED') ?> <div class="subtitle"><?= GetMessage('AIRPORT_BOARD_TIME') ?></div></th>
              <th class="time"><?= GetMessage('AIRPORT_BOARD_TIME_ACTUAL') ?> <div class="subtitle"><?= GetMessage('AIRPORT_BOARD_TIME') ?></div></th>
			  <th class="terminal"><?= GetMessage('AIRPORT_BOARD_TERMINAL') ?></th>
              <th class="state"><?= GetMessage('AIRPORT_BOARD_STATE') ?></th>
            </tr>
          </thead>
          <tbody>
          <? $n = 0; ?>
          <? foreach ( $flights['FLIGHTS'] as $flight ): ?>
          <? $n++; ?>
          <? $class = floor($n/2) == $n/2 ? 'even' : 'odd' ?>
          <tr class=" <?= strtolower($type) ?> terminal_<?= strtolower($flight['TERMINAL']) ?> state_<?= strtolower($flight['STATUS']['CODE']) ?> <?= $class ?>">
            <td class="company logo-normal-<?= $flight['FLIGHT']['AK_CODE'] ?>" title="<?= $flight['AK_NAME'] ?>">&nbsp;</td>
            <td class="flight"><?= $flight['FLIGHT']['AK_CODE'] ?>&nbsp;-&nbsp;<?= $flight['FLIGHT']['NUMBER'] ?></td>
            <td class="route"><?= $type == 'INBOUND' ? $flight['DEPARTURE'] : $flight['ARRIVAL'] ?></td>
            <td class="time"><?= $flight['TIME']['PLANNED']['TIME'] ?></td>
            <td class="time"><?= $flight['TIME']['ESTIMATED']['TIME'] ?></td>
            <td class="time"><?= $flight['TIME']['ACTUAL']['TIME'] ?></td>
			<td class="terminal"><?= $flight['TERMINAL'] ?></td>
            <td class="state state_<?= strtolower($flight['STATUS']['CODE']) ?>"><?= $flight['STATUS']['NAME'] ?></td>
		  </tr>
          <? endforeach; ?>
          </tbody>
        </table>
        <? endif; ?>
    <? endif; ?>
    </div>
  <? endforeach; ?>
  </div>
	<? /* trace($arResult["FLIGHTS"]["INBOUND"]["ERROR"]["CODE"]) ?>
	<div>Время жизни кеша: <?= $arParams["CACHE_TIME"] ?> c</div>
	<div>Прилетающих рейсов: <?= count($arResult["FLIGHTS"]["INBOUND"]["FLIGHTS"]) ?></div>
	<div>Вылетающих рейсов: <?= count($arResult["FLIGHTS"]["OUTBOUND"]["FLIGHTS"]) ?></div>
	*/ ?>
</div>
<script type="text/javascript">
// <![CDATA[
$('.board_top .board-selector li').click( function(){
	var boardType = $(this).hasClass('inbound') ? 'inbound' : $(this).hasClass('outbound') ?  'outbound' : '';
	var aptName;
	
	$('.sub_menu li').each(function(i){
		if( $(this).hasClass('selected') ){
			aptName = $(this).text();
		}
	});
	var pageTitle = 
		$(this).hasClass('inbound') ? '<h1 class="page_title"><?=GetMessage('AIRPORT_BOARD_INBOUND_HEADING') ?>' + aptName + '</h1>' :
		 $(this).hasClass('outbound') ? '<h1 class="page_title"><?=GetMessage('AIRPORT_BOARD_OUTBOUND_HEADING') ?>' + aptName + '</h1>' : 
		'';	
	$('h1.page_title').replaceWith(pageTitle);
	
	$('.board_top .board-selector li').removeClass('selected');
	$(this).addClass('selected');
	$(".airport-board .board").hide();
	$(".airport-board .board." + boardType ).show();
});

$(document).ready(function(){
  $(".board_top .board-selector .inbound").click();
})
// ]]>
</script>