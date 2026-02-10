<?php

declare(strict_types=1);

namespace Glavpro\Domain;

final class EventTypes
{
    public const ATTEMPT_CONTACT = 'attempt_contact';
    public const LPR_CALL_DONE = 'lpr_call_done';
    public const DISCOVERY_FILLED = 'discovery_filled';
    public const DEMO_SCHEDULED = 'demo_scheduled';
    public const DEMO_DONE = 'demo_done';
    public const APPLICATION_CREATED = 'application_created';
    public const COMMERCIAL_OFFER_SENT = 'commercial_offer_sent';
    public const INVOICE_ISSUED = 'invoice_issued';
    public const PAYMENT_RECEIVED = 'payment_received';
    public const FIRST_CERTIFICATE_ISSUED = 'first_certificate_issued';

    public const ALL = [
        self::ATTEMPT_CONTACT,
        self::LPR_CALL_DONE,
        self::DISCOVERY_FILLED,
        self::DEMO_SCHEDULED,
        self::DEMO_DONE,
        self::APPLICATION_CREATED,
        self::COMMERCIAL_OFFER_SENT,
        self::INVOICE_ISSUED,
        self::PAYMENT_RECEIVED,
        self::FIRST_CERTIFICATE_ISSUED,
    ];
}
