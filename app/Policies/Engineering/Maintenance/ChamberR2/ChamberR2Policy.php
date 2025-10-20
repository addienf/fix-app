<?php

namespace App\Policies\Engineering\Maintenance\ChamberR2;

use App\Models\User;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChamberR2Policy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_engineering::maintenance::chamber::r2::chamber::r2');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChamberR2 $chamberR2): bool
    {
        return $user->can('view_engineering::maintenance::chamber::r2::chamber::r2');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_engineering::maintenance::chamber::r2::chamber::r2');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChamberR2 $chamberR2): bool
    {
        return $user->can('update_engineering::maintenance::chamber::r2::chamber::r2');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChamberR2 $chamberR2): bool
    {
        return $user->can('delete_engineering::maintenance::chamber::r2::chamber::r2');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_engineering::maintenance::chamber::r2::chamber::r2');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ChamberR2 $chamberR2): bool
    {
        return $user->can('{{ ForceDelete }}');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ChamberR2 $chamberR2): bool
    {
        return $user->can('{{ Restore }}');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('{{ RestoreAny }}');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ChamberR2 $chamberR2): bool
    {
        return $user->can('{{ Replicate }}');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('{{ Reorder }}');
    }
}
