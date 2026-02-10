<?php

declare(strict_types=1);

namespace Glavpro\Domain;

final class StageCodes
{
    public const ICE = 'Ice';
    public const TOUCHED = 'Touched';
    public const AWARE = 'Aware';
    public const INTERESTED = 'Interested';
    public const DEMO_PLANNED = 'demo_planned';
    public const DEMO_DONE = 'Demo_done';
    public const COMMITTED = 'Committed';
    public const CUSTOMER = 'Customer';
    public const ACTIVATED = 'Activated';
    public const NULL = 'Null';

    public const ALL = [
        self::ICE,
        self::TOUCHED,
        self::AWARE,
        self::INTERESTED,
        self::DEMO_PLANNED,
        self::DEMO_DONE,
        self::COMMITTED,
        self::CUSTOMER,
        self::ACTIVATED,
        self::NULL,
    ];
}
