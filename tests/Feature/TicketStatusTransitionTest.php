<?php

namespace Tests\Feature;

use App\Enums\TicketStatus;
use App\Models\Organization;
use App\Models\SlaPolicy;
use App\Models\Ticket;
use App\Models\User;
use App\Services\Tickets\TransitionTicketStatus;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketStatusTransitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_open_to_in_progress_is_allowed(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id]);

        $ticket = Ticket::create([
            'organization_id' => $organization->id,
            'created_by' => $user->id,
            'assigned_to' => null,
            'title' => 'Test ticket',
            'description' => 'Transition test',
            'priority' => 'normal',
            'status' => TicketStatus::OPEN->value,
        ]);

        $service = new TransitionTicketStatus();
        $service->handle($ticket, TicketStatus::IN_PROGRESS);

        $ticket->refresh();

        $this->assertTrue($ticket->status === TicketStatus::IN_PROGRESS);
    }

    public function test_open_to_resolved_is_forbidden(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id]);

        $ticket = Ticket::create([
            'organization_id' => $organization->id,
            'created_by' => $user->id,
            'assigned_to' => null,
            'title' => 'Invalid transition',
            'description' => 'Should fail',
            'priority' => 'normal',
            'status' => TicketStatus::OPEN->value,
        ]);

        $this->expectException(DomainException::class);

        $service = new TransitionTicketStatus();
        $service->handle($ticket, TicketStatus::RESOLVED);
    }

    public function test_cannot_change_closed_ticket(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id]);

        $ticket = Ticket::create([
            'organization_id' => $organization->id,
            'created_by' => $user->id,
            'assigned_to' => null,
            'title' => 'Closed ticket',
            'description' => 'Should be immutable',
            'priority' => 'normal',
            'status' => TicketStatus::CLOSED->value,
        ]);

        $this->expectException(DomainException::class);

        $service = new TransitionTicketStatus();
        $service->handle($ticket, TicketStatus::OPEN);
    }

    public function test_reopening_resolved_ticket_is_allowed(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id]);

        $ticket = Ticket::create([
            'organization_id' => $organization->id,
            'created_by' => $user->id,
            'assigned_to' => null,
            'title' => 'Resolved ticket',
            'description' => 'Reopen test',
            'priority' => 'normal',
            'status' => TicketStatus::RESOLVED->value,
        ]);

        $service = new TransitionTicketStatus();
        $service->handle($ticket, TicketStatus::OPEN);

        $ticket->refresh();

        $this->assertTrue($ticket->status === TicketStatus::OPEN);
    }
}
