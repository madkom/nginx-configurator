<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 18.04.16
 * Time: 11:06
 */
namespace Madkom\NginxConfigurator;

use Countable;
use Madkom\Collection\CustomTypedCollection;
use Madkom\NginxConfigurator\Config\Server;
use Madkom\NginxConfigurator\Config\Upstream;
use Madkom\NginxConfigurator\Node\Directive;
use Madkom\NginxConfigurator\Node\Node;
use Madkom\NginxConfigurator\Node\Param;
use Madkom\NginxConfigurator\Node\RootNode;
use Traversable;

/**
 * Class Builder
 * @package Madkom\NginxConfigurator
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Builder implements Countable
{
    /**
     * @var RootNode Holds configuration root node
     */
    protected $rootNode;

    /**
     * Builder constructor.
     */
    public function __construct()
    {
        $this->rootNode = new RootNode();
    }

    /**
     * Append child node
     * @param Node $node
     * @return Node
     */
    public function append(Node $node) : Node
    {
        $this->rootNode->append($node);

        return $node;
    }

    /**
     * Remove child node
     * @param Node $node
     * @return bool
     */
    public function remove(Node $node) : bool
    {
        return $this->rootNode->remove($node);
    }

    /**
     * Search for specified nodes
     * @param callable $checker
     * @return CustomTypedCollection
     */
    public function search(callable $checker) : CustomTypedCollection
    {
        return $this->rootNode->filter($checker);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return count($this->rootNode);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     */
    public function getIterator()
    {
        return $this->rootNode->getIterator();
    }

    /**
     * @return string
     */
    public function dump() : string
    {
        return (string)$this->rootNode;
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function dumpFile(string $filename) : bool
    {
        return file_put_contents($filename, $this->dump());
    }
}
