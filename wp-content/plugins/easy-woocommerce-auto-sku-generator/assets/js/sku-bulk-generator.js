/**
 * Bulk SKU Generator — modal, progress pie, AJAX loops.
 *
 * Localised object: skuBulkData (see inc/skuautoffxf_all_settings.php)
 *   skuBulkData.numberDop             — initial skuautoffxf_number_dop value
 *   skuBulkData.totalProducts         — total published products count
 *   skuBulkData.formatOption          — skuautoffxf_format_an value
 *   skuBulkData.nonce                 — wp_create_nonce( 'load_more_posts' )
 *   skuBulkData.nonceCat              — wp_create_nonce( 'load_more_posts_category' )
 *   skuBulkData.batchHintNonce        — wp_create_nonce( 'skuautoffxf_batch_hint' )
 *   skuBulkData.ajaxUrl               — admin_url( 'admin-ajax.php' )
 *   skuBulkData.textProductsLeft      — i18n string
 *   skuBulkData.textDone              — i18n string
 *   skuBulkData.textProcessing        — i18n string
 *   skuBulkData.textComplete          — i18n string
 *   skuBulkData.textCategoryDone      — i18n string
 *   skuBulkData.textInCategory        — i18n string
 *   skuBulkData.textFound             — i18n string
 *   skuBulkData.textProducts          — i18n string
 *   skuBulkData.textInStore           — i18n string
 *   skuBulkData.textProductsTotal     — i18n string
 *   skuBulkData.textNoCategory        — i18n string
 *
 * @package EasyAutoSkuGenerator
 */

/* global skuBulkData, ajaxurl */

