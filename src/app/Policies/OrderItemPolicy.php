<?php

namespace App\Policies;

use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_order_item');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrderItem $orderItem): bool
    {
        // Customer can only view order items for their own orders
        if ($user->hasRole('customer')) {
            return $user->id === $orderItem->order->customer_id && $user->can('view_order_item');
        }
        
        return $user->can('view_order_item');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_order_item');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrderItem $orderItem): bool
    {
        return $user->can('update_order_item');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrderItem $orderItem): bool
    {
        return $user->can('delete_order_item');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_order_item');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, OrderItem $orderItem): bool
    {
        return $user->can('delete_order_item');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('delete_any_order_item');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, OrderItem $orderItem): bool
    {
        return $user->can('update_order_item');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('delete_any_order_item');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, OrderItem $orderItem): bool
    {
        return $user->can('create_order_item');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('update_order_item');
    }
}