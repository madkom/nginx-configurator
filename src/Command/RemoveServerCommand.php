<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 10.06.16
 * Time: 13:38
 */
namespace Madkom\NginxConfigurator\Command;

use Madkom\NginxConfigurator\Builder;
use Madkom\NginxConfigurator\Config\Server;
use Madkom\NginxConfigurator\Node\Directive;
use Madkom\NginxConfigurator\Node\Param;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RemoveServerCommand
 * @package Madkom\NginxConfigurator\Command
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class RemoveServerCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('server:remove');
        $this->setDescription("Removes server context and it's configuration");
        $this->addArgument('name', InputArgument::OPTIONAL, 'Server hostname:port', 'localhost:80');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getOption('file');
        $builder = $this->getConfig($input);

        list($name, $port) = explode(':', $input->getArgument('name') . ':80');

        $listenIPv4 = new Directive('listen', [new Param($port)]);
        $listenIPv6 = new Directive('listen', [new Param("[::]:{$port}"), new Param('default'), new Param('ipv6only=on')]);

        $server = new Server([$listenIPv4, $listenIPv6]);
        if ($name != 'localhost' && !empty($name)) {
            $server->append(new Directive('server_name', [new Param($name)]));
        }

        $builder->appendServerNode($server);
        $builder->dumpFile($filename);
    }
}
