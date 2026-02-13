<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Administrator\View\Company;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

final class HtmlView extends BaseHtmlView
{
    public object $company;
    public array $events = [];
    public array $actions = [];
    public string $script = '';

    public function display($tpl = null): void
    {
        $app = Factory::getApplication();
        $companyId = (int) $app->input->getInt('id');

        /** @var \Glavpro\Component\GlavproCrm\Administrator\Model\CompanyModel $model */
        $model = $this->getModel();
        $company = $model->getCompany($companyId);

        if ($company === null) {
            $app->enqueueMessage('Компания не найдена. Создайте демо-компанию кнопкой ниже.', 'warning');
            $this->company = (object) ['id' => 0, 'name' => 'Demo Company', 'stage_code' => 'Ice'];
            $this->events = [];
            $this->actions = [];
            $this->script = 'Скрипт: начните контакт с компанией.';
            parent::display($tpl);
            return;
        }

        $this->company = $company;
        $this->events = $model->getEvents($companyId);
        $this->actions = $model->getAvailableActions($companyId);
        $this->script = $this->buildScript((string) $company->stage_code);

        parent::display($tpl);
    }

    private function buildScript(string $stage): string
    {
        return match ($stage) {
            'Touched' => 'Скрипт: совершите звонок ЛПР, после ответа заполните комментарий и дискавери.',
            'Aware' => 'Скрипт: заполните форму дискавери, чтобы перейти дальше.',
            'Interested' => 'Скрипт: запланируйте демо с датой и временем.',
            'demo_planned' => 'Скрипт: проведите демо по ссылке и зафиксируйте событие.',
            'Demo_done' => 'Скрипт: подготовьте заявку, отправьте КП и выставьте счёт.',
            'Committed' => 'Скрипт: дождитесь оплаты для перехода.',
            'Customer' => 'Скрипт: выдайте первое удостоверение.',
            'Activated' => 'Скрипт: компания активирована.',
            default => 'Скрипт: начните контакт с компанией.',
        };
    }
}
