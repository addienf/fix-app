<?php

namespace App\Policies\Engineering\Maintenance\ChamberWalkinG2;

use App\Models\User;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChamberWalkinG2Policy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChamberWalkinG2 $chamberWalkinG2): bool
    {
        return $user->can('view_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChamberWalkinG2 $chamberWalkinG2): bool
    {
        return $user->can('update_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChamberWalkinG2 $chamberWalkinG2): bool
    {
        return $user->can('delete_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ChamberWalkinG2 $chamberWalkinG2): bool
    {
        return $user->can('force_delete_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ChamberWalkinG2 $chamberWalkinG2): bool
    {
        return $user->can('restore_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ChamberWalkinG2 $chamberWalkinG2): bool
    {
        return $user->can('replicate_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_engineering::maintenance::chamber::walkin::g2::chamber::walkin::g2');
    }
}
