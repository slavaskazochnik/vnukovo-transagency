<? 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ibe/classes/js_lang/formtools.php");
$APPLICATION->AddHeadString(GetFormToolsStrings());
$APPLICATION->AddHeadString($arResult['SCRIPT']);

/* Диапазон дат */
$bShowDateRange = ( isset( $GLOBALS['arParams']['DISPLAY_MATRIX'])
        && $GLOBALS['arParams']['DISPLAY_MATRIX'] == 'Y'
        && $GLOBALS['arParams']['FARES_DISPLAY_TYPE'] != 'SPLIT_FARES' );

//trace($arResult);
$minDate = 0;
$curMonth = date('n');
$curYear = date('y');
$curFullYear = date('Y');
$arMonths = explode(',', GetMessage('monthNamesShort'));
foreach($arMonths as &$month) {
//  $month = mb_strtolower(str_replace('\'', '', $month), LANG_CHARSET);
	$month = str_replace('\'', '', $month);
}
?>
<?=$arResult['SCRIPT'] ?>
<? if ( $arResult[ "~SHOW_FORM" ] ) : ?>

<form action="<?=$arResult['form_action'] ?>" class="form-order clearfix" method="post" name="reg_form" onsubmit="<? if ( $arParams[ "~IBE_AJAX_MODE" ] == "Y" ) : 
?>if ( checkForm( this ) ) { ibe_ajax.post( this, '<?= $arResult['form_action'] ?>', '#ts_ag_offer_filter_container,#ts_ag_carrier_matrix_container' ); } return false;<? 
else : 
?>return checkForm(this);<? 
endif; 
?>">
	<input name="next_page" type="hidden" value="<?= $arResult['next_page']; ?>" />
	<input name="date_format" type="hidden" value="site" />

	<!-- выбор типа рейса -->
	<fieldset class="route-types">
		<div class="types clearfix">
			<div class="type<?= $arResult['ow_checked'] ?' selected' : '' ?>">
				<label class="block title" for="ow">
					<?=GetMessage("TS_STEP1_SEARCHFORM_ROUTE_TYPE_OW") ?>
					<input<? if($arResult['ow_checked']): ?> checked="checked"<? endif; ?> id="ow" name="RT_OW" type="radio" value="OW" />
				</label>
			</div>
			<div class="type<?= $arResult['rt_checked'] ? ' selected' : ''?>">
				<label class="block title" for="rt">
					<?=GetMessage("TS_STEP1_SEARCHFORM_ROUTE_TYPE_RT") ?>
					<input<? if($arResult['rt_checked']): ?> checked="checked"<? endif; ?> id="rt" name="RT_OW" type="radio" value="RT" />
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
	
	<div class="search_form clearfix">
		<!-- выбор городов вылета и прилета -->
		<fieldset class="route clearfix">
			<div class="point">
				<label class="title" for="depart"><?=GetMessage("TS_STEP1_SEARCHFORM_DEPARTURE") ?></label>
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
					<input class="text" id="depart" name="depart" type="text" value="<?=$arResult['depart'] ?>" />
					<div class="link-container">
						<?=CTemplateToolsPoint::Link("depart", GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_DEPARTURE_SHORT_TITLE"), GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_DEPARTURE_TITLE")); ?>
					</div>
					<? endif; ?>
				</div>
			</div>
			<div class="point">
				<label class="title" for="arrival"><?=GetMessage("TS_STEP1_SEARCHFORM_ARRIVAL") ?></label>
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
					<input class="text" id="arrival" name="arrival" type="text" value="<?=$arResult['arrival'] ?>" />
					<div class="link-container">
						<?=CTemplateToolsPoint::Link("arrival", GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_ARRIVAL_SHORT_TITLE"), GetMessage("TS_STEP1_SEARCHFORM_TOOLS_POINT_ARRIVAL_TITLE")); ?>
					</div>
					<? endif; ?>
				</div>
			</div>
		</fieldset>	
		<!-- выбор дат вылета и прилета -->
		<div class="clearfix">
			<fieldset class="dates">
				<div class="date">
					<label class="title" for="dateto"><?=GetMessage("TS_STEP1_SEARCHFORM_DEPARTURE_DATE") ?></label>
					<div class="date-container">
						<div class="calendar-container">
							<input type="text" id="dateto" name="dateto" maxlength="10" size="10" value="<?=$arResult['d_to'] ?>" />
						</div>
						<? /* if(count($arResult['select_time']['REFERENCE_ID'])): ?>
						<select id="timeto" name="timeto">
							<? for($i=0; $i<count($arResult['select_time']['REFERENCE_ID']); $i++): ?>
							<option<? if($arResult['select_time']['REFERENCE_ID'][$i] == $arResult['select_timeto_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_time']['REFERENCE_ID'][$i] ?>">
							<?=$arResult['select_time']['REFERENCE'][$i] ?>
							</option>
							<? endfor; ?>
						</select>
						<? endif; */ ?>
					</div>
				</div>
				<div class="date<?= $arResult['ow_checked'] ?' disabled' : '' ?>"  id="form_dateback_title">
					<label class="title" for="dateback"><?=GetMessage("TS_STEP1_SEARCHFORM_ARRIVAL_DATE") ?></label>
					<div class="date-container">
						<div class="calendar-container">
							<input type="text" id="dateback" name="dateback" maxlength="10" size="10" value="<?= $arResult['d_back'] ?>" />
							<div class="date_curtain"></div>
						</div>
						<? /* if(count($arResult['select_time']['REFERENCE_ID'])): ?>
						<select id="timeback" name="timeback">
							<? for($i=0; $i<count($arResult['select_time']['REFERENCE_ID']); $i++): ?>
							<option<? if($arResult['select_time']['REFERENCE_ID'][$i] == $arResult['select_timeback_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_time']['REFERENCE_ID'][$i] ?>">
							<?=$arResult['select_time']['REFERENCE'][$i] ?>
							</option>
							<? endfor; ?>
						</select>
						<? endif; */?>
					</div>
				</div>
			</fieldset>		
			<!-- Предпочтения -->
			<fieldset class="preferences">
	
			<? if ($arResult['ak_onlysearch'] == '' && (!isset($arParams['DISPLAY_COMPANY']) || $arParams['DISPLAY_COMPANY'] == 'Y')): ?>
				<div class="preference company clearfix">
					<label class="title" for="company">
						<?=GetMessage("TS_STEP1_SEARCHFORM_COMPANY") ?>
					</label>
					<? if(count($arResult['select_faretype']['REFERENCE_ID'])): ?>
					<div class="select_wrapper">
						<select id="company" name="company">
							<? for($i=0; $i<count($arResult['select_ak']['REFERENCE_ID']); $i++): ?>
							<option<? if($arResult['select_ak']['REFERENCE_ID'][$i] == $arResult['select_ak_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_ak']['REFERENCE_ID'][$i] ?>">
							<?=$arResult['select_ak']['REFERENCE'][$i] ?>
							</option>
							<? endfor; ?>
						</select>
					</div>
					<? endif; ?>
				</div>
				<? else: ?>
				<input name="company" type="hidden" value="<?=$arResult['ak_onlysearch']?>">
			<? endif; ?>
				
			<? if(!isset($arParams['DISPLAY_CLASS']) || $arParams['DISPLAY_CLASS'] == 'Y'): ?>
				<div class="preference class">
					<label class="title" for="class"><?=GetMessage("TS_STEP1_SEARCHFORM_SERVICE_CLASS") ?></label>
					<? if(count($arResult['select_cos']['REFERENCE_ID'])): ?>
					<div class="select_wrapper">
						<select id="class" name="class">
							<? for($i=0; $i<count($arResult['select_cos']['REFERENCE_ID']); $i++): ?>
								<? if ( $arResult['select_cos']['REFERENCE_ID'][$i] != 'П' ) : ?> 
							<option<? if($arResult['select_cos']['REFERENCE_ID'][$i] == $arResult['select_cos_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_cos']['REFERENCE_ID'][$i] ?>">
							<?=$arResult['select_cos']['REFERENCE'][$i] ?>
							</option>
								<? endif; ?>
							<? endfor; ?>
						</select>
					</div>
					<? endif; ?>
				</div>
			<? endif; ?>			
			</fieldset>
		</div>
		<div class="clearfix">
		<? if ( $arResult[ "~REWARD_MODE" ] ) : ?>
			<input type="hidden" name="adult" value="1"/>
			<input type="hidden" name="child" value="0"/>
			<input type="hidden" name="infant" value="0"/>
		<? else : ?>
			<fieldset class="passengers">
				<legend>
				<?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_TITLE") ?>
				</legend>
				<!-- Взрослые -->
				<div class="passenger adult" id="form_adult_title">
					<label class="title" for="adult">
						<?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_ADULTS") ?>
						<span class="subtitle"><?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_ADULTS_TITLE") ?></span>
					</label>
					<? if(count($arResult['select_pcl_adult']['REFERENCE_ID'])): ?>
					<div class="select_wrapper">
						<select id="adult" name="adult">
							<? for($i=0; $i<count($arResult['select_pcl_adult']['REFERENCE_ID']); $i++): ?>
							<option<? if($arResult['select_pcl_adult']['REFERENCE_ID'][$i] == $arResult['select_pcl_adult_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_pcl_adult']['REFERENCE_ID'][$i] ?>">
							<?=$arResult['select_pcl_adult']['REFERENCE'][$i] ?>
							</option>
							<? endfor; ?>
						</select>
					</div>
					<? endif; ?>
				</div>
				<div class="passenger child" id="form_child_title">
					<label class="title" for="child">
						<?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_CHILDREN") ?>
						<span class="subtitle"><?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_CHILDREN_TITLE") ?></span>
					</label>
					<? if(count($arResult['select_pcl_child']['REFERENCE_ID'])): ?>
					<div class="select_wrapper">
						<select id="child" name="child">
							<? for($i=0; $i<count($arResult['select_pcl_child']['REFERENCE_ID']); $i++): ?>
							<option<? if($arResult['select_pcl_child']['REFERENCE_ID'][$i] == $arResult['select_pcl_child_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_pcl_child']['REFERENCE_ID'][$i] ?>">
							<?=$arResult['select_pcl_child']['REFERENCE'][$i] ?>
							</option>
							<? endfor; ?>
						</select>
					</div>
					<? endif; ?>
				</div>
				<div class="passenger infant" id="form_infant_title">
					<label class="title" for="infant">
						<?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_INFANTS") ?>
						<span class="subtitle"><?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGERS_INFANTS_TITLE") ?></span>
					</label>
					<? if(count($arResult['select_pcl_child']['REFERENCE_ID'])): ?>
					<div class="select_wrapper">
						<select id="infant" name="infant">
							<? for($i=0; $i<count($arResult['select_pcl_infant']['REFERENCE_ID']); $i++): ?>
							<option<? if($arResult['select_pcl_infant']['REFERENCE_ID'][$i] == $arResult['select_pcl_infant_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_pcl_infant']['REFERENCE_ID'][$i] ?>">
							<?=$arResult['select_pcl_infant']['REFERENCE'][$i] ?>
							</option>
							<? endfor; ?>
						</select>
					</div>
					<? endif; ?>
				</div>
			</fieldset>
		<? endif; ?>
		
		<? if(!isset($arParams['DISPLAY_DIRECT']) || $arParams['DISPLAY_DIRECT'] == 'Y'): ?>
			<div class="preference direct">
				<input<? if($arResult['directonly']): ?> checked="checked"<? endif; ?> id="DirectOnly" name="DirectOnly" type="checkbox" value="1" />
				<label id="DirectOnlyTitile" class="title<?= $arResult['directonly'] ? ' checked' : ''?>" for="DirectOnly">
					<div id="direct_checkbox"></div>
					<?=GetMessage("TS_STEP1_SEARCHFORM_FLIGHT_TYPE") ?>
				</label>
			</div>
		<script type="text/javascript">
		// <![CDATA[
		$('#DirectOnlyTitile').click( function(){
			if ( $('#DirectOnlyTitile').hasClass('checked') ) {
				$('#DirectOnlyTitile').removeClass('checked');
			} else {
				$('#DirectOnlyTitile').addClass('checked');
			}
		});
		// ]]>
		</script>
		<? endif; ?>
				
			<div class="submit">
				<input class="button" type="submit" value="<?=GetMessage("TS_STEP1_SEARCHFORM_SEARCH") ?>" />
			</div>
		</div>
		
		<fieldset class="preferences_add clearfix">
		<? if ( $arParams['FARES_DISPLAY_TYPE'] != 'SPLIT_FARES' && array_key_exists('SEARCHING_MODE', $arParams) && $arParams['SEARCHING_MODE'] == 'Y'): ?>
			<div class="preference tariff clearfix">
				<label class="title" for="searching_mode"><?= GetMessage("TS_STEP1_SEARCHFORM_SEARCHING_MODE") ?></label>
				<? if ($itemsCount = count($arResult['select_searching_mode']['REFERENCE_ID'])): ?>
				<select id="searching_mode" name="searching_mode">
					<? for ($item = 0; $item < $itemsCount; $item++): ?>
					<option<? if ($arResult['select_searching_mode']['REFERENCE_ID'][$item] == $arResult['select_searching_mode_selected']): ?> selected="selected"<? endif; ?> value="<?= $arResult['select_searching_mode']['REFERENCE_ID'][$item] ?>">
					<?= $arResult['select_searching_mode']['REFERENCE'][$item] ?>
					</option>
					<? endfor; ?>
				</select>
				<? endif; ?>
			</div>
		<? endif; ?>
			
		<? if(!isset($arParams['DISPLAY_TARIFFS']) || $arParams['DISPLAY_TARIFFS'] == 'Y'): ?>
			<div class="preference tariff clearfix">
				<label class="title" for="faretype"><?=GetMessage("TS_STEP1_SEARCHFORM_TARIFFS") ?></label>
				<? if(count($arResult['select_faretype']['REFERENCE_ID'])): ?>
				<select id="faretype" name="faretype">
					<? for($i=0; $i<count($arResult['select_faretype']['REFERENCE_ID']); $i++): ?>
					<option<? if($arResult['select_faretype']['REFERENCE_ID'][$i] == $arResult['select_faretype_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_faretype']['REFERENCE_ID'][$i] ?>">
					<?=$arResult['select_faretype']['REFERENCE'][$i] ?>
					</option>
					<? endfor; ?>
				</select>
				<? endif; ?>
			</div>
		<? endif; ?>
			
		<? if (isset($arResult['PAY_SYSTEMS']) && count($arResult['PAY_SYSTEMS']) > 0): ?>
			<? $field = $arResult['PAY_SYSTEMS']; ?>
			<? if ( $field[ "~TYPE" ] == "select" && count($arResult['PAY_SYSTEMS']['OPTIONS']) > 0 ) { ?>
			<div class="preference paysystem clearfix">
				<label class="title" for="paysystem"><?=GetMessage( $arResult[ "~REWARD_MODE" ] ? "TS_STEP1_SEARCHFORM_LOYALTY_PROGRAM" : "TS_STEP1_SEARCHFORM_PAYSYSTEM" ) ?></label>
				<select id="paysystem" name="<?=$field['NAME'] ?>">
					<? foreach($field['OPTIONS'] as $option): ?>
					<option<? if($option['~SELECTED']): ?> selected="selected"<? endif; ?> value="<?=$option['VALUE'] ?>">
					<?=$option['CAPTION'] ?>
					</option>
					<? endforeach; ?>
				</select>
			</div>
			<? } else { ?>
			<input type="hidden" name="<?=$field['NAME'] ?>" value="<?=$field['VALUE'] ?>" />
			<? } ?>
		<? endif; ?>	

		<? if ( $USER->IsAdmin() ) : ?>
			<div class="preference company clearfix">
				<label class="title" for="accountcode">Account code</label>
				<input class="text" id="accountcode" name="accountcode" value="<? $arParams[ "accountcode" ] ?>" />
			</div>
		<? endif; // if ( $USER->IsAdmin() ) ?>
		
		<? if(isset($arParams['DISPLAY_CURRENCY']) && $arParams['DISPLAY_CURRENCY'] == 'Y'): ?>
			<? $APPLICATION->IncludeComponent("travelshop:ibe.currency", "in_form", array("CURRENCY_DEFAULT" => "RUR")); ?>
		<? endif; ?>
		
		<? if ( isset($arParams['DISPLAY_PROMOCODE']) && $arParams['DISPLAY_PROMOCODE'] == 'Y' && isset($arResult['PROMOCODE']) && count($arResult['PROMOCODE']) > 0) : ?>
		<div class="discounts">
			<legend>
			<?=GetMessage('TS_STEP1_SEARCHFORM_DISCOUNT_TITLE') ?>
			</legend>
			<div class="promocode clearfix">
				<label class="title" for="promocode">
					<?=GetMessage("TS_STEP1_SEARCHFORM_PROMOCODE") ?>
				</label>
				<input class="text" id="promocode" name="<?= $arResult['PROMOCODE']['NAME'] ?>" type="text" value="<?= $arResult['PROMOCODE']['VALUE'] ?>" />
			</div>
		</div>
		<? endif; ?>
	
		<? if($arParams['DISPLAY_PAYMENT'] == 'Y'): ?>
		<div class="payment">
			<label class="title" for="waitpays">
				<?=GetMessage("TS_STEP1_SEARCHFORM_TARIFFS") ?>
			</label>
			<? if(count($arResult['select_waitpays']['REFERENCE_ID'])): ?>
			<select id="waitpays" name="waitpays" onchange="setWaitpays(this)">
				<? for($i=0; $i<count($arResult['select_waitpays']['REFERENCE_ID']); $i++): ?>
				<option<? if($arResult['select_waitpays']['REFERENCE_ID'][$i] == $arResult['select_waitpays_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_waitpays']['REFERENCE_ID'][$i] ?>">
				<?=$arResult['select_waitpays']['REFERENCE'][$i] ?>
				</option>
				<? endfor; ?>
			</select>
			<? endif; ?>
			<span id="paydate_block">
			<? ViewCalendar("paydate", $arResult['paydate'], '', "/bitrix/components/travelshop/ibe.tools/templates/.default/"); ?>
			</span>
		</div>
		<? endif; ?>
		
		<? if (array_key_exists('select_discount', $arResult)): ?>
			<!-- Скидки -->
			<div class="passenger category">
				<label class="title" for="passcat"><?=GetMessage("TS_STEP1_SEARCHFORM_PASSENGER_CATEGORY") ?></label>
				<? if(count($arResult['select_discount']['REFERENCE_ID'])): ?>
				<select id="passcat" name="passcat" onchange="setDiscount(this)">
					<? for($i=0; $i<count($arResult['select_discount']['REFERENCE_ID']); $i++): ?>
					<option<? if($arResult['select_discount']['REFERENCE_ID'][$i] == $arResult['select_discount_selected']): ?> selected="selected"<? endif; ?> value="<?=$arResult['select_discount']['REFERENCE_ID'][$i] ?>">
					<?=$arResult['select_discount']['REFERENCE'][$i] ?>
					</option>
					<? endfor; ?>
				</select>
				<? endif; ?>
			</div>
		<? endif; ?>
		</fieldset>
		
	</div>
</form>
<script type="text/javascript">
// <![CDATA[
formInit();

<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/travelshop/ibe.frontoffice/templates/.default/calendar_scripts.php'); ?>
 
<? 
  $JQ_CALENDAR_NUMBER_OF_MONTHS = intval( $arParams['JQ_CALENDAR_NUMBER_OF_MONTHS'] ) ? intval( $arParams['JQ_CALENDAR_NUMBER_OF_MONTHS'] ) : 1; // Количество отображаемых за раз месяцев во всплывающем календаре. По умолчанию 1.
  $JQ_CALENDAR_STEP_MONTHS = intval( $arParams['JQ_CALENDAR_STEP_MONTHS'] ) ? intval( $arParams['JQ_CALENDAR_STEP_MONTHS'] ) : $JQ_CALENDAR_NUMBER_OF_MONTHS; // На сколько месяцев сдвигаться за раз во всплывающем календаре. По умолчанию равно количеству отображаемых месяцев.
  $JQ_CALENDAR_SHOW_OTHER_MONTHS = ( "Y" ==  $arParams['JQ_CALENDAR_SHOW_OTHER_MONTHS'] ) ? "true" : "false"; // Показывать дни из соседних с выбранным месяцем. По умолчанию нет.
  $JQ_CALENDAR_SELECT_OTHER_MONTHS = ( "Y" ==  $arParams['JQ_CALENDAR_SELECT_OTHER_MONTHS'] ) ? "true" : "false"; // Разрешать выбор дня из соседних с выбранным месяцем. По умолчанию нет.
  $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR = ( isset($arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR']) && "Y" ==  $arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR'] || !isset($arParams['JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR']) ) ? "true" : "false"; // Разрешать выбор месяца и года. По умолчанию нет.
?>
 
$(function() {
  calendarTo.datepicker({ 
    showOn: 'both',
    buttonImage: '<?= $templateFolder ?>/img/calendar.gif',
    buttonText: '<?=GetMessage('TS_SHORTFORM_CALENDAR_BUTTON') ?>',
    buttonImageOnly: true,
    showOtherMonths: <?= $JQ_CALENDAR_SHOW_OTHER_MONTHS ?>,
    selectOtherMonths: <?= $JQ_CALENDAR_SELECT_OTHER_MONTHS ?>,
    changeMonth: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    changeYear: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    minDate: 0,
    maxDate: '+1y',
    stepMonths: <?= $JQ_CALENDAR_STEP_MONTHS ?>,
    numberOfMonths: <?= $JQ_CALENDAR_NUMBER_OF_MONTHS ?>,
    onSelect: function(dateText) {
      selectForwardDate(dateText)
    }
  });
  calendarTo.datepicker('setDate', defaultDateTo);
  
  calendarBack.datepicker({ 
    showOn: 'both',
    buttonImage: '<?= $templateFolder ?>/img/calendar.gif',
    buttonText: '<?=GetMessage('TS_SHORTFORM_CALENDAR_BUTTON') ?>',
    buttonImageOnly: true,
    showOtherMonths: <?= $JQ_CALENDAR_SHOW_OTHER_MONTHS ?>,
    selectOtherMonths: <?= $JQ_CALENDAR_SELECT_OTHER_MONTHS ?>,
    changeMonth: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    changeYear: <?= $JQ_CALENDAR_CHANGE_MONTGH_AND_YEAR ?>,
    minDate: 0,
    maxDate: '+1y',
    stepMonths: <?= $JQ_CALENDAR_STEP_MONTHS ?>,
    numberOfMonths: <?= $JQ_CALENDAR_NUMBER_OF_MONTHS ?>,
    onSelect: function(dateText) { 
      selectBackDate(dateText)
    }
  });
  calendarBack.datepicker('setDate', defaultDateBack)
});


 <? if( $USE_AUTOCOMPLETE ): // Если используется автозаполнение ?>
  // подключаем к полям ввода пунктов Autocomplete
  $("#depart, #arrival").autocomplete("<?= $componentPath ?>/get_cities.php", {
      extraParams: {
        lang: "<?= LANGUAGE_ID ?>" // Язык поиска
      },
      max: 40, // Максимальное количество пунктов в ответе
      scrollHeight: 300, // Высота в px
      autoFill: false, // Автоматически подставлять первый найденный пункт
      delay: 200, // Задержка перед отправкой запроса (в ms)
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
 <? endif; ?>

<? if ( is_array($arResult["ROUTES"]) && count($arResult["ROUTES"]) ): // Если задана маршрутная сеть ?>

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
   
   currentArrival = $("#ts_ag_reservation form #arrival option:selected").val();
   $("#ts_ag_reservation form #arrival option").each( function (i) { // Удаляем все пункты прибытия
     $(this).remove();
   });
   var depart = $("#ts_ag_reservation form #depart").val();
   if ( routes[depart]["ROUTES"] ) { // Если для выбранного пункта вылета заданы пункты прибытия
     for ( var code in routes[depart]["ROUTES"] ) {
       if ( routes[code] ) { // добавляем их в список
         $("#ts_ag_reservation form #arrival").append('<option value="' + code + '"' + ( currentArrival == code ? ' selected="selected"' : '' ) + '>' + routes[code]["NAME"] + '</option>');
       }
     }
   }
   
 }
 
 $(document).ready( function () { buildArrivalList() } );
 $("#ts_ag_reservation form #depart").change( function () { buildArrivalList() } );

<? endif; // if ( is_array($arResult["ROUTES"]) && count($arResult["ROUTES"]) ): ?>

// ]]>
</script>
<? endif; // ( $arResult[ "~SHOW_FORM" ] ) ?>
