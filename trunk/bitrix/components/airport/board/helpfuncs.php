<?php

function SortFreeStyleArray( &$src, $cmpIndexes )
{
	if( !is_array( $src ) || !is_array( $cmpIndexes ) || count( $cmpIndexes ) <= 0 || count( $src ) <= 0 )
		return true;
	
	$cmpFunctionText = '';
	foreach( $cmpIndexes as $indexes => $order )
    if( is_array( $order ) )
      foreach( $src as $key => $data )
      {
        $indexArray = array();
        $start = 0;
        $begin = strpos( $indexes, '["', $start );
        $end = strpos( $indexes, '"]', $start );
        while( !( false === $begin ) && !( false === $end ) && $end - $begin > 2 )
        {
          $begin += 2;
          $indexArray[] = substr( $indexes, $begin, $end - $begin );
          $start = $end + 2;
          $begin = strpos( $indexes, '["', $start );
          $end = strpos( $indexes, '"]', $start );
        }
        $ptr = &$src[$key];
        $isFullPath = true;
        if( count( $indexArray ) > 0 )
          foreach( $indexArray as $indx )
            if( array_key_exists( $indx, $ptr ) )
              $ptr = &$ptr[$indx];
            else
            {
              $isFullPath = false;
              break;
            }
        if( $isFullPath )
        {
          $result = SortFreeStyleArray( $ptr, $order );
          if( !$result )
            return $result;
        }
      }
    else
    {
      $cmpFunctionText .= 'if( $a'.$indexes.' < $b'.$indexes.' ) return ';
      $cmpFunctionText .= 'DESC' == $order ? '1' : '-1'; 
      $cmpFunctionText .= '; elseif( $a'.$indexes.' > $b'.$indexes.' ) return ';
      $cmpFunctionText .= 'DESC' == $order ? '-1' : '1'; 
      $cmpFunctionText .= '; '; 
    }
	$cmpFunctionText .= 'return 0;';

	$cmpFunction = create_function( '$a,$b', $cmpFunctionText );

	return uasort( $src, $cmpFunction );
}

?>
