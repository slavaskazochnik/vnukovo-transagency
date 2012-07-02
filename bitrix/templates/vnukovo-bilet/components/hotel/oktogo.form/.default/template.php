<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?
if ( !defined("__JQUERY_JS") ) {
	define("__JQUERY_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript("http://oktogo.ru/Content64/js/jQuery/jquery-1.5.2.min.js");
}
if ( !defined("__JQUERY_UI_JS") ) {
	define("__JQUERY_UI_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript("http://oktogo.ru/Content64/js/jQuery/jquery-ui-1.8.11.custom.min.js");
}
?>
<script type="text/javascript" src="http://oktogo.ru/Content64/js/jQuery/jquery.components.js" charset="utf-8"></script>
<script type="text/javascript" src="http://oktogo.ru/Content64/js/api.js"></script>
<!--[if IE 6]>
  <link type="text/css" rel="stylesheet" href="<?= $templateFolder ?>/ie6.css" />
<![endif]-->
<!--[if IE]>
  <style>
    .oktogo_search_form input,
    .oktogo_search_form input[type="text"],
    .oktogo_search_form form input[type="text"],
    .oktogo_search_form .pseudoinput { border-top: 1px solid #ddd!important; border-right: 1px solid #ddd!important; }
    .oktogo_search_form input[type="submit"],
    .oktogo_search_form form input[type="submit"],
    .oktogo_search_form .form input[type="submit"] { border: none!important; }
  </style>
<![endif]-->

<div class="oktogo_search_form sect_text">
	<form class="booking" method="get" id="frmSearch" name="frmSearch">
		<h2><?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_HEADER") ?></h2>
		<div class="line clearfix">
			<div class="destination">
				<input type="text" tabindex="1" placeholder="<?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_DESTINATION") ?>" id="dest" name="dest" maxlength="100" />
			</div>
			<input type="hidden" id="destination" name="destination"/>
			<input type="hidden" id="destinationId" name="destinationId"/>
			<input type="hidden" id="product" name="product" value="Hotel"/>
			<input type="hidden" name="CheckInDate" id="CheckInDate" />
			<input type="hidden" name="CheckOutDate"    id="CheckOutDate" />
			<div class="date">
				<input type="text" tabindex="2" placeholder="<?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_CHECKIN") ?>" id="CheckIn"/>
			</div>
			<div class="calendDelimetr">-</div>
			<div class="date">
				<input type="text" tabindex="3" placeholder="<?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_CHECKOUT") ?>" id="CheckOut"/>
			</div>
		</div>
		
		<div id="pnlSearchParams" class="line clearfix">
			<div id="pnlRooms"></div>
			<a href="javascript:;" title="<?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_ADD_ROOM") ?>" class="btnAddRoom"><?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_ADD_ROOM") ?></a>
		</div>
		<div>
			<div class="validation-summary-valid" data-valmsg-summary="true" id="valSumSearch">
				<ul><li style="display:none"></li></ul>
			</div>
		</div>
		<div data-form-hidden="1" style="display:none"></div>
		<div class="clearfix submit">
			<input type="submit" class="booking_submit" value="<?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_BOOKING_SUBMIT") ?>" />
		</div>
	</form>
</div>
<script type="text/javascript">
// <![CDATA[
function changeLabels() {
	$('#pnlRooms .numberLine').each(function(){
		if ( $(this).children('.label').text() != '<?=GetMessage('OKTOGO_HOTEL_SEARCH_FORM_GUESTS')?>' ) {
			$(this).children('.label').text('<?=GetMessage('OKTOGO_HOTEL_SEARCH_FORM_GUESTS')?>');
		}
		if ( $(this).children('.adults').text().indexOf('<?=GetMessage('OKTOGO_HOTEL_SEARCH_FORM_ADULT')?>') == -1 ){
			$(this).children('.adults').prepend('<label><?=GetMessage('OKTOGO_HOTEL_SEARCH_FORM_ADULT')?></label>');
		}
		if ( $(this).children('.children').text().indexOf('<?=GetMessage('OKTOGO_HOTEL_SEARCH_FORM_CHILD')?>') == -1 ){
			$(this).children('.children').prepend('<label><?=GetMessage('OKTOGO_HOTEL_SEARCH_FORM_CHILD')?></label>');
		}
	});
}

$().ready(function () {
  var api = new OKApi();
  api.searchForm();
  
  changeLabels();
})

$('#pnlSearchParams .btnAddRoom').click( function() {setTimeout(changeLabels, 20); });
// ]]>
</script>