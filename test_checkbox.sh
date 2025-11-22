#!/bin/bash

echo "🧪 Testing checkbox functionality..."
echo ""

# Test 1: Uncheck all checkboxes
echo "Test 1: Uncheck all protections"
php artisan tinker --execute="
use Pterodactyl\Http\Controllers\Admin\ProtectionController;
use Illuminate\Http\Request;

// Simulate unchecking all checkboxes
\$data = [
    'admin_ids' => '1',
    'access_denied_message' => 'Test message',
];

\$protectionFeatures = [
    'server_delete' => 'Protect server deletion',
    'user_management' => 'Protect user management',
];

foreach (\$protectionFeatures as \$feature => \$description) {
    \$value = isset(\$data['protection_' . \$feature]) ? 'true' : 'false';
    echo \$feature . ': ' . \$value . PHP_EOL;
}
"

echo ""
echo "Test 2: Check server_delete only"
php artisan tinker --execute="
// Simulate checking only server_delete
\$data = [
    'admin_ids' => '1',
    'access_denied_message' => 'Test message',
    'protection_server_delete' => '1',
];

\$protectionFeatures = [
    'server_delete' => 'Protect server deletion',
    'user_management' => 'Protect user management',
];

foreach (\$protectionFeatures as \$feature => \$description) {
    \$value = isset(\$data['protection_' . \$feature]) ? 'true' : 'false';
    echo \$feature . ': ' . \$value . PHP_EOL;
}
"

echo ""
echo "✅ Checkbox logic test completed!"
echo ""
echo "📋 Current actual settings:"
php artisan tinker --execute="
use Pterodactyl\Models\ProtectionSetting;
echo 'Server Delete: ' . ProtectionSetting::isProtectionEnabled('server_delete') . PHP_EOL;
echo 'User Management: ' . ProtectionSetting::isProtectionEnabled('user_management') . PHP_EOL;
echo 'Location Access: ' . ProtectionSetting::isProtectionEnabled('location_access') . PHP_EOL;
"