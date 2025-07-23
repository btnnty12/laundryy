# Laundry Management System

Sistem manajemen laundry berbasis web yang dibangun menggunakan Laravel 12 dan Filament 3. Aplikasi ini menyediakan dua panel terpisah untuk admin dan customer dengan sistem permission yang komprehensif.

## 🚀 Teknologi yang Digunakan

### Backend Framework
- **Laravel 12** - PHP Framework
- **Filament 3.3** - Admin Panel Framework
- **MySQL** - Database
- **Spatie Permission** - Role & Permission Management

### Packages Utama
- `bezhansalleh/filament-shield` - Permission Management
- `z3d0x/filament-logger` - Activity Logging
- `joaopaulolndev/filament-edit-profile` - Profile Management
- `awcodes/light-switch` - Dark Mode Toggle
- `hasnayeen/themes` - Theme Management
- `diogogpinto/filament-auth-ui-enhancer` - Enhanced Auth UI

### Development Tools
- **Docker & Docker Compose** - Containerization
- **Nginx** - Web Server
- **PHP 8.2** - Runtime
- **Pest** - Testing Framework
- **Laravel Pint** - Code Style Fixer

## 📊 Struktur Database

### Tabel Utama

#### 1. Users Table
```sql
- id, name, email, password
- phone, address (laundry fields)
- theme settings
- timestamps
```

#### 2. Services Table
```sql
- id, name, description
- price_per_kg, estimated_hours
- is_active, timestamps
```

#### 3. Orders Table
```sql
- id, order_number, customer_id, service_id
- weight, total_price
- pickup_date, delivery_date
- notes, status, payment_status
- created_by, timestamps
```

#### 4. Order Items Table
```sql
- id, order_id
- item_type, quantity, description
- timestamps
```

#### 5. Payments Table
```sql
- id, order_id, amount
- payment_method, payment_date
- reference_number, notes
- created_by, timestamps
```

#### 6. Order Histories Table
```sql
- id, order_id, status
- notes, changed_by
- timestamps
```

### Sistem Permission
- **Permission Tables** (Spatie)
- **Activity Log** (Audit Trail)
- **Role-based Access Control**

## 🏗️ Arsitektur Aplikasi

### Panel Structure

#### 1. Admin Panel (`/admin`)
**Provider**: `AdminPanelProvider.php`

**Resources**:
- `UserResource` - Manajemen pengguna
- `ServiceResource` - Manajemen layanan
- `OrderResource` - Manajemen pesanan
- `PaymentResource` - Manajemen pembayaran
- `OrderHistoryResource` - Riwayat pesanan

**Widgets**:
- `LatestAccessLogs` - Log aktivitas terbaru

#### 2. Customer Panel (`/customer`)
**Provider**: `CustomerPanelProvider.php`

**Pages**:
- `Dashboard` - Halaman utama customer
- `Auth/Login` - Halaman login
- `Auth/Register` - Halaman registrasi

**Resources**:
- `ServiceResource` - Lihat layanan (read-only)
- `PaymentResource` - Riwayat pembayaran

**Widgets**:
- `CustomerStatsWidget` - Statistik pesanan & pembayaran
- `ServicesOverviewWidget` - Overview layanan tersedia
- `RecentPaymentsWidget` - Pembayaran terbaru

## 👥 Sistem Role & Permission

### Roles yang Tersedia

#### 1. Owner
- **Akses**: Penuh ke semua fitur
- **Restrictions**: Tidak ada
- **Menu**: Semua menu admin + management role

#### 2. Admin
- **Akses**: Manajemen operasional
- **Restrictions**: 
  - Tidak bisa mengubah/hapus user dengan role 'owner'
  - Tidak bisa mengubah/hapus role 'owner'
- **Menu**: Dashboard, User Management (terbatas), Service, Order, Payment, Order History

#### 3. Customer
- **Akses**: Data pribadi saja
- **Restrictions**: 
  - Hanya bisa akses pesanan sendiri (`customer_id` = user ID)
  - Hanya bisa update/delete pesanan dengan status 'pending'
- **Menu**: Dashboard, Profile, Services (view), Orders, Payments, History

### Permission Structure

#### User Management
- `view_any_user`, `view_user`, `create_user`, `update_user`, `delete_user`, `delete_any_user`

#### Service Management
- `view_any_service`, `view_service`, `create_service`, `update_service`, `delete_service`, `delete_any_service`

#### Order Management
- `view_any_order`, `view_order`, `create_order`, `update_order`, `delete_order`, `delete_any_order`

#### Payment Management
- `view_any_payment`, `view_payment`, `create_payment`, `update_payment`, `delete_payment`, `delete_any_payment`

#### Order History Management
- `view_any_order_history`, `view_order_history`, `create_order_history`, `update_order_history`, `delete_order_history`, `delete_any_order_history`

#### Role Management
- `view_any_role`, `view_role`, `create_role`, `update_role`, `delete_role`, `delete_any_role`

## 🔄 Workflow Aplikasi

### Customer Workflow

1. **Registrasi/Login**
   - Customer mendaftar melalui `/customer/register`
   - Login melalui `/customer/login`
   - Otomatis mendapat role 'customer'

2. **Browse Services**
   - Lihat layanan yang tersedia
   - Filter berdasarkan kategori
   - Lihat detail harga dan estimasi waktu

3. **Create Order**
   - Pilih layanan
   - Input detail pesanan (berat, catatan)
   - Sistem generate order number otomatis
   - Status awal: 'pending'

4. **Track Order**
   - Lihat status pesanan real-time
   - Lihat history perubahan status
   - Update/cancel pesanan (jika status pending)

