<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Site\View\Company;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

final class HtmlView extends BaseHtmlView
{
    public object $company;
    public array $events = [];

    public function display($tpl = null): void
    {
        $app = Factory::getApplication();
        $companyId = (int) $app->input->getInt('id');

        /** @var \Glavpro\Component\GlavproCrm\Site\Model\CompanyModel $model */
        $model = $this->getModel();
        $company = $model->getItem($companyId);

        $this->company = $company;
        $this->events = $companyId > 0 ? $model->getEvents($companyId) : [];

        parent::display($tpl);
    }
}
