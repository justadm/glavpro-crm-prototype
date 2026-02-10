<?php

declare(strict_types=1);

use Glavpro\Domain\EventTypes;
use Glavpro\Domain\StageCodes;
use Glavpro\Domain\StageEngine;
use PHPUnit\Framework\TestCase;

final class StageEngineTest extends TestCase
{
    public function testTouchedActionsBeforeCall(): void
    {
        $engine = new StageEngine();
        $events = [];

        $this->assertSame(['call'], $engine->getAvailableActions(StageCodes::TOUCHED, $events));
    }

    public function testAdvanceFromIceWithAttemptContact(): void
    {
        $engine = new StageEngine();
        $events = [
            ['type' => EventTypes::ATTEMPT_CONTACT],
        ];

        $this->assertTrue($engine->canAdvance(StageCodes::ICE, $events));
        $this->assertSame(StageCodes::TOUCHED, $engine->getNextStage(StageCodes::ICE, $events));
        $this->assertSame(['call'], $engine->getAvailableActions(StageCodes::ICE, $events));
    }

    public function testTouchedActionsAfterCall(): void
    {
        $engine = new StageEngine();
        $events = [
            ['type' => EventTypes::LPR_CALL_DONE],
        ];

        $this->assertSame(['comment_after_call', 'fill_discovery'], $engine->getAvailableActions(StageCodes::TOUCHED, $events));
    }

    public function testAdvanceFromTouchedRequiresLprCall(): void
    {
        $engine = new StageEngine();

        $this->assertFalse($engine->canAdvance(StageCodes::TOUCHED, []));
        $this->assertTrue($engine->canAdvance(StageCodes::TOUCHED, [
            ['type' => EventTypes::LPR_CALL_DONE],
        ]));
    }

    public function testAdvanceFromAwareWithDiscovery(): void
    {
        $engine = new StageEngine();
        $events = [
            ['type' => EventTypes::DISCOVERY_FILLED],
        ];

        $this->assertTrue($engine->canAdvance(StageCodes::AWARE, $events));
        $this->assertSame(StageCodes::INTERESTED, $engine->getNextStage(StageCodes::AWARE, $events));
    }

    public function testAdvanceFromInterestedWithDemoScheduled(): void
    {
        $engine = new StageEngine();
        $events = [
            ['type' => EventTypes::DEMO_SCHEDULED],
        ];

        $this->assertTrue($engine->canAdvance(StageCodes::INTERESTED, $events));
        $this->assertSame(StageCodes::DEMO_PLANNED, $engine->getNextStage(StageCodes::INTERESTED, $events));
    }

    public function testAdvanceFromDemoPlannedWithDemoDone(): void
    {
        $engine = new StageEngine();
        $events = [
            ['type' => EventTypes::DEMO_DONE],
        ];

        $this->assertTrue($engine->canAdvance(StageCodes::DEMO_PLANNED, $events));
        $this->assertSame(StageCodes::DEMO_DONE, $engine->getNextStage(StageCodes::DEMO_PLANNED, $events));
    }

    public function testDemoDoneRequiresRecentDemo(): void
    {
        $engine = new StageEngine();
        $events = [
            ['type' => EventTypes::DEMO_DONE, 'created_at' => new DateTimeImmutable('-90 days')],
            ['type' => EventTypes::INVOICE_ISSUED],
        ];

        $this->assertFalse($engine->canAdvance(StageCodes::DEMO_DONE, $events));
        $this->assertSame([], $engine->getAvailableActions(StageCodes::DEMO_DONE, $events));
    }

    public function testAdvanceFromDemoDoneWithInvoiceAndRecentDemo(): void
    {
        $engine = new StageEngine();
        $events = [
            ['type' => EventTypes::DEMO_DONE, 'created_at' => new DateTimeImmutable('-10 days')],
            ['type' => EventTypes::INVOICE_ISSUED],
        ];

        $this->assertTrue($engine->canAdvance(StageCodes::DEMO_DONE, $events));
        $this->assertSame(StageCodes::COMMITTED, $engine->getNextStage(StageCodes::DEMO_DONE, $events));
        $this->assertSame(['create_application', 'send_commercial_offer', 'issue_invoice'], $engine->getAvailableActions(StageCodes::DEMO_DONE, $events));
    }

    public function testAdvanceFromCommittedWithPayment(): void
    {
        $engine = new StageEngine();
        $events = [
            ['type' => EventTypes::PAYMENT_RECEIVED],
        ];

        $this->assertTrue($engine->canAdvance(StageCodes::COMMITTED, $events));
        $this->assertSame(StageCodes::CUSTOMER, $engine->getNextStage(StageCodes::COMMITTED, $events));
        $this->assertSame(['mark_payment_received'], $engine->getAvailableActions(StageCodes::COMMITTED, $events));
    }

    public function testAdvanceFromCustomerWithFirstCertificate(): void
    {
        $engine = new StageEngine();
        $events = [
            ['type' => EventTypes::FIRST_CERTIFICATE_ISSUED],
        ];

        $this->assertTrue($engine->canAdvance(StageCodes::CUSTOMER, $events));
        $this->assertSame(StageCodes::ACTIVATED, $engine->getNextStage(StageCodes::CUSTOMER, $events));
        $this->assertSame(['issue_first_certificate'], $engine->getAvailableActions(StageCodes::CUSTOMER, $events));
    }
}
