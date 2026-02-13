<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Administrator\Controller;

use Glavpro\Component\GlavproCrm\Domain\EventTypes;
use Glavpro\Component\GlavproCrm\Domain\StageEngine;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Session\Session;
use Joomla\Database\ParameterType;

final class CompanyController extends FormController
{
    public function createDemo(): void
    {
        $app = Factory::getApplication();
        if (!Session::checkToken()) {
            $app->enqueueMessage('Неверный токен формы', 'error');
            $this->setRedirect($app->getRouter()->createUrl('index.php?option=com_glavpro_crm'));
            return;
        }

        $input = $app->input;
        $count = max(1, (int) $input->getInt('count', 1));
        $count = min($count, 50);

        $db = Factory::getContainer()->get('DatabaseDriver');
        $now = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        $firstId = null;

        $db->transactionStart();
        for ($i = 1; $i <= $count; $i++) {
            $name = $count === 1 ? 'Demo Company' : sprintf('Demo Company %d', $i);
            $query = $db->getQuery(true)
                ->insert($db->quoteName('#__glavpro_companies'))
                ->columns([$db->quoteName('name'), $db->quoteName('stage_code'), $db->quoteName('created_at'), $db->quoteName('updated_at')])
                ->values(':name, :stage, :created_at, :updated_at')
                ->bind(':name', $name)
                ->bind(':stage', 'Ice')
                ->bind(':created_at', $now)
                ->bind(':updated_at', $now);

            $db->setQuery($query);
            $db->execute();

            if ($firstId === null) {
                $firstId = (int) $db->insertid();
            }
        }
        $db->transactionCommit();

        if ($count === 1 && $firstId !== null) {
            $this->setRedirect($app->getRouter()->createUrl('index.php?option=com_glavpro_crm&view=company&id=' . $firstId));
            return;
        }

        $this->setRedirect($app->getRouter()->createUrl('index.php?option=com_glavpro_crm&view=companies'));
    }
    public function addEvent(): void
    {
        $app = Factory::getApplication();
        if (!Session::checkToken()) {
            $app->enqueueMessage('Неверный токен формы', 'error');
            $this->setRedirect($app->getRouter()->createUrl('index.php?option=com_glavpro_crm'));
            return;
        }
        $input = $app->input;

        $companyId = (int) $input->getInt('company_id');
        $eventType = (string) $input->getString('event_type');
        $payload = $this->buildPayload($eventType, $input);

        if ($companyId <= 0 || $eventType === '' || !in_array($eventType, EventTypes::ALL, true)) {
            $app->enqueueMessage('Некорректные параметры события', 'error');
            $this->setRedirect($app->getRouter()->createUrl('index.php?option=com_glavpro_crm'));
            return;
        }

        $db = Factory::getContainer()->get('DatabaseDriver');
        $now = (new \DateTimeImmutable())->format('Y-m-d H:i:s');

        $query = $db->getQuery(true)
            ->insert($db->quoteName('#__glavpro_crm_events'))
            ->columns([$db->quoteName('company_id'), $db->quoteName('event_type'), $db->quoteName('payload'), $db->quoteName('created_at')])
            ->values(':company_id, :event_type, :payload, :created_at')
            ->bind(':company_id', $companyId, ParameterType::INTEGER)
            ->bind(':event_type', $eventType)
            ->bind(':payload', $payload)
            ->bind(':created_at', $now);

        $db->setQuery($query);
        $db->execute();

        $company = $this->loadCompany($companyId);
        $events = $this->loadEvents($companyId);

        if ($company !== null) {
            $engine = new StageEngine();
            $nextStage = $engine->getNextStage((string) $company->stage_code, $events);

            if ($nextStage !== null) {
                $update = $db->getQuery(true)
                    ->update($db->quoteName('#__glavpro_companies'))
                    ->set($db->quoteName('stage_code') . ' = :stage')
                    ->set($db->quoteName('updated_at') . ' = :updated')
                    ->where($db->quoteName('id') . ' = :id')
                    ->bind(':stage', $nextStage)
                    ->bind(':updated', $now)
                    ->bind(':id', $companyId, ParameterType::INTEGER);

                $db->setQuery($update);
                $db->execute();
            }
        }

        $this->setRedirect($app->getRouter()->createUrl('index.php?option=com_glavpro_crm&view=company&id=' . $companyId));
    }

    private function loadCompany(int $companyId): ?object
    {
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__glavpro_companies'))
            ->where($db->quoteName('id') . ' = :id')
            ->bind(':id', $companyId, ParameterType::INTEGER);

        $db->setQuery($query);
        $company = $db->loadObject();

        return $company ?: null;
    }

    private function buildPayload(string $eventType, $input): string
    {
        $data = [];

        switch ($eventType) {
            case EventTypes::LPR_CALL_DONE:
                $data['comment'] = (string) $input->getString('comment', '');
                break;
            case EventTypes::DISCOVERY_FILLED:
                $data['discovery'] = (string) $input->getString('discovery', '');
                break;
            case EventTypes::DEMO_SCHEDULED:
                $data['demo_datetime'] = (string) $input->getString('demo_datetime', '');
                break;
            case EventTypes::DEMO_DONE:
                $data['demo_link'] = (string) $input->getString('demo_link', '');
                break;
            case EventTypes::APPLICATION_CREATED:
                $data['application_note'] = (string) $input->getString('application_note', '');
                break;
            case EventTypes::COMMERCIAL_OFFER_SENT:
                $data['offer_note'] = (string) $input->getString('offer_note', '');
                break;
            case EventTypes::INVOICE_ISSUED:
                $data['invoice_number'] = (string) $input->getString('invoice_number', '');
                break;
            case EventTypes::PAYMENT_RECEIVED:
                $data['payment_amount'] = (string) $input->getString('payment_amount', '');
                break;
            case EventTypes::FIRST_CERTIFICATE_ISSUED:
                $data['certificate_number'] = (string) $input->getString('certificate_number', '');
                break;
            default:
                break;
        }

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $json !== false ? $json : '{}';
    }

    /**
     * @return array<int, array{type:string, created_at?:\DateTimeInterface}>
     */
    private function loadEvents(int $companyId): array
    {
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select(['event_type', 'created_at'])
            ->from($db->quoteName('#__glavpro_crm_events'))
            ->where($db->quoteName('company_id') . ' = :id')
            ->order($db->quoteName('created_at') . ' DESC')
            ->bind(':id', $companyId, ParameterType::INTEGER);

        $db->setQuery($query);
        $rows = $db->loadAssocList();

        $events = [];
        foreach ($rows as $row) {
            $events[] = [
                'type' => (string) $row['event_type'],
                'created_at' => new \DateTimeImmutable((string) $row['created_at']),
            ];
        }

        return $events;
    }
}
