<?php

declare(strict_types=1);

namespace Glavpro\Administrator\Component\GlavproCrm\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

final class CompaniesModel extends ListModel
{
    protected function getListQuery()
    {
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select(['id', 'name', 'stage_code', 'created_at', 'updated_at'])
            ->from($db->quoteName('#__glavpro_companies'))
            ->order($db->quoteName('updated_at') . ' DESC');

        return $query;
    }
}
