<?php
/**
 * @license GPL-2.0-only
 *
 * Modified by __root__ on 29-March-2026 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace FluentPdf\Vendor\Mpdf\Tag;

class TocPageBreak extends FormFeed
{
	public function open($attr, &$ahtml, &$ihtml)
	{
		list($isbreak, $toc_id) = $this->tableOfContents->openTagTOCPAGEBREAK($attr);
		$this->toc_id = $toc_id;
		if ($isbreak) {
			return;
		}
		parent::open($attr, $ahtml, $ihtml);
	}
}
