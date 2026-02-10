<?php

declare(strict_types=1);

namespace Glavpro\Domain;

final class StageRules
{
    /**
     * Условия перехода на следующую стадию.
     * Ключ: текущая стадия, значение: требуемое событие.
     */
    public const NEXT_STAGE_EVENT = [
        StageCodes::TOUCHED => EventTypes::LPR_CALL_DONE,
        StageCodes::AWARE => EventTypes::DISCOVERY_FILLED,
        StageCodes::INTERESTED => EventTypes::DEMO_SCHEDULED,
        StageCodes::DEMO_PLANNED => EventTypes::DEMO_DONE,
        StageCodes::DEMO_DONE => EventTypes::INVOICE_ISSUED,
        StageCodes::COMMITTED => EventTypes::PAYMENT_RECEIVED,
        StageCodes::CUSTOMER => EventTypes::FIRST_CERTIFICATE_ISSUED,
    ];

    /**
     * Следующая стадия по цепочке.
     */
    public const NEXT_STAGE = [
        StageCodes::ICE => StageCodes::TOUCHED,
        StageCodes::TOUCHED => StageCodes::AWARE,
        StageCodes::AWARE => StageCodes::INTERESTED,
        StageCodes::INTERESTED => StageCodes::DEMO_PLANNED,
        StageCodes::DEMO_PLANNED => StageCodes::DEMO_DONE,
        StageCodes::DEMO_DONE => StageCodes::COMMITTED,
        StageCodes::COMMITTED => StageCodes::CUSTOMER,
        StageCodes::CUSTOMER => StageCodes::ACTIVATED,
    ];

    /**
     * Доступные действия на стадии.
     */
    public const AVAILABLE_ACTIONS = [
        StageCodes::TOUCHED => [
            'call',
            'comment_after_call',
            'fill_discovery',
        ],
        StageCodes::AWARE => [
            'fill_discovery',
        ],
        StageCodes::INTERESTED => [
            'schedule_demo',
        ],
        StageCodes::DEMO_PLANNED => [
            'do_demo_via_link',
        ],
        StageCodes::DEMO_DONE => [
            'create_application',
            'send_commercial_offer',
            'issue_invoice',
        ],
        StageCodes::COMMITTED => [
            'mark_payment_received',
        ],
        StageCodes::CUSTOMER => [
            'issue_first_certificate',
        ],
        StageCodes::ACTIVATED => [],
    ];
}
