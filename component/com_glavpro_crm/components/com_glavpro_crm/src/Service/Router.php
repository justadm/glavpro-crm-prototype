<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Site\Service;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Menu\AbstractMenu;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Minimal router so the SEF system plugin can build a non-empty route.
 * Without this, Joomla may redirect unknown component URLs back to Home.
 */
final class Router extends RouterView
{
    public function __construct(SiteApplication $app, AbstractMenu $menu)
    {
        $companies = new RouterViewConfiguration('companies');
        $this->registerView($companies);

        $company = new RouterViewConfiguration('company');
        $company->setKey('id');
        $this->registerView($company);

        parent::__construct($app, $menu);

        $this->attachRule(new MenuRules($this));
        $this->attachRule(new StandardRules($this));
        $this->attachRule(new NomenuRules($this));
    }
}

