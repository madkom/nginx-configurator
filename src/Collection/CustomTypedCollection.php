<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 16.02.16
 * Time: 12:23
 */
namespace Madkom\NginxConfigurator\Collection;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Class AbstractTypedCollection
 * @package Madkom\Collection
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
abstract class CustomTypedCollection extends Collection
{
    /**
     * Retrieves collection type
     * @return string
     */
    abstract protected function getType() : string;

    /**
     * AbstractTypedCollection constructor.
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        if (!class_exists($this->getType())) {
            throw new InvalidArgumentException("Expected type should be accessible class name, given: {$this->getType()}");
        }
        parent::__construct($elements);
    }

    /**
     * @inheritDoc
     */
    public function add($element) : bool
    {
        if (!$this->isElementValid($element)) {
            throw new UnexpectedValueException(
                "Unexpected element type, expecting: {$this->getType()}, given: " . get_class($element)
            );
        }

        return parent::add($element);
    }

    /**
     * @inheritDoc
     */
    public function remove($element) : bool
    {
        if (!$this->isElementValid($element)) {
            throw new UnexpectedValueException(
                "Unexpected element type, expecting: {$this->getType()}, given: " . get_class($element)
            );
        }

        return parent::remove($element);
    }

    /**
     * Check is element valid object type
     * @param $element
     * @return bool
     */
    protected function isElementValid($element) : bool
    {
        return is_a($element, $this->getType()) || is_subclass_of($element, $this->getType());
    }
}
