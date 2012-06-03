<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/xml.php');

/*************************************************************************
			Онлайн табло аэропорта Пулково (только рейсы авиакомпании "Россия")
*************************************************************************/

class CAirportBoard
{
  function GetBoard () // Возвращает табло вылета и прилета
  {
    $result["INBOUND"] = Array(); // Список прилетающих рейсов
    $result["OUTBOUND"] = Array(); // Список вылетающих рейсов
    $result["INBOUND"] = CAirportBoard::GetBoardFromSite( 'indicator_panel1/' );
    $result["OUTBOUND"] = CAirportBoard::GetBoardFromSite( 'indicator_panel2/' );

    return $result;
  }

  function GetDateTimeArray ( $string ) // Разбор строки на дату и время
  {
    preg_match_all( "/([0-9]{2})\.([0-9]{2})\.([0-9]{2})\s*([0-9]{2}\:[0-9]{2})/",
      $string,
      $matches,
      PREG_PATTERN_ORDER
    );
    return Array(
        "DATE"      => Array(
            "DAY"   => $matches[1][0],
            "MONTH" => $matches[2][0],
          ),
        "TIME"      => $matches[4][0]
      );

  }

  function GetStatusInfo ( $string ) // Возвращает информацию о статусе рейса
  {
    switch ( ToLower(trim($string)) )
    {
      case "прибыл":
        $result["CODE"] = "L";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_L");
      break;

      case "задержан":
      case "задерживается":
        $result["CODE"] = "D";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_D");
      break;

      case "":
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

  function GetBoardFromSite ( $queryParameters ) // Загрузка и разбор табло с сайта
  {
    global $APPLICATION;

    $result = Array();

    $ob = new CHTTP();
    $ob->http_timeout = 60;
    $ob->Query(
        "GET",
        "www.rossiya-airlines.com",
        80,
        "/ru/passenger/about_flight/".$queryParameters.'?ts='.mktime(),
        false,
        "",
        "N"
      );

    $result["ERROR"]["CODE"] = $ob->errno;
    $result["ERROR"]["MESSAGE"] = $ob->errstr;

    if ( intval($result["ERROR"]["CODE"]) == 0 ) // Если данные были получены без ошибки
    {
      $res = $ob->result;

      $res = str_replace('<table border="0" cellpadding="0" cellspacing="0" class="table" id="tblData">', "++++<table>", $res);
      $res = str_replace('$(document).ready(function()', "++++", $res);
      $explode = explode("++++", $res);
      $res = $explode[1];
      $res = substr($res, 0, strlen($res) - 22);
      $res = str_replace("<br>", " ", $res);
      $res = str_replace("<nobr>", "", $res);
      $res = str_replace("</nobr>", "", $res);
      //trace($res);

      $xml = new CDataXML();
      if ( $xml->LoadString($res) )
      {
        $node = $xml->SelectNodes("/table");
        $rows = $node->elementsByName("tr");
        $akNames = Array();
        $akCodes = Array();
        $departures = Array();
        $arrivals = Array();
        $terminals = Array();
        foreach ( $rows as $row )
        {
          if ( strstr($row->getAttribute("class"), "finddata") )
          {
            $cells = $row->elementsByName("td");
            $flightNumber = $cells[0]->elementsByName("div");
            // Определяем код авиакомпании и номер рейса
            preg_match_all( "/([A-Za-zА-Яа-я0-9]{2})([0-9]+)/",
                $flightNumber[0]->content,
                $flightNumber,
                PREG_PATTERN_ORDER
              );
            $terminal = $cells[1]->elementsByName("div");
            $city = $cells[2]->elementsByName("div");
            $plannedTime = $cells[3]->elementsByName("div");
            $actualTime = $cells[4]->elementsByName("div");
            $status = $cells[5]->elementsByName("span");
            $result["FLIGHTS"][] = Array(
                "FLIGHT"            => Array(
                    "AK_CODE"       => $flightNumber[1][0],
                    "NUMBER"        => $flightNumber[2][0]
                  ),
                "AK_NAME"           => "",
                "DEPARTURE"         => htmlspecialchars( $city[0]->content ),
                "ARRIVAL"           => htmlspecialchars( $city[0]->content ),
                "STATUS"            => CAirportBoard::GetStatusInfo( $status[0]->content ),
                "TIME"              => Array(
                    "PLANNED"       => CAirportBoard::GetDateTimeArray( $plannedTime[0]->content ),
                    "ESTIMATED"     => "",
                    "ACTUAL"        => CAirportBoard::GetDateTimeArray( $actualTime[0]->content )
                  ),
                "TERMINAL"          => htmlspecialchars( $terminal[0]->content )
              );
            // Формируем список уникальных терминалов и пунктов вылета и прилета для фильтра
            if ( !in_array( $flightNumber[1][0], $akCodes) )
            {
              $akCodes[] = $flightNumber[1][0];
            }
            if ( !in_array(htmlspecialchars( $city[0]->content ), $departures) )
            {
              $departures[] = htmlspecialchars( $city[0]->content );
            }
            if ( !in_array(htmlspecialchars( $city[0]->content ), $arrivals) )
            {
              $arrivals[] = htmlspecialchars( $city[0]->content );
            }
            if ( !in_array(htmlspecialchars( $terminal[0]->content ), $terminals) )
            {
              $terminals[] = htmlspecialchars( $terminal[0]->content );
            }
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
    //trace($result);
    return $result;
  }
}

?>
