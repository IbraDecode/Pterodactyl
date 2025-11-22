#!/bin/bash

echo "📝 Update Pesan Akses Ditolak"
echo "============================="
echo ""

cd /var/www/pterodactyl

echo "📋 Pesan saat ini:"
php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
echo 'Pesan: ' . ProtectionSetting::getAccessDeniedMessage() . PHP_EOL;
"

echo ""
read -p "Masukkan pesan akses ditolak baru: " new_message

if [ -z "$new_message" ]; then
    echo "❌ Pesan tidak boleh kosong!"
    exit 1
fi

echo ""
echo "🔄 Mengupdate pesan..."
php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
ProtectionSetting::set('access_denied_message', '$new_message');
echo 'Pesan berhasil diupdate!' . PHP_EOL;
"

echo ""
echo "🔄 Generate ulang proteksi..."
php generate_protection.php

echo ""
echo "✅ Pesan akses ditolak berhasil diperbarui!"
echo "📝 Pesan baru: $new_message"
echo ""
echo "🔄 Semua file proteksi telah diperbarui dengan pesan baru."