# Floors Today WordPress Plugin Audit

**Date:** June 2026  
**Local path:** `C:\wamp64\www\floorstoday`  
**Local URL expected by WordPress:** `http://localhost/floorstoday`

## Executive Summary

The site is a WordPress 7.0 install using Astra, Elementor Pro, and WooCommerce as a flooring product catalog. There are 31 normal plugin folders installed and 2 must-use plugins loaded from `wp-content/mu-plugins`.

The site is plugin-heavy, but the larger immediate problem is not only plugin count:

- The local `siteurl` and `home` options are correct: `http://localhost/floorstoday`.
- `.htaccess` is also correct for `/floorstoday/`.
- The database still contains many old `floorstoday.ca` URLs.
- Loading the full active plugin stack causes a PHP memory fatal at 128 MB, mostly while Elementor/Elementor Pro initializes.
- WooCommerce is required for the fatal to appear in the full stack, but WooCommerce alone does not fail.

## Location Findings

### Correct

| Setting | Value |
|---|---|
| `siteurl` | `http://localhost/floorstoday` |
| `home` | `http://localhost/floorstoday` |
| `.htaccess` RewriteBase | `/floorstoday/` |
| `.htaccess` target | `/floorstoday/index.php` |
| Permalink structure | `/%category%/` |

### Problem

Database dry-run checks found remaining live-domain references:

| Search | Dry-run result |
|---|---:|
| `https://floorstoday.ca` | 2,223 replacements |
| `floorstoday.ca` | 3,052 replacements |
| `floorstoday.com` | 0 replacements |
| `www.floorstoday.ca` | 0 replacements |

Most relevant hits are in `flod_posts.guid`, `flod_posts.post_content`, `flod_postmeta.meta_value`, and plugin/form tables. This can cause broken local images, links, Elementor assets, form redirects, or mixed local/live navigation.

Recommended local migration command after backup:

```powershell
php wp-cli.phar search-replace https://floorstoday.ca http://localhost/floorstoday --all-tables --skip-plugins --skip-themes --precise
```

Do not blindly replace plain `floorstoday.ca` unless you review email addresses and business text first.

## Critical Error Finding

Running WP-CLI normally fails with:

```text
Error: There has been a critical error on this website.
```

The PHP log shows:

```text
Allowed memory size of 134217728 bytes exhausted
```

The fatal appears around Elementor and Elementor Pro initialization, including modules such as floating buttons, forms, promotions, and content sanitizer.

Confirmed behavior:

- Active theme alone loads correctly.
- Active plugins together fail, even with theme skipped.
- Each active plugin tested alone loads correctly.
- The active stack without WooCommerce loads.
- WooCommerce alone loads.

Conclusion: this is primarily a memory/plugin-stack pressure issue, not a basic URL-location issue. Increase local PHP memory to at least 256 MB, preferably 512 MB for this stack, then retest. Plugin cleanup should still be done because the stack is heavier than needed for a catalog site.

## Actual Active Plugins

These are active in the database:

| Plugin | Recommendation |
|---|---|
| Elementor | Keep |
| Elementor Pro | Keep if the site templates/forms depend on it |
| WooCommerce | Keep for product catalog |
| YITH WooCommerce Catalog Mode | Keep only if WooCommerce cart/checkout must stay disabled |
| HUSKY / WooCommerce Products Filter | Keep if product filters are used on category/shop pages |
| WP Sheet Editor / Woo Bulk Edit Products | Keep for bulk catalog management |
| FluentSMTP | Keep |
| Fluent Forms | Keep if forms are built with it |
| Fluent Forms Pro | Keep only if Pro features are used |
| Fluent PDF Generator | Keep only if forms generate PDFs |
| FluentBooking | Keep only if appointment booking is live |
| FluentBooking Pro | Keep only if booking is live and Pro features are used |
| FluentCRM | Review; remove if not actively used for campaigns/CRM |
| Contact Form 7 | Remove if Fluent Forms replaced all forms |
| Classic Editor | Remove if pages/products are managed with Elementor/Woo fields |
| Google Site Kit | Keep if Analytics/Search Console dashboards are needed in WP |
| SaaS Pricing & Comparison Tables | Remove if no active pricing tables use it |
| Fluent Boards | Remove from production unless the team actively manages work inside WP |

