<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use App\Services\Tickets\TransitionTicketStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function index(): Response
    {
        $tickets = Ticket::all();

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Tickets/Create');
    }

    public function show(Ticket $ticket): Response
    {
        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket,
        ]);
    }

    public function store(StoreTicketRequest $request): Response
    {
        $validated = $request->validated();
        $user = $request->user();

        $ticket = Ticket::create([
            'organization_id' => $user->organization_id,
            'created_by' => $user->id,
            'assigned_to' => null,
            'status' => 'open',
            ...$validated,
        ]);

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket,
        ]); 
    }

    public function updateStatus(Request $request, Ticket $ticket, TransitionTicketStatus $service): Response
    {
        $service->handle($ticket, TicketStatus::from($request->status));

         return Inertia::render('Tickets/Show', [
            'ticket' => $ticket,
        ]); 
    }
}
