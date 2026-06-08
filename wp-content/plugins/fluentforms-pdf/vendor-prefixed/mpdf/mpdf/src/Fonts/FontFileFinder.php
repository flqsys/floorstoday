<?php
/**
 * @license GPL-2.0-only
 *
 * Modified by __root__ on 29-March-2026 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace FluentPdf\Vendor\Mpdf\Fonts;

class FontFileFinder
{

	private $directories;

	public function __construct($directories)
	{
		$this->setDirectories($directories);
	}

	public function setDirectories($directories)
	{
		if (!is_array($directories)) {
			$directories = [$directories];
		}

		$this->directories = $directories;
	}

	public function findFontFile($name)
	{
		foreach ($this->directories as $directory) {
			$filename = $directory . '/' . $name;
			if (file_exists($filename)) {
				return $filename;
			}
		}

		throw new \FluentPdf\Vendor\Mpdf\MpdfException(sprintf('Cannot find TTF TrueType font file "%s" in configured font directories.', $name));
	}
}
