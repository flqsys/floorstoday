<?php
/**
 * @license MIT
 *
 * Modified by __root__ on 29-March-2026 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace FluentPdf\Vendor\Mpdf\PsrLogAwareTrait;

use FluentPdf\Vendor\Psr\Log\LoggerInterface;

trait PsrLogAwareTrait 
{

	/**
	 * @var \FluentPdf\Vendor\Psr\Log\LoggerInterface
	 */
	protected $logger;

	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
	
}
