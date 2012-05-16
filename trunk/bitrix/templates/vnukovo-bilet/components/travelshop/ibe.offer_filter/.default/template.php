<? if (!defined('B_PROLOG_INCLUDED') || true !== B_PROLOG_INCLUDED) {
  die();
}
?>
<? if (file_exists($_SERVER['DOCUMENT_ROOT'] . ($sStyleFile = $this->GetFolder() . '/styles.css'))): ?>
<?= CIBECacheControl::RenderCSSLink( $sStyleFile ) ?>
<? endif; ?>

<span id="ts_ag_offer_filter_container">
<? if ( CIBEAjax::StartArea( "#ts_ag_offer_filter_container" ) ) { ?>
<? $iEnabledFiltersCount = 0; ?>
<? if ($arResult['FILTER']): ?>
<div class="ts_ag_offer_filter">
	<h2><?= GetMessage('TS_IBE_OFFER_FILTER_TITLE') ?></h2>
	<div class="inner">
		<? foreach ($arResult['FILTER'] as $arFilter): ?>
		<? if ($arFilter['~ENABLED']) {
  			$iEnabledFiltersCount++;
		} ?>
		<h3 class="title<?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>">
			<?= GetMessage($arFilter['TITLE']) ?>
			<span class="arr"></span></h3>
		<div class="filter filter-time <?= $arFilter['CLASSNAME'] ?><?= ($arFilter['~ENABLED'] ? ' enabled' : '') ?>">
			<? if ('CHECKBOX' == $arFilter['~TYPE']): ?>
			<div class="select-all"><a href="javascript:void(0)" id="<?= $arFilter['ITEM_PREFIX'] ?>link"><?= GetMessage('TS_IBE_OFFER_FILTER_SELECT_ALL_ITEMS') ?></a></div>
			<? endif; ?>
			<? if ('CHECKBOX' == $arFilter['~TYPE'] && $arFilter['CHANGEABLE']): ?>
			<div class="clear-all"><a href="javascript:void(0)" id="clear-<?= $arFilter['ITEM_PREFIX'] ?>link"><?= GetMessage('TS_IBE_OFFER_FILTER_CLEAR_ALL_ITEMS') ?></a></div>
			<? endif; ?>
			<? switch ($arFilter['NAME']) {
				case 'TIME':
			?>
			<? foreach ($arFilter['MULTIPLE_ITEMS'] as $arSubfilter): ?>
			<div class="slider-box">
				<span class=""><?= GetMessage('TS_IBE_OFFER_FILTER_' . $arSubfilter['DIRECTION']) ?></span>
				<span class="time-range"><span id="<?= $arSubfilter['ITEM_PREFIX'] ?>filter-from">00:00</span>&#151;<span id="<?= $arSubfilter['ITEM_PREFIX'] ?>filter-till">23:59</span>
				<?= (array_key_exists('DATE', $arSubfilter) ? ', ' . GetMessage('TS_IBE_OFFER_FILTER_DAY_OF_WEEK_' . $arSubfilter['DATE']['DAY_OF_WEEK']) : '')
. (array_key_exists('DATE_LAST', $arSubfilter) ? '&#151;' . GetMessage('TS_IBE_OFFER_FILTER_DAY_OF_WEEK_' . $arSubfilter['DATE_LAST']['DAY_OF_WEEK']) : '') ?>
				</span>
				<div class="time-slider" id="<?= $arSubfilter['ITEM_PREFIX'] ?>filter"></div>
			</div>
			<? endforeach; ?>
			<?
				break;

				case 'AIRPORT':
					$itemIndex = 0;
					$maxItemIndex = count($arApt) - 1;
					$prevLoc = false;
					$prevPoint = true;
					foreach ($arFilter['ITEMS'] as $arApt) {
						if ((($bNewPoint = $arApt['~POINT'] && $prevLoc != $arApt['LOC_NAME']) || ($bTransfer = $prevPoint && !$arApt['~POINT'])) && $prevLoc) { ?>
			</ul>
						<? }

     					 if ($bNewPoint || $bTransfer) { ?>
			<div class="point"><?= $bNewPoint ? $arApt['LOC_NAME'] : GetMessage('TS_IBE_OFFER_FILTER_TRANSFERS'); ?></div>
			<ul>
						<? } ?>
				<li<?= ($arApt['~DISABLED'] ? ' class="disabled"' : '') ?>>
					<input type="checkbox" checked="checked"<?= ($arApt['~DISABLED'] ? ' disabled="disabled"' : '') ?> id="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?>" />
					<label for="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?>">
						<? if ($arApt['~POINT']): ?>
						<?= $arApt['APT_NAME'] ?> (<?= $arApt['~APT_CODE'] ?>)
						<? else: ?>
						<?= $arApt['LOCATION'] ?>
						<? endif; ?>
					</label>
				</li>
					<?
						$prevLoc = $arApt['LOC_NAME'];
						$prevPoint = $arApt['~POINT'];
						$itemIndex++;
					}
					?>
			</ul>
			<? break;

				case 'CARRIER':
    				$itemIndex = 0; ?>
			<ul>
				<? foreach ($arFilter['ITEMS'] as $arCarrier) { ?>
				<li<?= ($arCarrier['~DISABLED'] ? ' class="disabled"' : '') ?>>
					<input type="checkbox" checked="checked"<?= ($arCarrier['~DISABLED'] ? ' disabled="disabled"' : '') ?> id="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?>" />
					<label class="logo-small-<?= $arCarrier['IATACODE'] ?>" for="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?>"><?= $arCarrier['TITLE'] ?> (<?= $arCarrier['CRTCODE'] ?>)</label>
				</li>
				<? $itemIndex++;
				} ?>
			</ul>
			<? break;

				case 'SERVICE_CLASS':
					$itemIndex = 0; ?>
			<ul>
				<? foreach ($arFilter['ITEMS'] as $arServiceClass) { ?>
				<li<?= ($arServiceClass['~DISABLED'] ? ' class="disabled"' : '') ?>>
					<input type="checkbox" checked="checked"<?= ($arServiceClass['~DISABLED'] ? ' disabled="disabled"' : '') ?> id="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?>" />
					<label for="<?= $arFilter['ITEM_PREFIX'] ?><?= $itemIndex ?>">
						<?= $arServiceClass['SERVICE_CLASS'] ?>
					</label>
				</li>
				<? $itemIndex++;
				} ?>
			</ul>
			<? break;
			} ?>
		</div>
		<? endforeach; ?>
		<div id="disable-all-filters"><a href="javascript:void(0)" class="disable-all-filters"><?= GetMessage('TS_IBE_OFFER_FILTER_DISABLE_ALL_FILTERS') ?></a></div>
	</div>
</div>
<script type="text/javascript">
// <![CDATA[
var arItemVariants = [];
var arItems = [];
var arSelectLinks = [];
var arFilters = [];
var ignoreSliderChange = false;
var enabledFiltersCount = <?= $iEnabledFiltersCount ?>;

$(document).ready(function() {
  $('div.ts_ag_offer_filter .title').click(function() {
    resizeAllowedNow = false;
    var filterHead = $(this);
    var filterBody = filterHead.next();
    var arFilterIds = [];

<? foreach ($arResult['FILTER'] as $arFilter): ?>
    if (0 == arFilterIds.length && filterBody.hasClass('<?= $arFilter['CLASSNAME'] ?>')) {
<? if (array_key_exists('ITEMS', $arFilter)): ?>
      arFilterIds.push('<?= $arFilter['ITEM_PREFIX'] ?>filter');
<? endif; ?>
<? if (array_key_exists('MULTIPLE_ITEMS', $arFilter)): ?>
<? foreach ($arFilter['MULTIPLE_ITEMS'] as $arSubfilter): ?>
      arFilterIds.push('<?= $arSubfilter['ITEM_PREFIX'] ?>filter');
<? endforeach; ?>
<? endif; ?>
    }
<? endforeach; ?>

    var prevEnabledFiltersCount = enabledFiltersCount;
    if (filterHead.hasClass('enabled')) {
      enabledFiltersCount--;
      filterHead.removeClass('enabled');
      var value = false;
    }
    else {
      enabledFiltersCount++;
      filterHead.addClass('enabled');
      var value = true;
    }

    if ($.browser.webkit) {
      filterBody.toggle();
    }
    else {
      filterBody.toggle('fast');
    }

    variantsCheckId++;
    var arFilterIdsLength = arFilterIds.length;
    for (filterIndex = 0; filterIndex < arFilterIdsLength; filterIndex++) {
      arFilters[arFilterIds[filterIndex]].enabled = value;
    }
    for (filterIndex = 0; filterIndex < arFilterIdsLength; filterIndex++) {
      filterUpdate(arFilterIds[filterIndex])
    }

    if (0 == enabledFiltersCount) {
      $('#disable-all-filters').hide();
    }
    if (0 == prevEnabledFiltersCount) {
      $('#disable-all-filters').show();
    }

    rebuildVariants();
    resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
  });

  $('div.ts_ag_offer_filter div.filter-time div.time-slider').slider({
    range: true,
    min: 0,
    max: 1439,
    values: [0, 1439],
    step: 5,

    start: function(event, ui) {
      resizeAllowedNow = false;
    },

    slide: function(event, ui) {
      sliderUpdate($(event.target).attr('id'), ui.values[0], ui.values[1]);
    },

    change: function(event, ui) {
      var curSlider = $(event.target);
      var filterId = curSlider.attr('id');
      try {
        var curFilter = arFilters[filterId];
        var range = curFilter.range;
        var rangeLength = range.length;

        if (!ignoreSliderChange) {
          variantsCheckId++;
          var value = arSelectLinks[curFilter.selectLink].selected;
          if (0 == ui.values[0] && 0 != curFilter.values[0]) {
            value--;
          }

          if (0 != ui.values[0] && 0 == curFilter.values[0]) {
            value++;
          }

          if (1439 == ui.values[1] && 1439 != curFilter.values[1]) {
            value--;
          }

          if (1439 != ui.values[1] && 1439 == curFilter.values[1]) {
            value++;
          }

          if (0 == arSelectLinks[curFilter.selectLink].selected && 0 != value) {
            $('#' + curFilter.selectLink).show('fast');
          }

          if (0 != arSelectLinks[curFilter.selectLink].selected && 0 == value) {
            $('#' + curFilter.selectLink).hide('fast');
          }

          arSelectLinks[curFilter.selectLink].selected = value;
          curFilter.values = ui.values;
        }

        for (var rangeIndex = 0; rangeIndex < rangeLength; rangeIndex++) {
          var curElement = range[rangeIndex];
          var curItem = arItems[curElement.item];
          var newValue = (curElement.data >= ui.values[0] && curElement.data <= ui.values[1] ? true : false);
          if (newValue != curItem.value) {
            curItem.value = newValue;
            filterItemUpdate(curItem, ignoreSliderChange);
          }
        }

        if (!ignoreSliderChange) {
          rebuildVariants();
        }
      }
      catch (e) {
      }

      if (!ignoreSliderChange) {
        resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
      }
    }
  });

<?

foreach ($arResult['FILTER'] as $filterIndex => $arFilter) {
  echo CIBEOfferFilter::GetFilterArrays($arFilter);

  switch ($arFilter['~TYPE']) {
    case 'CHECKBOX':
      echo "$('div.ts_ag_offer_filter div.{$arFilter['CLASSNAME']} input').click(function(event) {
        resizeAllowedNow = false;

        var item = $(event.target);
        var curItem = arItems[item.attr('id')];
        var curLink = arFilters[curItem.filter].selectLink;

        if (item.attr('checked')) {
          curItem.value = true;
          arSelectLinks[curLink].selected--;
        }
        else {
          curItem.value = false;
          arSelectLinks[curLink].selected++;
        }

        if (curItem.value && 0 == arSelectLinks[curLink].selected) {
          $('#'.concat(curLink)).hide('fast');
        }

        if (!curItem.value && 1 == arSelectLinks[curLink].selected) {
          $('#'.concat(curLink)).show('fast');
        }

        if (!curItem.value && arSelectLinks[curLink].total == arSelectLinks[curLink].selected) {
          $('#clear-'.concat(curLink)).hide('fast');
        }

        if (curItem.value && arSelectLinks[curLink].total == arSelectLinks[curLink].selected + 1) {
          $('#clear-'.concat(curLink)).show('fast');
        }

        variantsCheckId++;
        filterItemUpdate(curItem, true);
        rebuildVariants();
        resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
      });" . CRLF;
      break;
  }
}

?>

  $('div.ts_ag_offer_filter div.filter div.select-all a').click(function() {
    var link = $(this);
    link.hide('fast');
    var curLink = link.attr('id');
    var filters = arSelectLinks[curLink].filters;
    $('#clear-'.concat(curLink)).show('fast');

    try {
      var curFilter = arFilters[filters[0]];
      switch (curFilter.type) {
        case 'CHECKBOX':
          resizeAllowedNow = false;
          var itemsCount = curFilter.items.length;
          curFilter.selected = itemsCount;
          for (var itemIndex = 0; itemIndex < itemsCount; itemIndex++) {
            var itemId = curFilter.items[itemIndex];
            var curItem = arItems[itemId];
            if (!curItem.value && !curItem.disabled) {
              curItem.value = true;
              $('#' + itemId).attr('checked', 'checked');
              arSelectLinks[curFilter.selectLink].selected--;
              variantsCheckId++;
              filterItemUpdate(curItem, false);
            }
          }

          rebuildVariants();
          resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
          break;

        case 'RANGE':
          setSliders(filters);
          break;
      }
    }
    catch (e) {
      setSliders(filters);
    }
  });

  $('div.ts_ag_offer_filter div.filter div.clear-all a').click(function() {
    var link = $(this);
    link.hide('fast');
    var curLink = link.attr('id').substr(6);
    var filters = arSelectLinks[curLink].filters;
    $('#'.concat(curLink)).show('fast');

    try {
      var curFilter = arFilters[filters[0]];
      if ('CHECKBOX' == curFilter.type) {
        resizeAllowedNow = false;
        var itemsCount = curFilter.items.length;
        curFilter.selected = 0;
        for (var itemIndex = 0; itemIndex < itemsCount; itemIndex++) {
          var itemId = curFilter.items[itemIndex];
          var curItem = arItems[itemId];
          if (curItem.value && !curItem.disabled) {
            curItem.value = false;
            $('#'.concat(itemId)).removeAttr('checked');
            arSelectLinks[curFilter.selectLink].selected++;
            variantsCheckId++;
            filterItemUpdate(curItem, false);
          }
        }

        rebuildVariants();
        resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
      }
    }
    catch(e) {
    }
  });

  $('#disable-all-filters').click(function() {
    resizeAllowedNow = false;
    $(this).hide('fast');

    var arProcessedFilters = [];

    for (var filterId in arFilters) {
      var curFilter = arFilters[filterId];

      if (curFilter.enabled) {
        arProcessedFilters.push(filterId);
        curFilter.enabled = false;
      }
    }

    $('div.ts_ag_offer_filter .title.enabled').each(function(){
      var filterHead = $(this);
      filterHead.removeClass('enabled');
      filterHead.next().hide('fast');
      enabledFiltersCount--;
    });

    variantsCheckId++;
    for (var filterIndex in arProcessedFilters) {
      filterUpdate(arProcessedFilters[filterIndex]);
    }

    rebuildVariants();
    resizeTimer = setTimeout('resizeAllowedNow = resizeAllowed', resizeDelay);
  });
});

