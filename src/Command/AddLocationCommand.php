<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 10.06.16
 * Time: 14:25
 */
namespace Madkom\NginxConfigurator\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddLocationCommand
 * @package Madkom\NginxConfigurator\Command
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class AddLocationCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('location:add');
        $this->setDescription("Adds location context and configuration");
        $this->addArgument('name', InputArgument::OPTIONAL, 'Server hostname:port', 'localhost:80');
        $this->addOption('internal', null, InputOption::VALUE_NONE, 'Adds internal directive');
        $this->addOption(
            'proxy_pass',
            null,
            InputOption::VALUE_OPTIONAL,
            'Adds proxy_pass url <comment>(eg. http://proxy/)</comment>'
        );
        $this->addOption(
            'proxy_bind',
            null,
            InputOption::VALUE_OPTIONAL,
            'Adds proxy_bind directive url or variable <comment>(eg. $server_addr)</comment>'
        );
        $this->addOption(
            'proxy_redirect',
            null,
            InputOption::VALUE_OPTIONAL ^ InputOption::VALUE_IS_ARRAY,
            'Adds proxy_redirect directive <comment>(eg. http://$host or https://$host)</comment>'
        );
        $this->addOption(
            'proxy_set_header',
            null,
            InputOption::VALUE_OPTIONAL ^ InputOption::VALUE_IS_ARRAY,
            'Adds proxy_set_header directive <comment>(eg. "Content-Type: text/html"</comment>'
        );
        $this->addOption(
            'proxy_pass_request_body',
            null,
            InputOption::VALUE_OPTIONAL,
            'Adds proxy_pass_requeest_body directive <comment>(on|off)</comment>',
            'on'
        );

//        //    new Directive('internal'),
//        new Directive('expires', [new Param('-1')]),
//    new Directive('proxy_pass', [new Param('http://172.17.0.1:7777')]),
//    new Directive('proxy_bind', [new Param('$server_addr')]),
//    new Directive('proxy_redirect', [new Param('http://$host'), new Param('https://$host')]),
//    new Directive('proxy_set_header', [new Param('Content-Length'), new Literal("")]),
//    new Directive('proxy_pass_request_body', [new Param('off')]),
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $proxy_pass = $input->getOption('proxy_pass');

    }
}
