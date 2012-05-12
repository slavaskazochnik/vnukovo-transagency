<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/ibe/classes/ibe/utils.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/ibe/classes/ibe/tools/points.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ibe/classes/js_lang/formtools.php");
echo GetFormToolsStrings();

$USE_AUTOCOMPLETE = ( !count($arResult['points']) && true == $arParams["USE_AUTOCOMPLETE"] ) ; // Использовать автозаполнение, если используются поля для ввода пунктов и разрешено автозаполнение

require_once ("tools.php");

$minDate = 0;
$curMonth = date('n');
$curYear = date('y');
$curFullYear = date('Y');
$arMonths = explode(',', GetMessage('monthNamesShort'));
foreach($arMonths as &$month) {
//$month = mb_strtolower(str_replace('\'', '', $month), LANG_CHARSET);
	$month = str_replace('\'', '', $month);
}
?>

<div id="ts_ag_quick_reservation_form">
	<h2 class="caption"><?= GetMessage('TS_SHORTFORM_CAPTION') ?></h2>
	<div class="ext_form clearfix">
		<form method="post" action="<?= $arResult['form_action'] ?>" onsubmit="return checkForm(this);" name="reg_form">
			<input type="hidden" name="next_page" value="<?= $arResult['next_page']; ?>" />
			<input name="date_format" type="hidden" value="site" />
			
			<fieldset class="route_types clearfix">
				<div class="types">
					<div class="type<?= $arResult['rt_checked'] ? ' selected' : ''?>">
						<label for="rt" class="title block">
							<?=GetMessage('TS_SHORTFORM_ROUTE_TYPE_RT') ?>
							<input type="radio" value="RT" name="RT_OW" id="rt" <? if($arResult['rt_checked']) { echo 'checked="checked"'; } ?> />
						</label>
					</div>
					<div class="type<?= $arResult['ow_checked'] ? ' selected' : ''?>">
						<label for="ow" class="title block">
							<?=GetMessage('TS_SHORTFORM_ROUTE_TYPE_OW') ?>
							<input type="radio" value="OW" name="RT_OW" id="ow" <? if($arResult['ow_checked']) { echo 'checked="checked"'; } ?> />
						</label>
					</div>
				</div>
			<script type="text/javascript">
			// <![CDATA[
			$('.types .type .block').click(function(){
				$(this).parent().parent().children('.type').removeClass('selected');
				$(this).parent().addClass('selected');
				
				if ($(this).children('input').val() == 'RT') {
					$('#form_dateback_title').removeClass('disabled');
				} else {
					$('#form_dateback_title').addClass('disabled');
				}
			});
			// ]]>
			</script>
			</fieldset>
			
			<div class="search_form">
				<fieldset class="route clearfix">
					<div class="point">
						<label class="title" for="depart"><?=GetMessage('TS_SHORTFORM_DEPARTURE') ?></label>
						<div class="location">
							<? if(count($arResult['select_points_depart']['REFERENCE'])): ?>
							<select id="depart" name="depart">
								<? foreach($arResult['select_points_depart']['REFERENCE'] as $refKey => $refVal): ?>
								<? $bSelected = ( $arResult['select_points_depart']['REFERENCE_ID'][$refKey] == $arResult['select_points_depart_selected'] ); ?>
								<option value="<?= $arResult['select_points_depart']['REFERENCE_ID'][$refKey] ?>" <? echo ( $bSelected ? ' selected="selected"' : '' ); ?>>
								<?= $refVal ?>
								</option>
								<? endforeach; ?>
							</select>
							<? else: ?>
							<input type="text" name="depart" value="<?= $arResult['depart'] ?>" id="depart" />
							<div class="reference clearfix">
								<?= CTemplateToolsPoint::Link("depart", GetMessage('TS_SHORTFORM_TOOLS_POINT_DEPARTURE_SHORT_TITLE'), GetMessage('TS_SHORTFORM_TOOLS_POINT_DEPARTURE_TITLE')); ?>
							</div>
							<? endif; ?>
						</div>
					</div>
					
					<div class="point">
						<label class="title" for="arrival"><?=GetMessage('TS_SHORTFORM_ARRIVAL') ?></label>
						<div class="location">
							<? if(count($arResult['select_points_arrival']['REFERENCE'])): ?>
							<select id="arrival" name="arrival">
								<? foreach($arResult['select_points_arrival']['REFERENCE'] as $refKey => $refVal): ?>
								<? $bSelected = ( $arResult['select_points_arrival']['REFERENCE_ID'][$refKey] == $arResult['select_points_arrival_selected'] ); ?>
								<option value="<?= $arResult['select_points_arrival']['REFERENCE_ID'][$refKey] ?>" <? echo ( $bSelected ? ' selected="selected"' : '' ); ?>>
								<?= $refVal ?>
								</option>
								<? endforeach; ?>
							</select>
							<? else: ?>
							<input type="text" name="arrival" value="<?= $arResult['arrival'] ?>" id="arrival" />
							<div class="reference clearfix">
								<?=CTemplateToolsPoint::Link("arrival", GetMessage('TS_SHORTFORM_TOOLS_POINT_ARRIVAL_SHORT_TITLE'), GetMessage('TS_SHORTFORM_TOOLS_POINT_ARRIVAL_TITLE')); ?>
							</div>
							<? endif; ?>
						</div>
					</div>
				</fieldset>
				
				<fieldset class="dates clearfix">
					<div class="date">
						<label class="title" for="dateto"><?=GetMessage('TS_SHORTFORM_DEPARTURE_DATE') ?></label>
						<div class="date-container">
							<input type="text" id="dateto" name="dateto" maxlength="10" size="10" value="<?=$arResult['d_to'] ?>" />
						</div>
					</div>
					<div class="date<?= $arResult['ow_checked'] ?' disabled' : '' ?>" id="form_dateback_title">
						<label class="title" for="dateback"><?=GetMessage('TS_SHORTFORM_ARRIVAL_DATE') ?></label>
						<div class="date-container">
							<input type="text" id="dateback" name="dateback" maxlength="10" size="10" value="<?=$arResult['d_back'] ?>" />
							<div class="date_curtain"></div>
						</div>
					</div>
				</fieldset>
				
				<? if ( $arResult['ak_onlysearch'] != '') { ?>
				<input type="hidden" name="company" value="<?=$arResult['ak_onlysearch']?>" />
				<? }  ?>
				<? if(!isset($arParams['DISPLAY_COMPANY']) || $arParams['DISPLAY_COMPANY'] == 'Y'): ?>
				<div class="preference company">
					<label class="title" for="company"><?=GetMessage('TS_SHORTFORM_COMPANY') ?></label>
					<div class="select_wrapper"><?= SelectBoxFromArray('company', $arResult['select_ak'], $arResult['select_ak_selected']) ?></div>
				</div>
				<? endif ?>
				
				<fieldset class="passengers clearfix">
					<div class="passenger adult" id="form_adult_title">
						<label class="title"  title="<?=GetMessage('TS_SHORTFORM_PASSENGERS_ADULTS_TITLE') ?>" for="adult"><?=GetMessage('TS_SHORTFORM_PASSENGERS_ADULTS') ?></label>
						<div class="select_wrapper"><?= SelectBoxFromArray('adult', $arResult['select_pcl_adult'], $arResult['select_pcl_adult_selected']) ?></div>
					</div>
					<div class="passenger child" id="form_child_title">
						<label class="title" title="<?=GetMessage('TS_SHORTFORM_PASSENGERS_CHILDREN_TITLE') ?>" for="child"><?=GetMessage('TS_SHORTFORM_PASSENGERS_CHILDREN') ?></label>
						<div class="select_wrapper"><?= SelectBoxFromArray('child', $arResult['select_pcl_child'], $arResult['select_pcl_child_selected']) ?></div>
					</div>
					<div class="passenger infant" id="form_infant_title">
						<label class="title" title="<?=GetMessage('TS_SHORTFORM_PASSENGERS_INFANTS_TITLE') ?>" for="infant"><?=GetMessage('TS_SHORTFORM_PASSENGERS_INFANTS') ?></label>
						<div class="select_wrapper"><?= SelectBoxFromArray('infant', $arResult['select_pcl_infant'], $arResult['select_pcl_infant_selected']) ?></div>
					</div>
				</fieldset>
				
				<fieldset class="preferences clearfix">
					<? if(!isset($arParams['DISPLAY_CLASS']) || $arParams['DISPLAY_CLASS'] == 'Y'): ?>
					<div class="class clearfix">
					<? //trace($arResult) ?>
						<? for($i=0; $i<count($arResult['select_cos']['REFERENCE_ID']); $i++): ?>
							<? if ( $arResult['select_cos']['REFERENCE_ID'][$i] != 'П' ) : ?>
							<label for="class_<?=$i ?>" class="title<? if( ($i ==0 && empty($arResult['select_cos_selected'])) || $arResult['select_cos']['REFERENCE_ID'][$i] == $arResult['select_cos_selected']): ?> checked<? endif; ?><?= $i == 2 ? ' last' : '' ?>">
								<?=$arResult['select_cos']['REFERENCE'][$i] ?>
								<div class="class_radio"></div>
								<input type="radio"  id="class_<?=$i ?>" name="class" <? if($arResult['select_cos']['REFERENCE_ID'][$i] == $arResult['select_cos_selected']): ?> checked="checked"<? endif; ?> value="<?=$arResult['select_cos']['REFERENCE_ID'][$i] ?>" />
							</label>
							<? endif; ?>
						<? endfor; ?>
						<?//= SelectBoxFromArray('class', $arResult['select_cos'], $arResult['select_cos_selected']) ?>
					</div>
					<script type="text/javascript">
					// <![CDATA[
					$('.class label').click( function(){
						$(this).parent().children('label').removeClass('checked');
						$(this).addClass('checked');
					});
					// ]]>
					</script>
					<? endif ?>
				</fieldset>
				
				<fieldset class="preferences_add clearfix">
				<? if(!isset($arParams['DISPLAY_DIRECT']) || $arParams['DISPLAY_DIRECT'] == 'Y'): ?>
					<div class="direct clearfix">
						<label class="title" for="DirectOnly">
							<?=GetMessage('TS_SHORTFORM_FLIGHT_TYPE') ?>
						</label>
						<input name="DirectOnly" id="DirectOnly" type="checkbox" value="1"<? if($arResult['directonly']) echo ' checked="checked"'; ?> />
					</div>
				<? endif ?>
				
				<? if ( !$arResult["~LOWCOST"] && COption::GetOptionString( "ibe", "IBE_SETTINGS_ALLOW_MATRIX" ) == "Y"  && (!isset($arParams['DISPLAY_MATRIX']) || $arParams['DISPLAY_MATRIX'] == 'Y')) { ?>
					<div class="matrix clearfix">
						<label class="title" for="matrix">
							<?=GetMessage("TS_SHORTFORM_MATRIX") ?>
						</label>
						<?=SelectBoxFromArray( "matrix", $arResult[ "select_matrix" ], $arResult[ "select_matrix_selected" ] ) ?>
					</div>
				<? } ?>
				
				<? if (isset($arParams['DISPLAY_PROMOCODE']) && $arParams['DISPLAY_PROMOCODE'] == 'Y' && isset($arResult['PROMOCODE']) && count($arResult['PROMOCODE']) > 0) : ?>
					<div class="discounts">
						<legend>
						<?=GetMessage('TS_SHORTFORM_DISCOUNT_TITLE') ?>
						</legend>
						<div class="promocode clearfix">
							<label class="title" for="promocode">
								<?=GetMessage("TS_SHORTFORM_PROMOCODE") ?>
							</label>
							<input class="text" id="promocode" name="<?= $arResult['PROMOCODE']['NAME'] ?>" type="text" value="<?= $arResult['PROMOCODE']['VALUE'] ?>" />
						</div>
					</div>
				<? endif; ?>
				</fieldset>
				
				<div class="submit clearfix">
					<input type="submit" class="button" value="<?=GetMessage('TS_SHORTFORM_SEARCH'); ?>" />
				</div>
			</div>
		</form>
	</div>
