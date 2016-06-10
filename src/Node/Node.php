<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 06.04.16
 * Time: 13:23
 */
namespace Madkom\NginxConfigurator\Node;

use Countable;
use IteratorAggregate;
use Madkom\Collection\CustomTypedCollection;
use Traversable;

/**
 * Class Node
 * @package Madkom\NginxConfigurator
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
abstract class Node implements Countable, IteratorAggregate
{
    /**
     * Holds parent node
     * @var Node
     */
    protected $parent;
    /**
     * Holds node name
     * @var string
     */
    protected $name = '';
    /**
     * Holds node children
     * @var CustomTypedCollection
     */
    protected $childNodes;

    /**
     * Node constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->childNodes = new class extends CustomTypedCollection {
            /**
             * Retrieves collection type
             * @return string
             */
            protected function getType() : string
            {
                return Node::class;
            }
        };
    }

    /**
     * Append new child node
     * @param Node $node
     * @return bool
     */
    public function append(Node $node) : bool
    {
        $node->parent = $this;

        return $this->childNodes->add($node);
    }

    /**
     * Remove child node
     * @param Node $node
     * @return bool
     */
    public function remove(Node $node) : bool
    {
        return $this->childNodes->remove($node);
    }

    /**
     * Search for specified nodes
     * @param callable $checker
     * @return CustomTypedCollection
     */
    public function search(callable $checker) : CustomTypedCollection
    {
        return $this->childNodes->filter($checker);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return protocol is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->childNodes);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return $this->childNodes->getIterator();
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return (string)implode("\n", (array)$this->childNodes->getIterator());
    }
}
