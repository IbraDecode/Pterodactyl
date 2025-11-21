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
<script>
$(document).ready(function() {
    // Handle radio button style changes
    $('input[type="radio"]').on('change', function() {
        var name = $(this).attr('name');
        var value = $(this).val();
        
        // Update button styles
        $('input[name="' + name + '"]').each(function() {
            if ($(this).val() === value) {
                $(this).parent().removeClass('btn-default').addClass(value === '1' ? 'btn-success' : 'btn-danger');
            } else {
                $(this).parent().removeClass('btn-success btn-danger').addClass('btn-default');
            }
        });
    });
});
</script>
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
                    <h4>Fitur Proteksi yang Aktif</h4>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Hapus Server</label>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-{{ $settings['protection_server_delete'] ? 'success' : 'default' }}">
                                        <input type="radio" name="protection_server_delete" value="1" 
                                               {{ $settings['protection_server_delete'] ? 'checked' : '' }}> Aktif
                                    </label>
                                    <label class="btn btn-{{ !$settings['protection_server_delete'] ? 'danger' : 'default' }}">
                                        <input type="radio" name="protection_server_delete" value="0" 
                                               {{ !$settings['protection_server_delete'] ? 'checked' : '' }}> Nonaktif
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Manajemen User</label>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-{{ $settings['protection_user_management'] ? 'success' : 'default' }}">
                                        <input type="radio" name="protection_user_management" value="1" 
                                               {{ $settings['protection_user_management'] ? 'checked' : '' }}> Aktif
                                    </label>
                                    <label class="btn btn-{{ !$settings['protection_user_management'] ? 'danger' : 'default' }}">
                                        <input type="radio" name="protection_user_management" value="0" 
                                               {{ !$settings['protection_user_management'] ? 'checked' : '' }}> Nonaktif
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Akses Location</label>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-{{ $settings['protection_location_access'] ? 'success' : 'default' }}">
                                        <input type="radio" name="protection_location_access" value="1" 
                                               {{ $settings['protection_location_access'] ? 'checked' : '' }}> Aktif
                                    </label>
                                    <label class="btn btn-{{ !$settings['protection_location_access'] ? 'danger' : 'default' }}">
                                        <input type="radio" name="protection_location_access" value="0" 
                                               {{ !$settings['protection_location_access'] ? 'checked' : '' }}> Nonaktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Akses Nodes</label>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-{{ $settings['protection_nodes_access'] ? 'success' : 'default' }}">
                                        <input type="radio" name="protection_nodes_access" value="1" 
                                               {{ $settings['protection_nodes_access'] ? 'checked' : '' }}> Aktif
                                    </label>
                                    <label class="btn btn-{{ !$settings['protection_nodes_access'] ? 'danger' : 'default' }}">
                                        <input type="radio" name="protection_nodes_access" value="0" 
                                               {{ !$settings['protection_nodes_access'] ? 'checked' : '' }}> Nonaktif
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Akses Nests</label>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-{{ $settings['protection_nests_access'] ? 'success' : 'default' }}">
                                        <input type="radio" name="protection_nests_access" value="1" 
                                               {{ $settings['protection_nests_access'] ? 'checked' : '' }}> Aktif
                                    </label>
                                    <label class="btn btn-{{ !$settings['protection_nests_access'] ? 'danger' : 'default' }}">
                                        <input type="radio" name="protection_nests_access" value="0" 
                                               {{ !$settings['protection_nests_access'] ? 'checked' : '' }}> Nonaktif
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Modifikasi Server</label>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-{{ $settings['protection_server_modification'] ? 'success' : 'default' }}">
                                        <input type="radio" name="protection_server_modification" value="1" 
                                               {{ $settings['protection_server_modification'] ? 'checked' : '' }}> Aktif
                                    </label>
                                    <label class="btn btn-{{ !$settings['protection_server_modification'] ? 'danger' : 'default' }}">
                                        <input type="radio" name="protection_server_modification" value="0" 
                                               {{ !$settings['protection_server_modification'] ? 'checked' : '' }}> Nonaktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Akses File</label>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-{{ $settings['protection_file_access'] ? 'success' : 'default' }}">
                                        <input type="radio" name="protection_file_access" value="1" 
                                               {{ $settings['protection_file_access'] ? 'checked' : '' }}> Aktif
                                    </label>
                                    <label class="btn btn-{{ !$settings['protection_file_access'] ? 'danger' : 'default' }}">
                                        <input type="radio" name="protection_file_access" value="0" 
                                               {{ !$settings['protection_file_access'] ? 'checked' : '' }}> Nonaktif
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Akses Settings</label>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-{{ $settings['protection_settings_access'] ? 'success' : 'default' }}">
                                        <input type="radio" name="protection_settings_access" value="1" 
                                               {{ $settings['protection_settings_access'] ? 'checked' : '' }}> Aktif
                                    </label>
                                    <label class="btn btn-{{ !$settings['protection_settings_access'] ? 'danger' : 'default' }}">
                                        <input type="radio" name="protection_settings_access" value="0" 
                                               {{ !$settings['protection_settings_access'] ? 'checked' : '' }}> Nonaktif
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Proteksi Manajemen API</label>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-{{ $settings['protection_api_management'] ? 'success' : 'default' }}">
                                        <input type="radio" name="protection_api_management" value="1" 
                                               {{ $settings['protection_api_management'] ? 'checked' : '' }}> Aktif
                                    </label>
                                    <label class="btn btn-{{ !$settings['protection_api_management'] ? 'danger' : 'default' }}">
                                        <input type="radio" name="protection_api_management" value="0" 
                                               {{ !$settings['protection_api_management'] ? 'checked' : '' }}> Nonaktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="protection_user_management" value="1" 
                                           {{ $settings['protection_user_management'] ? 'checked' : '' }}>
                                    Proteksi Manajemen User
                                </label>
                                <input type="hidden" name="protection_user_management" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="protection_location_access" value="1" 
                                           {{ $settings['protection_location_access'] ? 'checked' : '' }}>
                                    Proteksi Akses Location
                                </label>
                                <input type="hidden" name="protection_location_access" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="protection_nodes_access" value="1" 
                                           {{ $settings['protection_nodes_access'] ? 'checked' : '' }}>
                                    Proteksi Akses Nodes
                                </label>
                                <input type="hidden" name="protection_nodes_access" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="protection_nests_access" value="1" 
                                           {{ $settings['protection_nests_access'] ? 'checked' : '' }}>
                                    Proteksi Akses Nests
                                </label>
                                <input type="hidden" name="protection_nests_access" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="protection_server_modification" value="1" 
                                           {{ $settings['protection_server_modification'] ? 'checked' : '' }}>
                                    Proteksi Modifikasi Server
                                </label>
                                <input type="hidden" name="protection_server_modification" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="protection_file_access" value="1" 
                                           {{ $settings['protection_file_access'] ? 'checked' : '' }}>
                                    Proteksi Akses File
                                </label>
                                <input type="hidden" name="protection_file_access" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="protection_settings_access" value="1" 
                                           {{ $settings['protection_settings_access'] ? 'checked' : '' }}>
                                    Proteksi Akses Settings
                                </label>
                                <input type="hidden" name="protection_settings_access" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="protection_api_management" value="1" 
                                           {{ $settings['protection_api_management'] ? 'checked' : '' }}>
                                    Proteksi Manajemen API
                                </label>
                                <input type="hidden" name="protection_api_management" value="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan Pengaturan
                    </button>
                    <a href="{{ route('admin.protection.apply') }}" class="btn btn-success" 
                       onclick="return confirm('Apakah Anda yakin ingin menerapkan proteksi sekarang?')">
                        <i class="fa fa-shield"></i> Terapkan Proteksi
                    </a>
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
                        <li>Pilih fitur proteksi yang ingin diaktifkan</li>
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