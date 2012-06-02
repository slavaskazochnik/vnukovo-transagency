<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/xml.php');

/*************************************************************************
			������ ����� ��������� ����������
*************************************************************************/

class CAirportBoard 
{
  function GetBoard () // ���������� ����� ������ � �������
  {
    $result["INBOUND"] = Array(); // ������ ����������� ������
    $result["OUTBOUND"] = Array(); // ������ ���������� ������
    $result["INBOUND"] = CAirportBoard::GetBoardFromSite( 'tabloname=TabloDeparture_R&d=1' );
    $result["OUTBOUND"] = CAirportBoard::GetBoardFromSite( 'tabloname=TabloArrive_R&d=1' );
    
    return $result;
  }
  
  function GetDateTimeArray ( $string ) // ������ ������ �� ���� � �����
  {
    $month_search = array("���", "���", "���", "���", "���", "���", "���", "���", "���", "���", "���", "���");
    $month_replace = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
    $string = str_replace($month_search, $month_replace, $string);
    
    preg_match_all( "/([0-9]{1,2})\s([0-9]{1,2})\s([0-9]{2}\:[0-9]{2})/",
      $string,
      $matches,
      PREG_PATTERN_ORDER
    );
    return Array(
        "DATE"      => Array(
            "DAY"   => strlen($matches[1][0]) == 1 ? "0".$matches[1][0] : $matches[1][0],
            "MONTH" => $matches[2][0],
          ),
        "TIME"      => $matches[3][0]
      );
      
  }
  
  function GetStatusInfo ( $string ) // ���������� ���������� � ������� �����
  {
    switch ( ToLower(trim($string)) )
    {
      case "������":
      case "������� ��������":
        $result["CODE"] = "L";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_L");
      break;
      
      case "��������":
        $result["CODE"] = "D";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_D");
      break;
      
      case "":
        $result["CODE"] = "P";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_P");
      break;
      
      case "���������":
        $result["CODE"] = "F";
        $result["NAME"] = GetMessage("AIRPORT_BOARD_STATUS_F");
      break;
      
      case "�������":
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
        "www.domodedovo.ru",
        80,
        "/onlinetablo/?".$queryParameters.'&'.$addQueryParameters.'&ts='.mktime(),
        false,
        "",
        "N"
      );
      
    $res = $APPLICATION->ConvertCharset($ob->result, "UTF-8", SITE_CHARSET);
      
    $res = str_replace('<table cellspacing="1" cellpadding="3" width="100%" border="0" id="onlinetablo" >', "++++", $res);
    $res = str_replace('</form>', "++++", $res);
    $explode = explode("++++", $res);
    $res = $explode[1];
    $res = str_replace('</table>', "++++", $res);
    $explode = explode("++++", $res);
    $res = $explode[0];
      
    return Array(
      "ERROR"      => Array(
        "CODE"     => $ob->errno,
        "MESSAGE"  => $ob->errstr
        ),
      "HTML"    => $res
      );
    unset($explode, $res);
  }
  
  function GetBoardFromSite ( $queryParameters ) // �������� � ������ ����� � �����
  {
    $result = Array();
    
    $result = CAirportBoard::GetOneBoardFromSite( $queryParameters, "v=3" );
    $res = $result["HTML"];
    unset($result["HTML"]);
    $result2 = CAirportBoard::GetOneBoardFromSite( $queryParameters, "v=11" );
    $res .= $result2["HTML"];
    unset($result2["HTML"]);
    //$result3 = CAirportBoard::GetOneBoardFromSite( $queryParameters, "v=19" );
    $res .= $result3["HTML"];
    unset($result3["HTML"]);
    
    if ( intval($result2["ERROR"]["CODE"]) )
    {
      $result["ERROR"] = $result2["ERROR"];
    } elseif ( intval($result3["ERROR"]["CODE"]) )
    {
      $result["ERROR"] = $result3["ERROR"];
    }
    
    if ( !intval($result["ERROR"]["CODE"]) ) // ���� ������ ���� �������� ��� ������
    {
      
      $res = '<table>'.$res.'</table>';
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
          if ( strstr($row->getAttribute("class"), "tr0") )
          {
            $cells = $row->elementsByName("td");
            // ���������� ��� ������������ � ����� �����
            preg_match_all( "/([A-Za-z�-��-�0-9]{2})\s*([0-9]+)\s*/",
                $cells[0]->content,
                $flightNumber,
                PREG_PATTERN_ORDER
              );
            $result["FLIGHTS"][] = Array(
                "FLIGHT"            => Array(
                    "AK_CODE"       => $flightNumber[1][0],
                    "NUMBER"        => $flightNumber[2][0]
                  ),
                "AK_NAME"           => "",
                "DEPARTURE"         => htmlspecialchars( /*preg_replace("/^([^(]+)(\([�-��-�]+\)\s*)\s*$/", "${1} ${2}", $cells[1]->content)*/ $cells[1]->content ),
                "ARRIVAL"           => htmlspecialchars( /*preg_replace("/^([^(]+)(\([�-��-�]+\)\s*)\s*$/", "${1} ${2}", $cells[1]->content)*/ $cells[1]->content ),
                "STATUS"            => CAirportBoard::GetStatusInfo( $cells[5]->content ),
                "TIME"              => Array(
                    "PLANNED"       => CAirportBoard::GetDateTimeArray( $cells[2]->content ),
                    "ESTIMATED"     => "",
                    "ACTUAL"        => CAirportBoard::GetDateTimeArray( $cells[3]->content )
                  ),
                "TERMINAL"          => ""
              );
            // ��������� ������ ���������� ���������� � ������� ������ � ������� ��� �������
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
    return $result;
  }
}

?>