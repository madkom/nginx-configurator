<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 08.04.16
 * Time: 21:18
 */
namespace Madkom\NginxConfigurator\Node;

/**
 * Class RootNode
 * @package Madkom\NginxConfigurator\node
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class RootNode extends Node
{
    /**
     * RootNode constructor.
     * @param Node[] $nodes
     */
    public function __construct(array $nodes = [])
    {
        parent::__construct('');
        foreach ($nodes as $node) {
            $this->append($node);
        }
    }
}
