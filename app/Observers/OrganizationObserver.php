<?php

namespace App\Observers;

use App\Models\Organization;
use App\Models\SlaPolicy;

class OrganizationObserver
{
    /**
     * Handle the Organization "created" event.
     */
    public function created(Organization $organization): void
    {
        if ($organization->slaPolicies()->exists()) {
            return;
        }

        SlaPolicy::insert([
            [
                'organization_id' => $organization->id,
                'priority' => 'low',
                'first_response_minutes' => 720,   // 12h
                'resolution_minutes' => 4320,      // 72h
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organization->id,
                'priority' => 'normal',
                'first_response_minutes' => 360,   // 6h
                'resolution_minutes' => 2880,      // 48h
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organization->id,
                'priority' => 'high',
                'first_response_minutes' => 120,   // 2h
                'resolution_minutes' => 1440,      // 24h
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organization->id,
                'priority' => 'urgent',
                'first_response_minutes' => 30,    // 30 min
                'resolution_minutes' => 240,       // 4h
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Handle the Organization "updated" event.
     */
    public function updated(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Organization "deleted" event.
     */
    public function deleted(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Organization "restored" event.
     */
    public function restored(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Organization "force deleted" event.
     */
    public function forceDeleted(Organization $organization): void
    {
        //
    }
}
