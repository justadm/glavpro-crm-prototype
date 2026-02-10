<?php

declare(strict_types=1);

namespace Glavpro\Administrator\Component\GlavproCrm\View\Companies;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

final class HtmlView extends BaseHtmlView
{
    public array $items = [];
    public $pagination;

    public function display($tpl = null): void
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        parent::display($tpl);
    }
}
