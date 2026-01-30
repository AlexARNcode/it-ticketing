<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Models\Organization;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\TicketPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected TicketPolicy $policy;
    protected Organization $organization;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new TicketPolicy();
        $this->organization = Organization::factory()->create(['name' => 'Test Org']);
    }

    public function test_viewAny_all_roles_allowed()
    {
        foreach (UserRole::cases() as $role) {
            $user = User::factory()->make([
                'name' => 'test', 
                'email' => 'test@example.com', 
                'password' => 'password', 
                'organization_id' => $this->organization->id, 
                'role' => $role->value
            ]);
                
            $this->assertTrue($this->policy->viewAny($user));
        }
    }

 
    public function test_update_admin_or_tech_can_update_any_ticket()
    {
        $user = User::factory()->make([
                'name' => 'test', 
                'email' => 'test@example.com', 
                'password' => 'password', 
                'organization_id' => $this->organization->id, 
                'role' => UserRole::USER->value,
        ]);

        $ticket = new Ticket(['created_by' => $user->id]);

        $admin = User::factory()->make([
                'name' => 'test', 
                'email' => 'test@example.com', 
                'password' => 'password', 
                'organization_id' => $this->organization->id, 
                'role' => UserRole::ADMIN->value,
        ]);

        $tech  = User::factory()->make([
                'name' => 'test', 
                'email' => 'test@example.com', 
                'password' => 'password', 
                'organization_id' => $this->organization->id, 
                'role' => UserRole::TECH->value,
        ]);

        $this->assertTrue($this->policy->update($admin, $ticket));
        $this->assertTrue($this->policy->update($tech, $ticket));
    }

    
    public function test_update_regular_user_can_update_own_ticket_only()
    {
        $user = User::factory()->create([
                'name' => 'user', 
                'email' => 'test@example.com', 
                'password' => 'password', 
                'organization_id' => $this->organization->id, 
                'role' => UserRole::USER->value,
        ]);

        $otherUser = User::factory()->create([
                'name' => 'otherUser', 
                'email' => 'test2@example.com', 
                'password' => 'password', 
                'organization_id' => $this->organization->id, 
                'role' => UserRole::USER->value,
        ]);

        $userTicket = new Ticket(['created_by' => $user->id]);
        $otherTicket = new Ticket(['created_by' => $otherUser->id]);

        $this->assertTrue($this->policy->update($user, $userTicket));
        $this->assertFalse($this->policy->update($user, $otherTicket));
    }
    
    public function test_delete_only_admin_can_delete()
    {
        $user = User::factory()->create([
                'name' => 'user', 
                'email' => 'test@example.com', 
                'password' => 'password', 
                'organization_id' => $this->organization->id, 
                'role' => UserRole::USER->value,
        ]);

         $admin = User::factory()->create([
                'name' => 'admin', 
                'email' => 'admin@example.com', 
                'password' => 'password', 
                'organization_id' => $this->organization->id, 
                'role' => UserRole::ADMIN->value,
        ]);

        $ticket = new Ticket(['created_by' => $user->id]);

        $this->assertTrue($this->policy->delete($admin, $ticket));
        $this->assertFalse($this->policy->delete($user, $ticket));
    }  
}
