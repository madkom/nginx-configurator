<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 10.06.16
 * Time: 14:25
 */
namespace Madkom\NginxConfigurator\Command;

/**
 * Class RemoveUpstreamCommand
 * @package Madkom\NginxConfigurator\Command
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class RemoveUpstreamCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('upstream:remove');
    }
}
