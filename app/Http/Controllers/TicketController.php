<?php

namespace App\Http\Controllers;

use App\Actions\Tickets\CreateTicketAction;
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
        $tickets = Ticket::all(); // TODO: Organization scope

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

    public function store(StoreTicketRequest $request, CreateTicketAction $action): Response
    {
        $ticket = $action->execute($request->validated(), $request->user());

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
