<?php

namespace App\Policies\Engineering\Permintaan;

use App\Models\User;
use App\Models\Engineering\Permintaan\PermintaanSparepart;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermintaanSparepartPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_engineering::permintaan::permintaan::sparepart');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PermintaanSparepart $permintaanSparepart): bool
    {
        return $user->can('view_engineering::permintaan::permintaan::sparepart');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_engineering::permintaan::permintaan::sparepart');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PermintaanSparepart $permintaanSparepart): bool
    {
        return $user->can('update_engineering::permintaan::permintaan::sparepart');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PermintaanSparepart $permintaanSparepart): bool
    {
        return $user->can('delete_engineering::permintaan::permintaan::sparepart');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_engineering::permintaan::permintaan::sparepart');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, PermintaanSparepart $permintaanSparepart): bool
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
    public function restore(User $user, PermintaanSparepart $permintaanSparepart): bool
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
    public function replicate(User $user, PermintaanSparepart $permintaanSparepart): bool
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
