<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); //trace($arResult);?>
<? if ( CIBEAjax::StartArea( "#ts_basket_container" ) ) : ?>
<span id="ts_basket_container">
<? if ( isset( $arResult[ "BASKET" ] ) ): ?>
  <? if ( isset( $arResult[ "ID" ] ) ) : ?>
  <? //этот div при вызове через ajax только мешает ?>
  <div id="<?= $arResult[ "ID" ] ?>">
  <? endif; ?>
  
    <div id="ts_basket">
	<? if ( isset($arResult[ "FLIGHT" ]) ): // Если есть рейсы ?>
     <h2 class="caption"><?= GetMessage("TRAVELSHOP_IBE_BASKET_FLIGHTS_TITLE") ?></h2>
	 <? endif; ?>
	 <div class="inner">
      <? if ( isset($arResult[ "FLIGHT" ]) ): // Если есть рейсы ?>
        <div class="flights">
        <? foreach ( $arResult[ "FLIGHT" ] as $key => $flight ): ?>
          <div class="flight">
            <h3><?= $flight["DEPARTURE"]["LOCATION"] ?> &#151; <?= $flight["ARRIVAL"]["LOCATION"] ?></h3>
            <div class="info"><?= $flight["DEPARTURE"]["DATE"] ?> <?= $flight["~AK"] ?> <?= $flight["NUMBER"] ?></div>
            <div class="departure">
				<span class="title"><?= GetMessage("TRAVELSHOP_IBE_BASKET_FLIGHTS_DEPARTURE") ?></span>
				<span class="airport"><?= $flight["DEPARTURE"]["~APT_CODE"] ?>&nbsp;&#151;</span>
				<span class="time"><?= $flight["DEPARTURE"]["TIME"] ?></span>
			</div>
            <div class="arrival">
				<span class="title"><?= GetMessage("TRAVELSHOP_IBE_BASKET_FLIGHTS_ARRIVAL") ?></span>
				<span class="airport"><?= $flight["ARRIVAL"]["~APT_CODE"] ?>&nbsp;&#151;</span>
				<span class="time"><?= $flight["ARRIVAL"]["TIME"] ?></span>
			</div>
          </div>        
        <? endforeach; ?>
          <div class="localtime"><?= GetMessage("TRAVELSHOP_IBE_BASKET_FLIGHTS_LOCALTIME_LABEL") ?></div>

        <? if ($arResult['LIMITS']): ?>
          <ul class="limits">
          <? foreach ($arResult['LIMITS'] as $message): ?>
            <li><?= $message ?></li>
          <? endforeach; ?>
          </ul>
        <? endif; ?>

        </div>
      <? endif; ?>

      <? if ( isset($arResult[ "BASKET" ][ "ALT_TOTAL_PRICE" ]) ): ?>
        <h2><?= GetMessage("TRAVELSHOP_IBE_BASKET_TOTAL_PRICE") ?>&nbsp;&#151; <span class="price"><?= $arResult[ "BASKET" ][ "ALT_TOTAL_PRICE" ] ?></span></h2>
      <? endif; ?>
        
      <? if ( count( $arResult[ "BASKET" ][ "TICKET" ] ) > 0 ): // Если есть билеты ?>
      	<div class="tickets">
      	<? foreach ( $arResult[ "BASKET" ][ "TICKET" ] as $ticket ): ?>
      		<div class="ticket"><?= $ticket["NAME"] ?> <?= GetMessage("TRAVELSHOP_IBE_BASKET_TICKET_AT") ?> <span class="price" title="<?= $ticket["ALT_PRICE_DETAILS"] ?>"><?= $ticket["ALT_PRICE"] ?></span> &#151; <?= $ticket["~COUNT"] ?> <?= GetMessage("TRAVELSHOP_IBE_BASKET_TICKET_ST") ?></div>
      	<? endforeach; ?>
      	</div>
      <? endif; ?>
      
      <? if ($arResult['~FFP_COUNT']): ?>
        <div class="ffp">
          <h3><?= GetMessage('TRAVELSHOP_IBE_BASKET_FFP_TITLE') ?></h3>
<?

reset($arResult['PASSENGER']);
$ffp_left = $arResult['~FFP_COUNT'];

while ($ffp_left && (list(, $passenger) = each($arResult['PASSENGER']))):

?>
          <? if (!empty($passenger['FREQUENT_FLYER']['AK']) && !empty($passenger['FREQUENT_FLYER']['CARD'])): ?>
<? $ffp_left--; ?>
<?= $passenger['FREQUENT_FLYER']['AK'] ?>&nbsp;<?= $passenger['FREQUENT_FLYER']['CARD']
. ($ffp_left ? ', ' : '') ?> 
          <? endif; ?>
        <? endwhile; ?>
        </div>
      <? endif; ?>

      <? if ( count( $arResult[ "BASKET" ][ "SERVICE" ] ) > 0 ): // Если есть услуги ?>
      	<div class="services">
      		<h3><?= GetMessage("TRAVELSHOP_IBE_BASKET_SERVICES_TITLE") ?></h3>
      	<? foreach ( $arResult[ "BASKET" ][ "SERVICE" ] as $service ): ?>
          <div class="service"><?= $service["DESCRIPTION"] ?>&nbsp;&#151; <span class="price"><?= $service["ALT_SUM_PRICE"] ?></span></div>
      	<? endforeach; ?>
      	</div>
      <? endif; ?>
      
      <? if ( count( $arResult[ "BASKET" ][ "PAYMENT" ] ) > 0 ): // Если есть платежи ?>
     		<h2><?= GetMessage("TRAVELSHOP_IBE_BASKET_PAYMENT_TITLE") ?></h2>
      	<div class="payments">
      	<? foreach ( $arResult[ "BASKET" ][ "PAYMENT" ] as $payment ): ?>
          <div class="payment"><?= $payment["DESCRIPTION"] ?>&nbsp;&#151; <span class="price"><?= $payment["ALT_SUM_PRICE"] ?></span></div>
      	<? endforeach; ?>
      	</div>
      <? endif; ?>
	<script type="text/javascript">
//Фиксирование корзины на экране
	$(function() {
		var blockID = $("#ts_basket");
		var offset = blockID.parent().offset();
		blockID.css('width' , blockID.width() + 'px');
		var blockIndentHor = blockID.outerWidth() - blockID.width();
		var blockIndentVer = blockID.outerHeight() - blockID.height();
		
		$(window).scroll(function() {
			if ($(window).scrollTop() > offset.top && blockID.outerHeight() < $(window).height()) {
				blockID.addClass('fixed');
				blockID.parent().css('height', blockID.height() + blockIndentVer +'px');
			}
			else {
				blockID.removeClass('fixed');
				blockID.parent().css('height', 'auto');
			};
		});
		
		$(window).resize(function(){
			offset = blockID.parent().offset();
			blockID.css('width' , (blockID.parent().width() - blockIndentHor) + 'px');
			if(blockID.hasClass('fixed')){
				blockID.parent().css('height', blockID.height() + blockIndentVer +'px');
			}
		});
	});
  </script>
  		</div>
    </div> 
    
  <? if ( isset( $arResult[ "ID" ] ) ): ?>
  <? //этот div при вызове через ajax только мешает ?>
  </div>   
  <? endif; ?>
<? endif; ?>
<?= $arResult[ "SCRIPT" ] ?>
</span>
<? CIBEAjax::EndArea(); ?>
<? endif; // if ( CIBEAjax::StartArea() ?>