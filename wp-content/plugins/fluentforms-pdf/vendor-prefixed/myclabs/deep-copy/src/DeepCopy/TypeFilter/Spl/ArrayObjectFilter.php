<?php
/**
 * @license MIT
 *
 * Modified by __root__ on 29-March-2026 using {@see https://github.com/BrianHenryIE/strauss}.
 */
namespace FluentPdf\Vendor\DeepCopy\TypeFilter\Spl;

use FluentPdf\Vendor\DeepCopy\DeepCopy;
use FluentPdf\Vendor\DeepCopy\TypeFilter\TypeFilter;

/**
 * In PHP 7.4 the storage of an ArrayObject isn't returned as
 * ReflectionProperty. So we deep copy its array copy.
 */
final class ArrayObjectFilter implements TypeFilter
{
    /**
     * @var DeepCopy
     */
    private $copier;

    public function __construct(DeepCopy $copier)
    {
        $this->copier = $copier;
    }

    /**
     * {@inheritdoc}
     */
    public function apply($arrayObject)
    {
        $clone = clone $arrayObject;
        foreach ($arrayObject->getArrayCopy() as $k => $v) {
            $clone->offsetSet($k, $this->copier->copy($v));
        }

        return $clone;
    }
}

