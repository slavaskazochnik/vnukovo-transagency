<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<? if ( defined("SHOW_404") || SHOW_404 == "Y") { return; } ?>
<? IncludeTemplateLangFile(__FILE__); ?>
			<?
			$pageTitle = $APPLICATION->GetTitle() != '' ? '<h1 class="page_title">' . $APPLICATION->GetTitle() . '</h1>' : ''; 		
			$rightColClass = !$APPLICATION->GetPageProperty("HIDE_RIGHT_COLLUMN") && $APPLICATION->GetPageProperty("TravelShopBookingCurrentStage") != 'FORM_ORDER' ? 'sect_text' : '' ;
			if ( 
				!$APPLICATION->GetPageProperty("HIDE_RIGHT_COLLUMN") &&
				(file_exists($_SERVER["DOCUMENT_ROOT"].$APPLICATION->GetCurDir()."sect_system.php") && 
				strlen($APPLICATION->GetFileContent($_SERVER["DOCUMENT_ROOT"].$APPLICATION->GetCurDir()."sect_system.php")) > 75 || 
				file_exists($_SERVER["DOCUMENT_ROOT"].$APPLICATION->GetCurDir()."sect_right.php") && 
				strlen($APPLICATION->GetFileContent($_SERVER["DOCUMENT_ROOT"].$APPLICATION->GetCurDir()."sect_right.php")) > 75)
			) {
				$bShowRightCol = true;
				$APPLICATION->SetPageProperty("COLLS_PREFACE", '<div class="sect_left">' . $pageTitle );				
			} else {
				$bShowRightCol = false;
				$APPLICATION->SetPageProperty("COLLS_PREFACE", $pageTitle );
			}		
			$APPLICATION->SetPageProperty("CONTENT_PREFACE", ( $rightColClass ? '<div class="' . $rightColClass . '">' : '' ) );
			?>
			<? if ( $bShowRightCol ): ?>
				<?= $rightColClass ? '</div>' : '' ; ?>
				</div>
				<div class="sect_right">
					<? $APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "sect", 
							"AREA_FILE_SUFFIX" => "system", 
							"EDIT_MODE" => "html", 
							"EDIT_TEMPLATE" => "standart.php",
							"AREA_FILE_RECURSIVE" => "N" 
						)
					); ?>
					<? $APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "sect", 
							"AREA_FILE_SUFFIX" => "right", 
							"EDIT_MODE" => "html", 
							"EDIT_TEMPLATE" => "standart.php",
							"AREA_FILE_RECURSIVE" => "N" 
						)
					); ?>
				</div>
			<? else: ?>
				<?= $rightColClass ? '</div>' : '' ; ?>
			<? endif; ?>
			</div>
			<div id="bottom" class="clearfix">
			<? $APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "sect", 
						"AREA_FILE_SUFFIX" => "bottom", 
						"EDIT_MODE" => "html", 
						"EDIT_TEMPLATE" => "standart.php",
						"AREA_FILE_RECURSIVE" => "Y" 
					)
				); ?>
			</div>
			<div id="footer" class="clearfix">
			<? $APPLICATION->IncludeFile("copyright.php"); ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
// <![CDATA[
tooltip();
// ]]>
</script>
</body>
</html>