## Installed But Inactive Plugins

Inactive plugins still add maintenance and security surface. These should normally be deleted after confirming they are not needed:

| Plugin | Recommendation |
|---|---|
| WP File Manager | Delete. High-risk plugin class. Use filesystem/hosting access instead. |
| LiteSpeed Cache | Keep only if the server uses LiteSpeed/OpenLiteSpeed; otherwise use a simpler cache choice or none locally |
| Yoast SEO | Activate if this is the chosen SEO plugin; otherwise remove after confirming metadata migration |
| Breadcrumb NavXT | Remove if breadcrumbs are handled by Elementor, Astra, or Yoast |
| Admin and Site Enhancements | Optional; keep only if specific admin tweaks are needed |
| CMB2 and extensions | Keep only if custom product fields depend on them |
| Easy Auto SKU Generator | Keep only if new products need generated SKUs |
| WP Crontrol | Keep inactive as a temporary diagnostic tool, or delete after cron review |
| Tawk.to Live Chat | Delete if chat is not used |
| WPvivid Backup | Keep inactive only if it is the chosen backup/migration tool |
| WPCode Lite / Insert Headers and Footers | Remove if snippets can move to a small custom plugin or child theme |

## Must-Use Plugins

| Plugin | Note |
|---|---|
| `0-worker.php` | ManageWP/Worker loader. Keep only if remote site management is used. |
| `elementor-safe-mode.php` | Elementor safe mode loader. Usually okay, but not business functionality. |

## Recommended Lean Stack

Target for this catalog site: about 14 to 18 active plugins.

Core stack:

- Astra
- Elementor
- Elementor Pro
- WooCommerce
- YITH WooCommerce Catalog Mode
- HUSKY / WooCommerce Products Filter
- WP Sheet Editor / Woo Bulk Edit Products
- FluentSMTP
- Fluent Forms
- Fluent Forms Pro, only if Pro fields/integrations are used
- LiteSpeed Cache, only on a LiteSpeed server
- Yoast SEO or another single SEO plugin
- Site Kit, optional
- WPvivid Backup, optional but useful for migrations

Remove or replace first:

- WP File Manager
- Contact Form 7
- Fluent Boards
- SaaS Pricing & Comparison Tables
- Classic Editor
- Tawk.to if unused
- Breadcrumb NavXT if breadcrumbs are already handled elsewhere
- WPCode Lite / Insert Headers and Footers after moving snippets

## Simple Code Instead Of Plugins

Good candidates for a tiny custom plugin or child-theme `functions.php`:

- Disable cart, checkout, and add-to-cart buttons if catalog behavior is simple.
- Add small header/footer scripts.
- Add simple shortcodes.
- Add small WooCommerce label/output tweaks.
- Redirect checkout/cart pages to the catalog.
- Add basic schema or breadcrumb output if SEO plugin already covers the rest.

Keep plugins for:

- Complex product filtering.
- Elementor templates and theme builder.
- Form storage, email routing, PDFs, and booking workflows.
- Bulk product editing.
- Backups/migration.
- SEO metadata/indexing.

## Immediate Action Plan

1. Back up the database and files.
2. Increase local PHP memory from 128 MB to 256-512 MB.
3. Run the exact URL search-replace for `https://floorstoday.ca` to `http://localhost/floorstoday`.
4. Regenerate permalinks from WordPress admin or with WP-CLI.
5. Delete `WP File Manager`.
6. Remove inactive plugins that are not needed.
7. Consolidate forms around Fluent Forms or Contact Form 7, not both.
8. Move simple snippets out of WPCode into a small custom plugin.
9. Retest with all required plugins active.

## Overall Assessment

**Location status:** Partially fixed. Main URL settings are correct, but database content still contains live-domain URLs.  
**Runtime status:** Failing under full active stack because PHP memory is too low for the current plugin load.  
**Security status:** Moderate risk, mainly due to installed WP File Manager and unnecessary inactive plugins.  
**Plugin count:** Higher than needed. A clean catalog build should target 14-18 active plugins.
