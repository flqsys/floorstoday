/**
 * Product edit page — slug-based SKU mode.
 *
 * Localised object: ffxf_slug  (see easy-woocommerce-auto-sku-generator.php)
 *
 * Warning icon visibility logic: see ffxf_auto_sku.js for full explanation.
 * Same localStorage key (ffxfRating) shared across both scripts so the state
 * is consistent regardless of which mode the user is in.
 *
 * @package EasyAutoSkuGenerator
 */

/* global ffxf_slug, jQuery */

( function () {
	'use strict';

	var skuInput = document.getElementById( '_sku' );
	if ( ! skuInput ) {
		return;
	}

	// -------------------------------------------------------------------------
	// Rating / visibility state  (shared key with ffxf_auto_sku.js)
	// -------------------------------------------------------------------------

	/**
	 * Load persisted rating state from localStorage.
	 *
	 * @return {{ clicked: boolean, visits: number, nextShow: number }}
	 */
	function loadRatingState() {
		try {
			if ( localStorage.ffxfRating ) {
				return JSON.parse( localStorage.ffxfRating );
			}
		} catch ( e ) { /* ignore parse errors */ }
		return { clicked: false, visits: 0, nextShow: 1 };
	}

	/**
	 * Persist rating state.
	 *
	 * @param {{ clicked: boolean, visits: number, nextShow: number }} state
	 */
	function saveRatingState( state ) {
		try {
			localStorage.ffxfRating = JSON.stringify( state );
		} catch ( e ) { /* storage might be full */ }
	}

	/**
	 * Schedule next random appearance: 1–3 page loads from now.
	 *
	 * @param {number} currentVisits
	 * @return {number}
	 */
	function nextShowVisit( currentVisits ) {
		return currentVisits + Math.floor( Math.random() * 3 ) + 1;
	}

	var ratingState = loadRatingState();
	ratingState.visits++;

	var showPulse = ! ratingState.clicked && ratingState.visits >= ratingState.nextShow;

	if ( showPulse ) {
		ratingState.nextShow = nextShowVisit( ratingState.visits );
	}

	saveRatingState( ratingState );

	// -------------------------------------------------------------------------
	// Slug mode helpers
	// -------------------------------------------------------------------------

	// Always seed the SKU field with the current product slug.
	skuInput.value = ffxf_slug.slug_product;

	/**
	 * Show the slug-mode info notice and reset SKU to the slug.
	 *
	 * @return {void}
	 */
	function resetToSlug() {
		skuInput.value = ffxf_slug.slug_product;

		if ( ! document.getElementById( 'sku_description' ) ) {
			var skuField = document.querySelector( '._sku_field' );
			if ( skuField ) {
				var notice = document.createElement( 'span' );
				notice.className = 'ffxf-slug-notice';
				notice.innerHTML =
					'<span class="dashicons dashicons-info" aria-hidden="true"></span>' +
					'<span id="sku_description">' + ffxf_slug.skuautoffxf_text_description + '</span>';
				skuField.appendChild( notice );
			}
		}
	}

	// -------------------------------------------------------------------------
	// Build icon strip (inline <span>)
	// -------------------------------------------------------------------------

	var warningInnerHtml;

	if ( ratingState.clicked ) {
		warningInnerHtml =
			'<span class="ffxf-sku-icon ffxf-sku-icon--static" aria-hidden="true">' +
				'<span class="ffxf-icon-circle">' +
					'<span class="dashicons dashicons-warning"></span>' +
				'</span>' +
			'</span>';
	} else if ( showPulse ) {
		warningInnerHtml =
			'<a class="ffxf-sku-icon ffxf-sku-icon--pulse tips" target="_blank"' +
				' id="ffxf-rating-link"' +
				' href="https://wordpress.org/support/plugin/easy-woocommerce-auto-sku-generator/"' +
				' data-tip="' + ffxf_slug.data_tooltip_bottom + '">' +
				'<span class="ffxf-pulse-ring" aria-hidden="true"></span>' +
				'<span class="ffxf-icon-circle" aria-hidden="true">' +
					'<span class="dashicons dashicons-warning" aria-hidden="true"></span>' +
				'</span>' +
			'</a>';
	} else {
		warningInnerHtml =
			'<span class="ffxf-sku-icon ffxf-sku-icon--ghost" aria-hidden="true"></span>';
	}

	var iconStripEl = document.createElement( 'span' );
	iconStripEl.className = 'ffxf-sku-icons';
	iconStripEl.innerHTML =
		'<a class="ffxf-sku-icon tips" target="_blank"' +
			' data-tip="' + ffxf_slug.data_tooltip + '"' +
			' href="' + ffxf_slug.skuautoffxf_site_url + '/wp-admin/admin.php?page=wc-settings&tab=products&section=skuautoffxf">' +
			'<span class="dashicons dashicons-admin-generic" aria-hidden="true"></span>' +
		'</a>' +
		'<a id="ffxf-refresh" class="ffxf-sku-icon tips"' +
			' data-tip="' + ffxf_slug.data_tooltip_trigger_script + '"' +
			' href="#" onclick="ffxfSlugRefreshSku();return false;">' +
			'<span class="dashicons dashicons-update-alt" aria-hidden="true"></span>' +
		'</a>';

	// Insert inline right after the WC help-tip (which precedes the input).
	var wcTip = skuInput.closest( '.form-field' )
		? skuInput.closest( '.form-field' ).querySelector( '.woocommerce-help-tip' )
		: null;

	if ( wcTip ) {
		wcTip.insertAdjacentElement( 'afterend', iconStripEl );
	} else {
		skuInput.insertAdjacentElement( 'afterend', iconStripEl );
	}

	if ( typeof jQuery !== 'undefined' && typeof jQuery.fn.tipTip === 'function' ) {
		jQuery( '.ffxf-sku-icons .tips' ).tipTip( {
			attribute: 'data-tip',
			fadeIn:    50,
			fadeOut:   50,
			delay:     200,
		} );
	}

	// -------------------------------------------------------------------------
	// DOMContentLoaded — rating link click handler
	// -------------------------------------------------------------------------

	document.addEventListener( 'DOMContentLoaded', function () {
		var ratingLink = document.getElementById( 'ffxf-rating-link' );

		if ( ratingLink ) {
			ratingLink.addEventListener( 'click', function () {
				ratingState.clicked = true;
				saveRatingState( ratingState );

				var staticIcon = document.createElement( 'span' );
				staticIcon.className = 'ffxf-sku-icon ffxf-sku-icon--static';
				staticIcon.setAttribute( 'aria-hidden', 'true' );
				staticIcon.innerHTML =
					'<span class="ffxf-icon-circle">' +
						'<span class="dashicons dashicons-warning"></span>' +
					'</span>';

				var thanks = document.createElement( 'span' );
				thanks.className = 'ffxf-thanks';
				thanks.textContent = ffxf_slug.data_tooltip_trigger_script_thanks;

				ratingLink.parentNode.insertBefore( staticIcon, ratingLink );
				ratingLink.parentNode.insertBefore( thanks, ratingLink );
				ratingLink.parentNode.removeChild( ratingLink );
			} );
		}
	} );

	// -------------------------------------------------------------------------
	// Public refresh handler
	// -------------------------------------------------------------------------

	/**
	 * Reset the SKU field to the product slug and animate.
	 *
	 * @return {void}
	 */
	window.ffxfSlugRefreshSku = function () {
		resetToSlug();
		skuInput.classList.remove( 'animation_sku' );
		void skuInput.offsetWidth; // eslint-disable-line no-void
		skuInput.classList.add( 'animation_sku' );
	};

} )();