5. **Payment**
   - Lihat detail pembayaran
   - Riwayat transaksi
   - Filter berdasarkan metode pembayaran

### Admin Workflow

1. **Order Management**
   - Terima pesanan baru
   - Update status pesanan (pending → processing → completed)
   - Manage order items
   - Add notes dan tracking

2. **Service Management**
   - CRUD layanan laundry
   - Set harga per kg
   - Manage availability
   - Set estimasi waktu

3. **Payment Processing**
   - Record pembayaran
   - Multiple payment methods (cash, transfer, e-wallet)
   - Generate reference numbers
   - Update payment status

4. **User Management**
   - Manage customer accounts
   - Assign roles
   - View activity logs

5. **Reporting & Analytics**
   - Dashboard statistics
   - Order trends
   - Payment analytics
   - Activity monitoring

## 🛡️ Security Features

### Authentication
- **Multi-Panel Auth**: Terpisah untuk admin dan customer
- **Session Management**: Secure session handling
- **Password Hashing**: Bcrypt encryption

### Authorization
- **Policy-based Access Control**: Laravel Policies
- **Permission Gates**: Spatie Permission integration
- **Resource Filtering**: Data isolation per user

### Data Protection
- **CSRF Protection**: Built-in Laravel CSRF
- **SQL Injection Prevention**: Eloquent ORM
- **XSS Protection**: Blade templating
- **Activity Logging**: Complete audit trail

## 🚀 Deployment

### Docker Setup

```bash
# Clone repository
git clone <repository-url>
cd laundry

# Start containers
docker-compose up -d

# Install dependencies
docker exec laundry_php composer install

# Run migrations & seeders
docker exec laundry_php php artisan migrate --seed

# Generate application key
docker exec laundry_php php artisan key:generate
```

### Services
- **Web**: https://laundry.test (Nginx + SSL)
- **Database**: MySQL 8.0
- **PHP**: 8.2-fpm

### Default Accounts

#### Owner Account
- **Email**: owner@laundry.test
- **Password**: password
- **Access**: Full system access

#### Admin Account
- **Email**: admin@laundry.test
- **Password**: password
- **Access**: Operational management

#### Customer Account
- **Email**: customer@laundry.test
- **Password**: password
- **Access**: Personal data only

## 📱 Features

### Admin Panel Features
- ✅ **Dashboard Analytics** - Statistics dan metrics
- ✅ **User Management** - CRUD users dengan role management
- ✅ **Service Management** - Manage layanan laundry
- ✅ **Order Management** - Complete order lifecycle
- ✅ **Payment Processing** - Multiple payment methods
- ✅ **Activity Logging** - Comprehensive audit trail
- ✅ **Role & Permission** - Granular access control
- ✅ **Dark Mode** - Theme switching
- ✅ **Responsive Design** - Mobile-friendly interface

### Customer Panel Features
- ✅ **Registration & Login** - Self-service account creation
- ✅ **Service Catalog** - Browse available services
- ✅ **Order Tracking** - Real-time order status
- ✅ **Payment History** - Transaction records
- ✅ **Profile Management** - Update personal information
- ✅ **Dashboard Widgets** - Quick overview
- ✅ **Responsive Design** - Mobile-optimized

### System Features
- ✅ **Multi-tenant Architecture** - Separate admin/customer panels
- ✅ **RESTful API Ready** - Laravel API structure
- ✅ **Database Seeding** - Sample data for testing
- ✅ **Error Handling** - Comprehensive error management
- ✅ **Validation** - Form validation & business rules
- ✅ **Caching** - Performance optimization
- ✅ **Queue System** - Background job processing
- ✅ **File Storage** - Asset management

## 🔧 Development

### Code Structure

```
src/
├── app/
│   ├── Filament/
│   │   ├── Admin/          # Admin panel components
│   │   ├── Customer/       # Customer panel components
│   │   └── Pages/          # Shared pages
│   ├── Models/             # Eloquent models
│   ├── Policies/           # Authorization policies
│   └── Providers/
│       └── Filament/       # Panel providers
├── database/
│   ├── migrations/         # Database schema
│   └── seeders/           # Sample data
└── config/                # Configuration files
```

### Testing

```bash
# Run tests
docker exec laundry_php php artisan test

# Run with coverage
docker exec laundry_php php artisan test --coverage
```

### Code Quality

```bash
# Fix code style
docker exec laundry_php ./vendor/bin/pint

# Static analysis
docker exec laundry_php ./vendor/bin/phpstan analyse
```

## 📈 Future Enhancements

### Planned Features
- [ ] **SMS Notifications** - Order status updates
- [ ] **Email Notifications** - Automated communications
- [ ] **API Documentation** - Swagger/OpenAPI
- [ ] **Mobile App** - React Native/Flutter
- [ ] **Advanced Reporting** - Charts dan analytics
- [ ] **Inventory Management** - Stock tracking
- [ ] **Delivery Tracking** - GPS integration
- [ ] **Multi-language Support** - Internationalization
- [ ] **Payment Gateway** - Online payment integration
- [ ] **Barcode System** - QR code tracking

### Technical Improvements
- [ ] **Redis Caching** - Performance optimization
- [ ] **Elasticsearch** - Advanced search
- [ ] **WebSocket** - Real-time updates
- [ ] **CI/CD Pipeline** - Automated deployment
- [ ] **Load Balancing** - Scalability
- [ ] **Monitoring** - Application performance monitoring

## 📞 Support

Untuk pertanyaan atau dukungan teknis, silakan hubungi tim development atau buat issue di repository ini.

---

**Laundry Management System** - Solusi lengkap untuk manajemen bisnis laundry modern.