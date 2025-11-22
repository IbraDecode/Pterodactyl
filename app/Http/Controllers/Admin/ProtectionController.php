<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Models\ProtectionSetting;

class ProtectionController extends Controller
{
    public function __construct(
        protected AlertsMessageBag $alert,
        protected ViewFactory $view
    ) {
    }

    public function index(): View
    {
        $user = Auth::user();
        if (!$user || !in_array($user->id, ProtectionSetting::getAdminIds())) {
            abort(403, ProtectionSetting::getAccessDeniedMessage());
        }

        $settings = [
            'admin_ids' => ProtectionSetting::get('admin_ids', '1'),
            'access_denied_message' => ProtectionSetting::getAccessDeniedMessage(),
            'protection_server_delete' => ProtectionSetting::isProtectionEnabled('server_delete'),
            'protection_user_management' => ProtectionSetting::isProtectionEnabled('user_management'),
            'protection_location_access' => ProtectionSetting::isProtectionEnabled('location_access'),
            'protection_nodes_access' => ProtectionSetting::isProtectionEnabled('nodes_access'),
            'protection_nests_access' => ProtectionSetting::isProtectionEnabled('nests_access'),
            'protection_server_modification' => ProtectionSetting::isProtectionEnabled('server_modification'),
            'protection_file_access' => ProtectionSetting::isProtectionEnabled('file_access'),
            'protection_settings_access' => ProtectionSetting::isProtectionEnabled('settings_access'),
            'protection_api_management' => ProtectionSetting::isProtectionEnabled('api_management'),
            'show_credit' => ProtectionSetting::get('show_credit', 'true'),
            'credit_text' => ProtectionSetting::get('credit_text', 'Proteksi Tools By'),
            'credit_author' => ProtectionSetting::get('credit_author', 'Ibra Decode'),
            'credit_link' => ProtectionSetting::get('credit_link', 'https://t.me/ibradecode'),
        ];

        return $this->view->make('admin.protection.index_toggle', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !in_array($user->id, ProtectionSetting::getAdminIds())) {
            abort(403, ProtectionSetting::getAccessDeniedMessage());
        }

        $data = $request->validate([
            'admin_ids' => 'required|string',
            'access_denied_message' => 'required|string',
            'protection_server_delete' => 'nullable',
            'protection_user_management' => 'nullable',
            'protection_location_access' => 'nullable',
            'protection_nodes_access' => 'nullable',
            'protection_nests_access' => 'nullable',
            'protection_server_modification' => 'nullable',
            'protection_file_access' => 'nullable',
            'protection_settings_access' => 'nullable',
            'protection_api_management' => 'nullable',
            'show_credit' => 'nullable',
            'credit_text' => 'nullable|string',
            'credit_author' => 'nullable|string',
            'credit_link' => 'nullable|string',
        ]);

        ProtectionSetting::set('admin_ids', $data['admin_ids'], 'List of admin IDs that can access protection settings (comma separated)');
        ProtectionSetting::set('access_denied_message', $data['access_denied_message'], 'Message shown when access is denied');

        $protectionFeatures = [
            'server_delete' => 'Protect server deletion',
            'user_management' => 'Protect user management',
            'location_access' => 'Protect location access',
            'nodes_access' => 'Protect nodes access',
            'nests_access' => 'Protect nests access',
            'server_modification' => 'Protect server modification',
            'file_access' => 'Protect file access',
            'settings_access' => 'Protect settings access',
            'api_management' => 'Protect API management',
        ];

        foreach ($protectionFeatures as $feature => $description) {
            // Toggle switch dengan hidden field - checkbox hanya mengirim value jika dicentang
            $value = isset($data["protection_{$feature}"]) ? 'true' : 'false';
            ProtectionSetting::set("protection_{$feature}", $value, $description);
        }

        // Save credit settings
        ProtectionSetting::set('show_credit', isset($data['show_credit']) ? 'true' : 'false', 'Show credit in admin panel');
        ProtectionSetting::set('credit_text', $data['credit_text'] ?? 'Proteksi Tools By', 'Credit text to display');
        ProtectionSetting::set('credit_author', $data['credit_author'] ?? 'Ibra Decode', 'Credit author name');
        ProtectionSetting::set('credit_link', $data['credit_link'] ?? 'https://t.me/ibradecode', 'Credit author link');

        $this->alert->success('Pengaturan proteksi berhasil diperbarui!')->flash();

        return redirect()->route('admin.protection.index');
    }

    public function applyProtection(): RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !in_array($user->id, ProtectionSetting::getAdminIds())) {
            abort(403, ProtectionSetting::getAccessDeniedMessage());
        }

        $scriptPath = base_path('protect.sh');
        if (file_exists($scriptPath)) {
            $output = shell_exec("bash {$scriptPath} 2>&1");
            $this->alert->success("Proteksi berhasil diterapkan! Output: {$output}")->flash();
        } else {
            $this->alert->danger('File protect.sh tidak ditemukan!')->flash();
        }

        return redirect()->route('admin.protection.index');
    }
}