function filterItemUpdate(curItem, click) {
  if (!click || curItem.value) {
    var variantsCount = curItem.variants.length;
    for (var variant = 0; variant < variantsCount; variant++) {
      var filterIndex = 0;
      var varId = curItem.variants[variant];
      var curVariant = getVariant(varId);
      if (variantsCheckId != curVariant.checkId) {
        var visible = true;
        var filtersCount = curVariant.filters.length;
        while (visible && filterIndex < filtersCount) {
          var curFilter = curVariant.filters[filterIndex];
          var otherItem = arItems[curFilter.item];
          if (arFilters[otherItem.filter].enabled) {
            visible = visible && otherItem.value;
          }
          filterIndex++;
        }
        if (visible && !curVariant.visible) {
          $(varId).show();
        }
        if (!visible && curVariant.visible) {
          $(varId).hide();
        }
        curVariant.visible = visible;
        curVariant.checkId = variantsCheckId;
      }
    }
  }
  else {
    var variantsCount = curItem.variants.length;
    for (var variant = 0; variant < variantsCount; variant++) {
      var varId = curItem.variants[variant];
      var curVariant = getVariant(varId);
      if (variantsCheckId != curVariant.checkId) {
        $(varId).hide();
        curVariant.visible = false;
        curVariant.checkId = variantsCheckId;
      }
    }
  }
}

