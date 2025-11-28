#!/bin/bash

# Script untuk update Pterodactyl Panel
# Pastikan menjalankan script ini sebagai root atau dengan sudo

# Fungsi untuk menampilkan pesan status
print_status() {
    echo -e "\033[1;34m[$1]\033[0m $2"
}

# Fungsi untuk menangani error
handle_error() {
    echo -e "\033[1;31m[ERROR]\033[0m $1"
    exit 1
}

# Direktori kerja
PANEL_DIR="/var/www/pterodactyl"

# Cek apakah direktori panel exists
if [ ! -d "$PANEL_DIR" ]; then
    handle_error "Direktori $PANEL_DIR tidak ditemukan!"
fi

# Cek apakah sedang berjalan sebagai root
if [ "$EUID" -ne 0 ]; then
    echo -e "\033[1;33m[PERINGATAN]\033[0m Disarankan menjalankan script ini sebagai root/sudo"
    read -p "Lanjutkan anyway? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

print_status "INFO" "Memulai proses update Pterodactyl Panel..."

# 1. Mengaktifkan mode maintenance
print_status "1/11" "Mengaktifkan mode maintenance..."
cd "$PANEL_DIR" || handle_error "Gagal masuk ke direktori $PANEL_DIR"
php artisan down || handle_error "Gagal mengaktifkan mode maintenance"

# 2. Mengunduh file update
print_status "2/11" "Mengunduh file protect..."
cd "$PANEL_DIR" || handle_error "Gagal masuk ke direktori $PANEL_DIR"
curl -LO https://github.com/IbraDecode/Pterodactyl/releases/download/v11.1.1/Pterodactyl-main.zip || handle_error "Gagal mengunduh file update"

# 3. Mengekstrak file
print_status "3/11" "Mengekstrak file protect..."
cd "$PANEL_DIR" || handle_error "Gagal masuk ke direktori $PANEL_DIR"
unzip -o Pterodactyl-main.zip || handle_error "Gagal mengekstrak file"

# 4. Menyesuaikan permission
print_status "4/11" "Menyesuaikan permission..."
cd "$PANEL_DIR" || handle_error "Gagal masuk ke direktori $PANEL_DIR"
chmod -R 755 storage/* bootstrap/cache || handle_error "Gagal menyesuaikan permission"

# 5. Menjalankan composer
print_status "5/11" "Menjalankan composer..."
cd "$PANEL_DIR" || handle_error "Gagal masuk ke direktori $PANEL_DIR"
composer install --no-dev --optimize-autoloader || handle_error "Gagal menjalankan composer"

# 6. Membersihkan cache view
print_status "6/11" "Membersihkan cache view..."
cd "$PANEL_DIR" || handle_error "Gagal masuk ke direktori $PANEL_DIR"
php artisan view:clear || handle_error "Gagal membersihkan cache view"

# 7. Membersihkan cache config
print_status "7/11" "Membersihkan cache config..."
cd "$PANEL_DIR" || handle_error "Gagal masuk ke direktori $PANEL_DIR"
php artisan config:clear || handle_error "Gagal membersihkan cache config"

# 8. Migrasi database
print_status "8/11" "Migrasi database..."
cd "$PANEL_DIR" || handle_error "Gagal masuk ke direktori $PANEL_DIR"
php artisan migrate --seed --force || handle_error "Gagal migrasi database"

# 9. Mengatur kepemilikan file
print_status "9/11" "Mengatur kepemilikan file..."
cd "$PANEL_DIR" || handle_error "Gagal masuk ke direktori $PANEL_DIR"
chown -R www-data:www-data /var/www/pterodactyl/* || handle_error "Gagal mengatur kepemilikan file"

# 10. Restart queue
print_status "10/11" "Restart queue..."
cd "$PANEL_DIR" || handle_error "Gagal masuk ke direktori $PANEL_DIR"
php artisan queue:restart || handle_error "Gagal restart queue"

# 11. Menonaktifkan maintenance
print_status "11/11" "Menonaktifkan maintenance..."
cd "$PANEL_DIR" || handle_error "Gagal masuk ke direktori $PANEL_DIR"
php artisan up || handle_error "Gagal menonaktifkan mode maintenance"

# Membersihkan file zip
print_status "CLEANUP" "Membersihkan file sementara..."
rm -f "$PANEL_DIR/Pterodactyl-main.zip" 2>/dev/null

echo -e "\033[1;32m[SUKSES]\033[0m Update Pterodactyl Panel berhasil diselesaikan!"
echo -e "\033[1;33m[INFO]\033[0m Jangan lupa untuk restart worker queue jika diperlukan:"
echo "sudo php artisan queue:restart"
