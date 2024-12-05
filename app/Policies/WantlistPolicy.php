<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wantlist;
use App\Models\Customer;

use Illuminate\Auth\Access\Response;

class WantlistPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Wantlist $wantlist): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Customer $customer): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Wantlist $wantlist): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Wantlist $wantlist): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Wantlist $wantlist): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Wantlist $wantlist): bool
    {
        return false;
    }

    // Check if modification of wantlist is allowed.
    public function modify(User $user, Wantlist $wantlist){
        $customer = Customer::find($wantlist->customer_id);
        return $user->id == $customer->user_id ? Response::allow() : Response::deny();
    }

    // Check if storing of wantlist is allowed.

    public function store(User $user, Customer $customer){
        return $user->id == $customer->user_id ? Response::allow() : Response::deny();
    }
}
