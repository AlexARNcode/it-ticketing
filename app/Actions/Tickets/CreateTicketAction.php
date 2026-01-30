<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;
use App\Models\User;

class CreateTicketAction
{
    public function execute(array $data, User $user): Ticket
    {
        return Ticket::create([
            'organization_id' => $user->organization_id,
            'created_by' => $user->id,
            'assigned_to' => null,
            'status' => 'open',
            ...$data,
        ]);
    }
}