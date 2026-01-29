<?php

namespace App\Services\Tickets;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use DomainException;

class TransitionTicketStatus
{
    public function handle(Ticket $ticket, TicketStatus $to): void
    {
        $current = $ticket->status;

        if (!$current instanceof TicketStatus) {
            throw new DomainException('Invalid ticket status');
        }

        if ($current === TicketStatus::CLOSED) {
            throw new DomainException('Cannot change status of a closed ticket.');
        }

        if ($current === $to) {
            return;
        }

        match ($current) {
            TicketStatus::OPEN => $this->validateOpenTransitions($to),
            TicketStatus::IN_PROGRESS => $this->validateInProgressTransitions($to),
            TicketStatus::RESOLVED => $this->validateResolvedTransitions($to),
            default => throw new DomainException('Invalid status transition.'),
        };

        $ticket->status = $to;
        $ticket->save();
    }

    private function validateOpenTransitions(TicketStatus $to): void
    {
        if ($to !== TicketStatus::IN_PROGRESS) {
            throw new DomainException('Invalid transition from OPEN to ' . $to->value);
        }
    }

    private function validateInProgressTransitions(TicketStatus $to): void
    {
        if ($to !== TicketStatus::RESOLVED) {
            throw new DomainException('Invalid transition from IN_PROGRESS to ' . $to->value);
        }
    }

    private function validateResolvedTransitions(TicketStatus $to): void
    {
        if ($to !== TicketStatus::OPEN) {
            throw new DomainException('Invalid transition from RESOLVED to ' . $to->value);
        }
    }
}
