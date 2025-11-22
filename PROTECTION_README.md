# Sistem Proteksi Panel Ibra Decode

## Fitur

Sistem proteksi panel Ibra Decode yang dapat dikonfigurasi melalui admin panel dengan fitur:

- **Admin IDs**: Mengatur ID admin yang diizinkan mengakses proteksi
- **Custom Messages**: Pesan akses ditolak dapat disesuaikan
- **Selective Protection**: Pilih fitur proteksi yang ingin diaktifkan
- **Dynamic Generation**: Proteksi di-generate secara otomatis dari database

## Cara Penggunaan

### 1. Akses Menu Proteksi

- Login sebagai admin (sesuai ID yang diizinkan)
- Buka menu **Admin > Proteksi** di sidebar

### 2. Konfigurasi Admin IDs

- Masukkan ID admin yang diizinkan (pisahkan dengan koma)
- Contoh: `1,2,3` untuk admin ID 1, 2, dan 3

### 3. Custom Pesan Akses Ditolak

- Tulis pesan yang akan muncul saat akses ditolak
- Contoh: "Akses ditolak: Anda tidak memiliki izin"

### 4. Pilih Fitur Proteksi

Aktifkan/nonaktifkan fitur proteksi:

- ✅ Proteksi Hapus Server
- ✅ Proteksi Manajemen User
- ✅ Proteksi Akses Location
- ✅ Proteksi Akses Nodes
- ✅ Proteksi Akses Nests
- ✅ Proteksi Modifikasi Server
- ✅ Proteksi Akses File
- ✅ Proteksi Akses Settings
- ✅ Proteksi Manajemen API

### 5. Terapkan Proteksi

- Klik **"Simpan Pengaturan"** untuk menyimpan konfigurasi
- Klik **"Terapkan Proteksi"** untuk menjalankan script proteksi

## Cara Kerja

1. **Database Settings**: Konfigurasi disimpan di tabel `protection_settings`
2. **Dynamic Generation**: Script `generate_protection.php` membaca konfigurasi dan generate file PHP
3. **Protection Files**: Semua file proteksi di-update otomatis sesuai konfigurasi
4. **Access Control**: Hanya admin yang ID-nya terdaftar yang dapat mengakses fitur yang diproteksi

## File yang Di-proteksi

- `app/Services/Servers/ServerDeletionService.php` - Proteksi hapus server
- `app/Http/Controllers/Admin/UserController.php` - Proteksi manajemen user
- `app/Http/Controllers/Admin/LocationController.php` - Proteksi akses location
- `app/Http/Controllers/Admin/Nodes/NodeController.php` - Proteksi akses nodes
- `app/Http/Controllers/Admin/Nests/NestController.php` - Proteksi akses nests
- `app/Services/Servers/DetailsModificationService.php` - Proteksi modifikasi server
- `app/Http/Controllers/Api/Client/Servers/FileController.php` - Proteksi akses file
- `app/Http/Controllers/Admin/Settings/IndexController.php` - Proteksi akses settings
- `app/Http/Controllers/Admin/ApiController.php` - Proteksi manajemen API

## Script Penting

- `protect.sh` - Script utama untuk menjalankan proteksi
- `generate_protection.php` - Script PHP untuk generate proteksi dinamis

## Keamanan

- Hanya admin yang ID-nya terdaftar dapat mengakses halaman proteksi
- Konfigurasi disimpan aman di database
- Backup file otomatis dibuat sebelum proteksi diterapkan
- Pesan error dapat dicustom untuk menghindari exposure informasi sensitif

## Troubleshooting

### Jika tidak bisa akses menu proteksi:

- Pastikan ID admin Anda terdaftar di pengaturan
- Clear cache: `php artisan cache:clear`
- Restart queue worker: `php artisan queue:restart`

### Jika proteksi tidak berfungsi:

- Jalankan manual: `php generate_protection.php`
- Periksa permissions file
- Pastikan tabel `protection_settings` ada dan terisi

### Jika terjadi error:

- Cek log Laravel di `storage/logs/laravel.log`
- Pastikan semua dependencies terinstall
- Restart web server jika perlu
