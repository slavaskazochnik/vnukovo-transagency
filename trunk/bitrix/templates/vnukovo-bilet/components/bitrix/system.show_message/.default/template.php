<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if(!empty($arParams["MESSAGE"])): ?>
<div class="common-error">
	<div class="content">
		<?= htmlspecialcharsBack($arParams["MESSAGE"]) ?>
	</div>
</div>
<? endif; ?>