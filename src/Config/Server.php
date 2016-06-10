<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 08.04.16
 * Time: 21:00
 */
namespace Madkom\NginxConfigurator\Config;

use Madkom\NginxConfigurator\Node\Context;

/**
 * Class Server
 * @package Madkom\NginxConfigurator\Config
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Server extends Context
{
    /**
     * Server constructor.
     * @param array $directives
     */
    public function __construct(array $directives = [])
    {
        parent::__construct('server', $directives);
    }
}
