<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 08.04.16
 * Time: 20:53
 */
namespace Madkom\NginxConfigurator\Node;

/**
 * Class Context
 * @package Madkom\NginxConfigurator\Node
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
abstract class Context extends Node
{
    /**
     * Context constructor.
     * @param string $name
     * @param Directive[] $directives
     */
    public function __construct($name, array $directives = [])
    {
        parent::__construct($name);
        foreach ($directives as $directive) {
            $this->append($directive);
        }
    }

    public function __toString() : string
    {
        $childStrings = [];
        foreach ($this->childNodes as $childNode) {
            $childStrings[] = implode("\n\t", explode("\n", (string)$childNode));
        }

        return sprintf(
            "{$this->name} {\n\t%s\n}\n",
            implode("\n\t", $childStrings)
        );
    }
}
