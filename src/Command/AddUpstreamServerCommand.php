<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 10.06.16
 * Time: 14:31
 */
namespace Madkom\NginxConfigurator\Command;

/**
 * Class AddUpstreamServerCommand
 * @package Madkom\NginxConfigurator\Command
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class AddUpstreamServerCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('upstream:server:add');
        $this->setDescription("Adds server directive to upstream context and configuration");
    }
}
