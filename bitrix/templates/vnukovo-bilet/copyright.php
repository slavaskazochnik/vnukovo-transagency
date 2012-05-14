<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
IncludeTemplateLangFile(__FILE__);
$START_YEAR = $START_YEAR > 0 ? $START_YEAR : 2005;
?>
<div id="copyright">
	<div class="company">&copy; <a href="<?= SITE_DIR ?>"><?=GetMessage('COPYRIGHT_COMPANY') ?></a> <?=$START_YEAR ?><?=date("Y") > $START_YEAR ? '&ndash;'.date("Y") : '' ?></div>
	<div class="reserved"><?=GetMessage('COPYRIGHT_RESERVED') ?></div>
	<div class="policy"><a href="<?=SITE_DIR ?>privacy/"><?=GetMessage('COPYRIGHT_POLICY') ?></a></div>
</div>