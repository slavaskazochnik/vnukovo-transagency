<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?
if ( !defined("__JQUERY_JS") ) {
	define("__JQUERY_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript($templateFolder."/js/jquery-1.5.1.min.js");
}
if ( !defined("__JQUERY_PLACEHOLDER_JS") ) {
	define("__JQUERY_PLACEHOLDER_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript($templateFolder."/js/jquery.placeholder.js");
}
if ( !defined("__JQUERY_FROM_JS") ) {
	define("__JQUERY_FROM_JS", true);
	$GLOBALS["APPLICATION"]->AddHeadScript($templateFolder."/js/jquery.form.js");
}
?>

<div class="itinerary_search">
  <h2><?= GetMessage("IBE_ITINERARY_SEARCH_TITLE") ?></h2>

  <form method="post" action="<?= $arResult["GET_ITINERARY_URL"] ?>" id="itnSearch" name="itnSearch" class="clearfix">
    <div class="col">
        <label for="is-order"><?= GetMessage("IBE_ITINERARY_SEARCH_ORDER_LABEL") ?></label>
        <input type="text" name="is-order" maxlength="9" id="is-order" class="digits-only" placeholder="<?= GetMessage("IBE_ITINERARY_SEARCH_ORDER_PLACEHOLDER") ?>" />
    </div>
    <div class="col">
        <label for="is-email"><?= GetMessage("IBE_ITINERARY_SEARCH_EMAIL_LABEL") ?></label>
        <input type="text" name="is-email" maxlength="64" id="is-email" placeholder="<?= GetMessage("IBE_ITINERARY_SEARCH_EMAIL_PLACEHOLDER") ?>" />
    </div>
    <div class="col">
      <label for="is-submit">&nbsp;</label>
      <input type="submit" id="is-submit" class="submit" value="<?= GetMessage("IBE_ITINERARY_SEARCH_SUBMIT_LABEL") ?>" />
    </div>
  </form>
</div>

<script type="text/javascript">
// <![CDATA[
$().ready(function () {
  // Ðàññòàâëÿåì ïëåéñõîëäåðû
  $('#itnSearch input[type="text"]').placeholder();

  // Ðàçðåøàåì ââîäèòü òîëüêî òîëüêî öèôðû
  $('.digits-only').each(function () {
    $(this).keypress(function (evt) {
      var cc = evt.charCode;
      if (((cc < 48) || (cc > 57)) && (evt.keyCode === 0)) {
          evt.preventDefault();
      }
    });
  });

  // Äèíàìè÷åñêè ïðîâåðÿåì çàïîëíåíèå ïîëåé
  $('#itnSearch #is-order').bind('keypress keyup blur', function () {
    if ( !parseInt( $(this).val() ) ) {
      $('#itnSearch label[for="is-order"]').addClass('error');
    } else {
      $('#itnSearch label[for="is-order"]').removeClass('error');
    }
  });
  $('#itnSearch #is-email').bind('keypress keyup blur', function () {
    if ( !$(this).val().length ) {
      $('#itnSearch label[for="is-email"]').addClass('error');
    } else {
      $('#itnSearch label[for="is-email"]').removeClass('error');
    }
  });

  // Îòïðàâëÿåì ôîðìó ÷åðåç AJAX
  $('#itnSearch').ajaxForm({
    cache: false, // Çàïðåùàåì êåøèðîâàíèå
    // Ïðîâåðÿåì ïîëÿ ïåðåä îòïðàâêîé
    beforeSubmit: function(formData, jqForm, options) {
      submitOK = true;
      if ( !parseInt( $(jqForm).find('#is-order').val() ) ) {
        $(jqForm).find('label[for="is-order"]').addClass('error');
        $(jqForm).find('#is-order').focus();
        submitOK = false;
      } else {
        $(jqForm).find('label[for="is-order"]').removeClass('error');
      }
      if ( !$(jqForm).find('#is-email').val().length ) {
        $(jqForm).find('label[for="is-email"]').addClass('error');
        if ( submitOK ) {
          $(jqForm).find('#is-email').focus();
        }
        submitOK = false;
      } else {
        $(jqForm).find('#is-email').removeClass('error');
      }
      if ( submitOK ) {
        $(jqForm).find('label[for="is-submit"]').removeClass('error').html('<p class="wait"><?= GetMessage("IBE_ITINERARY_SEARCH_IN_PROGRESS") ?></p>');
        $(jqForm).find('#is-submit').attr('disabled', 'disabled');
      };
      return submitOK;
    },
    // Îáðàáàòûâàåì îòâåò îò ñåðâåðà
    success: function(responseXML, statusText, xhr, jqForm) {
      $(jqForm).find('#is-submit').removeAttr('disabled');
      if ( $(responseXML).find('itineraryUrl').text().length ) { // Åñëè ìàðøðóò-êâèòàíöèÿ íàéäåíà
        $(jqForm).find('label[for="is-submit"]').html( '&nbsp;' );
        window.location = $(responseXML).find('itineraryUrl').text(); // îòêðûâàåì åå
      } else if ( $(responseXML).find('error').text().length ) { // Åñëè âîçíèêëà îøèáêà
        $(jqForm).find('label[for="is-submit"]').addClass('error') // ïîêàçûâàåì åå íàä êíîïêîé "Ñêà÷àòü" è ïîòîì ñêðûâàåì
        .html( $(responseXML).find('error').text() )
        .delay(4000)
        .fadeOut('slow', function() {
          $(this).html( '&nbsp;' )
          .removeClass('error')
          .show();
        });
      }
    },
    complete: function (jqXHR, textStatus){ // Åñëè ñåðâåð íå äîñòóïåí
      if ( textStatus != 'success' ) {
        $('#itnSearch').find('#is-submit').removeAttr('disabled');
        $('#itnSearch').find('label[for="is-submit"]').addClass('error') // ïîêàçûâàåì îøèáêó íàä êíîïêîé "Ñêà÷àòü" è ïîòîì ñêðûâàåì
        .html( '<?= GetMessage("IBE_ITINERARY_SEARCH_NOT_ACCESS") ?>' )
        .delay(4000)
        .fadeOut('slow', function() {
          $(this).html( '&nbsp;' )
          .removeClass('error')
          .show();
        });
      }
    },
    /*
    uploadProgress: function(event, position, total, percentComplete) {
      $(jqForm).find('label[for="is-submit"]').text( 'Èäåò ïîèñê çàêàçà ... ' + percentComplete + '%' );
    }
    */
  });

});
// ]]>
</script>