<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Site\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;

final class CompaniesModel extends ListModel
{
    protected function getListQuery()
    {
        $db = Factory::getContainer()->get('DatabaseDriver');

        $query = $db->getQuery(true)
            ->select(['id', 'name', 'stage_code', 'updated_at'])
            ->from($db->quoteName('#__glavpro_companies'))
            ->order($db->quoteName('updated_at') . ' DESC');

        $search = (string) Factory::getApplication()->input->getString('filter_search', '');
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query
                ->where($db->quoteName('name') . ' LIKE :search')
                ->bind(':search', $like, ParameterType::STRING);
        }

        return $query;
    }
}
