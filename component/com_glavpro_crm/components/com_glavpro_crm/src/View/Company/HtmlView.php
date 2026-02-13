<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Site\View\Company;

// Fallback for environments where the extension namespace autoload is not yet active.
require_once __DIR__ . '/../../Domain/StageCodes.php';

use Glavpro\Component\GlavproCrm\Domain\StageCodes;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;

final class HtmlView extends BaseHtmlView
{
    public object $company;
    public array $events = [];
    public array $actions = [];
    public string $script = '';

    public function display($tpl = null): void
    {
        $app = Factory::getApplication();
        $user = $app->getIdentity();

        if ($user->guest) {
            $app->enqueueMessage('Для просмотра CRM нужно войти.', 'warning');
            $companyId = (int) $app->input->getInt('id');
            $return = base64_encode('index.php?option=com_glavpro_crm&view=company&id=' . $companyId);
            $app->redirect(Route::_('index.php?option=com_users&view=login&return=' . $return, false));
            return;
        }

        $companyId = (int) $app->input->getInt('id');

        /** @var \Glavpro\Component\GlavproCrm\Site\Model\CompanyModel $model */
        $model = $this->getModel();
        $company = $model->getItem($companyId);

        $this->company = $company;
        $this->events = $companyId > 0 ? $model->getEvents($companyId) : [];
        $this->actions = $companyId > 0 ? $model->getAvailableActions($companyId) : [];
        $this->script = $this->buildScript((string) ($company->stage_code ?? StageCodes::ICE));

        parent::display($tpl);
    }

    private function buildScript(string $stage): string
    {
        return match ($stage) {
            StageCodes::TOUCHED => 'Скрипт: совершите звонок ЛПР, после ответа заполните комментарий и дискавери.',
            StageCodes::AWARE => 'Скрипт: заполните форму дискавери, чтобы перейти дальше.',
            StageCodes::INTERESTED => 'Скрипт: запланируйте демо с датой и временем.',
            StageCodes::DEMO_PLANNED => 'Скрипт: проведите демо по ссылке и зафиксируйте событие.',
            StageCodes::DEMO_DONE => 'Скрипт: выставьте счёт (или заведите заявку/отправьте КП).',
            StageCodes::COMMITTED => 'Скрипт: дождитесь оплаты для перехода.',
            StageCodes::CUSTOMER => 'Скрипт: выдайте первое удостоверение.',
            StageCodes::ACTIVATED => 'Скрипт: компания активирована.',
            default => 'Скрипт: начните контакт с компанией.',
        };
    }
}

