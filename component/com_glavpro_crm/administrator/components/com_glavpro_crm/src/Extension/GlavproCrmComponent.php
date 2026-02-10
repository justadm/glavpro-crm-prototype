<?php

declare(strict_types=1);

namespace Glavpro\Administrator\Component\GlavproCrm\Extension;

use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\DI\Container;

final class GlavproCrmComponent extends MVCComponent implements BootableExtensionInterface
{
    public function boot(Container $container): void
    {
        $container->registerServiceProvider(new MVCFactory('Glavpro'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('Glavpro'));
        $container->registerServiceProvider(new RouterFactory('Glavpro'));
    }
}
