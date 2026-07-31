# ElectroIntiDinamika - Smart Manufacturing Dashboard

Aplikasi web dashboard untuk memonitor performa mesin produksi pabrik secara real-time. Dibangun sebagai technical test dengan fokus pada kerapian arsitektur, real-time handling, dan kualitas kode.

## Tech Stack

- **Laravel 12** — Fullstack framework (Blade + Livewire + Alpine.js)
- **PostgreSQL** — Database via Eloquent ORM
- **Laravel Breeze** (Livewire stack) — Session-based authentication
- **Tailwind CSS** — Utility-first CSS styling
- **Laravel Reverb** — WebSocket server untuk real-time broadcasting
- **Laravel Echo** + **Pusher.js** — WebSocket client di frontend

## Fitur

### Autentikasi & Role-based Access
- Login/logout dengan Laravel Breeze (session-based)
- Dua role: **Admin** (full access) dan **Viewer** (read-only dashboard & reports)
- Tidak ada registrasi publik — user di-seed via database seeder

### Dashboard Monitoring Real-time
- Card status semua mesin dengan update **push-based** via Laravel Reverb
- Menampilkan: output/menit, suhu, operator, shift, dan timestamp
- Update otomatis tanpa refresh halaman — event `ProductionDataUpdated` di-broadcast via WebSocket

### Manajemen Mesin (CRUD) — Admin only
- List, tambah, edit, hapus mesin via Livewire component
- Modal edit tanpa reload halaman
- Field: nama, tipe (CNC/Milling/Press/Assembly), status

### Laporan Produksi Harian
- Filter berdasarkan tanggal dan shift (1/2/3)
- Agregasi per mesin: total output, rata-rata suhu, downtime events
- Export CSV (dengan BOM UTF-8 — bisa langsung dibuka di Excel)

### Simulasi Data Mesin
- Artisan command `machines:simulate` untuk generate data produksi acak
- Mensimulasikan 8 mesin dengan status random, output, suhu, dan operator

## Struktur Proyek

```
eid_technicaltest_rafly/
├── app/
│   ├── Console/Commands/SimulateMachines.php    # Artisan command simulasi
│   ├── Events/ProductionDataUpdated.php         # Event broadcast (ShouldBroadcastNow)
│   ├── Http/
│   │   ├── Controllers/ReportExportController.php  # CSV export controller
│   │   └── Middleware/EnsureUserIsAdmin.php        # Admin-only middleware
│   ├── Livewire/
│   │   ├── Dashboard.php        # Real-time monitoring component
│   │   ├── MachineManager.php   # CRUD mesin component
│   │   └── Report.php           # Laporan + export component
│   ├── Models/
│   │   ├── Machine.php
│   │   ├── ProductionLog.php
│   │   └── User.php
│   └── Providers/AppServiceProvider.php  # Gate: admin
├── database/
│   ├── migrations/
│   │   ├── ..._add_role_to_users_table.php
│   │   ├── ..._create_machines_table.php
│   │   └── ..._create_production_logs_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php       # admin@eid.com + viewer@eid.com
│       └── MachineSeeder.php    # 8 mesin (2 per tipe)
├── resources/views/
│   ├── layouts/app.blade.php    # Layout utama (light theme)
│   ├── livewire/
│   │   ├── dashboard.blade.php
│   │   ├── machine-manager.blade.php
│   │   ├── report.blade.php
│   │   └── layout/navigation.blade.php
│   ├── dashboard.blade.php
│   ├── machines.blade.php
│   └── reports.blade.php
└── routes/
    └── web.php                  # Route definitions + middleware
```

## Data Model

### users
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | string | |
| email | string, unique | |
| password | string (hashed) | |
| role | enum('admin','viewer') | default 'viewer' |
| timestamps | | |

### machines
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | string | contoh: "CNC-01" |
| type | enum('CNC','Milling','Press','Assembly') | |
| status | enum('Running','Idle','Maintenance','Error') | default 'Idle' |
| current_operator | string, nullable | |
| timestamps | | |

### production_logs (append-only)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| machine_id | bigint FK | |
| output_count | integer | |
| status | enum | snapshot status |
| temperature | decimal(5,2) | Celsius |
| operator | string | |
| shift | enum('1','2','3') | |
| recorded_at | timestamp | |
| timestamps | | |

## Prasyarat

- PHP 8.2+
- Composer
- PostgreSQL
- Node.js & npm

## Cara Menjalankan

### 1. Install Dependencies

```bash
cd eid_technicaltest_achmad-rafly-khatamy-zain
composer install
npm install
```

### 2. Konfigurasi Environment

Salin `.env.example` ke `.env` (atau biarkan `.env` yang sudah ada), lalu sesuaikan kredensial database:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=eid_dashboard
DB_USERNAME=postgres
DB_PASSWORD=password
```

### 3. Setup Database

```bash
php artisan migrate --seed
```

Seed data:
- **Admin**: admin@eid.com / password (role: admin)
- **Viewer**: viewer@eid.com / password (role: viewer)
- 8 mesin: CNC-01, CNC-02, Milling-01, Milling-02, Press-01, Press-02, Assembly-01, Assembly-02

### 4. Build Frontend

```bash
npm run build
```

### 5. Jalankan Aplikasi (3 terminal)

**Terminal 1** — Laravel dev server:
```bash
php artisan serve
```

**Terminal 2** — Reverb WebSocket server:
```bash
php artisan reverb:start
```

**Terminal 3** — Simulasi data mesin:
```bash
php artisan machines:simulate --interval=5
```

Buka `http://localhost:8000` di browser.

## Real-time Flow

```
php artisan machines:simulate
    → Insert row ke production_logs
    → Update machines.status & current_operator
    → Dispatch ProductionDataUpdated (ShouldBroadcastNow)
    → Reverb broadcast ke channel "machines"
    → Laravel Echo terima event ".ProductionDataUpdated"
    → Livewire Dashboard component re-render data mesin
```

## Role Access

| Halaman | Admin | Viewer |
|---|---|---|
| Dashboard (real-time) | ✅ | ✅ |
| Machines (CRUD) | ✅ | ❌ |
| Reports + Export | ✅ | ✅ |
| Profile | ✅ | ✅ |
