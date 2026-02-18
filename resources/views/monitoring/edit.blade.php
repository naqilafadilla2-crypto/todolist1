@extends('layouts.sidebar')

@section('title', 'Edit Monitoring')

@section('content')
<style>
    .page-container { max-width: 700px; margin: auto; padding: 20px; }
    .page-header { margin-bottom: 20px; display:flex; justify-content:space-between; align-items:center; }
    .page-header h2 { color: #2c2f7e; font-size: 24px; margin: 0 0 10px 0; }
    .btn-back { background: #6c757d; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; }
    .btn-back:hover { background: #5a6268; }
    .card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
    input, select, textarea { width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; }
    input:focus, select:focus, textarea:focus { border-color: #2c2f7e; }
    textarea { resize: vertical; min-height: 100px; }
    .status-group { display: flex; gap: 20px; margin-top: 10px; }
    .status-item { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .status-item input[type="radio"] { display: none; }
    .dot { width: 20px; height: 20px; border-radius: 50%; border: 2px solid transparent; }
    .green { background-color: #2ecc71; }
    .yellow { background-color: #f1c40f; }
    .red { background-color: #e74c3c; }
    .status-item input[type="radio"]:checked + .dot { border-color: #2c2f7e; box-shadow: 0 0 0 2px rgba(44,47,126,0.3); }
    .error { color: #dc3545; font-size: 13px; margin-top: 5px; }
    .btn-submit { background: #2c2f7e; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; width: 100%; }
    .btn-submit:hover { background: #1f2258; }
    .file-thumb { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #2c2f7e; margin-right:8px; }
    .help-text { font-size: 12px; color: #666; margin-top: 4px; }
</style>

<div class="page-container">
    <div class="page-header">
        <a href="{{ route('monitoring.index') }}" class="btn-back">← Kembali</a>
        <h2>Edit Data Pantauan</h2>
    </div>

    <div class="card">
        <form action="{{ route('monitoring.update', $monitoring->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="applink_id">Nama Aplikasi *</label>
                <select id="applink_id" name="applink_id" required>
                    <option value="">Pilih Aplikasi</option>
                    @foreach($applinks as $applink)
                        <option value="{{ $applink->id }}" {{ old('applink_id', $monitoring->nama_aplikasi) == $applink->name ? 'selected' : '' }}>{{ $applink->name }}</option>
                    @endforeach
                </select>
                @error('applink_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Status *</label>
                <div class="status-group">
                    <label class="status-item">
                        <input type="radio" name="status" value="hijau" {{ old('status', $monitoring->status) == 'hijau' ? 'checked' : '' }} required>
                        <span class="dot green"></span>
                        Hijau
                    </label>
                    <label class="status-item">
                        <input type="radio" name="status" value="kuning" {{ old('status', $monitoring->status) == 'kuning' ? 'checked' : '' }} required>
                        <span class="dot yellow"></span>
                        Kuning
                    </label>
                    <label class="status-item">
                        <input type="radio" name="status" value="merah" {{ old('status', $monitoring->status) == 'merah' ? 'checked' : '' }} required>
                        <span class="dot red"></span>
                        Merah
                    </label>
                </div>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal & Waktu *</label>
                <input type="datetime-local" id="tanggal" name="tanggal" value="{{ old('tanggal', $monitoring->tanggal ? \Carbon\Carbon::parse($monitoring->tanggal)->format('Y-m-d\TH:i') : '') }}" required>
                @error('tanggal')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Foto Saat Ini</label>
                @php
                    $files = $monitoring->file ? json_decode($monitoring->file, true) : [];
                    if (!is_array($files)) {
                        $files = $monitoring->file ? [$monitoring->file] : [];
                    }
                @endphp
                @if(!empty($files))
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:8px;">
                        @foreach($files as $file)
                            <img src="{{ asset('storage/' . $file) }}" alt="File" class="file-thumb" onerror="this.style.display='none';">
                        @endforeach
                    </div>
                @else
                    <div class="help-text">Belum ada foto.</div>
                @endif
                <div class="help-text">Jika mengupload foto baru, semua foto lama akan diganti.</div>
            </div>

            <div class="form-group">
                <label for="file">Ganti Foto (opsional)</label>
                <input type="file" id="file" name="file[]" multiple accept="image/*">
                <div class="help-text">Format: JPG, JPEG, PNG, WEBP (Max 2MB per file)</div>
                @error('file.*')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi">{{ old('deskripsi', $monitoring->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection

