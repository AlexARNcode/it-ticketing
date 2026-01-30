<?php

namespace Tests\Unit;

use App\Actions\Tickets\CreateTicketAction;
use App\Models\Organization;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTicketActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_ticket_with_correct_default_values()
    {
        // Arrange
        $organization = Organization::factory()->create(['name' => 'Test Organization']);

        $user = User::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $data = [
            'title' => 'Bug login',
            'description' => 'Cannot log in',
            'priority' => 'normal',
            'status' => 'open',
        ];

        $action = new CreateTicketAction();

        // Act
        $ticket = $action->execute($data, $user);

        // Assert
        $this->assertInstanceOf(Ticket::class, $ticket);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'organization_id' => $organization->id,
            'created_by' => $user->id,
            'assigned_to' => null,
            'status' => 'open',
            'priority' => 'normal',
            'title' => 'Bug login',
            'description' => 'Cannot log in',
        ]);
    }
}
