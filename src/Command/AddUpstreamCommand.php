<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 10.06.16
 * Time: 14:25
 */
namespace Madkom\NginxConfigurator\Command;

/**
 * Class AddUpstreamCommand
 * @package Madkom\NginxConfigurator\Command
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class AddUpstreamCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('upstream:add');
    }
}
