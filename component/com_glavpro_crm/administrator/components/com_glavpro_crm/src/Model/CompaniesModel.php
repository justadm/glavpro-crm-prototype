<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;

final class CompaniesModel extends ListModel
{
    protected function populateState($ordering = 'updated_at', $direction = 'DESC'): void
    {
        $app = Factory::getApplication();
        $search = (string) $app->input->getString('filter_search', '');
        $this->setState('filter.search', $search);

        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select(['id', 'name', 'stage_code', 'created_at', 'updated_at'])
            ->from($db->quoteName('#__glavpro_companies'));

        $search = $this->getState('filter.search');
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where($db->quoteName('name') . ' LIKE :search')
                ->bind(':search', $like, ParameterType::STRING);
        }

        $query->order($db->quoteName('updated_at') . ' DESC');

        return $query;
    }
}
