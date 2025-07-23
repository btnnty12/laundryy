<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $customer = Role::firstOrCreate(['name' => 'customer']);

        // Create Permissions
        $permissions = [
            // User Management
            'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user', 'delete_any_user',
            
            // Service Management
            'view_any_service', 'view_service', 'create_service', 'update_service', 'delete_service', 'delete_any_service',
            
            // Order Management
            'view_any_order', 'view_order', 'create_order', 'update_order', 'delete_order', 'delete_any_order',
            'view_own_orders', 'create_own_order',
            
            // Order Item Management
            'view_any_order_item', 'view_order_item', 'create_order_item', 'update_order_item', 'delete_order_item', 'delete_any_order_item',
            
            // Payment Management
            'view_any_payment', 'view_payment', 'create_payment', 'update_payment', 'delete_payment', 'delete_any_payment',
            'view_own_payments',
            
            // Order History Management
            'view_any_order_history', 'view_order_history', 'create_order_history', 'update_order_history', 'delete_order_history', 'delete_any_order_history',
            
            // Role Management
            'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role', 'delete_any_role',
            
            // Dashboard Access
            'access_admin_dashboard', 'access_customer_dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign Permissions to Roles
        
        // Owner - Full Access
        $owner->syncPermissions(Permission::all());
        
        // Admin - Management Access (no user role management)
        $adminPermissions = [
            'view_any_user', 'view_user', 'create_user', 'update_user',
            'view_any_service', 'view_service', 'create_service', 'update_service', 'delete_service', 'delete_any_service',
            'view_any_order', 'view_order', 'create_order', 'update_order', 'delete_order', 'delete_any_order',
            'view_any_order_item', 'view_order_item', 'create_order_item', 'update_order_item', 'delete_order_item', 'delete_any_order_item',
            'view_any_payment', 'view_payment', 'create_payment', 'update_payment', 'delete_payment', 'delete_any_payment',
            'view_any_order_history', 'view_order_history', 'create_order_history', 'update_order_history',
            'access_admin_dashboard',
        ];
        $admin->syncPermissions($adminPermissions);
        
        // Customer - Limited Access (only own data)
        $customerPermissions = [
            'view_own_orders', 'create_own_order',
            'view_own_payments',
            'view_any_service', 'view_service',
            'access_customer_dashboard',
        ];
        $customer->syncPermissions($customerPermissions);
    }
}
