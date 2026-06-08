<?php
/**
 * @license MIT
 *
 * Modified by __root__ on 29-March-2026 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace FluentPdf\Vendor\DeepCopy\TypeMatcher;

class TypeMatcher
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @param mixed $element
     *
     * @return boolean
     */
    public function matches($element)
    {
        return is_object($element) ? is_a($element, $this->type) : gettype($element) === $this->type;
    }
}
