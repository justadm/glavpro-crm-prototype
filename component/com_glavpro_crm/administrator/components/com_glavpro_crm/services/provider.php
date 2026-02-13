<?php

/**
 * Service provider for com_glavpro_crm.
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Glavpro\Component\GlavproCrm\Administrator\Extension\GlavproCrmComponent;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class () implements ServiceProviderInterface {
    public function register(Container $container)
    {
        $container->registerServiceProvider(new MVCFactory('\\Glavpro\\Component\\GlavproCrm'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\Glavpro\\Component\\GlavproCrm'));
        $container->registerServiceProvider(new RouterFactory('\\Glavpro\\Component\\GlavproCrm'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new GlavproCrmComponent($container->get(ComponentDispatcherFactoryInterface::class));
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                $component->setRouterFactory($container->get(RouterFactoryInterface::class));

                return $component;
            }
        );
    }
};
