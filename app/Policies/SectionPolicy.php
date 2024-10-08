<?php

namespace App\Policies;

use App\Models\User;
use App\Models\section;
use Illuminate\Auth\Access\Response;

class SectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, section $section): bool
    {
        return $user->hasPermissionTo('index_assignment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
        // return $user->hasAnyRole(['student', 'teacher', 'manager', 'admin']);

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, section $section): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, section $section): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, section $section): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, section $section): bool
    {
        //
    }
}
