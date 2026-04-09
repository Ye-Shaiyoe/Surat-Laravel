# Archive-Surat-Konsep

Sistem Manajemen Surat Digital untuk Kantor Metrologi dengan alur approval multi-tahap, tracking SLA real-time, dan dashboard komprehensif.



### 🔐 Autentikasi & Keamanan
- Login & Register dengan email verification
- Reset password via email
- Konfirmasi password untuk aksi sensitif
- Role-based access control (Admin & User)

### 👥 Fitur User (Staff/Pegawai)
- **Ajukan Surat** - Upload dokumen Word (.docx/.doc) + lampiran (PDF/JPG/PNG)
- **Tracking Real-time** - Pantau progres surat di 10 tahapan approval
- **Monitoring SLA** - Deadline 24 jam kerja dengan countdown & indikator overdue
- **Template Surat** - Download template surat resmi
- **Notifikasi** - Notifikasi real-time untuk setiap update status surat
- **Dashboard** - Statistik surat, surat aktif, dan template tersedia

### 👨‍💼 Fitur Admin
- **Dashboard Admin** - Statistik lengkap, antrian surat, tracking SLA
- **Approval System** - Approve/Reject surat dengan alasan
- **Preview Dokumen** - Preview PDF inline, Word via Microsoft Online Viewer
- **Download File** - Download dokumen surat & lampiran
- **Laporan** - Filter berdasarkan bulan/tipe surat, export CSV
- **Grafik & Statistik** - 7 jenis chart (tren bulanan, distribusi tipe, kepatuhan SLA, dll)
- **Manajemen Template** - Upload & hapus template surat
- **Manajemen User** - Lihat statistik user, filter, dan hapus user

### ⚙️ Fitur Sistem
- **10 Tahapan Approval** - Alur kerja berurutan dengan tracking processor
- **SLA Tracking** - Perhitungan deadline 24 jam kerja (skip weekend)
- **File Expiration** - Auto-hapus file 3 hari setelah approval
- **Notifikasi Real-time** - Polling setiap 3 detik + Server-Sent Events (SSE)
- **Scheduled Tasks** - Cleanup expired files otomatis setiap hari

---

## 🛠 Teknologi yang Digunakan

| Kategori | Teknologi |
|----------|-----------|
| **Framework** | Laravel 12 |
| **Authentication** | Laravel Breeze |
| **Frontend** | Tailwind CSS, Alpine.js, Vite |
| **Database** | MySQL |
| **Testing** | Pest PHP |
| **Charts** | JavaScript Charting (via JSON API) |
| **Notifications** | Database driver dengan polling & SSE |
| **Web Server** | Apache (XAMPP) |

---

## 💻 Persyaratan Sistem

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **NPM** >= 9.x
- **MySQL** >= 8.0 (via XAMPP)
- **Git**

---

## 📦 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/Ye-Shaiyoe/Surat-Laravel.git
cd Surat-Metrologi
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
copy .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` sesuai konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=surat_metrologi
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat Database
Buat database `surat_metrologi` melalui phpMyAdmin atau MySQL CLI:
```sql
CREATE DATABASE surat_metrologi;
```

### 6. Run Migration & Seeder
```bash
php artisan migrate --seed
```

Seeder akan membuat:
- **Admin default**: admin@metrologi.com / password
- **Test user** untuk development

### 7. Buat Storage Link
```bash
php artisan storage:link
```

### 8. Build Assets
```bash
npm run build
```

### 9. Setup Data Pegawai (Opsional)
```bash
php artisan tinker < setup_data_pegawai.php
```

---

## ⚙️ Konfigurasi

### Konfigurasi Queue (Opsional)
Untuk notifikasi real-time, jalankan queue worker:
```bash
php artisan queue:work
```

### Konfigurasi Schedule
Untuk fitur auto-cleanup expired files:
```bash
php artisan schedule:work
```

Di production, tambahkan ke crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Konfigurasi Email
Edit `.env` untuk notifikasi via email:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@metrologi.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🚀 Menjalankan Aplikasi

### Development Mode
Jalankan server dan file watcher secara bersamaan:
```bash
composer run dev
```

Atau jalankan terpisah:
```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite (Hot Reload)
npm run dev

# Terminal 3 - Queue Worker (Opsional)
php artisan queue:work

