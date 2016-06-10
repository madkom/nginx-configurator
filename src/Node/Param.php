<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 08.04.16
 * Time: 10:31
 */
namespace Madkom\NginxConfigurator\Node;

/**
 * Class Param
 * @package Madkom\NginxConfigurator
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Param
{
    /**
     * @var string
     */
    protected $value;

    /**
     * Param constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Retrieve param value
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->value;
    }
}
