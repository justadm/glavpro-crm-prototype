<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Site\View\Companies;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

final class HtmlView extends BaseHtmlView
{
    public array $items = [];

    public function display($tpl = null): void
    {
        $this->items = $this->get('Items');
        parent::display($tpl);
    }
}
