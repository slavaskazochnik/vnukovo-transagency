<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/xml.php');

/*************************************************************************
			Онлайн табло аэропорта Домодедово
*************************************************************************/

class CAirportBoard 
{
  function GetBoard () // Возвращает табло вылета и прилета
  {
    $result["INBOUND"] = Array(); // Список прилетающих рейсов
    $result["OUTBOUND"] = Array(); // Список вылетающих рейсов
    $result["INBOUND"] = CAirportBoard::GetBoardFromSite( 'tabloname=TabloDeparture_R&d=1&v=19' );
    $result["OUTBOUND"] = CAirportBoard::GetBoardFromSite( 'tabloname=TabloArrive_R&d=1&v=19' );
    
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
  
  function GetBoardFromSite ( $queryParameters ) // Загрузка и разбор табло с сайта
  {
    global $APPLICATION;
    
    $result = Array();
  
    $ob = new CHTTP();
    $ob->http_timeout = 60;
    $ob->Query(
        "GET",
        "www.domodedovo.ru",
        80,
        "/onlinetablo/?".$queryParameters.'&ts='.mktime(),
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
      
      $GrabStart = '<table cellspacing="1" cellpadding="3" width="100%" border="0" id="onlinetablo" >';
      $GrabEnd = '</table>';
      
      $file = str_replace($GrabStart, "!!!", $file);
      $file = str_replace($GrabEnd, "!!!", $file);
      $file = str_replace("';this.bgColor='#E7F5FA';\" onmouseout=\"window.status='';this.bgColor='#ffffff';return;\"", "</td><td>1</td", $file);
      $explode = explode("!!!", $file);
      $file = $explode[1];
      
      $file = str_replace("onmouseover=\"window.status='", "><td>", $file);

      $file = str_replace("<td", "::<td", $file);
      $file = str_replace("<tr", "\n<tr", str_replace("\n", "", str_replace("\r", "", $file)));
      
      $file = str_replace("&quot;", "", $file);
      $file = strip_tags($file);
      
      $explode = explode("\n", $file);
      
      foreach ( $explode as $line_num => $line_text )
      {
        $line_explode = explode("::", $line_text);
        if ( $line_explode[2] == "1" || $line_explode[2] == "2" )
        {
          $temp = explode("(", $line_explode[1]);
          $line_explode[1] = $temp[0];
    
          $temp = explode("(", $line_explode[2]);
          $line_explode[2] = $temp[0];
    
          $temp = explode("(", $line_explode[3]);
          $line_explode[3] = $temp[0];
    
          $temp = explode("(", $line_explode[4]);
          $line_explode[4] = $temp[0];
    
          $type = str_replace("Идет регистрация", "регистр.", trim($line_explode[8]));
          $type = str_replace("Посадка завершена", "на взлёте", $type);
          $type = str_replace("Идет посадка", "посадка", $type);
          
          $type_test = explode(" ", $type);
          if ( $type_test[2] == "на" )
          {
            $type = "регистр.";
            $line_explode[10] = $type_test[3];
          } else {
            $line_explode[10] = "";
          }
          
          $time_rasp = trim($line_explode[5]);
          $time_wait = trim($line_explode[6]);
            
          $month_repl = array("янв" => "01", "фев" => "02", "мар" => "03", "апр" => "04", "мая" => "05", "июн" => "06", "июл" => "07", "авг" => "08", "сен" => "09", "окт" => "10", "ноя" => "11", "дек" => "12");
      
          foreach ( $month_repl as $a => $b )
          {
            $time_rasp = str_replace($a, $b, $time_rasp);
            $time_wait = str_replace($a, $b, $time_wait);
          }
      
          $time_rasp = explode(" ", $time_rasp);
          $time_rasp = strtotime(date("Y")."-".$time_rasp[1]."-".$time_rasp[0]." ".$time_rasp[2].":00");
          $time_wait = explode(" ", $time_wait);
          $time_wait = strtotime(date("Y")."-".$time_wait[1]."-".$time_wait[0]." ".$time_wait[2].":00");
      
          $time = $time_rasp;
      
          $count++;
      
          $array_cells[$time][$count]['reis']				= trim(str_replace(" ", "", $line_explode[3]));
          $array_cells[$time][$count]['aviacomp']			= trim($line_explode[1]);
          $array_cells[$time][$count]['airport_from']		= trim($line_explode[4]);
          $array_cells[$time][$count]['airport_to']		= trim($line_explode[4]);
          $array_cells[$time][$count]['status']			= $type;
          $array_cells[$time][$count]['time_plane']		= $time_rasp;
          $array_cells[$time][$count]['time_wait']		= $time_wait;
      
          $array_cells[$time][$count]['terminal']			= trim($line_explode[10]);
          $array_cells[$time][$count]['others']			= $row_array['cell'][9];
          if ( $line_explode[2] == "1" )
          {
            $array_cells[$time][$count]['direction']		= "in";
          } else {
            $array_cells[$time][$count]['direction']		= "out";
          }
        }
      }
      
      trace($array_cells);
      
      /*
      $xml = new CDataXML();
      if ( $xml->LoadString($res) )
      {
        $node = $xml->SelectNodes("html");
        trace($node->GetArray());
        $rows = $node->elementsByName("row");
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
        }
      }
      */
    }
    return $result;
  }
}

?>
