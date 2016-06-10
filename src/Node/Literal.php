<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 08.04.16
 * Time: 10:32
 */
namespace Madkom\NginxConfigurator\Node;

/**
 * Class Literal
 * @package Madkom\NginxConfigurator
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Literal extends Param
{
    /**
     * @return string
     */
    public function __toString() : string
    {
        return '"' . addslashes($this->value) . '"';
    }
}
