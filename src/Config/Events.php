<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 08.04.16
 * Time: 20:55
 */
namespace Madkom\NginxConfigurator\Config;

use Madkom\NginxConfigurator\Node\Context;
use Madkom\NginxConfigurator\Node\Directive;

/**
 * Class Events
 * @package Madkom\NginxConfigurator\Config
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Events extends Context
{
    /**
     * Events constructor.
     * @param Directive[] $directives
     */
    public function __construct(array $directives = [])
    {
        parent::__construct('events', $directives);
    }
}
