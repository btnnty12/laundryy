<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_payment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payment $payment): bool
    {
        // Customer can only view payments for their own orders
        if ($user->hasRole('customer')) {
            return $user->id === $payment->order->customer_id && $user->can('view_payment');
        }
        
        return $user->can('view_payment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_payment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment): bool
    {
        return $user->can('update_payment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $payment): bool
    {
        return $user->can('delete_payment');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_payment');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Payment $payment): bool
    {
        return $user->can('delete_payment');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('delete_any_payment');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Payment $payment): bool
    {
        return $user->can('update_payment');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('delete_any_payment');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Payment $payment): bool
    {
        return $user->can('create_payment');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('update_payment');
    }
}
