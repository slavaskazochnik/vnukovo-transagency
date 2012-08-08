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
        <input type="text" name="is-order" maxlength="6" id="is-order" class="digits-only" placeholder="<?= GetMessage("IBE_ITINERARY_SEARCH_ORDER_PLACEHOLDER") ?>" />
    </div>
    <div class="col">
        <label for="is-email"><?= GetMessage("IBE_ITINERARY_SEARCH_EMAIL_LABEL") ?></label>
        <input type="text" name="is-email" id="is-email" placeholder="<?= GetMessage("IBE_ITINERARY_SEARCH_EMAIL_PLACEHOLDER") ?>" />
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
  // Расставляем плейсхолдеры
  $('#itnSearch input[type="text"]').placeholder();

  // Разрешаем вводить только только цифры
  $('.digits-only').each(function () {
    $(this).keypress(function (evt) {
      var cc = evt.charCode;
      if (((cc < 48) || (cc > 57)) && (evt.keyCode === 0)) {
          evt.preventDefault();
      }
    });
  });

  // Динамически проверяем заполнение полей
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

  // Отправляем форму через AJAX
  $('#itnSearch').ajaxForm({
    cache: false, // Запрещаем кеширование
    timeout: 5000,
    // Проверяем поля перед отправкой
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
    // Обрабатываем ответ от сервера
    success: function(responseXML, statusText, xhr, jqForm) {
      $(jqForm).find('#is-submit').removeAttr('disabled');
      if ( $(responseXML).find('itineraryUrl').text().length ) { // Если маршрут-квитанция найдена
        $(jqForm).find('label[for="is-submit"]').html( '&nbsp;' );
        window.location = $(responseXML).find('itineraryUrl').text(); // открываем ее
      } else if ( $(responseXML).find('error').text().length ) { // Если возникла ошибка
        $(jqForm).find('label[for="is-submit"]').addClass('error') // показываем ее над кнопкой "Скачать" и потом скрываем
        .html( $(responseXML).find('error').text() )
        .delay(4000)
        .fadeOut('slow', function() {
          $(this).html( '&nbsp;' )
          .removeClass('error')
          .show();
        });
      }
    },
    complete: function (jqXHR, textStatus){ // Если сервер не доступен
      if ( textStatus != 'success' ) {
        $('#itnSearch').find('#is-submit').removeAttr('disabled');
        $('#itnSearch').find('label[for="is-submit"]').addClass('error') // показываем ошибку над кнопкой "Скачать" и потом скрываем
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
      $(jqForm).find('label[for="is-submit"]').text( 'Идет поиск заказа ... ' + percentComplete + '%' );
    }
    */
  });

});
// ]]>
</script>