#!/bin/bash

echo "🎨 Pilih Tampilan Proteksi Panel"
echo "================================="
echo ""
echo "Pilih tipe tampilan:"
echo "1. Toggle Switch (Modern & Interaktif)"
echo "2. Dropdown (Simple & Reliable)"
echo "3. Radio Button (Bootstrap Style)"
echo ""

read -p "Pilih (1-3): " choice

cd /var/www/pterodactyl

case $choice in
  1)
    echo "🔄 Menggunakan Toggle Switch..."
    sed -i "s/admin.protection.index_dropdown/admin.protection.index_toggle/g" app/Http/Controllers/Admin/ProtectionController.php
    echo "✅ Tampilan Toggle Switch diaktifkan!"
    ;;
  2)
    echo "🔄 Menggunakan Dropdown..."
    sed -i "s/admin.protection.index_toggle/admin.protection.index_dropdown/g" app/Http/Controllers/Admin/ProtectionController.php
    echo "✅ Tampilan Dropdown diaktifkan!"
    ;;
  3)
    echo "🔄 Menggunakan Radio Button..."
    sed -i "s/admin.protection.index_toggle/admin.protection.index/g" app/Http/Controllers/Admin/ProtectionController.php
    echo "✅ Tampilan Radio Button diaktifkan!"
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
echo "✅ Selesai! Silakan akses /admin/protection untuk melihat perubahan."