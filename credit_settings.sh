#!/bin/bash

echo "⚙️ Pengaturan Credit Admin Panel"
echo "================================="
echo ""

cd /var/www/pterodactyl

echo "📋 Pengaturan saat ini:"
php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
echo 'Tampilkan Credit: ' . (ProtectionSetting::get('show_credit') === 'true' ? 'Ya' : 'Tidak') . PHP_EOL;
echo 'Teks Credit: ' . ProtectionSetting::get('credit_text') . PHP_EOL;
echo 'Author: ' . ProtectionSetting::get('credit_author') . PHP_EOL;
echo 'Link: ' . ProtectionSetting::get('credit_link') . PHP_EOL;
"

echo ""
echo "Pilih aksi:"
echo "1. Ubah Teks Credit"
echo "2. Ubah Author"
echo "3. Ubah Link"
echo "4. Sembunyikan Credit"
echo "5. Tampilkan Credit"
echo "6. Reset ke Default"
echo ""

read -p "Pilih (1-6): " choice

case $choice in
  1)
    read -p "Masukkan teks credit baru: " new_text
    php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
ProtectionSetting::set('credit_text', '$new_text');
echo 'Teks credit berhasil diupdate!' . PHP_EOL;
"
    echo "✅ Teks credit berhasil diubah!"
    ;;
  2)
    read -p "Masukkan nama author baru: " new_author
    php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
ProtectionSetting::set('credit_author', '$new_author');
echo 'Author berhasil diupdate!' . PHP_EOL;
"
    echo "✅ Author berhasil diubah!"
    ;;
  3)
    read -p "Masukkan link baru: " new_link
    php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
ProtectionSetting::set('credit_link', '$new_link');
echo 'Link berhasil diupdate!' . PHP_EOL;
"
    echo "✅ Link berhasil diubah!"
    ;;
  4)
    php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
ProtectionSetting::set('show_credit', 'false');
echo 'Credit disembunyikan!' . PHP_EOL;
"
    echo "✅ Credit disembunyikan!"
    ;;
  5)
    php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
ProtectionSetting::set('show_credit', 'true');
echo 'Credit ditampilkan!' . PHP_EOL;
"
    echo "✅ Credit ditampilkan!"
    ;;
  6)
    php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
ProtectionSetting::set('show_credit', 'true');
ProtectionSetting::set('credit_text', 'Proteksi Tools By');
ProtectionSetting::set('credit_author', 'Ibra Decode');
ProtectionSetting::set('credit_link', 'https://t.me/ibradecode');
echo 'Credit di-reset ke default!' . PHP_EOL;
"
    echo "✅ Credit di-reset ke default!"
    ;;
  *)
    echo "❌ Pilihan tidak valid!"
    exit 1
    ;;
esac

echo ""
echo "🧹 Membersihkan cache..."
php artisan cache:clear
php artisan view:clear

echo ""
echo "✅ Selesai! Silakan refresh halaman admin untuk melihat perubahan."