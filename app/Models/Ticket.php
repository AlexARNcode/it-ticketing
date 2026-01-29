<?php

namespace App\Models;

use App\Enums\TicketStatus;
use App\Events\TicketCreated;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'organization_id',
        'created_by',
        'assigned_to',
        'title',
        'description',
        'priority',
        'status',
        'first_response_due_at',
        'resolution_due_at',
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'first_response_due_at' => 'datetime',
        'resolution_due_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (Ticket $ticket) {
            event(new TicketCreated($ticket));
        });
    }
}
