# Panduan Sistem Permission dan Policy Laundry

## Struktur Role dan Permission

### Roles yang Tersedia:
1. **Owner** - Akses penuh ke semua fitur
2. **Admin** - Akses manajemen operasional (tidak bisa mengubah owner)
3. **Customer** - Akses terbatas hanya untuk data mereka sendiri

### Permission Structure:

#### User Management
- `view_any_user`, `view_user`, `create_user`, `update_user`, `delete_user`, `delete_any_user`

#### Service Management
- `view_any_service`, `view_service`, `create_service`, `update_service`, `delete_service`, `delete_any_service`

#### Order Management
- `view_any_order`, `view_order`, `create_order`, `update_order`, `delete_order`, `delete_any_order`

#### Order Item Management
- `view_any_order_item`, `view_order_item`, `create_order_item`, `update_order_item`, `delete_order_item`, `delete_any_order_item`

#### Payment Management
- `view_any_payment`, `view_payment`, `create_payment`, `update_payment`, `delete_payment`, `delete_any_payment`

#### Order History Management
- `view_any_order_history`, `view_order_history`, `create_order_history`, `update_order_history`, `delete_order_history`, `delete_any_order_history`

#### Role Management
- `view_any_role`, `view_role`, `create_role`, `update_role`, `delete_role`, `delete_any_role`

## Role Permissions Assignment:

### Owner Role:
- **Semua permission** untuk semua modul
- Akses penuh tanpa batasan

### Admin Role:
- **User Management**: Semua permission kecuali tidak bisa mengubah/hapus owner
- **Service Management**: Semua permission
- **Order Management**: Semua permission
- **Order Item Management**: Semua permission
- **Payment Management**: Semua permission
- **Order History Management**: Semua permission
- **Role Management**: View dan update saja (tidak bisa mengubah role owner)

### Customer Role:
- **User Management**: Hanya bisa view dan update profil sendiri
- **Service Management**: Hanya view
- **Order Management**: Bisa view, create, update, delete pesanan sendiri (update/delete hanya jika status pending)
- **Order Item Management**: Hanya view item dari pesanan sendiri
- **Payment Management**: Hanya view pembayaran dari pesanan sendiri
- **Order History Management**: Hanya view history dari pesanan sendiri
- **Role Management**: Tidak ada akses

## Policy Logic:

### Customer Restrictions:
1. **Orders**: Customer hanya bisa mengakses pesanan mereka sendiri (`customer_id` = user ID)
2. **Order Items**: Customer hanya bisa melihat item dari pesanan mereka sendiri
3. **Payments**: Customer hanya bisa melihat pembayaran dari pesanan mereka sendiri
4. **Order History**: Customer hanya bisa melihat riwayat dari pesanan mereka sendiri
5. **Users**: Customer hanya bisa melihat dan mengubah profil mereka sendiri
6. **Order Updates**: Customer hanya bisa mengubah/hapus pesanan jika status masih 'pending'

### Admin Restrictions:
1. **Users**: Admin tidak bisa mengubah atau menghapus user dengan role 'owner'
2. **Roles**: Admin tidak bisa mengubah atau menghapus role 'owner'
3. **System Roles**: Role sistem (owner, admin, customer) tidak bisa dihapus

### General Rules:
1. User tidak bisa menghapus diri sendiri
2. Role sistem tidak bisa dihapus
3. Semua policy menggunakan permission yang sesuai dengan Spatie Permission

## Menu Visibility:

### Owner:
- Dashboard
- User Management
- Service Management
- Order Management
- Order Item Management
- Payment Management
- Order History Management
- Role Management
- Settings

### Admin:
- Dashboard
- User Management (terbatas)
- Service Management
- Order Management
- Order Item Management
- Payment Management
- Order History Management
- Role Management (view only)

### Customer:
- Dashboard
- My Profile
- Services (view only)
- My Orders
- My Payments
- Order History

Sistem ini memastikan keamanan data dan pembatasan akses yang sesuai dengan peran masing-masing user.