</div>
<!-- окно всплывающей подсказки -->
<script type="text/javascript">
// <![CDATA[
formInit();

$(document).ready(function(){
	// Выделяем содержимое поля ввода пунктов при фокусе
	$("#depart, #arrival").bind("focus", function(){
 		$(this).select();
	});
});
// ]]>
</script>
<? $arDateFormat = array();
if (defined('FORMAT_DATE')) {
  $arDateFormat = array( 
  'day' => array ('begin' => ($pos = strpos(FORMAT_DATE, 'D')), 'end' => $pos + 2),
  'month' => array ('begin' => ($pos = strpos(FORMAT_DATE, 'M')), 'end' => $pos + 2),
  'year' => array ('begin' => ($pos = strpos(FORMAT_DATE, 'Y')), 'end' => $pos + 4)
  );
  $arDateFormatJS = '"day":{"begin":'.strpos(FORMAT_DATE, 'D').',"end":'.(strpos(FORMAT_DATE, 'D') + 2).'},';
  $arDateFormatJS .= '"month":{"begin":'.strpos(FORMAT_DATE, 'M').',"end":'.(strpos(FORMAT_DATE, 'M') + 2).'},';
  $arDateFormatJS .= '"year":{"begin":'.strpos(FORMAT_DATE, 'Y').',"end":'.(strpos(FORMAT_DATE, 'Y') + 4).'}';
}
?>
<script type="text/javascript">
// <![CDATA[
// Календарь 
var dateFormat = {<?=$arDateFormatJS?>};
var date_format = '<?= ($date_format = strtolower(str_replace('YYYY', 'YY', FORMAT_DATE))); ?>';
var defaultDeltaDays = 1;
var oneDay= 0/*1000*60*60*24*/;
var calendarTo;
var calendarBack;
var defaultDateTo;
var defaultDateBack;

