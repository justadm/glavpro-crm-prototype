<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Site\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;

final class CompanyModel extends ItemModel
{
    public function getItem($pk = null): object
    {
        $id = (int) ($pk ?? Factory::getApplication()->input->getInt('id'));

        return (object) ['id' => $id];
    }
}
