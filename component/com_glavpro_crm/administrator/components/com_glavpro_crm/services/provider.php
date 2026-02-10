<?php

declare(strict_types=1);

use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new MVCFactory('Glavpro'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('Glavpro'));
        $container->registerServiceProvider(new RouterFactory('Glavpro'));
        $container->registerServiceProvider(new ComponentFactory('\\Glavpro\\Administrator\\Component\\GlavproCrm'));
    }
};
