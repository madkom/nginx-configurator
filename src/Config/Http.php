<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 08.04.16
 * Time: 20:57
 */
namespace Madkom\NginxConfigurator\Config;

use Madkom\NginxConfigurator\Node\Context;
use Madkom\NginxConfigurator\Node\Directive;

/**
 * Class Http
 * @package Madkom\NginxConfigurator\Config
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Http extends Context
{
    /**
     * Http constructor.
     * @param Directive[] $directives
     */
    public function __construct(array $directives = [])
    {
        parent::__construct('http', $directives);
    }
}
