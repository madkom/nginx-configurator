<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 18.04.16
 * Time: 11:06
 */
namespace Madkom\NginxConfigurator;

use Madkom\NginxConfigurator\Config\Server;
use Madkom\NginxConfigurator\Config\Upstream;
use Madkom\NginxConfigurator\Node\Directive;
use Madkom\NginxConfigurator\Node\Param;
use Madkom\NginxConfigurator\Node\RootNode;

/**
 * Class Builder
 * @package Madkom\NginxConfigurator
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Builder
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
        $this->clear();
    }

    public function clear()
    {
        $this->rootNode = new RootNode();
    }

    /**
     * @param int $port
     * @return Server
     */
    public function addServerNode(int $port) : Server
    {
        $listenIPv4 = new Directive('listen', [new Param($port)]);
        $listenIPv6 = new Directive('listen', [new Param("[::]:{$port}"), new Param('default'), new Param('ipv6only=on')]);
        $httpNode = new Server([$listenIPv4, $listenIPv6]);
        $this->rootNode->append($httpNode);

        return $httpNode;
    }

    /**
     * @param Server $server
     * @return Server
     */
    public function appendServerNode(Server $server) : Server
    {
        $this->rootNode->append($server);

        return $server;
    }

    /**
     * @param Upstream $upstream
     * @return Upstream
     */
    public function appendUpstreamNode(Upstream $upstream) : Upstream
    {
        $this->rootNode->append($upstream);

        return $upstream;
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
