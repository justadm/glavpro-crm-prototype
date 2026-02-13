<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Site\Model;

// Fallback for environments where the extension namespace autoload is not yet active.
require_once __DIR__ . '/../Domain/StageCodes.php';
require_once __DIR__ . '/../Domain/EventTypes.php';
require_once __DIR__ . '/../Domain/StageRules.php';
require_once __DIR__ . '/../Domain/StageEngine.php';

use Glavpro\Component\GlavproCrm\Domain\StageEngine;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\Database\ParameterType;

final class CompanyModel extends ItemModel
{
    public function getItem($pk = null): object
    {
        $id = (int) ($pk ?? Factory::getApplication()->input->getInt('id'));
        $db = Factory::getContainer()->get('DatabaseDriver');

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__glavpro_companies'))
            ->where($db->quoteName('id') . ' = :id')
            ->bind(':id', $id, ParameterType::INTEGER);

        $db->setQuery($query);
        $company = $db->loadObject();

        return $company ?: (object) ['id' => 0, 'name' => '', 'stage_code' => 'Ice'];
    }

    /**
     * @return array<int, array{type:string, created_at?:\DateTimeInterface, payload:?string}>
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
        $rows = $db->loadAssocList() ?: [];

        $events = [];
        foreach ($rows as $row) {
            $events[] = [
                'type' => (string) ($row['event_type'] ?? ''),
                'created_at' => new \DateTimeImmutable((string) ($row['created_at'] ?? 'now')),
                'payload' => isset($row['payload']) ? (string) $row['payload'] : null,
            ];
        }

        return $events;
    }

    /**
     * @return string[]
     */
    public function getAvailableActions(int $companyId): array
    {
        $company = $this->getItem($companyId);
        if ((int) ($company->id ?? 0) <= 0) {
            return [];
        }

        $events = $this->getEvents($companyId);
        $engine = new StageEngine();

        return $engine->getAvailableActions((string) $company->stage_code, $events);
    }
}
