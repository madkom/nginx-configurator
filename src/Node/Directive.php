<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 06.04.16
 * Time: 13:40
 */
namespace Madkom\NginxConfigurator\Node;

use Madkom\Collection\CustomTypedCollection;
use Traversable;

/**
 * Class Directive
 * @package Madkom\NginxConfigurator
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Directive extends Node
{
    /**
     * Holds directive name
     * @var string
     */
    protected $name;
    /**
     * Holds param collection
     * @var CustomTypedCollection|Param[]
     */
    protected $params;

    /**
     * Directive constructor.
     * @param string $name
     * @param array $params
     */
    public function __construct(string $name, array $params = [])
    {
        parent::__construct($name);
        $this->params = new class($params) extends CustomTypedCollection {

            /**
             * Retrieves collection type
             * @return string
             */
            protected function getType() : string
            {
                return Param::class;
            }
        };
    }

    /**
     * Retrieve directive name
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Retrieve params iterator
     * @return Traversable|Param[]
     */
    public function getParams() : Traversable
    {
        return $this->params->getIterator();
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf("{$this->name} %s;", implode(' ', (array)$this->params->getIterator()));
    }
}
