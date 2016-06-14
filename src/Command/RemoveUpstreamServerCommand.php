<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 10.06.16
 * Time: 14:31
 */
namespace Madkom\NginxConfigurator\Command;

/**
 * Class RemoveUpstreamServerCommand
 * @package Madkom\NginxConfigurator\Command
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class RemoveUpstreamServerCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('upstream:server:remove');
        $this->setDescription("Removes server directive from upstream context and configuration");
    }
}