$(function() {
  // локализация календаря
  $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'] = {
    closeText: '<?=GetMessage('closeText') ?>',
    prevText: '<?=GetMessage('prevText') ?>',
    nextText: '<?=GetMessage('nextText') ?>',
    currentText: '<?=GetMessage('currentText') ?>',
    monthNames: [<?=GetMessage('monthNames') ?>],
    monthNamesShort: [<?=GetMessage('monthNamesShort') ?>],
    dayNames: [<?=GetMessage('dayNames') ?>],
    dayNamesShort: [<?=GetMessage('dayNamesShort') ?>],
    dayNamesMin: [<?=GetMessage('dayNamesMin') ?>],
    dateFormat: '<?= $date_format; ?>', 
	firstDay: <?=GetMessage('firstDay') ?>,
    isRTL: <?=GetMessage('isRTL') ?>};
  $.datepicker.setDefaults($.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>']);
  
  var minDate = new Date(<?=(time()+$arResult['DATE_OFFSET'])*1000 ?>);
  defaultDateTo = dateSiteToJS($('#dateto').val());
  defaultDateBack = dateSiteToJS($('#dateback').val());
 
  calendarTo = $("#dateto");  
  calendarTo.datepicker({ 
	showOn: 'both',
  buttonImage: '<?= $templateFolder ?>/img/calendar.gif',
	buttonText: '<?=GetMessage('TS_SHORTFORM_CALENDAR_BUTTON') ?>',
	buttonImageOnly: true,
	
	showOtherMonths: true,
	selectOtherMonths: true,
	
    changeMonth: true,
    changeYear: true,
    minDate: 0,
    maxDate: '+1y',
    onSelect: function(dateText) {
      selectForwardDate(dateText)
    }
  });
  calendarTo.datepicker('setDate', defaultDateTo);
  
  calendarBack = $("#dateback");
  calendarBack.datepicker({ 
	showOn: 'both',
  buttonImage: '<?= $templateFolder ?>/img/calendar.gif',
	buttonText: '<?=GetMessage('TS_SHORTFORM_CALENDAR_BUTTON') ?>',
	buttonImageOnly: true,  
	
	showOtherMonths: true,
	selectOtherMonths: true,
  
    changeMonth: true,
    changeYear: true,
    minDate: 0,
    maxDate: '+1y',
    onSelect: function(dateText) { 
      selectBackDate(dateText)
    }
  });
  calendarBack.datepicker('setDate', defaultDateBack)
});

