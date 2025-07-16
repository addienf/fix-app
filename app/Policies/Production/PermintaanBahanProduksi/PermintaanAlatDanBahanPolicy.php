<?php

namespace App\Policies\Production\PermintaanBahanProduksi;

use App\Models\User;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermintaanAlatDanBahanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PermintaanAlatDanBahan $permintaanAlatDanBahan): bool
    {
        return $user->can('view_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PermintaanAlatDanBahan $permintaanAlatDanBahan): bool
    {
        return $user->can('update_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PermintaanAlatDanBahan $permintaanAlatDanBahan): bool
    {
        return $user->can('delete_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, PermintaanAlatDanBahan $permintaanAlatDanBahan): bool
    {
        return $user->can('force_delete_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, PermintaanAlatDanBahan $permintaanAlatDanBahan): bool
    {
        return $user->can('restore_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, PermintaanAlatDanBahan $permintaanAlatDanBahan): bool
    {
        return $user->can('replicate_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_production::permintaan::bahan::produksi::permintaan::alat::dan::bahan');
    }
}
