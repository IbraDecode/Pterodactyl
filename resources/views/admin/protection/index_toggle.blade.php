@extends('layouts.admin')

@section('title')
    Proteksi Panel
@endsection

@section('content-header')
    <h1>Pengaturan Proteksi Panel</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Proteksi</li>
    </ol>
@endsection

@push('scripts')
<style>
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
}

input:checked + .slider {
    background-color: #5cb85c;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.protection-item {
    background: #f8f9fa;
    padding: 15px;
    margin: 10px 0;
    border-radius: 5px;
    border-left: 4px solid #ddd;
}

.protection-item.active {
    border-left-color: #5cb85c;
}

.protection-item.inactive {
    border-left-color: #d9534f;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-xs-12">
        @if(session()->has('alert'))
            <div class="alert alert-{{ session()->get('alert_type', 'success') }}">
                {{ session()->get('alert') }}
            </div>
        @endif

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Konfigurasi Proteksi</h3>
            </div>
            <form action="{{ route('admin.protection.update') }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="admin_ids">Admin IDs</label>
                                <input type="text" id="admin_ids" name="admin_ids" class="form-control" 
                                       value="{{ $settings['admin_ids'] }}" required>
                                <p class="help-block">ID admin yang diizinkan mengakses proteksi (pisahkan dengan koma)</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="access_denied_message">Pesan Akses Ditolak</label>
                                <textarea id="access_denied_message" name="access_denied_message" 
                                          class="form-control" rows="3" required>{{ $settings['access_denied_message'] }}</textarea>
                                <p class="help-block">Pesan yang ditampilkan saat akses ditolak</p>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4>Pengaturan Credit</h4>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tampilkan Credit</label>
                                <select name="show_credit" class="form-control">
                                    <option value="1" {{ $settings['show_credit'] ? 'selected' : '' }}>✅ Ya</option>
                                    <option value="0" {{ !$settings['show_credit'] ? 'selected' : '' }}>❌ Tidak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Teks Credit</label>
                                <input type="text" name="credit_text" class="form-control" 
                                       value="{{ $settings['credit_text'] }}" placeholder="Proteksi Tools By">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Author</label>
                                <input type="text" name="credit_author" class="form-control" 
                                       value="{{ $settings['credit_author'] }}" placeholder="Ibra Decode">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Link</label>
                                <input type="text" name="credit_link" class="form-control" 
                                       value="{{ $settings['credit_link'] }}" placeholder="https://t.me/ibradecode">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4>Fitur Proteksi yang Aktif</h4>
                    
                    <div class="protection-item {{ $settings['protection_server_delete'] ? 'active' : 'inactive' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    </svg>
                                    Proteksi Hapus Server
                                </h5>
                                <p class="help-block">Mencegah penghapusan server oleh user yang tidak berwenang</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <label class="switch">
                                    <input type="hidden" name="protection_server_delete" value="0">
                                    <input type="checkbox" name="protection_server_delete" value="1" 
                                           {{ $settings['protection_server_delete'] ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <br><small>{{ $settings['protection_server_delete'] ? 'Aktif' : 'Nonaktif' }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="protection-item {{ $settings['protection_user_management'] ? 'active' : 'inactive' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    Proteksi Manajemen User
                                </h5>
                                <p class="help-block">Mencegah modifikasi data user oleh admin yang tidak berwenang</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <label class="switch">
                                    <input type="hidden" name="protection_user_management" value="0">
                                    <input type="checkbox" name="protection_user_management" value="1" 
                                           {{ $settings['protection_user_management'] ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <br><small>{{ $settings['protection_user_management'] ? 'Aktif' : 'Nonaktif' }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="protection-item {{ $settings['protection_location_access'] ? 'active' : 'inactive' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    Proteksi Akses Location
                                </h5>
                                <p class="help-block">Membatasi akses menu Location hanya untuk admin berwenang</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <label class="switch">
                                    <input type="hidden" name="protection_location_access" value="0">
                                    <input type="checkbox" name="protection_location_access" value="1" 
                                           {{ $settings['protection_location_access'] ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <br><small>{{ $settings['protection_location_access'] ? 'Aktif' : 'Nonaktif' }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="protection-item {{ $settings['protection_nodes_access'] ? 'active' : 'inactive' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                                        <line x1="6" y1="6" x2="6.01" y2="6"></line>
                                        <line x1="6" y1="18" x2="6.01" y2="18"></line>
                                    </svg>
                                    Proteksi Akses Nodes
                                </h5>
                                <p class="help-block">Membatasi akses menu Nodes hanya untuk admin berwenang</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <label class="switch">
                                    <input type="hidden" name="protection_nodes_access" value="0">
                                    <input type="checkbox" name="protection_nodes_access" value="1" 
                                           {{ $settings['protection_nodes_access'] ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <br><small>{{ $settings['protection_nodes_access'] ? 'Aktif' : 'Nonaktif' }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="protection-item {{ $settings['protection_nests_access'] ? 'active' : 'inactive' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                    Proteksi Akses Nests
                                </h5>
                                <p class="help-block">Membatasi akses menu Nests hanya untuk admin berwenang</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <label class="switch">
                                    <input type="hidden" name="protection_nests_access" value="0">
                                    <input type="checkbox" name="protection_nests_access" value="1" 
                                           {{ $settings['protection_nests_access'] ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <br><small>{{ $settings['protection_nests_access'] ? 'Aktif' : 'Nonaktif' }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="protection-item {{ $settings['protection_server_modification'] ? 'active' : 'inactive' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path d="M12 1v6m0 6v6m4.22-13.22l4.24 4.24M1.54 9.96l4.24 4.24M1.54 14.04l4.24-4.24M18.46 14.04l4.24-4.24"></path>
                                    </svg>
                                    Proteksi Modifikasi Server
                                </h5>
                                <p class="help-block">Mencegah modifikasi detail server oleh user tidak berwenang</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <label class="switch">
                                    <input type="hidden" name="protection_server_modification" value="0">
                                    <input type="checkbox" name="protection_server_modification" value="1" 
                                           {{ $settings['protection_server_modification'] ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <br><small>{{ $settings['protection_server_modification'] ? 'Aktif' : 'Nonaktif' }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="protection-item {{ $settings['protection_file_access'] ? 'active' : 'inactive' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                        <polyline points="13 2 13 9 20 9"></polyline>
                                    </svg>
                                    Proteksi Akses File
                                </h5>
                                <p class="help-block">Mencegah akses file server orang lain</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <label class="switch">
                                    <input type="hidden" name="protection_file_access" value="0">
                                    <input type="checkbox" name="protection_file_access" value="1" 
                                           {{ $settings['protection_file_access'] ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <br><small>{{ $settings['protection_file_access'] ? 'Aktif' : 'Nonaktif' }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="protection-item {{ $settings['protection_settings_access'] ? 'active' : 'inactive' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                    </svg>
                                    Proteksi Akses Settings
                                </h5>
                                <p class="help-block">Membatasi akses menu Settings hanya untuk admin berwenang</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <label class="switch">
                                    <input type="hidden" name="protection_settings_access" value="0">
                                    <input type="checkbox" name="protection_settings_access" value="1" 
                                           {{ $settings['protection_settings_access'] ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <br><small>{{ $settings['protection_settings_access'] ? 'Aktif' : 'Nonaktif' }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="protection-item {{ $settings['protection_api_management'] ? 'active' : 'inactive' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                        <rect x="7" y="7" width="10" height="10" rx="2" ry="2"></rect>
                                        <path d="M12 17V22"></path>
                                        <path d="M12 7V2"></path>
                                        <path d="M17 12H22"></path>
                                        <path d="M7 12H2"></path>
                                    </svg>
                                    Proteksi Manajemen API
                                </h5>
                                <p class="help-block">Membatasi pembuatan dan manajemen API Key</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <label class="switch">
                                    <input type="hidden" name="protection_api_management" value="0">
                                    <input type="checkbox" name="protection_api_management" value="1" 
                                           {{ $settings['protection_api_management'] ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <br><small>{{ $settings['protection_api_management'] ? 'Aktif' : 'Nonaktif' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan Pengaturan
                    </button>
                    <form action="{{ route('admin.protection.apply') }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success" 
                                onclick="return confirm('Apakah Anda yakin ingin menerapkan proteksi sekarang?')">
                            <i class="fa fa-shield"></i> Terapkan Proteksi
                        </button>
                    </form>
                </div>
            </form>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Cara Penggunaan</h3>
            </div>
            <div class="box-body">
                <div class="alert alert-info">
                    <h4><i class="fa fa-info-circle"></i> Panduan Penggunaan</h4>
                    <ol>
                        <li>Atur Admin IDs yang diizinkan mengakses halaman proteksi ini</li>
                        <li>Pesan akses ditolak akan muncul saat user tidak memiliki izin</li>
                        <li>Gunakan toggle switch untuk mengaktifkan/nonaktifkan fitur proteksi</li>
                        <li>Klik "Simpan Pengaturan" untuk menyimpan konfigurasi</li>
                        <li>Klik "Terapkan Proteksi" untuk menjalankan script protect.sh dengan konfigurasi terbaru</li>
                    </ol>
                </div>
                
                <div class="alert alert-warning">
                    <h4><i class="fa fa-warning"></i> Perhatian</h4>
                    <p>Setelah menerapkan proteksi, hanya admin yang ID-nya terdaftar di "Admin IDs" yang dapat mengakses fitur-fitur yang diproteksi.</p>
                    <p>Pastikan Anda memasukkan ID admin Anda dengan benar sebelum menerapkan proteksi!</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle toggle switches
    $('input[type="checkbox"]').on('change', function() {
        var protectionItem = $(this).closest('.protection-item');
        var statusText = $(this).is(':checked') ? 'Aktif' : 'Nonaktif';
        
        if ($(this).is(':checked')) {
            protectionItem.removeClass('inactive').addClass('active');
        } else {
            protectionItem.removeClass('active').addClass('inactive');
        }
        
        protectionItem.find('small').text(statusText);
    });
});
</script>
@endpush
@endsection