/* Дни недели */
$(document).ready( function(){
  $('#dateto-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[defaultDateTo.getDay()] );
  $('#dateback-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[defaultDateBack.getDay()] );
});

// Выбор даты рейса "туда"
function selectForwardDate(dateText) {
  $('#dateto').val(dateText);
  $('#dateto-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[calendarTo.datepicker('getDate').getDay()] );

  // если дата вылета становится больше даты возврата, то добавляем к дате возврата разницу дней между между датой возврата по умолчанию и датой вылета по умолчанию
  if (calendarTo.datepicker('getDate') > calendarBack.datepicker('getDate')) {
    var newDate = calendarTo.datepicker('getDate').getTime()+defaultDeltaDays*oneDay;
    newDate = new Date(newDate);
    calendarBack.datepicker('setDate', newDate);
	$('#dateback-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[newDate.getDay()] );
  }
}

// Выбор даты рейса "обратно"
function selectBackDate(dateText) {
  // если дата вылета становится больше даты возврата, устанавливаем дату вылета на день раньше
  if (calendarTo.datepicker('getDate') > calendarBack.datepicker('getDate')) {
    var newDate = calendarBack.datepicker('getDate').getTime() - oneDay;
    newDate = new Date(newDate);
    calendarTo.datepicker('setDate', newDate);
	$('#dateto-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[newDate.getDay()] );
  }
  $('#dateback').val(dateText);
  $('#dateback-day').text( $.datepicker.regional['<?=GetMessage('lang') ? GetMessage('lang') : LANGUAGE_ID ?>'].dayNames[calendarBack.datepicker('getDate').getDay()] );
}

// Преобразование строки с датой в формате сайте в объект javascript-даты
function dateSiteToJS(dateSite) {
  var dateObj = {'day':'', 'month':'', 'year':''};
  for (key in dateObj) {
    dateObj[key] = dateSite.substring(dateFormat[key]['begin'], dateFormat[key]['end']);
  }
  return (new Date(dateObj.year, dateObj.month - 1, dateObj.day));
}

$(document).ready(function(){
	if(typeof tooltip == 'function') {
	  /* Замена исходной функции Datepicker'a */
	  var _updateDatepicker_o = $.datepicker._updateDatepicker;
		$.datepicker._updateDatepicker = function(inst){
		_updateDatepicker_o.apply(this, [inst]);
		if ( $(".ui-datepicker .ui-datepicker-prev").css('display') == 'none'
		  || $(".ui-datepicker .ui-datepicker-next").css('display') == 'none') {
		  $("#tooltip").hide();
		}
		tooltip();
	}
  };

  // Выделяем содержимое поля ввода пунктов при фокусе
  $("#depart, #arrival").bind("focus", function(){
  $(this).select();
  });
});

// ]]>
</script> 
<script type="text/javascript">
// <![CDATA[ 
<? // Автозаполнение
if( $USE_AUTOCOMPLETE ): // Если используется автозаполнение ?>
  // подключаем к полям ввода пунктов Autocomplete
  $("#depart, #arrival").autocomplete("<?= $componentPath ?>/get_cities.php", {
      extraParams: {
        lang: "<?= LANGUAGE_ID ?>" // Язык поиска
      },
      max: 40, // Максимальное количество пунктов в ответе
      scrollHeight: 300, // Высота в px
      autoFill: false, // Автоматически подставлять первый найденный пункт
      delay: 400, // Задержка перед отправкой запроса (в ms)
      minChars: 2, // Минимальное количество символов, при котором необходимо отправлять запрос 
      matchSubset: false, // Показывать только пункты, совпдающие с маской запроса
      selectFirst: true, // Если установить в true, то по нажатию клавиши Tab или Enter будет выбрано то значение, которое в данный момент установлено в элементе ввода
      formatResult: function (row) {
        return row[0].concat(' (', row[1], ')');
      },
      formatItem: function (row, i, total) {
    			return row[0] + '<b class="point_info"><em class="code">' + row[1] + '</em> <em class="country">(' + row[2] + ')</em></b>';
    		}
    });
 <? endif; //if( $USE_AUTOCOMPLETE ): ?>
 
<? // Маршрутная сеть
if ( is_array($arResult["ROUTES"]) && count($arResult["ROUTES"]) ): // Если задана маршрутная сеть ?>

 var routes = {
 <? $count = count($arResult["ROUTES"]);
   foreach ( $arResult["ROUTES"] as $code => $info ): // строим копию массива с маршрутной сетью в JS ?>
   "<?= $code ?>" : {
     "NAME" : "<?= $info["NAME"] ?>",
     "ROUTES" : {
   <? foreach ( $info["ROUTES"] as $point ): ?>
       "<?= $point ?>" : "<?= $point ?>"<? if ( end($info["ROUTES"]) !== $point ) echo "," ?>
   <? endforeach; // foreach ( $info["ROUTES"] as $point ) ?>
      }
   }<? if ( --$count ) echo "," ?>
 <? endforeach; // foreach ( $arResult["ROUTES"] as $code => $info ) ?>
 };
 
 var currentArrival = '';
 function buildArrivalList() {
   
   currentArrival = $("#ts_ag_quick_reservation_form form #arrival option:selected").val();
   $("#ts_ag_quick_reservation_form form #arrival option").each( function (i) { // Удаляем все пункты прибытия
     $(this).remove();
   });
   var depart = $("#ts_ag_quick_reservation_form form #depart").val();
   if ( 'undefined' != typeof routes[depart]["ROUTES"] ) { // Если для выбранного пункта вылета заданы пункты прибытия
     for ( var code in routes[depart]["ROUTES"] ) {
       if ( routes[code] ) { // добавляем их в список
         $("#ts_ag_quick_reservation_form form #arrival").append('<option value="' + code + '"' + ( currentArrival == code ? ' selected="selected"' : '' ) + '>' + routes[code]["NAME"] + '</option>');
       }
     }
   }
 }
 
 $(document).ready( function () { buildArrivalList() } );
 $("#ts_ag_quick_reservation_form form #depart").change( function () { buildArrivalList() } );

<? endif; // if ( is_array($arResult["ROUTES"]) && count($arResult["ROUTES"]) ): ?>

// ]]>
</script> 
