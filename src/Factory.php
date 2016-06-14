<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 14.06.16
 * Time: 11:22
 */
namespace Madkom\NginxConfigurator;

use Madkom\NginxConfigurator\Config\Location;
use Madkom\NginxConfigurator\Config\Server;
use Madkom\NginxConfigurator\Node\Directive;
use Madkom\NginxConfigurator\Node\Param;

/**
 * Class Factory
 * @package Madkom\NginxConfigurator
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Factory
{
    /**
     * Creates Server node
     * @param int $port
     * @return Server
     */
    public function createServer(int $port = 80) : Server
    {
        $listenIPv4 = new Directive('listen', [new Param($port)]);
        $listenIPv6 = new Directive('listen', [new Param("[::]:{$port}"), new Param('default'), new Param('ipv6only=on')]);

        return new Server([$listenIPv4, $listenIPv6]);
    }

    /**
     * Creates Location node
     * @param string $location
     * @param string|null $match
     * @return Location
     */
    public function createLocation(string $location, string $match = null) : Location
    {
        return new Location(new Param($location), is_null($match) ? null : new Param($match));
    }
}
