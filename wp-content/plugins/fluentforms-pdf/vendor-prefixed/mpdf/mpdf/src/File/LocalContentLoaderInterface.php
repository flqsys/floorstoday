<?php
/**
 * @license GPL-2.0-only
 *
 * Modified by __root__ on 29-March-2026 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace FluentPdf\Vendor\Mpdf\File;

interface LocalContentLoaderInterface
{

	/**
	 * @return string|null
	 */
	public function load($path);

}
