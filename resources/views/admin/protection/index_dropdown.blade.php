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
                    <h4>Fitur Proteksi yang Aktif</h4>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px;">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    </svg>
                                    Proteksi Hapus Server
                                </label>
                                <select name="protection_server_delete" class="form-control">
                                    <option value="1" {{ $settings['protection_server_delete'] ? 'selected' : '' }}>✅ Aktif</option>
                                    <option value="0" {{ !$settings['protection_server_delete'] ? 'selected' : '' }}>❌ Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px;">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    Proteksi Manajemen User
                                </label>
                                <select name="protection_user_management" class="form-control">
                                    <option value="1" {{ $settings['protection_user_management'] ? 'selected' : '' }}>✅ Aktif</option>
                                    <option value="0" {{ !$settings['protection_user_management'] ? 'selected' : '' }}>❌ Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px;">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    Proteksi Akses Location
                                </label>
                                <select name="protection_location_access" class="form-control">
                                    <option value="1" {{ $settings['protection_location_access'] ? 'selected' : '' }}>✅ Aktif</option>
                                    <option value="0" {{ !$settings['protection_location_access'] ? 'selected' : '' }}>❌ Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Akses Nodes</label>
                                <select name="protection_nodes_access" class="form-control">
                                    <option value="1" {{ $settings['protection_nodes_access'] ? 'selected' : '' }}>🟢 Aktif</option>
                                    <option value="0" {{ !$settings['protection_nodes_access'] ? 'selected' : '' }}>🔴 Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Akses Nests</label>
                                <select name="protection_nests_access" class="form-control">
                                    <option value="1" {{ $settings['protection_nests_access'] ? 'selected' : '' }}>🟢 Aktif</option>
                                    <option value="0" {{ !$settings['protection_nests_access'] ? 'selected' : '' }}>🔴 Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Modifikasi Server</label>
                                <select name="protection_server_modification" class="form-control">
                                    <option value="1" {{ $settings['protection_server_modification'] ? 'selected' : '' }}>🟢 Aktif</option>
                                    <option value="0" {{ !$settings['protection_server_modification'] ? 'selected' : '' }}>🔴 Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Akses File</label>
                                <select name="protection_file_access" class="form-control">
                                    <option value="1" {{ $settings['protection_file_access'] ? 'selected' : '' }}>🟢 Aktif</option>
                                    <option value="0" {{ !$settings['protection_file_access'] ? 'selected' : '' }}>🔴 Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Akses Settings</label>
                                <select name="protection_settings_access" class="form-control">
                                    <option value="1" {{ $settings['protection_settings_access'] ? 'selected' : '' }}>🟢 Aktif</option>
                                    <option value="0" {{ !$settings['protection_settings_access'] ? 'selected' : '' }}>🔴 Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Manajemen API</label>
                                <select name="protection_api_management" class="form-control">
                                    <option value="1" {{ $settings['protection_api_management'] ? 'selected' : '' }}>🟢 Aktif</option>
                                    <option value="0" {{ !$settings['protection_api_management'] ? 'selected' : '' }}>🔴 Nonaktif</option>
                                </select>
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
                        <li>Pilih status proteksi (Aktif/Nonaktif) untuk setiap fitur</li>
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
@endsection