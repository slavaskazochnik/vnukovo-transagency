<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/xml.php');

/*************************************************************************
			Онлайн табло аэропорта Пулково
*************************************************************************/

class CAirportBoard
{
  function GetBoard () // Возвращает табло вылета и прилета
  {
    $result["INBOUND"] = Array(); // Список прилетающих рейсов
    $result["OUTBOUND"] = Array(); // Список вылетающих рейсов
    $result["INBOUND"] = CAirportBoard::GetBoardFromSite( 'arrivals/' );
    $result["OUTBOUND"] = CAirportBoard::GetBoardFromSite( 'departures/' );

    return $result;
  }

  function GetDateTimeArray ( $string ) // Разбор строки на дату и время
  {
    preg_match_all( "/([0-9]{2})\.([0-9]{2})\s+([0-9]{2}\:[0-9]{2})/",
      $string,
      $matches,
      PREG_PATTERN_ORDER
    );
    return Array(
        "DATE"      => Array(
            "DAY"   => $matches[1][0],
            "MONTH" => $matches[2][0],
          ),
        "TIME"      => $matches[3][0],
        "DATETIME"  => $matches[2][0].$matches[1][0].str_replace(":", "", $matches[3][0])
      );

  }

  function GetStatusInfo ( $string ) // Возвращает информацию о статусе рейса
  {
    switch ( ToLower(trim($string)) )
    {
      case "прибыл":
      case "приземлился":
        $result["CODE"] = "L";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_L");
      break;

      case "задержан":
        $result["CODE"] = "D";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_D");
      break;

      case "":
        $result["CODE"] = "P";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_P");
      break;

      case "отправлен":
        $result["CODE"] = "F";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_F");
      break;

      case "отменен":
      case "отмена":
        $result["CODE"] = "C";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_C");
      break;

      default:
        $result["CODE"] = "";
        $result["NAME"] = htmlspecialchars($string);

    }
    $result["~NAME"] = htmlspecialchars($string);

    return $result;
  }

  function GetOneBoardFromSite ( $queryParameters, $addQueryParameters )
  {
    global $APPLICATION;

    $ob = new CHTTP();
    $ob->http_timeout = 60;
    $ob->Query(
        "GET",
        "www.pulkovoairport.ru",
        80,
        "/online_serves/online_timetable/".$queryParameters.'?ts='.mktime().(strlen($addQueryParameters) ? '&'.$addQueryParameters : ''),
        false,
        "",
        "N"
      );

    $res = $APPLICATION->ConvertCharset($ob->result, "KOI8-R", SITE_CHARSET);

    $res = str_replace('<table class="tablo tabloBigNew bigTableZebra" border="0">', "++++", $res);
    $res = str_replace('</div> <!-- / gridTbox -->', "++++", $res);
    $explode = explode("++++", $res);
    $res = $explode[1];
    $res = substr($res, 0, strlen($res) - 22);

    return Array(
      "ERROR"      => Array(
        "CODE"     => $ob->errno,
        "MESSAGE"  => $ob->errstr
        ),
      "HTML"    => $res
      );
    unset($explode, $res);
  }

  function GetBoardFromSite ( $queryParameters ) // Загрузка и разбор табло с сайта
  {
    $result = Array();

    $result = CAirportBoard::GetOneBoardFromSite( $queryParameters, "p=1" );
    $res = $result["HTML"];
    unset($result["HTML"]);
    $result2 = CAirportBoard::GetOneBoardFromSite( $queryParameters, "p=2" );
    $res .= $result2["HTML"];
    unset($result2["HTML"]);

    if ( intval($result2["ERROR"]["CODE"]) )
    {
      $result["ERROR"] = $result2["ERROR"];
    }

    if ( !intval($result["ERROR"]["CODE"]) ) // Если данные были получены без ошибки
    {

      $res = '<table>'.$res.'</table>';
      $res = str_replace("<br>", " ", $res);
      $res = str_replace("<nobr>", "", $res);
      $res = str_replace("</nobr>", "", $res);
      $res = str_replace("&nbsp;", " ", $res);
      $res = preg_replace("/<!--.*-->/Uis", "", $res);
      $res = preg_replace("/<colgroup>.*<\/colgroup>/Uis", "", $res);
      $res = preg_replace("/<a[^>]*>[^<]+<\/a>/Uis", "", $res);
      //$res = preg_replace("/<param[^>]*>/Uis", "", $res);
      trace($res);

      $xml = new CDataXML();
      if ( $xml->LoadString($res) )
      {trace($xml->GetArray());
        $node = $xml->SelectNodes("/table");
        $rows = $node->elementsByName("tr");
        $akNames = Array();
        $akCodes = Array();
        $departures = Array();
        $arrivals = Array();
        $terminals = Array();
        $i = 0;
        foreach ( $rows as $row )
        {
          if ( !strstr($row->getAttribute("class"), "onlineDetailTr") )
          {
            $cells = $row->elementsByName("td");
            // Определяем код авиакомпании и номер рейса
            preg_match_all( "/([A-Za-zА-Яа-я0-9]{2})\s*([0-9]+)\s*/",
                $cells[0]->content,
                $flightNumber,
                PREG_PATTERN_ORDER
              );

            $result["FLIGHTS"][$i] = Array(
                "FLIGHT"            => Array(
                    "AK_CODE"       => $flightNumber[1][0],
                    "NUMBER"        => $flightNumber[2][0]
                  ),
                "AK_NAME"           => htmlspecialchars( $cells[5]->content ),
                "DEPARTURE"         => htmlspecialchars( $cells[1]->content ),
                "ARRIVAL"           => htmlspecialchars( $cells[1]->content ),
                "STATUS"            => CAirportBoard::GetStatusInfo( $cells[4]->content ),
                "TIME"              => Array(
                    "PLANNED"       => CAirportBoard::GetDateTimeArray( $cells[2]->content ),
                    "ESTIMATED"     => "",
                    "ACTUAL"        => CAirportBoard::GetDateTimeArray( $cells[3]->content )
                  ),
                "TERMINAL"          => ""
              );
            // Формируем список уникальных терминалов и пунктов вылета и прилета для фильтра
            if ( !in_array( $flightNumber[1][0], $akCodes) )
            {
              $akCodes[] = $flightNumber[1][0];
            }
            if ( !in_array($result["FLIGHTS"][$i]["DEPARTURE"], $departures) )
            {
              $departures[] = $result["FLIGHTS"][$i]["DEPARTURE"];
            }
            if ( !in_array($result["FLIGHTS"][$i]["ARRIVAL"], $arrivals) )
            {
              $arrivals[] = $result["FLIGHTS"][$i]["ARRIVAL"];
            }
            $i++;
          }
        }
        sort($akNames);
        sort($akCodes);
        sort($departures);
        sort($arrivals);
        sort($terminals);
        $result["AK"] = $akNames;
        $result["AK_CODES"] = $akCodes;
        $result["DEPARTURES"] = $departures;
        $result["ARRIVALS"] = $arrivals;
        $result["TERMINALS"] = $terminals;
      }
    }
    return $result;
  }
}

?>