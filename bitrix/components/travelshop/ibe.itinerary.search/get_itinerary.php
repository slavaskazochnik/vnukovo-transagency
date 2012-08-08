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

$order = utf8win1251( htmlspecialchars(trim($_REQUEST[ "is-order" ])) ); // ����� ������
$email = utf8win1251( htmlspecialchars(trim($_REQUEST[ "is-email" ])) ); // E-mail ��� ������� ����������

if ( strlen( $order ) &&  strlen( $email ) ) { // ���� ����� ����� ������ � E-mail ��� ������� ����������
  $arOrder = CIBEOrder::Get( $order ); // ���� ����� �� ������
  if ( ToLower( $arOrder['CTC_MAIL'] ) == ToLower( $email ) || ToLower( $arOrder['CTC_PHONE'] ) == ToLower( $email ) ) { // ���� E-mail ��� ������� ������������� ������
    $itineraryUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/bitrix/components/travelshop/ibe.frontoffice/order_doc.php?id=' . $order . '&order_key=' . order_key($arOrder) . '&mode=pdf&lang=' . LANGUAGE_ID . '&site=' . SITE_ID; // ���������� ������ �� �������-��������� (������ �������� � � ������ ����������� PDF-��������� � � ������ ������������� �������-���������)
  } else { // � ��������� ������
    $error = true; // ���������� ������� ������
  }
} else { // � ��������� ������
  $error = true; // ���������� ������� ������
}

/*************************************************************************
	Body
*************************************************************************/
$xml = '<?xml version="1.0" encoding="windows-1251"?>'; // �����������, ����� �������� � IE
$xml .= '<xml>';
if ( $error ) { // � ������ ������������� ������
  $xml .= '<error>';
  $xml .= GetMessage("IBE_ORDER_NOT_FOUND"); // ���������� �� � XML-�������
  $xml .= '</error>';
  //echo json_encode( array("error" => "Order not found") ); // ���������� �� � JSON-�������
} else { // � ��������� ������
  $xml .= '<itineraryUrl>';
  $xml .= htmlentities( $itineraryUrl ); // ���������� ������ �� �������-���������
  $xml .= '</itineraryUrl>';
}
$xml .= '</xml>';
header( 'Content-Type: text/xml; charset=windows-1251' ); // ���������� XML-������
print $xml; // ���������� XML

ob_start(); // ��������� ��������� �����, ������� ����� ��������� XML
require_once( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php" );
ob_end_flush();
?>
