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
if ( !defined("__JQUERY_UI_CSS") ) {
  define("__JQUERY_UI_CSS", true);
  $GLOBALS['APPLICATION']->SetAdditionalCSS($templateFolder."/jquery-ui-1.8.21.custom.css");
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

<div class="oktogo_search_form">
  <form class="booking" method="get" id="frmSearch" name="frmSearch">
      <div class="likeH1"><?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_HEADER") ?></div>
      <div class="line">
        <input type="text" tabindex="1" title="<?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_DESTINATION") ?>" id="dest" name="dest" maxlength="100" />
        <input type="hidden" id="destination" name="destination"/>
        <input type="hidden" id="destinationId" name="destinationId"/>
        <input type="hidden" id="product" name="product" value="Hotel"/>
        <input type="hidden" name="CheckInDate" id="CheckInDate" />
        <input type="hidden" name="CheckOutDate"    id="CheckOutDate" />
      </div>
      <div class="calendLine">
        <input type="text" tabindex="2" title="<?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_CHECKIN") ?>" id="CheckIn"/>
        <div class="calendDelimetr">-</div>
        <input type="text" tabindex="3" title="<?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_CHECKOUT") ?>" id="CheckOut"/>
  
      </div>
      <div class="cb"></div>
      <div id="pnlSearchParams">
        <div id="pnlRooms"></div>
        <a href="javascript:;" title="<?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_ADD_ROOM") ?>" class="btnAddRoom"><?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_ADD_ROOM") ?></a>
      </div>
      <div class="line">
        <div class="validation-summary-valid" data-valmsg-summary="true" id="valSumSearch"><ul><li style="display:none"></li></ul></div>
      </div>
      <div data-form-hidden="1" style="display:none"></div>
      <input type="submit" class="booking_submit" value="&nbsp;" title="<?= GetMessage("OKTOGO_HOTEL_SEARCH_FORM_BOOKING_SUBMIT") ?>" />
          <!--[if lt IE 9]>
            <em class="tl"></em>
            <em class="tr"></em>
          <![endif]-->
          <em class="bottom">
        <em class="bl"></em>
        <em class="br"></em>
      </em>
  </form>
</div>

<script type="text/javascript">
// <![CDATA[
$().ready(function () {
  var api = new OKApi();
  api.searchForm();
})
// ]]>
</script>