<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Site\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

final class CompaniesModel extends ListModel
{
    protected function getListQuery()
    {
        $db = Factory::getContainer()->get('DatabaseDriver');

        return $db->getQuery(true)
            ->select(['id', 'name', 'stage_code', 'updated_at'])
            ->from($db->quoteName('#__glavpro_companies'))
            ->order($db->quoteName('updated_at') . ' DESC');
    }
}
