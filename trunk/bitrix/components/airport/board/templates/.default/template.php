<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<script type="text/javascript" src="<?= $templateFolder ?>/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="<?= $templateFolder ?>/js/placeholder.js"></script>

<div class="board_top">
	<div class="terminal-selector clearfix">
		<? foreach( $arResult['FLIGHTS'] as $type => $flights ): ?>
			<? if ( count($flights['TERMINALS']) < 2 ) continue; ?>
		<ul class="terminals <?= ToLower($type) ?>">
			<li class="title"><?= GetMessage('AIRPORT_BOARD_TERMINALS') ?></li>
			<? foreach ( $flights['TERMINALS'] as $terminal ) : ?>
				<? if ( $terminal ) : ?>
			<li id="terminal_<?= trim(ToLower($terminal)) ?>"><?= $terminal ?><div class="arr"></div></li>
				<? endif; ?>
			<? endforeach; ?>
			<li class="terminal_all"><?= GetMessage('AIRPORT_BOARD_TERMINALS_ALL') ?><div class="arr"></div></li>
		</ul>
		<? endforeach; ?>
	</div>
	<div class="board-selector clearfix">
		<ul>
		<? foreach( $arResult['FLIGHTS'] as $type => $flights ): ?>
		  <li class="<?= ToLower($type) ?>">
			<?= GetMessage('AIRPORT_BOARD_'.$type) ?>
			<div class="arr"></div>
		  </li>
		<? endforeach; ?>
		</ul>
	</div>
</div>
<div class="cl"></div>

<div class="airport-board">
<? //trace($arResult) ?>

	<? if ( !empty($arResult['AIRPORTS_LIST']) && $arResult['SHOW_AIRPORTS_FILTER'] == 'Y' ): ?>
	<ul class="sub_menu clearfix">
	<? foreach($arResult['AIRPORTS_LIST'] as $arItem): ?>
		<li<?= $arItem['SELECTED'] == 'Y' ? '  class="selected"' : '' ?> id="<?= ToLower($arItem['CODE']) ?>"><a class="block" href="?airport=<?= $arItem['CODE'] ?>"><?= $arItem['NAME'] ?></a></li>
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
			<? ShowError( $flights['ERROR']['MESSAGE'] ) ?>
		<? else: ?>
      <? if ( count($flights['FLIGHTS']) ): ?>
	  
			<div class="filters clearfix">
				<form action="#" name="filters" mathod="post">
					<div class="filter filter_flight">
						<input class="text" id="filter_flight_<?= ToLower($type) ?>" name="filter_flight" type="text" placeholder="<?= GetMessage('AIRPORT_BOARD_PH_FLIGHT_NUMBER') ?>" value="" onchange="filterFlights('<?= ToLower($type) ?>');checkFilterByFlight('<?= ToLower($type) ?>', $(this).val());" />
						<div class="clear" onclick="clearFilterByFlight('<?= ToLower($type) ?>');">&times;</div>
						<script type="text/javascript">// <![CDATA[
						  inputPlaceholder( document.getElementById('filter_flight_<?= ToLower($type) ?>') );
						// ]]></script>
					</div>
					<? if ( count($flights['AK_NAMES']) > 1 ) : ?>
					<div class="filter filter_ak">
						<div class="select_wrapper">
							<select id="filetr_ak_<?= ToLower($type) ?>" name="filetr_ak" onchange="filterFlights('<?= ToLower($type) ?>');">
								<option selected="selected" value="all"><?= GetMessage('AIRPORT_BOARD_PH_AIRCOMPANY') ?></option>
								<? foreach ( $flights['AK_NAMES'] as $n => $ak ) : ?>
								<option value="<?= $ak ?>">
								<?= $ak ?>
								</option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					<? endif; ?>
					<? $dir = $type == 'INBOUND' ? 'DEPARTURES' : 'ARRIVALS' ?>
					<? if ( count($flights[$dir]) > 1 ) : ?>
					<div class="filter filter_route">
						<div class="select_wrapper">
							<select id="filter_route_<?= ToLower($type) ?>" name="filter_route" onchange="filterFlights('<?= ToLower($type) ?>');">
								<option selected="selected" value="all"><?= GetMessage('AIRPORT_BOARD_PH_ROUTE') ?></option>
								<? foreach ( $flights[$dir] as $route ) : ?>
								<option value="<?= $route ?>">
								<?= $route ?>
								</option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					<? endif; ?>
					<?  if ( 0 ) : ?>
					<div class="filter filter_days_<?= ToLower($type) ?>">
						<div class="select_wrapper">
							<select id="filter_days" name="filter_days" value="all">
								<option selected="selected" value="all"><?= GetMessage('AIRPORT_BOARD_PH_DAYS') ?></option>
							<? foreach ( $flights[$x] as $y ) : ?>
								<option value="">
								<?= '' ?>
								</option>
							<? endforeach; ?>
							</select>
						</div>
					</div>
					<? endif;  ?>
					
					<div class="submit">
						<input class="button" type="submit" value="<?=GetMessage("AIRPORT_BOARD_PH_SEARCH") ?>" id="filters_submit_<?= ToLower($type) ?>" onclick="filterFlights('<?= ToLower($type) ?>');return false;" />
					</div>
				</form>
			</div>
	  
	  
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
          <tr class="<?= ToLower($type) ?> terminal_<?= trim(ToLower($flight['TERMINAL'])) ?> state_<?= ToLower($flight['STATUS']['CODE']) ?> <?= $class ?>">
            <td class="company logo-normal-<?= $flight['FLIGHT']['AK_CODE'] ?>"<? if ( strlen($flight['AK_NAME']) ): ?>title="<?= $flight['AK_NAME'] ?>"<? endif; ?>>
				<div class="company_name"><?= $flight['AK_NAME'] ?></div>
				&nbsp;
			</td>
            <td class="flight"><?= $flight['FLIGHT']['AK_CODE'] ?>&ndash;<?= $flight['FLIGHT']['NUMBER'] ?></td>
            <td class="route"><?= $type == 'INBOUND' ? $flight['DEPARTURE'] : $flight['ARRIVAL'] ?></td>
            <td class="time"><?= $flight['TIME']['PLANNED']['TIME'] ?><?= isset($flight['TIME']['PLANNED']['DATE']['DAY']) ? ' <div class="date">'.$flight['TIME']['PLANNED']['DATE']['DAY'].'/'.$flight['TIME']['PLANNED']['DATE']['MONTH'].'</div>' : "" ?></td>
            <td class="time"><?= $flight['TIME']['ESTIMATED']['TIME'] ?><?= isset($flight['TIME']['ESTIMATED']['DATE']['DAY']) ? ' <div class="date">'.$flight['TIME']['ESTIMATED']['DATE']['DAY'].'/'.$flight['TIME']['ESTIMATED']['DATE']['MONTH'].'</div>' : "" ?></td>
            <td class="time"><?= $flight['TIME']['ACTUAL']['TIME'] ?><?= isset($flight['TIME']['ACTUAL']['DATE']['DAY']) ? ' <div class="date">'.$flight['TIME']['ACTUAL']['DATE']['DAY'].'/'.$flight['TIME']['ACTUAL']['DATE']['MONTH'].'</div>' : "" ?></td>
			<td class="terminal"><?= $flight['TERMINAL'] ?></td>
            <td class="state state_<?= ToLower($flight['STATUS']['CODE']) ?>"><?= $flight['STATUS']['NAME'] ?></td>
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