( function ( $ ) {
	'use strict';

	/**
	 * Increment a number-string preserving leading zeros.
	 *
	 * @param {string} numberStr
	 * @return {string}
	 */
	function incrementWithLeadingZeros( numberStr ) {
		numberStr = String( numberStr );

		var leadingZeros = '';
		var leadingMatch = numberStr.match( /^(0+)/ );
		if ( leadingMatch ) {
			leadingZeros = leadingMatch[ 1 ];
			numberStr    = numberStr.slice( leadingZeros.length );
		}

		var parsed = parseInt( numberStr, 10 );
		if ( isNaN( parsed ) ) {
			return leadingZeros + numberStr;
		}

		parsed++;

		var formatted    = String( parsed );
		var formatOption = skuBulkData.formatOption || '';

		if ( 'ffxf_format_an_up' === formatOption ) {
			// Keep total length, but allow natural overflow (008 → 009 → 010)
			while ( formatted.length < numberStr.length ) {
				formatted = '0' + formatted;
			}
			formatted = leadingZeros + formatted;
			// Drop one leading zero when the number overflows its digit count
			if (
				formatted.endsWith( '010' ) ||
				formatted.endsWith( '100' ) ||
				formatted.endsWith( '1000' )
			) {
				formatted = formatted.slice( 1 );
			}
		} else {
			// Classic mode: always prepend leading zeros then re-prepend stored prefix
			formatted = formatted.padStart( numberStr.length, '0' );
			formatted = leadingZeros + formatted;
		}

		return formatted;
	}

	// -------------------------------------------------------------------------
	// Progress pie helpers
	// -------------------------------------------------------------------------

	/**
	 * Update a progress-pie-chart element to a given percent (0–100).
	 *
	 * Uses conic-gradient for smooth, artefact-free animation.
	 * The .gt-50 class toggle is kept for backward compatibility only.
	 *
	 * @param {jQuery} $pie    jQuery wrapper of .progress-pie-chart
	 * @param {number} percent Value from 0 to 100
	 */
	function updateProgressPie( $pie, percent ) {
		percent = Math.min( 100, Math.max( 0, Math.round( percent ) ) );

		var deg = ( 360 * percent ) / 100;

		// conic-gradient: fill colour sweeps from 0 to deg, rest is track colour
		$pie.css( 'background', 'conic-gradient(#81CE97 ' + deg + 'deg, #E5E5E5 ' + deg + 'deg)' );

		// Keep class toggle so any legacy CSS rules still apply
		if ( percent > 50 ) {
			$pie.addClass( 'gt-50' );
		}

		$pie.find( '.ppc-percents span' ).html( percent + '%' );
	}

	// -------------------------------------------------------------------------
	// All-products bulk generator state
	// -------------------------------------------------------------------------

	var allState = {
		paged:     1,
		numberDop: skuBulkData.numberDop,
		processed: 0,
		total:     parseInt( skuBulkData.totalProducts, 10 ) || 1,
		batchSize: 1,
	};

	/**
	 * Reset all-products state (called when generation starts).
	 */
	function resetAllState() {
		allState.paged     = 1;
		allState.numberDop = skuBulkData.numberDop;
		allState.processed = 0;
		allState.total     = parseInt( skuBulkData.totalProducts, 10 ) || 1;
		allState.batchSize = Math.min( 50, Math.max( 1, parseInt( $( '#ffxf_batch_size_all' ).val(), 10 ) || 1 ) );
	}

	/**
	 * Process one batch for all-products bulk.
	 */
	function processNextProduct() {
		// Increment numberDop after first batch
		if ( allState.paged > 1 && '' !== allState.numberDop ) {
			allState.numberDop = incrementWithLeadingZeros( allState.numberDop );
		}

		var data = {
			action:     'load_posts_by_ajax',
			paged:      allState.paged,
			number_dop: allState.numberDop,
			security:   skuBulkData.nonce,
			'class':    'load_more_posts',
			checked:    $( '#check_generate' ).prop( 'checked' ) ? 1 : 0,
			batch_size: allState.batchSize,
		};

		$.get( skuBulkData.ajaxUrl || ajaxurl, data, function ( response ) {
			var $modal = $( '.modal_generate' );

			if ( ! response || ! response.success ) {
				// Network/server error — inform user and unlock close button
				updateProgressPie( $modal.find( '.progress-pie-chart' ), 100 );
				$modal.find( '.ffxf-modal-close' ).prop( 'disabled', false );
				$modal.find( '.ffxf-progress-wrap .ps' ).text(
					( skuBulkData.textError || 'Error. Please try again.' )
				);
				return;
			}

			var respData  = response.data;
			var hasMore   = respData.has_more;
			var html      = respData.html || '';
			var batchDone = respData.processed || 0;

			if ( html ) {
				$modal.find( '.my-posts' ).append( html );
			}

			allState.processed += batchDone;
			allState.paged++;

			var percent   = ( allState.processed / allState.total ) * 100;
			updateProgressPie( $modal.find( '.progress-pie-chart' ), percent );

			var remaining = Math.max( 0, allState.total - allState.processed );
			$modal.find( '.ffxf-progress-wrap .ps' ).text( skuBulkData.textProductsLeft + ' ' + remaining );

			if ( hasMore ) {
				processNextProduct();
			} else {
				// Finished — guarantee 100%, re-enable close button
				updateProgressPie( $modal.find( '.progress-pie-chart' ), 100 );
				$modal.find( '.ffxf-modal-close' ).prop( 'disabled', false );
				$modal.find( '.ffxf-progress-wrap .ps' ).text( skuBulkData.textDone );
				$modal.find( '#text_generate_modal' )
					.fadeIn( 200 )
					.text( skuBulkData.textComplete );
			}
		} ).fail( function () {
			var $modal = $( '.modal_generate' );
			$modal.find( '.ffxf-modal-close' ).prop( 'disabled', false );
			$modal.find( '.ffxf-progress-wrap .ps' ).text(
				skuBulkData.textError || 'Network error. Please try again.'
			);
		} );
	}

	// -------------------------------------------------------------------------
	// Category bulk generator state
	// -------------------------------------------------------------------------

	var catState = {
		paged:     1,
		numberDop: skuBulkData.numberDop,
		processed: 0,
		total:     1,
		batchSize: 1,
	};

	/**
	 * Reset category state (called when generation starts).
	 *
	 * @param {number} categoryTotal Number of products in selected category.
	 */
	function resetCatState( categoryTotal ) {
		catState.paged     = 1;
		catState.numberDop = skuBulkData.numberDop;
		catState.processed = 0;
		catState.total     = Math.max( 1, categoryTotal );
		catState.batchSize = Math.min( 50, Math.max( 1, parseInt( $( '#ffxf_batch_size_cat' ).val(), 10 ) || 1 ) );
	}

	/**
	 * Process one batch for category bulk.
	 */
	function processNextProductCategory() {
		if ( '' !== catState.numberDop ) {
			catState.numberDop = incrementWithLeadingZeros( catState.numberDop );
		}

		var data = {
			action:      'load_posts_by_ajax_category',
			paged:       catState.paged,
			number_dop:  catState.numberDop,
			security:    skuBulkData.nonceCat,
			'class':     'load_more_posts_category',
			checked:     $( '.modal_generate_category #check_generate_category' ).prop( 'checked' ) ? 1 : 0,
			select_cat:  $( '.modal_generate_category #product_cat' ).val(),
			batch_size:  catState.batchSize,
		};

		$.get( skuBulkData.ajaxUrl || ajaxurl, data, function ( response ) {
			var $modal = $( '.modal_generate_category' );

			if ( ! response || ! response.success ) {
				updateProgressPie( $modal.find( '.progress-pie-chart' ), 100 );
				$modal.find( '.ffxf-modal-close' ).prop( 'disabled', false );
				$modal.find( '.ffxf-progress-wrap .ps' ).text(
					skuBulkData.textError || 'Error. Please try again.'
				);
				return;
			}

			var respData  = response.data;
			var hasMore   = respData.has_more;
			var html      = respData.html || '';
			var batchDone = respData.processed || 0;

			if ( html ) {
				$modal.find( '.my-posts' ).append( html );
			}

			catState.processed += batchDone;
			catState.paged++;

			var percent   = ( catState.processed / catState.total ) * 100;
			updateProgressPie( $modal.find( '.progress-pie-chart' ), percent );

			var remaining = Math.max( 0, catState.total - catState.processed );
			$modal.find( '.ffxf-progress-wrap .ps' ).text( skuBulkData.textProductsLeft + ' ' + remaining );

			if ( hasMore ) {
				processNextProductCategory();
			} else {
				// Finished — guarantee 100%, re-enable close button
				updateProgressPie( $modal.find( '.progress-pie-chart' ), 100 );
				$modal.find( '.ffxf-modal-close' ).prop( 'disabled', false );
				$modal.find( '.ffxf-progress-wrap .ps' ).text( skuBulkData.textCategoryDone );
				$modal.find( '#text_generate_modal' )
					.fadeIn( 200 )
					.text( skuBulkData.textComplete );
			}
		} ).fail( function () {
			var $modal = $( '.modal_generate_category' );
			$modal.find( '.ffxf-modal-close' ).prop( 'disabled', false );
			$modal.find( '.ffxf-progress-wrap .ps' ).text(
				skuBulkData.textError || 'Network error. Please try again.'
			);
		} );
	}

	// -------------------------------------------------------------------------
	// Modal open / close
	// -------------------------------------------------------------------------

	$( function () {
		var $overlay  = $( '.ffxf-modal-overlay' );
		var $modalAll = $( '.modal_generate' );
		var $modalCat = $( '.modal_generate_category' );

		// Remove state-leave class after animation ends
		$modalAll.on( 'webkitAnimationEnd oanimationend msAnimationEnd animationend', function () {
			if ( $modalAll.hasClass( 'state-leave' ) ) {
				$modalAll.removeClass( 'state-leave' );
			}
		} );

		$modalCat.on( 'webkitAnimationEnd oanimationend msAnimationEnd animationend', function () {
			if ( $modalCat.hasClass( 'state-leave' ) ) {
				$modalCat.removeClass( 'state-leave' );
			}
		} );

		// Open all-products modal
		$( '.open' ).on( 'click', function () {
			$overlay.addClass( 'state-show' );
			$modalAll.removeClass( 'state-leave' ).addClass( 'state-appear' );
		} );

		// Close all-products modal
		$( '.close' ).on( 'click', function () {
			$overlay.removeClass( 'state-show' );
			$modalAll.removeClass( 'state-appear' ).addClass( 'state-leave' );
		} );

		// Open category modal
		$( '.open_category' ).on( 'click', function () {
			$overlay.addClass( 'state-show' );
			$modalCat.removeClass( 'state-leave' ).addClass( 'state-appear' );
		} );

		// Close category modal
		$( '.close_category' ).on( 'click', function () {
			$overlay.removeClass( 'state-show' );
			$modalCat.removeClass( 'state-appear' ).addClass( 'state-leave' );
		} );

		// -------------------------------------------------------------------------
		// Start all-products generation
		// -------------------------------------------------------------------------
		$( 'body' ).on( 'click', '.generate_button', function () {
			resetAllState();

			var $modal = $( '.modal_generate' );

			// Lock the close button so user can't interrupt mid-run
			$modal.find( '.ffxf-modal-close' ).prop( 'disabled', true );

			// Hide form controls and action button
			$modal.find( '.ffxf-modal-inner' ).fadeOut( 150 );
			$modal.find( '.ffxf-modal-action' ).fadeOut( 150 );

			// Update status text, show progress pie
			$modal.find( '#text_generate_modal' ).text( skuBulkData.textProcessing );
			$modal.find( '.ffxf-progress-wrap' ).fadeIn( 300 );

			processNextProduct();
		} );

		// -------------------------------------------------------------------------
		// Start category generation
		// -------------------------------------------------------------------------
		$( 'body' ).on( 'click', '.generate_button_category', function () {
			var categoryTotal = parseInt( $( '#product_cat option:selected' ).text().replace( /\D+/g, '' ), 10 ) || 1;
			resetCatState( categoryTotal );

			var $modal = $( '.modal_generate_category' );

			// Lock the close button so user can't interrupt mid-run
			$modal.find( '.ffxf-modal-close' ).prop( 'disabled', true );

			// Hide form controls and action button
			$modal.find( '.ffxf-modal-inner' ).fadeOut( 150 );
			$modal.find( '.ffxf-modal-action' ).fadeOut( 150 );

			// Update status text, show progress pie
			$modal.find( '#text_generate_modal' ).text( skuBulkData.textProcessing );
			$modal.find( '.ffxf-progress-wrap' ).fadeIn( 300 );

			processNextProductCategory();
		} );

		// -------------------------------------------------------------------------
		// Batch hint — "Recommend" button
		// -------------------------------------------------------------------------
		$( 'body' ).on( 'click', '.ffxf-batch-hint-btn', function () {
			var $btn    = $( this );
			var targetId = $btn.data( 'target' );
			var nonce   = $btn.data( 'nonce' );
			var $result = $btn.siblings( '.ffxf-batch-hint-result' );

			$btn.prop( 'disabled', true );
			$result.text( '…' );

			$.get(
				skuBulkData.ajaxUrl || ajaxurl,
				{
					action:   'skuautoffxf_get_batch_hint',
					security: nonce,
				},
				function ( response ) {
					$btn.prop( 'disabled', false );

					if ( response && response.success && response.data ) {
						var batch  = parseInt( response.data.batch, 10 );
						var memory = response.data.memory;
						// Clamp to input max (10)
						batch = Math.min( 10, Math.max( 1, batch ) );
						$( '#' + targetId ).val( batch );
						// Human-readable: "recommended N products (RAM: 512M)"
						$result.html(
							'&rarr; ' + batch + ' ' + ( skuBulkData.textProducts || 'products' ) +
							' <span style="color:#8c8f94">(' + memory + ')</span>'
						);
					} else {
						$result.text( '' );
					}
				}
			);
		} );

		// -------------------------------------------------------------------------
		// Category select — count products in selected category
		// -------------------------------------------------------------------------
		var totalStoreProducts = parseInt( skuBulkData.totalProducts, 10 ) || 0;

		function refreshCategoryInfo() {
			var $selected = $( '#product_cat option:selected' );

			if ( '' === $selected.val() ) {
				$( '.generate_button_category' ).attr( 'disabled', 'disabled' ).addClass( 'disabled' );
				$( '.modal_generate_category .ffxf-cat-status' ).html(
					skuBulkData.textInStore + ' ' + totalStoreProducts + ' ' + skuBulkData.textProductsTotal + ' ' + skuBulkData.textNoCategory
				);
			} else {
				$( '.generate_button_category' ).removeAttr( 'disabled' ).removeClass( 'disabled' );
				var selectText = $selected.text();
				var catCount   = parseInt( selectText.replace( /\D+/g, '' ), 10 ) || 0;
				var catName    = selectText.replace( /[^A-Za-z\u0400-\u04FF\s]/g, '' ).trim();
				$( '.modal_generate_category .ffxf-cat-status' ).html(
					skuBulkData.textInCategory + ' <b>' + catName + '</b> ' +
					skuBulkData.textFound + ' <b><span id="num_category">' + catCount + '</span></b> ' +
					skuBulkData.textProducts
				);
			}
		}

		refreshCategoryInfo();
		$( '#product_cat' ).on( 'change', refreshCategoryInfo );

		// -------------------------------------------------------------------------
		// Variation settings toggle
		// -------------------------------------------------------------------------
		function toggleVariationSettings() {
			var $varSep = $( '#skuautoffxf_variation_separator' ).closest( 'tr' );
			var $varTog = $( '#skuautoffxf_auto_variant' ).closest( 'tr' );

			if ( $( '#skuautoffxf_variation_settings' ).is( ':checked' ) ) {
				$varSep.show();
				$varTog.show();
			} else {
				$varSep.hide();
				$varTog.hide();
			}
		}

		$( '#skuautoffxf_variation_settings' ).on( 'change', toggleVariationSettings );
		toggleVariationSettings();

		// -------------------------------------------------------------------------
		// Additional number format toggle
		// -------------------------------------------------------------------------
		function toggleFormatAnRow() {
			var val = $( '#skuautoffxf_number_dop' ).val() || '';
			if ( /^0/.test( val ) ) {
				$( '#skuautoffxf_format_an' ).closest( 'tr' ).show();
			} else {
				$( '#skuautoffxf_format_an' ).closest( 'tr' ).hide();
			}
		}

		$( '#skuautoffxf_number_dop' ).on( 'input', toggleFormatAnRow );
		toggleFormatAnRow();

		// -------------------------------------------------------------------------
		// SKU format radio — slug notice
		// -------------------------------------------------------------------------
		var $slugRadio = $( 'input[name="skuautoffxf_letters_and_numbers"]' );

		function toggleSlugNotice() {
			var isSlug = $( 'input[name="skuautoffxf_letters_and_numbers"]:checked' ).val() === 'ffxf_slug';

			if ( isSlug ) {
				if ( ! $( '#sku_description' ).length ) {
					var $notice = $( '<div class="FFxF_icon_setting"><div id="sku_description" class="updated inline sku_description" style="max-width:340px"><p><strong></strong></p></div></div>' );
					$notice.find( 'p strong' ).text( skuBulkData.msgSlug );
					$slugRadio.last().closest( 'label' ).parent().after( $notice );
				}
			} else {
				$( '#sku_description' ).closest( '.FFxF_icon_setting' ).remove();
			}
		}

		toggleSlugNotice();
		$slugRadio.on( 'change', toggleSlugNotice );

		// -------------------------------------------------------------------------
		// Take previous product — notice + disable controls
		// -------------------------------------------------------------------------
		function togglePreviousNotice() {
			var $checkbox = $( '#skuautoffxf_previous' );
			var isChecked = $checkbox.is( ':checked' );

			if ( isChecked ) {
				if ( ! $( '#sku_description_preiv' ).length ) {
					var $notice = $( '<div class="FFxF_icon_setting_preiv"><div id="sku_description_preiv" class="updated inline sku_description" style="max-width:340px;z-index:999;top:0;left:0"><p><strong></strong></p></div></div>' );
					$notice.find( 'p strong' ).html( skuBulkData.msgPrevious );
					$checkbox.closest( 'fieldset' ).append( $notice );
				}

				// Inject disabling styles via a <style> tag
				if ( ! $( '#sku-generator-prev-style' ).length ) {
					$( '<style id="sku-generator-prev-style">' +
						'.mass_generate{background:rgba(134,134,134,.3);cursor:no-drop;color:#9a9a9a}' +
						'#mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(1),' +
						'#mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(2),' +
						'#mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(3),' +
						'#mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(4)' +
						'{background:rgba(134,134,134,.3);cursor:no-drop;color:#9a9a9a}' +
					'</style>' ).appendTo( 'head' );
				}

				$( '#generate_mass_sku, #generate_mass_sku_category' ).attr( 'disabled', 'disabled' );
			} else {
				$( '#sku_description_preiv' ).closest( '.FFxF_icon_setting_preiv' ).remove();
				$( '#sku-generator-prev-style' ).remove();
				$( '#generate_mass_sku, #generate_mass_sku_category' ).removeAttr( 'disabled' );
			}
		}

		$( 'label[for="skuautoffxf_previous"]' ).on( 'change', togglePreviousNotice );
		togglePreviousNotice();
	} );

} )( jQuery );
