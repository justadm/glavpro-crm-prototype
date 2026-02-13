<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Administrator\Model;

use Glavpro\Component\GlavproCrm\Domain\StageEngine;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\Database\ParameterType;

final class CompanyModel extends ItemModel
{
    public function getItem($pk = null): object
    {
        $id = (int) ($pk ?? Factory::getApplication()->input->getInt('id'));
        $company = $this->getCompany($id);

        // ItemModelInterface requires an object return type; keep UI logic in the view.
        return $company ?? (object) ['id' => 0, 'name' => '', 'stage_code' => 'Ice'];
    }

    public function getCompany(int $companyId): ?object
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

    /**
     * @return array<int, array{type:string, created_at?:\DateTimeInterface}>
     */
    public function getEvents(int $companyId): array
    {
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select(['event_type', 'created_at', 'payload'])
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
                'payload' => $row['payload'],
            ];
        }

        return $events;
    }

    /**
     * @return string[]
     */
    public function getAvailableActions(int $companyId): array
    {
        $company = $this->getCompany($companyId);
        if ($company === null) {
            return [];
        }

        $events = $this->getEvents($companyId);
        $engine = new StageEngine();

        return $engine->getAvailableActions((string) $company->stage_code, $events);
    }

    public function getNextStage(int $companyId): ?string
    {
        $company = $this->getCompany($companyId);
        if ($company === null) {
            return null;
        }

        $events = $this->getEvents($companyId);
        $engine = new StageEngine();

        return $engine->getNextStage((string) $company->stage_code, $events);
    }
}
