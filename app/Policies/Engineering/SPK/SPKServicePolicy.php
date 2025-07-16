<?php

namespace App\Policies\Engineering\SPK;

use App\Models\User;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Auth\Access\HandlesAuthorization;

class SPKServicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_engineering::s::p::k::s::p::k::service');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SPKService $sPKService): bool
    {
        return $user->can('view_engineering::s::p::k::s::p::k::service');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_engineering::s::p::k::s::p::k::service');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SPKService $sPKService): bool
    {
        return $user->can('update_engineering::s::p::k::s::p::k::service');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SPKService $sPKService): bool
    {
        return $user->can('delete_engineering::s::p::k::s::p::k::service');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_engineering::s::p::k::s::p::k::service');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, SPKService $sPKService): bool
    {
        return $user->can('force_delete_engineering::s::p::k::s::p::k::service');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_engineering::s::p::k::s::p::k::service');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, SPKService $sPKService): bool
    {
        return $user->can('restore_engineering::s::p::k::s::p::k::service');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_engineering::s::p::k::s::p::k::service');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, SPKService $sPKService): bool
    {
        return $user->can('replicate_engineering::s::p::k::s::p::k::service');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_engineering::s::p::k::s::p::k::service');
    }
}
