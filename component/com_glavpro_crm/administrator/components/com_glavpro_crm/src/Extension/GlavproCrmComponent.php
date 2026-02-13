<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Administrator\Extension;

use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\MVCComponent;

final class GlavproCrmComponent extends MVCComponent implements RouterServiceInterface
{
    use RouterServiceTrait;
}
