<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vault;

class VaultPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vault $vault): bool
    {
        return $user->id === $vault->user_id;
    }

    /**
     * Détermine si l'utilisateur peut retirer de l'argent.
     * On peut ajouter une logique métier : pas de retrait si le vault est 'locked'.
     */
    public function withdraw(User $user, Vault $vault): bool
    {
        return $user->id === $vault->user_id;
    }

    public function deposit(User $user, Vault $vault): bool
    {
        return $user->id === $vault->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vault $vault): bool
    {
        return $user->id === $vault->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vault $vault): bool
    {
        return $user->id === $vault->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vault $vault): bool
    {
        return $user->id === $vault->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vault $vault): bool
    {
        return false;
    }
}
