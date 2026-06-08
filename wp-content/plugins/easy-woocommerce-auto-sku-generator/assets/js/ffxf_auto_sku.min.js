/**
 * Product edit page — auto-fill and refresh SKU.
 *
 * Localised object: ffxf_sku  (see easy-woocommerce-auto-sku-generator.php)
 *
 * Layout context (WooCommerce admin):
 *   <p class="form-field _sku_field">   (padding-left: 162px)
 *     <label>SKU</label>                (float:left, ~150 px, margin-left:-150px)
 *     <a class="woocommerce-help-tip">  (inline-block, 16×16 px, vertical-align:middle)
 *     <input class="short" id="_sku">   (float:left, 50%)
 *     → our strip is inserted HERE via insertAdjacentElement('afterend')
 *   </p>
 *   The strip MUST be an inline element so it sits on the same line as the input.
 *
 * Warning icon visibility logic:
 *   - Place in strip is ALWAYS reserved (ghost placeholder keeps layout stable).
 *   - Pulse animation + link shown randomly: every 1–3 page loads.
 *   - Once user clicks the link → stored in localStorage; never shown again.
 *   - localStorage key: ffxfRating  { clicked: bool, visits: N, nextShow: N }
 *
 * @package EasyAutoSkuGenerator
 */

/* global ffxf_sku, jQuery */

