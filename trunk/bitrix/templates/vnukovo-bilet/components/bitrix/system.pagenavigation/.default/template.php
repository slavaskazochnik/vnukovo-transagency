<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$aSizes = array( 10, 20, 50, 100, 200, 500 );
$arResult['NavShowPageSelection'] = true;

?><span id="nav_start_<?= $arResult['NavNum']?>"></span><?

if( !$arResult["NavShowAlways"] )
{
	if ( $arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false) )
		return;
}

//echo "<pre>"; print_r($arResult);echo "</pre>";

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");

?><table class="nav"><?
	?><tr><?
		?><td class="inf"><?
			?><div class="infpgs"><span class="ttl"><?=$arResult["NavTitle"]?></span><?
			?> <?= $arResult["NavFirstRecordShow"] ?>-<?=$arResult["NavLastRecordShow"]?> <?=GetMessage("nav_of")?> <?=$arResult["NavRecordCount"]?><?
			?>
      <? if ( $arResult["NavShowPageSelection"] ): ?>
        | <?= GetMessage("navigation_records"); ?>:
        <select name="" onchange="
          self.location = '<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1'
            + '&amp;SHOWALL_<?= $arResult["NavNum"] ?>=0&amp;SIZEN_<?= $arResult["NavNum"] ?>=' + this[selectedIndex].value;
          ">
          <? foreach( $aSizes as $size ): ?>
            <option value="<?= $size; ?>"<? if ( $arResult['NavPageSize'] == $size ){ echo ' selected="selected"'; } ?>><?= $size; ?></option>
          <? endforeach; ?>
        </select>
      <? endif; ?>
      </div><?
      if ( 0 && $arResult["bShowAll"] )
			{
				if ($arResult["NavShowAll"])
				{
					?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $arResult["NavNum"] ?>=0" class="shwall"><?= GetMessage("nav_paged") ?></a><?
				}
				else
				{
					?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $arResult["NavNum"] ?>=1" class="shwall"><?= GetMessage("nav_all") ?></a><?
				}
			}  
		?></td><?
		?><td class="pgs"><?
		if ( !$arResult["NavShowAll"] )
		{
			?><div class="pgsblck"><?
			?><span class="ttl"><?= GetMessage("nav_pages") ?></span> <?
			if ( $arResult["bDescPageNumbering"] === true )
			{
				if ( $arResult["NavPageNomer"] < $arResult["NavPageCount"])
				{
					if ( $arResult["bSavePage"])
					{
						?><?= GetMessage("nav_prev_symbol") ?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]+1) ?>"><?= GetMessage("nav_prev") ?></a> <?
					}
					else
					{
						if ( $arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) )
						{
							?><?= GetMessage("nav_prev_symbol") ?><a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_prev") ?></a> <?
						}
						else
						{
							?><?= GetMessage("nav_prev_symbol") ?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]+1) ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_prev") ?></a> <?
						}
					}
				}
				if ( $arResult["NavPageNomer"] > 1 )
				{
					?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]-1) ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_next") ?></a><?= GetMessage("nav_next_symbol") ?><?
				}	
			}
			else
			{
				if ( $arResult["NavPageNomer"] > 1 )
				{
					if ( $arResult["bSavePage"] )
					{
						?><?= GetMessage("nav_prev_symbol") ?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]-1) ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_prev") ?></a><?
						if ( $arResult["NavPageNomer"] < $arResult["NavPageCount"] )
						{
							?> <?
						}
					}
					else
					{
						if ( $arResult["NavPageNomer"] > 2)
						{
							?><?= GetMessage("nav_prev_symbol") ?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]-1) ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_prev") ?></a> <?
						}
						else
						{
							?><?= GetMessage("nav_prev_symbol") ?><a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_prev") ?></a> <?
						}
					}
				}
				if ( $arResult["NavPageNomer"] < $arResult["NavPageCount"] )
				{
					?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]+1) ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_next") ?></a><?= GetMessage("nav_next_symbol") ?><?
				}
			}
			?></div><?
			?><div class="itms"><?
			if ( $arResult["bDescPageNumbering"] === true )
			{
				if ( $arResult["NavPageNomer"] < $arResult["NavPageCount"])
				{
					if ( $arResult["bSavePage"])
					{
						?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_begin") ?></a> <?
					}
					else
					{
						if ( $arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) )
						{
							?><?= GetMessage("nav_prev_symbol") ?><a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_prev") ?></a> <?
						}
						else
						{
							?><?= GetMessage("nav_prev_symbol") ?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"]+1) ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_prev") ?></a> <?
						}
					}
				}
				else
				{
					/*
					?><?= GetMessage("nav_begin") ?>&nbsp;|&nbsp;<?= GetMessage("nav_prev") ?>&nbsp;|&nbsp;<?
					*/
				}
				
				while ( $arResult["nStartPage"] >= $arResult["nEndPage"] )
				{
					$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;

					if ($arResult["nStartPage"] == $arResult["NavPageNomer"])
					{
						?><span class="actitm"><?= $NavRecordGroupPrint ?></span> <?
					}
					elseif ( $arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false )
					{
						?><span class="itm"><a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>#nav_start_<?= $arResult['NavNum']?>"><?= $NavRecordGroupPrint ?></a></span> <?
					}
					else
					{
						?><span class="itm"><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>#nav_start_<?= $arResult['NavNum']?>"><?= $NavRecordGroupPrint ?></a></span> <?
					}

					$arResult["nStartPage"]--;
				}
				
				?> <?
			   
				if ( $arResult["NavPageNomer"] > 1 )
				{
					?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_end") ?></a> <?
				}
				else
				{
					/*
					?><?= GetMessage("nav_next") ?>&nbsp;|&nbsp;<?= GetMessage("nav_end") ?>&nbsp;<?
					*/
				}
			}
			else
			{
				if ( $arResult["NavPageNomer"] > 1 )
				{
					if ( $arResult["bSavePage"] )
					{
						?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_begin") ?></a> <?
					}
					else
					{
						?><a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_begin") ?></a> <?
					}
				}
				else
				{
					/*
					?><?= GetMessage("nav_begin") ?>&nbsp;|&nbsp;<?= GetMessage("nav_prev") ?>&nbsp;|&nbsp;<?
					*/
				}
				
				while ( $arResult["nStartPage"] <= $arResult["nEndPage"] )
				{
					if ( $arResult["nStartPage"] == $arResult["NavPageNomer"] )
					{
						?><span class="actitm"><?= $arResult["nStartPage"] ?></span> <?
					}
					elseif ( $arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false )
					{
						?><span class="itm"><a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>#nav_start_<?= $arResult['NavNum']?>"><?= $arResult["nStartPage"] ?></a></span> <?
					}
					else
					{
						?><span class="itm"><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>#nav_start_<?= $arResult['NavNum']?>"><?= $arResult["nStartPage"] ?></a></span> <?
					}
					$arResult["nStartPage"]++;
				}

				if ( $arResult["NavPageNomer"] < $arResult["NavPageCount"] )
				{
					?> <?
					?><a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>#nav_start_<?= $arResult['NavNum']?>"><?= GetMessage("nav_end") ?></a><?
				}
				else
				{
					/*
					<?= GetMessage("nav_next") ?>&nbsp;|&nbsp;<?= GetMessage("nav_end") ?>&nbsp;
					*/
				}
			}
			?></div><?
		}
		else
		{
			?><br/><?
		}
		?></td><?
	?></tr><?
?></table><?

?>