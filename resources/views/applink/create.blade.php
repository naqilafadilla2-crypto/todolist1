@extends('layouts.sidebar')

@section('title', 'Tambah Aplikasi')

@section('content')
<style>
    .page-container { max-width: 600px; margin:auto; padding:20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 16px; }
    .page-header h2 { margin:0; color:#0a978e; }
    .btn-back { background:#6c757d; color:#fff; padding:6px 14px; border-radius:6px; text-decoration:none; font-size:14px; }
    .card { background:#fff; border-radius:12px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
    .form-group { margin-bottom:16px; }
    label { display:block; margin-bottom:6px; font-weight:600; color:#333; }
    input { width:100%; padding:9px 10px; border-radius:8px; border:2px solid #e1e4f0; font-size:14px; outline:none; }
    input:focus { border-color:#0a978e; }
    .error { color:#e74c3c; font-size:12px; margin-top:4px; }
    .btn-submit { background:#0a978e; color:#fff; border:none; padding:10px 18px; border-radius:8px; font-size:15px; cursor:pointer; width:100%; }
    .btn-submit:hover { background:#0a978e; }
</style>

<div class="page-container">
    <div class="page-header">
        <h2>Tambah Aplikasi ke Menu</h2>
        <a href="{{ route('applink.index') }}" class="btn-back">← Kembali</a>
    </div>

    <div class="card">
        <form action="{{ route('applink.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Nama Aplikasi</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="url">URL Website</label>
                <input type="url" id="url" name="url" value="{{ old('url') }}" placeholder="https://contoh.com" required>
                @error('url')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="image">Logo / Foto (opsional)</label>
                <input type="file" id="image" name="image" accept="image/*">
                @error('image')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Simpan</button>
        </form>
    </div>
</div>
@endsection

