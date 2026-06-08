<?php
/**
 * @license GPL-2.0-only
 *
 * Modified by __root__ on 29-March-2026 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace FluentPdf\Vendor\Mpdf\Tag;

class Tta extends SubstituteTag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		$this->mpdf->tta = true;
		$this->mpdf->InlineProperties['TTA'] = $this->mpdf->saveInlineProperties();

		if (in_array($this->mpdf->FontFamily, $this->mpdf->mono_fonts)) {
			$this->mpdf->setCSS(['FONT-FAMILY' => 'ccourier'], 'INLINE');
		} elseif (in_array($this->mpdf->FontFamily, $this->mpdf->serif_fonts)) {
			$this->mpdf->setCSS(['FONT-FAMILY' => 'ctimes'], 'INLINE');
		} else {
			$this->mpdf->setCSS(['FONT-FAMILY' => 'chelvetica'], 'INLINE');
		}
	}

}
