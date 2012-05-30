<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/xml.php');

/*************************************************************************
			Онлайн табло аэропорта Внуково
*************************************************************************/

class CAirportBoard 
{
  function GetBoard () // Возвращает табло вылета и прилета
  {
    $result["INBOUND"] = Array(); // Список прилетающих рейсов
    $result["OUTBOUND"] = Array(); // Список вылетающих рейсов
    $result["INBOUND"] = CAirportBoard::GetBoardFromSite( 'time-table.direction=0&time-table.flight-number=&time-table.dep-airport-id=&time-table.airline-id=&status=&lang=rus' );
    $result["OUTBOUND"] = CAirportBoard::GetBoardFromSite( 'time-table.direction=1&time-table.flight-number=&time-table.dep-airport-id=&time-table.airline-id=&status=&lang=rus' );
    
    return $result;
  }
  
  function GetDateTimeArray ( $string ) // Разбор строки на дату и время
  {
    preg_match_all( "/([0-9]{2}\:[0-9]{2})\s*([0-9]{2})\.([0-9]{2})/",
      $string,
      $matches,
      PREG_PATTERN_ORDER
    );
    return Array(
        "DATE"      => Array(
            "DAY"   => $matches[2][0],
            "MONTH" => $matches[3][0],
          ),
        "TIME"      => $matches[1][0]
      );
      
  }
  
  function GetStatusInfo ( $string ) // Возвращает информацию о статусе рейса
  {
    switch ( ToLower(trim($string)) )
    {
      case "прилетел":
        $result["CODE"] = "L";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_L");
      break;
      
      case "не вылетел":
        $result["CODE"] = "D";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_D");
      break;
      
      case "-":
        $result["CODE"] = "P";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_P");
      break;
      
      case "вылетел":
        $result["CODE"] = "F";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_F");
      break;
      
      case "отменен":
        $result["CODE"] = "C";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_C");
      break;
      
      default:
        $result["CODE"] = "";
        $result["NAME"] = "";
        
    }
    $result["~NAME"] = htmlspecialchars($string);
    
    return $result;
  }
  
  function GetBoardFromSite ( $queryParameters ) // Загрузка и разбор табло с сайта
  {
    global $APPLICATION;
    
    $result = Array();
  
    $ob = new CHTTP();
    $ob->http_timeout = 60;
    $ob->Query(
        "GET",
        "www.vnukovo.ru",
        80,
        "/rus/for-passengers/board/data.wbp?".$queryParameters.'&ts='.mktime(),
        false,
        "",
        "N"
      );
  
    $result["ERROR"]["CODE"] = $ob->errno;
    $result["ERROR"]["MESSAGE"] = $ob->errstr; 
    
    if ( !intval($result["ERROR"]["CODE"]) ) // Если данные были получены без ошибки
    {
      $res = $APPLICATION->ConvertCharset($ob->result, "UTF-8", SITE_CHARSET);
      //trace($res);
      
      $xml = new CDataXML();
      if ( $xml->LoadString($res) && $node = $xml->SelectNodes("/responce/rows") )
      {
        $rows = $node->elementsByName("row");
        $ak = Array();
        $departures = Array();
        $arrivals = Array();
        foreach ( $rows as $row )
        {
          $cells = $row->elementsByName("cell");
          // Определяем код авиакомпании и номер рейса
          preg_match_all( "/([A-Za-zА-Яа-я0-9]{2})([0-9]+)/",
              $cells[0]->content,
              $flightNumber,
              PREG_PATTERN_ORDER
            );
          $result["FLIGHTS"][] = Array(
              "FLIGHT"            => Array(
                  "AK_CODE"       => $flightNumber[1][0],
                  "NUMBER"        => $flightNumber[2][0]
                ),
              "AK_NAME"           => htmlspecialchars( $cells[1]->content ),
              "DEPARTURE"         => htmlspecialchars( $cells[2]->content ),
              "ARRIVAL"           => htmlspecialchars( $cells[3]->content ),
              "STATUS"            => CAirportBoard::GetStatusInfo( $cells[4]->content ),
              "TIME"              => Array(
                  "PLANNED"       => CAirportBoard::GetDateTimeArray( $cells[5]->content ),
                  "ESTIMATED"     => CAirportBoard::GetDateTimeArray( $cells[6]->content ),
                  "ACTUAL"        => CAirportBoard::GetDateTimeArray( $cells[7]->content )
                ),
              "TERMINAL"          => htmlspecialchars( $cells[8]->content )
            );
          // Формируем список уникальных авиакомпаний, пунктов вылета и прилета для фильтра
          if ( !in_array(htmlspecialchars( $cells[1]->content ), $ak) )
          {
            $ak[] = htmlspecialchars( $cells[1]->content );
          }
          if ( !in_array(htmlspecialchars( $cells[2]->content ), $departures) )
          {
            $departures[] = htmlspecialchars( $cells[2]->content );
          }
          if ( !in_array(htmlspecialchars( $cells[3]->content ), $arrivals) )
          {
            $arrivals[] = htmlspecialchars( $cells[3]->content );
          }
        }
        $result["AK"] = ksort($ak);
        $result["DEPARTURES"] = ksort($departures);
        $result["ARRIVALS"] = ksort($arrivals);
      }
    }
    return $result;
  }
}

?>
