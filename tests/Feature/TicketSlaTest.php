<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\SlaPolicy;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketSlaTest extends TestCase
{
    use RefreshDatabase;

    public function test_sla_is_applied_when_ticket_is_created(): void
    {
        $organization = Organization::factory()->create();

        $user = User::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $before = now();

        $ticket = Ticket::create([
            'organization_id' => $organization->id,
            'created_by' => $user->id,
            'assigned_to' => null,
            'title' => 'Printer is on fire',
            'description' => 'Send help',
            'priority' => 'high',
            'status' => 'open',
        ]);

        $ticket->refresh();

        $this->assertNotNull($ticket->first_response_due_at);
        $this->assertNotNull($ticket->resolution_due_at);

        $this->assertTrue(
            $ticket->first_response_due_at->greaterThan($before)
        );

        $this->assertTrue(
            $ticket->resolution_due_at->greaterThan($ticket->first_response_due_at)
        );
    }
}
