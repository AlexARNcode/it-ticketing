<?php

namespace App\Services\Sla;

use App\Models\SlaPolicy;
use App\Models\Ticket;
use Illuminate\Support\Carbon;
use RuntimeException;

class ResolveSlaForTicket
{
    public function handle(Ticket $ticket): void
    {
        if ($ticket->first_response_due_at || $ticket->resolution_due_at) {
            return;
        }

        $policy = $this->findPolicyForTicket($ticket);

        $now = Carbon::now();

        $firstResponseDueAt = $now->copy()->addMinutes($policy->first_response_minutes);
        $resolutionDueAt = $now->copy()->addMinutes($policy->resolution_minutes);

        $ticket->update([
            'first_response_due_at' => $firstResponseDueAt,
            'resolution_due_at' => $resolutionDueAt,
        ]);
    }

    protected function findPolicyForTicket(Ticket $ticket): SlaPolicy
    {
        $policy = SlaPolicy::query()
            ->where('organization_id', $ticket->organization_id)
            ->where('priority', $ticket->priority)
            ->first();

        if (!$policy) {
            throw new RuntimeException(
                "No SLA policy found for organization {$ticket->organization_id} and priority {$ticket->priority}"
            );
        }

        return $policy;
    }
}