// Выбор типа табло (вылет/прилет)
$('.board_top .board-selector li').click( function(){
	var boardType = $(this).hasClass('inbound') ? 'inbound' : $(this).hasClass('outbound') ?  'outbound' : '';
	var aptName = '';

	$('.sub_menu li').each(function(i){
		if( $(this).hasClass('selected') ){
			aptName = $(this).text();
		}
	});

	var pageTitle =
		$(this).hasClass('inbound') ? '<?=GetMessage('AIRPORT_BOARD_INBOUND_HEADING') ?>' + aptName :
		$(this).hasClass('outbound') ? '<?=GetMessage('AIRPORT_BOARD_OUTBOUND_HEADING') ?>' + aptName :
		'<?=GetMessage('AIRPORT_BOARD') ?>';
	$('h1.page_title').replaceWith('<h1 class="page_title">' + pageTitle + '</h1>');
	$('title').replaceWith('<title>' + pageTitle + '</title>');

	$('.board_top .board-selector li').removeClass('selected');
	$(this).addClass('selected');
	$('.airport-board .board').hide();
	$('.airport-board .board.' + boardType ).show();
	$('.terminal-selector .terminals').hide();
	fixThead();
	$('.terminal-selector .terminals.' + boardType ).show();
});

//Выбор терминала
$('.terminal-selector .terminals li').click( function(){
	if ( $(this).hasClass('title') ) { return;}
	var boardType = $(this).parent('ul').hasClass('inbound') ? 'inbound' : $(this).parent('ul').hasClass('outbound') ?  'outbound' : '';
	var terminal = $(this).attr('id') ? $(this).attr('id') : 'all';
	if ( terminal == 'all' ) {
		$('.airport-board .board tbody tr').removeClass('terminal_hide');
	} else {
		$('.airport-board .board.' + boardType +' tbody tr').addClass('terminal_hide');
		$('.airport-board .board.' + boardType +' tbody tr.' + terminal ).removeClass('terminal_hide');
	}
	$('.terminal-selector .terminals.' + boardType +' li').removeClass('selected');
	$(this).addClass('selected');
	
	var flightsCount = 0;
	$('.airport-board .board.' + boardType +' tbody tr').each(function(){
		if(!$(this).hasClass('terminal_hide') && !$(this).hasClass('filtered')){
			flightsCount++;
		}
	});
	if ( flightsCount == 0 ) {
			alert('<?= GetMessage('AIRPORT_BOARD_NO_RESULT') ?>');
	}
	fixThead();
});

