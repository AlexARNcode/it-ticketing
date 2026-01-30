<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, array_map(fn (UserRole $r) => $r->value, UserRole::cases()));

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return in_array($user->role, array_map(fn (UserRole $r) => $r->value, UserRole::cases()));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, array_map(fn (UserRole $r) => $r->value, UserRole::cases()));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->role === UserRole::TECH->value || $user->role === UserRole::ADMIN->value) {
            return true;
        }

        return $user->id === $ticket->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->role === UserRole::ADMIN->value;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->role === UserRole::ADMIN->value;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->role === UserRole::ADMIN->value;
    }
}
