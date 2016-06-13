<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 10.06.16
 * Time: 14:25
 */
namespace Madkom\NginxConfigurator\Command;

/**
 * Class RemoveLocationCommand
 * @package Madkom\NginxConfigurator\Command
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class RemoveLocationCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('location:remove');
        $this->setDescription("Remove location context and it's configuration");
    }
}