// Включаем сортировку для таблицы с рейсами
$(document).ready(function(){
  $(".airport-board .board table").tablesorter({
    cssHeader: "flightHeader",
    cssAsc: "flightHeaderSortUp",
    cssDesc: "flightHeaderSortDown",
    headers: { 0: { sorter: false}}
  });
});

// Инициализация табло
$(document).ready(function(){
  $('.board_top .board-selector .inbound').click();
  $('.terminal-selector .terminals .terminal_all').click();

});

// Фильтры
function trim( str, charlist ) {
	charlist = !charlist ? ' \s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
	var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');
	return str.replace(re, '');
}

function checkFilterResult(type){
	var flightsCount = 0;
	$('.board.'+type+' table tbody tr').each(function(){
		if(!$(this).hasClass('terminal_hide') && !$(this).hasClass('filtered')){
			flightsCount++;
		}
	});
	if ( flightsCount == 0 ) {
			alert('<?= GetMessage('AIRPORT_BOARD_NO_RESULT') ?>');
	}
}

function filterByFlight (type) {
	var filterFlight = 0;
	
	if ( $('#filter_flight_'+type).val() ) {
		filterFlight = $('#filter_flight_'+type).val();
		
		var FilterFlightNum = Number(filterFlight);
		var FilterFlightCode;
		if ( isNaN(FilterFlightNum)) {
			var FilterFlightCode = filterFlight.substr(0,2).toUpperCase();
			FilterFlightNum = Number(trim(filterFlight.substr(2), '-–— '));
			if ( isNaN(FilterFlightNum)) {
				filterFlight = 0;
				alert('<?= GetMessage('AIRPORT_BOARD_FLIGNT_NUN_ERR')?>');
			}
		}
		
		var flight_num, flight_code;
		$('.board.'+type+' table tbody tr').each(function(){
			flight_num = Number(trim($(this).children('.flight').text()).substr(3));
			flight_code =  trim($(this).children('.flight').text()).substr(0,2);
			
			if ( !$(this).hasClass('filtered') && (
					(!FilterFlightCode && FilterFlightNum && FilterFlightNum != flight_num) ||
					( FilterFlightCode && FilterFlightNum && (FilterFlightNum != flight_num || FilterFlightCode != flight_code))  
			)) {
				$(this).addClass('filtered');
			}
		});
	}
}

function filterByAk (type) {
	var filterAk = $('#filetr_ak_'+type).val() == 'all' ? 0 : $('#filetr_ak_'+type).val();
	$('.board.'+type+' table tbody tr').each(function(){
		ak = trim($(this).children().children('.company_name').text());
		if ( !$(this).hasClass('filtered') && filterAk != ak ){
			$(this).addClass('filtered');
		}
	}); 
} 

function filterByRoute (type) {
	var filterRoute = $('#filter_route_'+type).val() == 'all' ? 0 : $('#filter_route_'+type).val();
	$('.board.'+type+' table tbody tr').each(function(){
		route = trim($(this).children('.route').text());
		if ( !$(this).hasClass('filtered') && filterRoute != route ){
			$(this).addClass('filtered');
		}
	}); 
}

function filterFlights(type){	
	var filterFlight = $('#filter_flight_'+type).val() ? $('#filter_flight_'+type).val() : 0;
	var filterAk = $('#filetr_ak_'+type).val() == 'all' ? 0 : $('#filetr_ak_'+type).val();
	var filterRoute = $('#filter_route_'+type).val() == 'all' ? 0 : $('#filter_route_'+type).val();
	$('.board.'+type+' table tbody tr').removeClass('filtered');
	if ( !filterFlight && !filterAk && !filterRoute ) { return false; }
	if ( filterFlight ) { filterByFlight(type); }
	if ( filterAk ) { filterByAk(type); }
	if ( filterRoute ) { filterByRoute(type); }
	checkFilterResult(type);
	fixThead();
}

function clearFilterByFlight(type){
	$('#filter_flight_'+type).val('');
	$('#filter_flight_'+type).parent().children('.clear').hide();
	filterFlights(type);
}

function checkFilterByFlight(type,val){
	if( val ){
		$('#filter_flight_'+type).parent().children('.clear').show();
	} else {
		$('#filter_flight_'+type).parent().children('.clear').hide();
	}
}

// Фиксирование заголовка таблицы
function fixThead(){
	var fixDiv;
	$('.board table thead th').each(function(){
		$(this).children('.fix').remove();
		fixDiv = '<div class="fix" style="position:fixed;width:'+$(this).width()+'px;height:'+$(this).height()+'px;">' +$(this).html()+ '</div>';
		$(this).append(fixDiv);
	});
}
$(document).ready(fixThead());

$(window).scroll(function() {
	var type = $('.board.inbound').css('display') == 'block' ? 'inbound' : 'outbound';
	var offset = $('.board.'+type+' table thead').offset();
	if ($(window).scrollTop() > offset.top) {
		$('.board.'+type+' table thead tr th .fix').show();
	}
	else {
		$('.board.'+type+' table thead tr th .fix').hide();
	};
});

// ]]>
</script>