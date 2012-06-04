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
						<input class="text" id="filter_flight" name="filter_flight" type="text" placeholder="<?= GetMessage('AIRPORT_BOARD_PH_FLIGHT_NUMBER') ?>" value="" />
						<script type="text/javascript">// <![CDATA[
						  inputPlaceholder( document.getElementById('filter_flight') );
						// ]]></script>
					</div>
					<? if ( count($flights['AK_NAMES']) > 1 ) : ?>
					<div class="filter filter_ak">
						<div class="select_wrapper">
							<select id="filetr_ak" name="filetr_ak">
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
							<select id="filter_route" name="filter_route">
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
					<div class="filter filter_days">
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
						<input class="button" type="submit" value="<?=GetMessage("AIRPORT_BOARD_PH_SEARCH") ?>" id="filters_submit" />
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
	<div>����� ����� ����: <?= $arParams["CACHE_TIME"] ?> c</div>
	<div>����������� ������: <?= count($arResult["FLIGHTS"]["INBOUND"]["FLIGHTS"]) ?></div>
	<div>���������� ������: <?= count($arResult["FLIGHTS"]["OUTBOUND"]["FLIGHTS"]) ?></div>
	*/ ?>
</div>
<script type="text/javascript">
// <![CDATA[

// ����� ���� ����� (�����/������)
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
	$('.terminal-selector .terminals.' + boardType ).show();
});

//����� ���������
$('.terminal-selector .terminals li').click( function(){
	if ( $(this).hasClass('title') ) { return;}
	var boardType = $(this).parent('ul').hasClass('inbound') ? 'inbound' : $(this).parent('ul').hasClass('outbound') ?  'outbound' : '';
	var terminal = $(this).attr('id') ? $(this).attr('id') : 'all';
	if ( terminal == 'all' ) {
		$('.airport-board .board tbody tr').show();
	} else {
		$('.airport-board .board.' + boardType +' tbody tr').hide();
		$('.airport-board .board.' + boardType +' tbody tr.' + terminal ).show();
	}
	$('.terminal-selector .terminals.' + boardType +' li').removeClass('selected');
	$(this).addClass('selected');
});

// �������� ���������� ��� ������� � �������
$(document).ready(function(){
  $(".airport-board .board table").tablesorter({
    cssHeader: "flightHeader",
    cssAsc: "flightHeaderSortUp",
    cssDesc: "flightHeaderSortDown",
    headers: { 0: { sorter: false}}
  });
});

// ������������� �����
$(document).ready(function(){
  $('.board_top .board-selector .inbound').click();
  $('.terminal-selector .terminals .terminal_all').click();

});

// �������
function trim( str, charlist ) {
	charlist = !charlist ? ' \s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
	var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');
	return str.replace(re, '');
}

$('.filters #filters_submit').click( function(){
	var boardType = $(this).parents('.board').hasClass('inbound') ? 'inbound' : 'outbound';
	$('.board.'+boardType+' table tbody tr').show();
	
	var filterFlight = $('#filter_flight').val() ? $('#filter_flight').val() : 0;
	var filterAk = $('#filetr_ak').val() == 'all' ? 0 : $('#filetr_ak').val();
	var filterRoute = $('#filter_route').val() == 'all' ? 0 : $('#filter_route').val();
	//var filterDays = $('filter_days').val() == 'all ? 0 : $('filter_days').val()';
	
	if (filterFlight) {
		var FilterFlightNum = Number(filterFlight);
		var FilterFlightCode;
		if ( isNaN(FilterFlightNum)) {
			var FilterFlightCode = filterFlight.substr(0,2).toUpperCase();
			FilterFlightNum = Number(trim(filterFlight.substr(2), '-�� '));
		}
	}
	
	if ( filterFlight || filterAk || filterRoute ) {
		$('.board.'+boardType+' table tbody tr').hide();
		var flightsCount = 0;
		var flight_num, ak, route;
		$('.board.'+boardType+' table tbody tr').each(function(index){
			flight_num = Number(trim($(this).children('.flight').text()).substr(3));
			flight_code =  trim($(this).children('.flight').text()).substr(0,2);
			
			ak = trim($(this).children().children('.company_name').text());
			route = trim($(this).children('.route').text());
			
			if (
				(	(filterFlight && !filterAk && !filterRoute) && 
					(FilterFlightCode && FilterFlightNum) &&
					(FilterFlightCode == flight_code && FilterFlightNum == flight_num)
				) || 
				(	(filterFlight && !filterAk && !filterRoute) && 
					(!FilterFlightCode && FilterFlightNum) &&
					(FilterFlightNum == flight_num)
				) ||
				(!filterFlight && filterAk && !filterRoute && filterAk == ak) || 
				(!filterFlight && !filterAk && filterRoute && filterRoute == route) ||
				(filterFlight && filterAk && !filterRoute && filterAk == ak && (
					(FilterFlightCode && FilterFlightNum) && (FilterFlightCode == flight_code && FilterFlightNum == flight_num) ||
					(!FilterFlightCode && FilterFlightNum) && (FilterFlightNum == flight_num))
				) ||
				(filterFlight && !filterAk && filterRoute && filterRoute == route && (
					(FilterFlightCode && FilterFlightNum) && (FilterFlightCode == flight_code && FilterFlightNum == flight_num) ||
					(!FilterFlightCode && FilterFlightNum) && (FilterFlightNum == flight_num))
				) ||
				(!filterFlight && filterAk && filterRoute &&  filterAk == ak && filterRoute == route) ||
				(filterFlight && filterAk && filterRoute && filterAk == ak && filterRoute == route && (
					(FilterFlightCode && FilterFlightNum) && (FilterFlightCode == flight_code && FilterFlightNum == flight_num) ||
					(!FilterFlightCode && FilterFlightNum) && (FilterFlightNum == flight_num))
				) 
			) {
				$(this).show();
				flightsCount++;
			}
		});
		if ( flightsCount == 0 ) {
			alert('<?= GetMessage('AIRPORT_BOARD_NO_RESULT') ?>');
		}
	}
	
	return false;
});

// ]]>
</script>