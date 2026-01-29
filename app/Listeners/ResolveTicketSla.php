<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Services\Sla\ResolveSlaForTicket;

class ResolveTicketSla
{
    public function __construct(
        protected ResolveSlaForTicket $resolver
    ) {}

    public function handle(TicketCreated $event): void
    {
        $this->resolver->handle($event->ticket);
    }
}
