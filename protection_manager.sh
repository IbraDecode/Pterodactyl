#!/bin/bash

echo "🚀 Sistem Proteksi Panel Pterodactyl"
echo "=================================="
echo ""

# Cek apakah script dijalankan sebagai root
if [ "$EUID" -ne 0 ]; then
  echo "❌ Script ini harus dijalankan sebagai root!"
  exit 1
fi

# Pindah ke directory pterodactyl
cd /var/www/pterodactyl

echo "📋 Menu:"
echo "1. Terapkan proteksi (jalankan protect.sh)"
echo "2. Generate proteksi dinamis"
echo "3. Lihat konfigurasi proteksi"
echo "4. Reset ke default settings"
echo "5. Keluar"
echo ""

read -p "Pilih menu (1-5): " choice

case $choice in
  1)
    echo "🛡️ Menerapkan proteksi..."
    bash protect.sh
    ;;
  2)
    echo "⚙️ Generate proteksi dinamis..."
    php generate_protection.php
    ;;
  3)
    echo "📖 Konfigurasi proteksi saat ini:"
    php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
echo 'Admin IDs: ' . ProtectionSetting::get('admin_ids') . PHP_EOL;
echo 'Access Denied Message: ' . ProtectionSetting::get('access_denied_message') . PHP_EOL;
echo 'Server Delete Protection: ' . (ProtectionSetting::isProtectionEnabled('server_delete') ? 'Aktif' : 'Nonaktif') . PHP_EOL;
echo 'User Management Protection: ' . (ProtectionSetting::isProtectionEnabled('user_management') ? 'Aktif' : 'Nonaktif') . PHP_EOL;
echo 'Location Access Protection: ' . (ProtectionSetting::isProtectionEnabled('location_access') ? 'Aktif' : 'Nonaktif') . PHP_EOL;
echo 'Nodes Access Protection: ' . (ProtectionSetting::isProtectionEnabled('nodes_access') ? 'Aktif' : 'Nonaktif') . PHP_EOL;
echo 'Nests Access Protection: ' . (ProtectionSetting::isProtectionEnabled('nests_access') ? 'Aktif' : 'Nonaktif') . PHP_EOL;
echo 'Server Modification Protection: ' . (ProtectionSetting::isProtectionEnabled('server_modification') ? 'Aktif' : 'Nonaktif') . PHP_EOL;
echo 'File Access Protection: ' . (ProtectionSetting::isProtectionEnabled('file_access') ? 'Aktif' : 'Nonaktif') . PHP_EOL;
echo 'Settings Access Protection: ' . (ProtectionSetting::isProtectionEnabled('settings_access') ? 'Aktif' : 'Nonaktif') . PHP_EOL;
echo 'API Management Protection: ' . (ProtectionSetting::isProtectionEnabled('api_management') ? 'Aktif' : 'Nonaktif') . PHP_EOL;
"
    ;;
  4)
    echo "🔄 Reset ke default settings..."
    php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
ProtectionSetting::set('admin_ids', '1', 'List of admin IDs that can access protection settings (comma separated)');
ProtectionSetting::set('access_denied_message', 'Akses ditolak: Anda tidak memiliki izin untuk mengakses fitur ini.', 'Message shown when access is denied');
ProtectionSetting::set('protection_server_delete', 'true', 'Protect server deletion');
ProtectionSetting::set('protection_user_management', 'true', 'Protect user management');
ProtectionSetting::set('protection_location_access', 'true', 'Protect location access');
ProtectionSetting::set('protection_nodes_access', 'true', 'Protect nodes access');
ProtectionSetting::set('protection_nests_access', 'true', 'Protect nests access');
ProtectionSetting::set('protection_server_modification', 'true', 'Protect server modification');
ProtectionSetting::set('protection_file_access', 'true', 'Protect file access');
ProtectionSetting::set('protection_settings_access', 'true', 'Protect settings access');
ProtectionSetting::set('protection_api_management', 'true', 'Protect API management');
echo 'Settings reset to default!' . PHP_EOL;
"
    echo "✅ Settings telah di-reset ke default!"
    ;;
  5)
    echo "👋 Keluar..."
    exit 0
    ;;
  *)
    echo "❌ Pilihan tidak valid!"
    exit 1
    ;;
esac

echo ""
echo "✅ Selesai! Kunjungi /admin/protection untuk mengatur konfigurasi lebih lanjut."