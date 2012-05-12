<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<span id="ts_ag_reservation_stages_container">
<? if ( CIBEAjax::StartArea( "#ts_ag_reservation_stages_container" ) ) : ?>
<? if(!empty($arResult['STAGES'])): ?>
<ul id="ts_ag_reservation_stages" class="clearfix">
	<? foreach($arResult['STAGES'] as $stage): ?>
	<li class="<?=$stage['CLASS'] ?>">
		<div class="block">
			<? if($stage['URL'] === false): ?>
			<?=$stage['TITLE'] ?>
			<? else: ?>
      <a href="<?=htmlspecialchars($stage['URL']) ?>" <?
        if ( $arParams[ "~IBE_AJAX_MODE" ] == "Y" ) {
          ?> onclick="ibe_ajax.post( null, this.href, '#ts_ag_offer_filter_container,#ts_ag_carrier_matrix_container' );return false;"<?
        }
      ?>><?=$stage['TITLE'] ?></a>
			<? endif; ?>
		</div>
	</li>
	<? endforeach; ?>
</ul>
<? endif; // if(!empty($arResult['STAGES'])) ?>
<? CIBEAjax::EndArea() ?>
<? endif; // if ( CIBEAjax::StartArea() ) ?>
</span>