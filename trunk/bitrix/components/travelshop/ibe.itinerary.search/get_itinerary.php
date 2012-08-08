<?
require_once( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php" );

__IncludeLang( $componentPath . "/lang/" . LANGUAGE_ID . "/component.php" );

/*************************************************************************
	Init
*************************************************************************/
if ( !CModule::IncludeModule("ibe") ) {
	die();
}

/*************************************************************************
	Processing of received parameters
*************************************************************************/
$error = false;

$order = utf8win1251( htmlspecialchars(trim($_REQUEST[ "is-order" ])) ); // Ќомер заказа
$email = utf8win1251( htmlspecialchars(trim($_REQUEST[ "is-email" ])) ); // E-mail или телефон покупател€

if ( strlen( $order ) &&  strlen( $email ) ) { // ≈сли задан номер заказа и E-mail или телефон покупател€
  $arOrder = CIBEOrder::Get( $order ); // »щем заказ по номеру
  if ( ToLower( $arOrder['CTC_MAIL'] ) == ToLower( $email ) || ToLower( $arOrder['CTC_PHONE'] ) == ToLower( $email ) ) { // ≈сли E-mail или телефон соответствуют заказу
    $itineraryUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/bitrix/components/travelshop/ibe.frontoffice/order_doc.php?id=' . $order . '&order_key=' . order_key($arOrder) . '&mode=pdf&lang=' . LANGUAGE_ID . '&site=' . SITE_ID; // возвращаем ссылку на маршрут-квитанцию (должно работать и в случае стандартной PDF-квитанции и в случае нестандартной маршрут-квитанции)
  } else { // в противном случае
    $error = true; // выставл€ем признак ошибки
  }
} else { // в противном случае
  $error = true; // выставл€ем признак ошибки
}

/*************************************************************************
	Body
*************************************************************************/
$xml = '<xml>';
if ( $error ) { // ¬ случае возникновени€ ошибки
  $xml .= '<error>';
  $xml .= GetMessage("IBE_ORDER_NOT_FOUND"); // возвращаем ее в XML-формате
  $xml .= '</error>';
  //echo json_encode( array("error" => "Order not found") ); // возвращаем ее в JSON-формате
} else { // в противном случае
  $xml .= '<itineraryUrl>';
  $xml .= htmlentities( $itineraryUrl ); // возвращаем ссылку на маршрут-квитанцию
  $xml .= '</itineraryUrl>';
}
$xml .= '</xml>';
header( 'Content-Type: text/xml; charset=cp1251' ); // ¬ыставл€ем XML-формат
print $xml; // ¬озвращаем XML

ob_start(); // ѕодавл€ем служебный вывод, который может испортить XML
require_once( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php" );
ob_end_flush();
?>
