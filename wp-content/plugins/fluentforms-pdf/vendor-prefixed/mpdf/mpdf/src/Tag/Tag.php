<?php
/**
 * @license GPL-2.0-only
 *
 * Modified by __root__ on 29-March-2026 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace FluentPdf\Vendor\Mpdf\Tag;

use FluentPdf\Vendor\Mpdf\Strict;

use FluentPdf\Vendor\Mpdf\Cache;
use FluentPdf\Vendor\Mpdf\Color\ColorConverter;
use FluentPdf\Vendor\Mpdf\CssManager;
use FluentPdf\Vendor\Mpdf\Form;
use FluentPdf\Vendor\Mpdf\Image\ImageProcessor;
use FluentPdf\Vendor\Mpdf\Language\LanguageToFontInterface;
use FluentPdf\Vendor\Mpdf\Mpdf;
use FluentPdf\Vendor\Mpdf\Otl;
use FluentPdf\Vendor\Mpdf\SizeConverter;
use FluentPdf\Vendor\Mpdf\TableOfContents;

abstract class Tag
{

	use Strict;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\Mpdf
	 */
	protected $mpdf;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\Cache
	 */
	protected $cache;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\CssManager
	 */
	protected $cssManager;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\Form
	 */
	protected $form;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\Otl
	 */
	protected $otl;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\TableOfContents
	 */
	protected $tableOfContents;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\SizeConverter
	 */
	protected $sizeConverter;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\Color\ColorConverter
	 */
	protected $colorConverter;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\Image\ImageProcessor
	 */
	protected $imageProcessor;

	/**
	 * @var \FluentPdf\Vendor\Mpdf\Language\LanguageToFontInterface
	 */
	protected $languageToFont;

	const ALIGN = [
		'left' => 'L',
		'center' => 'C',
		'right' => 'R',
		'top' => 'T',
		'text-top' => 'TT',
		'middle' => 'M',
		'baseline' => 'BS',
		'bottom' => 'B',
		'text-bottom' => 'TB',
		'justify' => 'J'
	];

	public function __construct(
		Mpdf $mpdf,
		Cache $cache,
		CssManager $cssManager,
		Form $form,
		Otl $otl,
		TableOfContents $tableOfContents,
		SizeConverter $sizeConverter,
		ColorConverter $colorConverter,
		ImageProcessor $imageProcessor,
		LanguageToFontInterface $languageToFont
	) {

		$this->mpdf = $mpdf;
		$this->cache = $cache;
		$this->cssManager = $cssManager;
		$this->form = $form;
		$this->otl = $otl;
		$this->tableOfContents = $tableOfContents;
		$this->sizeConverter = $sizeConverter;
		$this->colorConverter = $colorConverter;
		$this->imageProcessor = $imageProcessor;
		$this->languageToFont = $languageToFont;
	}

	public function getTagName()
	{
		$tag = get_class($this);
		return strtoupper(str_replace('FluentPdf\Vendor\Mpdf\Tag\\', '', $tag));
	}

	protected function getAlign($property)
	{
		$property = strtolower($property);
		return array_key_exists($property, self::ALIGN) ? self::ALIGN[$property] : '';
	}

	abstract public function open($attr, &$ahtml, &$ihtml);

	abstract public function close(&$ahtml, &$ihtml);

}
