<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/xml.php');

/*************************************************************************
			Онлайн табло аэропорта Шереметьево
*************************************************************************/

class CAirportBoard
{
  function GetBoard () // Возвращает табло вылета и прилета
  {
    $result = Array(); // Список прилетающих ["INBOUND"] и вылетающих ["OUTBOUND"] рейсов
    $result = CAirportBoard::GetBoardFromSite();

    return $result;
  }

  function GetDateTimeArray ( $time, $date ) // Разбор строки на дату и время
  {  
    $result = Array(
        "TIME" => $time
      );
    if ( isset($date) && strlen($date) )
    {
      $month_search = array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
      $month_replace = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
      $date = str_ireplace($month_search, $month_replace, $date);
      $explode = preg_match_all( "/([0-9]{1,2})[^\d]+([0-9]{2})/",
          $date,
          $matches,
          PREG_PATTERN_ORDER
        );//trace($matches);
      $result["DATE"] = Array(
          "DAY"   => strlen($matches[1][0]) == 1 ? "0".$matches[1][0] : $matches[1][0],
          "MONTH" => $matches[2][0],
        );
      $result["DATETIME"] = $result["DATE"]["MONTH"].$result["DATE"]["DAY"].str_replace(":", "", $result["DATE"]["TIME"]);
    }
    return $result;
  }

  function GetStatusInfo ( $string ) // Возвращает информацию о статусе рейса
  {
    $string = $string[1][0];
    switch ( ToLower($string) )
    {
      case "прибыл":
      case "совершил":
        $result["CODE"] = "L";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_L");
      break;

      case "задерживается":
      case "задержан":
        $result["CODE"] = "D";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_D");
      break;

      /*
      case "":
        $result["CODE"] = "P";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_P");
      break;
      */

      case "отправлен":
      case "вылетел":
        $result["CODE"] = "F";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_F");
      break;

      case "отменен":
        $result["CODE"] = "C";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_C");
      break;
      
      case "регистрация":
        $result["CODE"] = "R";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_R");
      break;

      default:
        $result["CODE"] = "";
        $result["NAME"] = htmlspecialchars($string);

    }
    $result["~NAME"] = htmlspecialchars($string);

    return $result;
  }