function filterUpdate(filterId) {
  var filter = arFilters[filterId];
  var itemsCount = filter.items.length;
  for (var itemIndex = 0; itemIndex < itemsCount; itemIndex++) {
    var curItem = arItems[filter.items[itemIndex]];
    if (!curItem.value) {
      filterItemUpdate(curItem, false);
    }
  }
}

function setSliders(filters) {
  ignoreSliderChange = true;
  sliders = [];

  for (filterIndex = 0; filterIndex < filters.length; filterIndex++) {
    var curFilter = arFilters[filters[filterIndex]];
    if ('RANGE' == curFilter.type) {
      sliders.push(filters[filterIndex]);
    }
  }

  var maxSliderIndex = sliders.length - 1;
  for (sliderIndex = 0; sliderIndex < sliders.length; sliderIndex++) {
    var filterId = sliders[sliderIndex];
    var curSlider = $('#' + filterId);
    var curFilter = arFilters[filterId];

    if (maxSliderIndex != sliderIndex) {
      curFilter.values = [0, 1439];
    }
    else {
      ignoreSliderChange = false;
    }

    if (ignoreSliderChange && 0 != curSlider.slider('values', 0)) {
      arSelectLinks[curFilter.selectLink].selected--;
    }

    if (ignoreSliderChange && 1439 != curSlider.slider('values', 1)) {
      arSelectLinks[curFilter.selectLink].selected--;
    }

    curSlider.slider({
      values: [0, 1439]
    });

    sliderUpdate(curSlider.attr('id'), 0, 1439);
  }
}

function sliderUpdate(sliderId, from, till) {
  var fromHours = Math.floor(from / 60);
  var fromMinutes = from % 60;
  var tillHours = Math.floor(till / 60);
  var tillMinutes = till % 60;
  var sliderId = '#' + sliderId;
  $(sliderId + '-from').text((fromHours < 10 ? '0' : '') + fromHours + ':' + (fromMinutes < 10 ? '0' : '') + fromMinutes);
  $(sliderId + '-till').text((tillHours < 10 ? '0' : '') + tillHours + ':' + (tillMinutes < 10 ? '0' : '') + tillMinutes);
}

// ]]>
</script>
<? endif; // if ($arResult['FILTER']) ?>
<? CIBEAjax::EndArea(); ?>
<? } // if ( CIBEAjax::StartArea() ) ?>
</span>