# Terminal 4 - Schedule (Opsional)
php artisan schedule:work
```

### Production Mode
```bash
# Build assets untuk production
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan server (jika tanpa web server)
php artisan serve --host=0.0.0.0 --port=8000
```

Akses aplikasi di: **http://localhost:8000**

---

## 👥 Struktur Role

### User (Staff/Pegawai)
- Mengajukan surat baru
- Melihat status & progres surat
- Menerima notifikasi update
- Download template surat

### Admin
- Melihat semua surat masuk
- Approve/Reject surat
- Upload template surat
- Generate laporan
- Melihat grafik & statistik
- Manajemen user

---

## 🔄 Alur Kerja Surat

Surat melewati **10 tahapan approval** berurutan:

| Tahap | Nama | Deskripsi |
|-------|------|-----------|
| 1 | Usulan Diajukan | User mengajukan surat |
| 2 | Verifikasi Arsiparis | Verifikasi oleh arsiparis |
| 3 | Verifikasi Kasubbag TU | Verifikasi oleh Kepala Sub Bagian TU |
| 4 | Persetujuan Kepala Balai | Approval oleh Kepala Balai |
| 5 | Penomoran Surat | Assignment nomor surat resmi |
| 6 | Tanda Tangan (DS) | Penandatanganan dokumen |
| 7 | Pengiriman via TNDe | Kirim melalui sistem TNDe |
| 8 | Pengiriman via Srikandi | Kirim melalui sistem Srikandi |
| 9 | Pengarsipan | Dokumen diarsipkan |
| 10 | Follow Up / Selesai | Proses selesai |

Setiap tahap mencatat:
- ✅ Status (menunggu/proses/selesai/ditolak)
- 👤 Processor (siapa yang memproses)
- 📝 Notes (catatan dari processor)
- ⏰ Timestamp (waktu selesai)

---

## 📄 Jenis Surat

Sistem mendukung **7 jenis surat**:

1. **Nota Dinas** - Surat internal untuk keperluan dinas
2. **Surat Dinas** - Surat resmi kedinasan
3. **Surat Keputusan** - SK pejabat berwenang
4. **Surat Pernyataan** - Surat pernyataan resmi
5. **Surat Keterangan** - Surat keterangan suatu status/fakta
6. **Surat Undangan** - Undangan rapat/kegiatan
7. **Surat/Note Lainnya** - Jenis surat lainnya

---

## ⏱ Tracking SLA

**Service Level Agreement (SLA)** diterapkan untuk memastikan ketepatan waktu:

- **Deadline**: 24 jam kerja per tahap
- **Perhitungan**: Hanya menghitung jam kerja (Senin-Jumat, 08:00-16:00)
- **Skip Weekend**: Sabtu & Minggu tidak dihitung
- **Status**: OK (tepat waktu) atau Terlambat (overdue)
- **Tracking**: Menampilkan sisa waktu atau jumlah jam overdue

### Dashboard SLA
- Total surat overdue bulan ini
- Distribusi SLA OK vs Terlambat
- Chart kepatuhan SLA per bulan

---

## 🔔 Notifikasi

Sistem notifikasi **real-time** untuk update status surat:

### Jenis Notifikasi
1. **Surat Masuk** - Dikirim ke admin saat user mengajukan surat
2. **Surat Diproses** - Dikirim ke admin lain saat surat diproses
3. **Surat Status Update** - Dikirim ke user saat surat diapprove/reject/selesai

### Metode Delivery
- **Polling**: Cek notifikasi setiap 3 detik
- **Server-Sent Events (SSE)**: Streaming real-time (alternatif polling)
- **Database**: Disimpan di tabel `notifications`

### Fitur Notifikasi
- 🎨 Color-coded by severity (success/info/warning/danger)
- ⏱ Auto-dismiss (8 detik untuk non-critical)
- 🔴 Badge counter untuk unread notifications
- 🔗 Click to navigate ke detail surat
- ✅ Mark as read / delete notification

---

## 🗃 Struktur Database

### Tabel Utama

#### `users`
- id, name, email, password, role, nip, email_verified_at

#### `surat`
- id, user_id, jenis_surat, prioritas, status, tujuan, perihal
- file_word, file_lampiran, nomor_surat
- deadline_sla, status_sla, jam_terlambat
- created_at, updated_at

#### `surat_tahapan`
- id, surat_id, tahapan_ke, nama_tahapan
- status, processor_id, notes, completed_at
- created_at, updated_at

#### `notifications`
- id, type, notifiable_id, notifiable_type, data
- read_at, created_at, updated_at

#### `template_surat`
- id, nama, file_path, file_size
- created_at, updated_at

---

## 🧪 Testing

Jalankan test suite menggunakan Pest:

```bash
# Run all tests
composer run test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test tests/Feature/ExampleTest.php
```

---

## 🚀 Deployment

### Pre-deployment Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate app key: `php artisan key:generate`
- [ ] Configure database production
- [ ] Configure mail settings
- [ ] Set proper file permissions
- [ ] Setup SSL certificate (HTTPS)

### Deployment Steps
```bash
# Install dependencies (production)
composer install --optimize-autoloader --no-dev
npm ci --production

# Build assets
npm run build

# Setup environment
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Setup storage
php artisan storage:link

# Start queue worker (supervisor recommended)
php artisan queue:work --daemon
```

### Supervisor Configuration (Queue Worker)
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path-to-your-project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/path-to-your-project/worker.log
```

---

## 📁 Struktur Folder Penting

```
Surat-Metrologi/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Controller admin
│   │   ├── User/           # Controller user
│   │   └── Auth/           # Controller autentikasi
│   ├── Models/             # Eloquent models
│   ├── Notifications/      # Notification classes
│   └── Console/Commands/   # Artisan commands
├── resources/
│   ├── views/
│   │   ├── admin/          # Views admin
│   │   ├── user/           # Views user
│   │   └── auth/           # Views autentikasi
│   └── js/                 # JavaScript assets
├── routes/
│   ├── web.php             # Web routes
│   └── console.php         # Console/artisan routes
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
└── storage/
    └── app/public/         # Public file storage
```

---

## 📝 License

Sistem ini menggunakan [MIT License](https://opensource.org/licenses/MIT).

---

## 📞 Support

Untuk pertanyaan atau bantuan teknis, hubungi tim developer atau administrator sistem.

---

**Dibuat dengan ❤️ untuk Kantor Metrologi**