  function GetBoardFromSite () // Загрузка и разбор табло с сайта
  {
    $result = Array();
    global $APPLICATION;

    $ob = new CHTTP();
    $ob->http_timeout = 60;
    $ob->Query(
        "GET",
        "svo.aero",
        80,
        "/timetable/today/?ts=".mktime(),
        false,
        "",
        "N"
      );
      
    $result["ERROR"] = Array(
      "CODE"     => $ob->errno,
      "MESSAGE"  => $ob->errstr
      );

    $res = $APPLICATION->ConvertCharset($ob->result, "UTF-8", SITE_CHARSET);

    $res = str_replace('</thead><tbody>', "++++", $res);
    $res = str_replace('</tr></tbody></table></div>', "</tr>++++", $res);
    $explode = explode("++++", $res);
    $res = '<table>'.$explode[1].'</table>';
    $resPatterns = Array(
      "/<a[^>]*>/Uis",
      "/<\/a>/Uis"
      );
    $resReplace = Array(
      "",
      ""
      );
    $res = preg_replace($resPatterns, $resReplace, $res);
    
    //trace($res);

    if ( !intval($result["ERROR"]["CODE"]) ) // Если данные были получены без ошибки
    {
      $result["INBOUND"]["AK_NAMES"] = Array();
      $result["INBOUND"]["AK_CODES"] = Array();
      $result["INBOUND"]["DEPARTURES"] = Array();
      $result["INBOUND"]["ARRIVALS"] = Array();
      $result["INBOUND"]["TERMINALS"] = Array();
      $result["OUTBOUND"]["AK_NAMES"] = Array();
      $result["OUTBOUND"]["AK_CODES"] = Array();
      $result["OUTBOUND"]["DEPARTURES"] = Array();
      $result["OUTBOUND"]["ARRIVALS"] = Array();
      $result["OUTBOUND"]["TERMINALS"] = Array();
      
      $xml = new CDataXML();
      if ( $xml->LoadString($res) )
      {
        $node = $xml->SelectNodes("/table");
        $rows = $node->elementsByName("tr");
        $i = 0;
        foreach ( $rows as $row )
        {
          $boardType = strstr($row->getAttribute("class"), "sA") ? "INBOUND" : ( strstr($row->getAttribute("class"), "sD") ? "OUTBOUND" : false ) ;
          if ( $boardType )
          {
            $cells = $row->elementsByName("td");
            if ( count($cells) )
            {
              $img = $cells[4]->elementsByName("img");
              preg_match_all( "/([0-9]{1,2}:[0-9]{1,2})/",
                  $cells[7]->content,
                  $timeFromStatus,
                  PREG_PATTERN_ORDER
                );
              preg_match_all( "/^([а-я]+)\s*/i",
                  $cells[7]->content,
                  $status,
                  PREG_PATTERN_ORDER
                );
              $result[$boardType]["FLIGHTS"][$i] = Array(
                  "FLIGHT"            => Array(
                      "AK_CODE"       => $cells[2]->content,
                      "NUMBER"        => $cells[3]->content
                    ),
                  "AK_NAME"           => count($img) ? htmlspecialchars( $img[0]->getAttribute("alt") ) : "",
                  "DEPARTURE"         => htmlspecialchars( $cells[5]->content ),
                  "ARRIVAL"           => htmlspecialchars( $cells[5]->content ),
                  "STATUS"            => CAirportBoard::GetStatusInfo( $status ),
                  "TIME"              => Array(
                      "PLANNED"       => CAirportBoard::GetDateTimeArray( $cells[1]->content, $cells[0]->content ),
                      "ESTIMATED"     => strpos($cells[7]->content, "задерживается") !== false || strpos($cells[7]->content, "ожидается") !== false ? CAirportBoard::GetDateTimeArray( $timeFromStatus[1][0] ) : "",
                      "ACTUAL"        => strpos($cells[7]->content, "совершил посадку") !== false || strpos($cells[7]->content, "прибыл") !== false || strpos($cells[7]->content, "вылетел") !== false || strpos($cells[7]->content, "отправлен") !== false ? CAirportBoard::GetDateTimeArray( $timeFromStatus[1][0] ) : ""
                    ),
                  "TERMINAL"          => $cells[6]->content
                );
              // Формируем список уникальных терминалов и пунктов вылета и прилета для фильтра
              if ( !in_array($result[$boardType]["FLIGHTS"][$i]["FLIGHT"]["AK_CODE"], $result[$boardType]["AK_CODES"]) )
              {
                $result[$boardType]["AK_CODES"][] = $result[$boardType]["FLIGHTS"][$i]["FLIGHT"]["AK_CODE"];
              }
              if ( !in_array($result[$boardType]["FLIGHTS"][$i]["AK_NAME"], $result[$boardType]["AK_NAMES"]) && strlen($result[$boardType]["FLIGHTS"][$i]["AK_NAME"]) )
              {
                $result[$boardType]["AK_NAMES"][] = $result[$boardType]["FLIGHTS"][$i]["AK_NAME"];
              }
              if ( !in_array($result[$boardType]["FLIGHTS"][$i]["DEPARTURE"], $result[$boardType]["DEPARTURES"]) )
              {
                $result[$boardType]["DEPARTURES"][] = $result[$boardType]["FLIGHTS"][$i]["DEPARTURE"];
              }
              if ( !in_array($result[$boardType]["FLIGHTS"][$i]["ARRIVAL"], $result[$boardType]["ARRIVALS"]) )
              {
                $result[$boardType]["ARRIVALS"][] = $result[$boardType]["FLIGHTS"][$i]["ARRIVAL"];
              }
              if ( !in_array($result[$boardType]["FLIGHTS"][$i]["TERMINAL"], $result[$boardType]["TERMINALS"]) )
              {
                $result[$boardType]["TERMINALS"][] = $result[$boardType]["FLIGHTS"][$i]["TERMINAL"];
              }
              $i++;
            }
          }
        }
      }
      
      sort($result["INBOUND"]["AK_NAMES"]);
      sort($result["INBOUND"]["AK_CODES"]);
      sort($result["INBOUND"]["DEPARTURES"]);
      sort($result["INBOUND"]["ARRIVALS"]);
      sort($result["INBOUND"]["TERMINALS"]);
      sort($result["OUTBOUND"]["AK_NAMES"]);
      sort($result["OUTBOUND"]["AK_CODES"]);
      sort($result["OUTBOUND"]["DEPARTURES"]);
      sort($result["OUTBOUND"]["ARRIVALS"]);
      sort($result["OUTBOUND"]["TERMINALS"]);
      
      /*
      // Сортируем рейсы по абсолютному времени вылета
      SortFreeStyleArray( $result["FLIGHTS"], Array(
          '["TIME"]["PLANNED"]["DATETIME"]' => 'ASC',
          '["FLIGHT"]'                      => 'ASC'
          )
        );
      */
      
      //trace($result);
    }
    return $result;
  }
}

?>