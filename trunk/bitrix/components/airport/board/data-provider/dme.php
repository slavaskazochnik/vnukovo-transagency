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
    $result["INBOUND"] = CAirportBoard::GetBoardFromSite( 'tabloname=TabloDeparture_R&d=1&v=19' );
    $result["OUTBOUND"] = CAirportBoard::GetBoardFromSite( 'tabloname=TabloArrive_R&d=1&v=19' );
    
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
  
  function GetBoardFromSite ( $queryParameters ) // �������� � ������ ����� � �����
  {
    global $APPLICATION;
    
    $result = Array();
  
    $ob = new CHTTP();
    $ob->http_timeout = 60;
    $ob->Query(
        "GET",
        "http://www.domodedovo.ru",
        80,
        "/onlinetablo/?".$queryParameters.'&ts='.mktime(),
        false,
        "",
        "N"
      );
  
    $result["ERROR"]["CODE"] = $ob->errno;
    $result["ERROR"]["MESSAGE"] = $ob->errstr; 
    
    if ( !intval($result["ERROR"]["CODE"]) ) // ���� ������ ���� �������� ��� ������
    {
      //trace($res);
      
      $xml = new CDataXML();
      $xml->LoadString($res);
      
      $node = $xml->SelectNodes("/html/body/");
      $rows = $node->elementsByName("row");
      foreach ( $rows as $row )
      {
        $cells = $row->elementsByName("cell");
        // ���������� ��� ������������ � ����� �����
        preg_match_all( "/([A-Za-z�-��-�0-9]{2})([0-9]+)/",
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
      }
    }
    return $result;
  }
}

?>