( function () {
	'use strict';

	var skuInput = document.getElementById( '_sku' );
	if ( ! skuInput ) {
		return;
	}

	// -------------------------------------------------------------------------
	// Rating / visibility state
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

		// First visit: show on visit 1, then schedule randomly
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
	 * Schedule the next random appearance: 1–3 page loads from now.
	 *
	 * @param {number} currentVisits
	 * @return {number}
	 */
	function nextShowVisit( currentVisits ) {
		return currentVisits + Math.floor( Math.random() * 3 ) + 1;
	}

	// Increment visit counter and determine whether to show pulse this load.
	var ratingState  = loadRatingState();
	ratingState.visits++;

	var showPulse = ! ratingState.clicked && ratingState.visits >= ratingState.nextShow;

	if ( showPulse ) {
		// Schedule next appearance BEFORE saving so refresh doesn't re-trigger
		ratingState.nextShow = nextShowVisit( ratingState.visits );
	}

	saveRatingState( ratingState );

	// -------------------------------------------------------------------------
	// SKU generation helpers
	// -------------------------------------------------------------------------

	/**
	 * Build a random SKU core string.
	 * When "take previous product" is active, returns the pre-computed value.
	 *
	 * @param {number} length Number of random characters.
	 * @return {string}
	 */
	function makeSkuCore( length ) {
		if ( ffxf_sku.ffxf_prev_ID ) {
			return ffxf_sku.ffxf_prev_ID;
		}

		var characters       = ffxf_sku.ffxf_format_sku;
		var charactersLength = characters.length;
		var prefix           = ffxf_sku.skuautoffxf_auto_prefix || '';
		var result           = prefix;

		for ( var i = 0; i < length; i++ ) {
			result += characters.charAt( Math.floor( Math.random() * charactersLength ) );
		}

		return result;
	}

	/**
	 * Compose the full SKU value: core + ID + suffix + additional number.
	 *
	 * @param {number} length Number of random characters.
	 * @return {string}
	 */
	function composeSku( length ) {
		return makeSkuCore( length ) +
			( ffxf_sku.skuautoffxf_id        || '' ) +
			( ffxf_sku.skuautoffxf_suffix     || '' ) +
			( ffxf_sku.skuautoffxf_number_dop || '' );
	}

	// Auto-fill on page load when the field is empty.
	if ( '' === skuInput.value || null === skuInput.value ) {
		skuInput.value = composeSku( ffxf_sku.skuautoffxf_auto_number );
	}

	// -------------------------------------------------------------------------
	// Build previous-product info panel (shown when "take previous" is on)
	// -------------------------------------------------------------------------

	var prevInfoEl = null;

	if ( ffxf_sku.skuautoffxf_prev_ID_old || ffxf_sku.skuautoffxf_prev_ID_draft ) {
		var prevInfoHtml = ffxf_sku.skuautoffxf_auto_prefix_text;

		if ( ffxf_sku.skuautoffxf_prev_ID_old ) {
			prevInfoHtml += '<hr>' +
				ffxf_sku.skuautoffxf_prev_ID_old_text +
				ffxf_sku.skuautoffxf_prev_ID_old;
		}

		if ( ffxf_sku.skuautoffxf_prev_ID_draft ) {
			prevInfoHtml += '<br>' +
				ffxf_sku.skuautoffxf_prev_ID_draft_text +
				ffxf_sku.skuautoffxf_prev_ID_draft;
		}

		prevInfoEl = document.createElement( 'span' );
		prevInfoEl.className = 'ffxf-prev-info';
		prevInfoEl.innerHTML =
			'<span class="dashicons dashicons-info" aria-hidden="true"></span>' +
			'<span>' + prevInfoHtml + '</span>';
	}

	// -------------------------------------------------------------------------
	// Build icon strip
	// CRITICAL: <span> (inline), NOT <div> (block) to preserve WC float layout.
	//
	// Warning icon slot is ALWAYS in the DOM for layout stability.
	// The pulse animation + clickable link is toggled via CSS class .is-visible.
	// -------------------------------------------------------------------------

	var warningInnerHtml;

	if ( ratingState.clicked ) {
		// User already rated: show static circle, no pulse, no link.
		warningInnerHtml =
			'<span class="ffxf-sku-icon ffxf-sku-icon--pulse ffxf-sku-icon--static"' +
				' aria-hidden="true">' +
				'<span class="ffxf-icon-circle">' +
					'<span class="dashicons dashicons-warning"></span>' +
				'</span>' +
			'</span>';
	} else if ( showPulse ) {
		// Show on this page load: active pulse + link.
		warningInnerHtml =
			'<a class="ffxf-sku-icon ffxf-sku-icon--pulse tips" target="_blank"' +
				' id="ffxf-rating-link"' +
				' href="https://wordpress.org/support/plugin/easy-woocommerce-auto-sku-generator/"' +
				' data-tip="' + ffxf_sku.data_tooltip_bottom + '">' +
				'<span class="ffxf-pulse-ring" aria-hidden="true"></span>' +
				'<span class="ffxf-icon-circle" aria-hidden="true">' +
					'<span class="dashicons dashicons-warning" aria-hidden="true"></span>' +
				'</span>' +
			'</a>';
	} else {
		// Hidden this load: ghost placeholder — same size, no animation, invisible.
		warningInnerHtml =
			'<span class="ffxf-sku-icon ffxf-sku-icon--ghost" aria-hidden="true"></span>';
	}

	var iconStripEl = document.createElement( 'span' );
	iconStripEl.className = 'ffxf-sku-icons';
	iconStripEl.innerHTML =
		// Gear icon — settings link
		'<a class="ffxf-sku-icon tips" target="_blank"' +
			' data-tip="' + ffxf_sku.data_tooltip + '"' +
			' href="' + ffxf_sku.skuautoffxf_site_url + '/wp-admin/admin.php?page=wc-settings&tab=products&section=skuautoffxf">' +
			'<span class="dashicons dashicons-admin-generic" aria-hidden="true"></span>' +
		'</a>' +
		// Refresh icon
		'<a id="ffxf-refresh" class="ffxf-sku-icon tips"' +
			' data-tip="' + ffxf_sku.data_tooltip_trigger_script + '"' +
			' href="#" onclick="ffxfRefreshSku();return false;">' +
			'<span class="dashicons dashicons-update-alt" aria-hidden="true"></span>' +
		'</a>';

	// Insert inline right after the WC help-tip (which precedes the input).
	// The help-tip is an inline-block element so our strip sits flush next to it
	// without being disrupted by the input's float:left.
	var wcTip = skuInput.closest( '.form-field' )
		? skuInput.closest( '.form-field' ).querySelector( '.woocommerce-help-tip' )
		: null;

	if ( wcTip ) {
		wcTip.insertAdjacentElement( 'afterend', iconStripEl );
	} else {
		skuInput.insertAdjacentElement( 'afterend', iconStripEl );
	}

	// Activate WooCommerce tipTip tooltips if available.
	if ( typeof jQuery !== 'undefined' && typeof jQuery.fn.tipTip === 'function' ) {
		jQuery( '.ffxf-sku-icons .tips' ).tipTip( {
			attribute: 'data-tip',
			fadeIn:    50,
			fadeOut:   50,
			delay:     200,
		} );
	}

	// -------------------------------------------------------------------------
	// DOMContentLoaded
	// -------------------------------------------------------------------------

	document.addEventListener( 'DOMContentLoaded', function () {

		// Show previous-product info panel when relevant.
		if ( prevInfoEl && ffxf_sku.ffxf_prev_ID ) {
			var skuField = document.querySelector( '._sku_field' );
			if ( skuField ) {
				var prevWrapper = document.createElement( 'span' );
				prevWrapper.className = 'ffxf-prev-info-wrap';
				prevWrapper.appendChild( prevInfoEl );
				skuField.appendChild( prevWrapper );
			}
		}

		// Rating link click handler (only exists when pulse is shown).
		var ratingLink = document.getElementById( 'ffxf-rating-link' );
		if ( ratingLink ) {
			ratingLink.addEventListener( 'click', function () {
				// Persist: user clicked — never show pulse again.
				ratingState.clicked = true;
				saveRatingState( ratingState );

				// Swap link for a static circle + thank-you text so layout stays.
				var staticIcon = document.createElement( 'span' );
				staticIcon.className = 'ffxf-sku-icon ffxf-sku-icon--static';
				staticIcon.setAttribute( 'aria-hidden', 'true' );
				staticIcon.innerHTML =
					'<span class="ffxf-icon-circle">' +
						'<span class="dashicons dashicons-warning"></span>' +
					'</span>';

				var thanks = document.createElement( 'span' );
				thanks.className = 'ffxf-thanks';
				thanks.textContent = ffxf_sku.data_tooltip_trigger_script_thanks;

				ratingLink.parentNode.insertBefore( staticIcon, ratingLink );
				ratingLink.parentNode.insertBefore( thanks, ratingLink );
				ratingLink.parentNode.removeChild( ratingLink );
			} );
		}
	} );

	// -------------------------------------------------------------------------
	// Public refresh handler — called via onclick attribute
	// -------------------------------------------------------------------------

	/**
	 * Re-generate SKU and animate the input field.
	 *
	 * @return {void}
	 */
	window.ffxfRefreshSku = function () {
		skuInput.value = composeSku( ffxf_sku.skuautoffxf_auto_number );
		skuInput.classList.remove( 'animation_sku' );
		void skuInput.offsetWidth; // eslint-disable-line no-void
		skuInput.classList.add( 'animation_sku' );
	};

} )();
