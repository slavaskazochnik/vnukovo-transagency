<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/xml.php');

/*************************************************************************
			������ ����� ��������� �������
*************************************************************************/

class CAirportBoard
{
  function GetBoard () // ���������� ����� ������ � �������
  {
    $result["INBOUND"] = Array(); // ������ ����������� ������
    $result["OUTBOUND"] = Array(); // ������ ���������� ������
    $result["INBOUND"] = CAirportBoard::GetBoardFromSite( 'time-table.direction=0&time-table.flight-number=&time-table.dep-airport-id=&time-table.airline-id=&status=&lang=rus' );
    $result["OUTBOUND"] = CAirportBoard::GetBoardFromSite( 'time-table.direction=1&time-table.flight-number=&time-table.dep-airport-id=&time-table.airline-id=&status=&lang=rus' );

    return $result;
  }

  function GetDateTimeArray ( $string ) // ������ ������ �� ���� � �����
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

  function GetStatusInfo ( $string ) // ���������� ���������� � ������� �����
  {
    switch ( ToLower(trim($string)) )
    {
      case "��������":
        $result["CODE"] = "L";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_L");
      break;

      case "�� �������":
        $result["CODE"] = "D";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_D");
      break;

      case "-":
        $result["CODE"] = "P";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_P");
      break;

      case "�������":
        $result["CODE"] = "F";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_F");
      break;

      case "�������":
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

  function GetBoardFromSite ( $queryParameters ) // �������� � ������ ����� � �����
  {
    global $APPLICATION;

    $result = Array();

    $ob = new CHTTP();
    $ob->http_timeout = 60;
    $ob->Query(
        "GET",
        "old.vnukovo.ru",
        80,
        "/rus/for-passengers/board1/data.wbp?".$queryParameters.'&ts='.mktime(),
        false,
        "",
        "N"
      );

    $result["ERROR"]["CODE"] = $ob->errno;
    $result["ERROR"]["MESSAGE"] = $ob->errstr;

    if ( !intval($result["ERROR"]["CODE"]) ) // ���� ������ ���� �������� ��� ������
    {
      $res = $APPLICATION->ConvertCharset($ob->result, "UTF-8", SITE_CHARSET);
      //trace($res);

      $xml = new CDataXML();
      if ( $xml->LoadString($res) && $node = $xml->SelectNodes("/responce/rows") )
      {
        $rows = $node->elementsByName("row");
        $akNames = Array();
        $akCodes = Array();
        $departures = Array();
        $arrivals = Array();
        $terminals = Array();
        foreach ( $rows as $row )
        {
          $cells = $row->elementsByName("cell");
          // ���������� ��� ������������ � ����� �����
          preg_match_all( "/([A-Za-z�-��-�0-9]{2})[\s]*([0-9]+)/",
              $cells[0]->content,
              $flightNumber,
              PREG_PATTERN_ORDER
            );
          $result["FLIGHTS"][] = Array(
              "FLIGHT"            => Array(
                  "AK_CODE"       => $flightNumber[1][0],
                  "NUMBER"        => $flightNumber[2][0]
                ),
              "AK_NAME"           => htmlspecialcharsEx( $cells[1]->content ),
              "DEPARTURE"         => htmlspecialcharsEx( $cells[2]->content ),
              "ARRIVAL"           => htmlspecialcharsEx( $cells[3]->content ),
              "STATUS"            => CAirportBoard::GetStatusInfo( $cells[4]->content ),
              "TIME"              => Array(
                  "PLANNED"       => CAirportBoard::GetDateTimeArray( $cells[5]->content ),
                  "ESTIMATED"     => CAirportBoard::GetDateTimeArray( $cells[6]->content ),
                  "ACTUAL"        => CAirportBoard::GetDateTimeArray( $cells[7]->content )
                ),
              "TERMINAL"          => htmlspecialcharsEx( $cells[8]->content )
            );
          // ��������� ������ ���������� ������������, ���������� � ������� ������ � ������� ��� �������
          if ( !in_array(htmlspecialcharsEx( $cells[1]->content ), $akNames) )
          {
            $akNames[] = htmlspecialcharsEx( $cells[1]->content );
          }
          if ( !in_array( $flightNumber[1][0], $akCodes) )
          {
            $akCodes[] = $flightNumber[1][0];
          }
          if ( !in_array(htmlspecialcharsEx( $cells[2]->content ), $departures) )
          {
            $departures[] = htmlspecialcharsEx( $cells[2]->content );
          }
          if ( !in_array(htmlspecialcharsEx( $cells[3]->content ), $arrivals) )
          {
            $arrivals[] = htmlspecialcharsEx( $cells[3]->content );
          }
          if ( !in_array(htmlspecialcharsEx( $cells[8]->content ), $terminals) )
          {
            $terminals[] = htmlspecialcharsEx( $cells[8]->content );
          }
        }
        sort($akNames);
        sort($akCodes);
        sort($departures);
        sort($arrivals);
        sort($terminals);
        $result["AK_NAMES"] = $akNames;
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
