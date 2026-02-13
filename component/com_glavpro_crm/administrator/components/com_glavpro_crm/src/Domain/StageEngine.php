<?php

declare(strict_types=1);

namespace Glavpro\Component\GlavproCrm\Domain;

use DateTimeImmutable;
use DateTimeInterface;

final class StageEngine
{
    /**
     * @param array<int, array{type:string, created_at?:DateTimeInterface}> $events
     * @return string[]
     */
    public function getAvailableActions(string $stage, array $events): array
    {
        if ($stage === StageCodes::TOUCHED) {
            // В стадии Touched сначала фиксируем попытки контакта, затем — разговор с ЛПР.
            // Иначе пользователь застревает: "comment_after_call" (lpr_call_done) никогда не появляется.
            if ($this->hasEvent($events, EventTypes::ATTEMPT_CONTACT)) {
                return ['comment_after_call', 'fill_discovery'];
            }

            return ['call', 'comment_after_call', 'fill_discovery'];
        }

        if ($stage === StageCodes::DEMO_DONE && !$this->hasRecentDemo($events, 60)) {
            return [];
        }

        return StageRules::AVAILABLE_ACTIONS[$stage] ?? [];
    }

    /**
     * @param array<int, array{type:string, created_at?:DateTimeInterface}> $events
     */
    public function canAdvance(string $stage, array $events): bool
    {
        $requiredEvent = StageRules::NEXT_STAGE_EVENT[$stage] ?? null;
        if ($requiredEvent === null) {
            return false;
        }

        if ($stage === StageCodes::DEMO_DONE) {
            return $this->hasRecentDemo($events, 60) && $this->hasEvent($events, $requiredEvent);
        }

        return $this->hasEvent($events, $requiredEvent);
    }

    /**
     * @param array<int, array{type:string, created_at?:DateTimeInterface}> $events
     */
    public function getNextStage(string $stage, array $events): ?string
    {
        if (!$this->canAdvance($stage, $events)) {
            return null;
        }

        return StageRules::NEXT_STAGE[$stage] ?? null;
    }

    /**
     * @param array<int, array{type:string, created_at?:DateTimeInterface}> $events
     */
    private function hasEvent(array $events, string $eventType): bool
    {
        foreach ($events as $event) {
            if (($event['type'] ?? null) === $eventType) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<int, array{type:string, created_at?:DateTimeInterface}> $events
     */
    private function hasRecentDemo(array $events, int $days): bool
    {
        $cutoff = (new DateTimeImmutable())->modify(sprintf('-%d days', $days));

        foreach ($events as $event) {
            if (($event['type'] ?? null) !== EventTypes::DEMO_DONE) {
                continue;
            }

            $createdAt = $event['created_at'] ?? null;
            if ($createdAt instanceof DateTimeInterface && $createdAt >= $cutoff) {
                return true;
            }
        }

        return false;
    }
}
