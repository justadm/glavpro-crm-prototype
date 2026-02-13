<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Site\View\Companies;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;

final class HtmlView extends BaseHtmlView
{
    public array $items = [];
    public string $search = '';

    public function display($tpl = null): void
    {
        $app = Factory::getApplication();
        $user = $app->getIdentity();
        if ($user->guest) {
            $app->enqueueMessage('Для просмотра CRM нужно войти.', 'warning');
            $return = base64_encode('index.php?option=com_glavpro_crm&view=companies');
            $this->setLayout('default');
            $app->redirect(Route::_('index.php?option=com_users&view=login&return=' . $return, false));
            return;
        }

        $this->search = (string) $app->input->getString('filter_search', '');
        $this->items = $this->get('Items');
        parent::display($tpl);
    }
}
