<?php
/**
 * @license GPL-2.0-only
 *
 * Modified by __root__ on 29-March-2026 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace FluentPdf\Vendor\Mpdf\Writer;

use FluentPdf\Vendor\Mpdf\Strict;
use FluentPdf\Vendor\Mpdf\Mpdf;

final class JavaScriptWriter
{

	use Strict;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\Mpdf
	 */
	private $mpdf;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\Writer\BaseWriter
	 */
	private $writer;

	public function __construct(Mpdf $mpdf, BaseWriter $writer)
	{
		$this->mpdf = $mpdf;
		$this->writer = $writer;
	}

	public function writeJavascript() // _putjavascript
	{
		$this->writer->object();
		$this->mpdf->n_js = $this->mpdf->n;
		$this->writer->write('<<');
		$this->writer->write('/Names [(EmbeddedJS) ' . (1 + $this->mpdf->n) . ' 0 R ]');
		$this->writer->write('>>');
		$this->writer->write('endobj');

		$this->writer->object();
		$this->writer->write('<<');
		$this->writer->write('/S /JavaScript');
		$this->writer->write('/JS ' . $this->writer->string($this->mpdf->js));
		$this->writer->write('>>');
		$this->writer->write('endobj');
	}

}
