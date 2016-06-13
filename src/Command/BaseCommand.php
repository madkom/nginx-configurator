<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 10.06.16
 * Time: 14:01
 */
namespace Madkom\NginxConfigurator\Command;

use Exception;
use Madkom\NginxConfigurator\Builder;
use Madkom\NginxConfigurator\Node\Node;
use Madkom\NginxConfigurator\Node\RootNode;
use Madkom\NginxConfigurator\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class BaseCommand
 * @package Madkom\NginxConfigurator\Command
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
abstract class BaseCommand extends Command
{
    protected function configure()
    {
        $this->addOption('file', 'f', InputOption::VALUE_OPTIONAL, 'Output filename', 'php://stdout');
    }

    /**
     * @param InputInterface $input
     * @return RootNode
     * @throws Exception
     */
    protected function getConfig(InputInterface $input) : RootNode
    {
        $filename = $input->getOption('file');
        if ($filename != 'php://stdout' && !file_exists($filename)) {
            @touch($filename);
        }
        if ($filename != 'php://stdout' && file_exists($filename)) {
            if (!is_writable($filename)) {
                throw new Exception('Given filename is not writable!');
            }
        }
        if ($filename != 'php://stdout' && file_exists($filename)) {
            $parser = new Parser();
            return $parser->parseFile($filename);
        }

        return new RootNode();
    }
}
