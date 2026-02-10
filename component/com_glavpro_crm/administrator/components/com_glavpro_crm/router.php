<?php

declare(strict_types=1);

use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;

class GlavproCrmRouter extends RouterView
{
    public function __construct($app = null, $menu = null)
    {
        parent::__construct($app, $menu);
        $this->registerView('company');

        $this->attachRule(new MenuRules($this));
        $this->attachRule(new StandardRules($this));
        $this->attachRule(new NomenuRules($this));
    }
}
