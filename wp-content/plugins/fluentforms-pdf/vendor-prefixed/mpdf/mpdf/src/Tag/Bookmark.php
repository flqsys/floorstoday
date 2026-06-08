<?php
/**
 * @license GPL-2.0-only
 *
 * Modified by __root__ on 29-March-2026 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace FluentPdf\Vendor\Mpdf\Tag;

use FluentPdf\Vendor\Mpdf\Mpdf;

class Bookmark extends Tag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		if (isset($attr['CONTENT'])) {
			$objattr = [];
			$objattr['CONTENT'] = htmlspecialchars_decode($attr['CONTENT'], ENT_QUOTES);
			$objattr['type'] = 'bookmark';
			if (!empty($attr['LEVEL'])) {
				$objattr['bklevel'] = $attr['LEVEL'];
			} else {
				$objattr['bklevel'] = 0;
			}
			$e = Mpdf::OBJECT_IDENTIFIER . "type=bookmark,objattr=" . serialize($objattr) . Mpdf::OBJECT_IDENTIFIER;
			if ($this->mpdf->tableLevel) {
				$this->mpdf->cell[$this->mpdf->row][$this->mpdf->col]['textbuffer'][] = [$e];
			} // *TABLES*
			else { // *TABLES*
				$this->mpdf->textbuffer[] = [$e];
			} // *TABLES*
		}
	}

	public function close(&$ahtml, &$ihtml)
	{
	}
}
