@extends('layouts.sidebar')

@section('title', 'Tambah Data Monitoring')

@section('content')
<style>
/* ===== LAYOUT ===== */
.page-container {
    max-width: 700px;
    margin: auto;
    padding: 20px;
}

.page-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.page-header h2 {
    color: #2c2f7e;
    font-size: 24px;
    margin: 0;
}

.btn-back {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
}

.btn-back:hover {
    background: #5a6268;
}

.card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* ===== FORM ===== */
.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

input, select, textarea {
    width: 100%;
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

input:focus, select:focus, textarea:focus {
    border-color: #2c2f7e;
    outline: none;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

/* ===== STATUS CARD ===== */
.status-box {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.status-card {
    border: 2px solid #ddd;
    border-radius: 12px;
    padding: 15px;
    cursor: pointer;
    transition: 0.2s;
    background: #fff;
}

.status-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.status-card input {
    display: none;
}

.card-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.status-dot {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    flex-shrink: 0;
}

.green { background: #2ecc71; }
.yellow { background: #f1c40f; }
.red { background: #e74c3c; }

.status-card strong {
    font-size: 16px;
    color: #333;
}

.status-card p {
    margin: 4px 0 0;
    font-size: 13px;
    color: #666;
}

/* ACTIVE */
.status-card.hijau input:checked + .card-content {
    border-left: 6px solid #2ecc71;
    padding-left: 10px;
}

.status-card.kuning input:checked + .card-content {
    border-left: 6px solid #f1c40f;
    padding-left: 10px;
}

.status-card.merah input:checked + .card-content {
    border-left: 6px solid #e74c3c;
    padding-left: 10px;
}

/* ===== BUTTON & ERROR ===== */
.btn-submit {
    background: #2c2f7e;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
}

.btn-submit:hover {
    background: #1f2258;
}

.error {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
}

#filePreview {
    display: flex;
    gap: 10px;
    margin-top: 10px;
    flex-wrap: wrap;
}

#filePreview img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #2c2f7e;
}

.help-text {
    font-size: 12px;
    color: #666;
}
</style>

<div class="page-container">
    <div class="page-header">
        <a href="{{ route('monitoring.index') }}" class="btn-back">← Kembali</a>
        <h2>Tambah Data Monitoring</h2>
    </div>

    <div class="card">
        <form action="{{ route('monitoring.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- NAMA APLIKASI --}}
            <div class="form-group">
                <label for="applink_id">Nama Aplikasi *</label>
                <select id="applink_id" name="applink_id" required>
                    <option value="">Pilih Aplikasi</option>
                    @foreach($applinks as $applink)
                        <option value="{{ $applink->id }}"
                            {{ old('applink_id') == $applink->id ? 'selected' : '' }}>
                            {{ $applink->name }}
                        </option>
                    @endforeach
                </select>
                @error('applink_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            {{-- STATUS --}}
            <div class="form-group">
                <label>Status *</label>

                <div class="status-box">
                    <label class="status-card hijau">
                        <input type="radio" name="status" value="hijau" {{ old('status')=='hijau'?'checked':'' }} required>
                        <div class="card-content">
                            <span class="status-dot green"></span>
                            <div>
                                <strong>Hijau</strong>
                                <p>Tampil normal, berjalan dengan baik</p>
                            </div>
                        </div>
                    </label>

                    <label class="status-card kuning">
                        <input type="radio" name="status" value="kuning" {{ old('status')=='kuning'?'checked':'' }} required>
                        <div class="card-content">
                            <span class="status-dot yellow"></span>
                            <div>
                                <strong>Kuning</strong>
                                <p>lambat, tidak responsif</p>
                            </div>
                        </div>
                    </label>

                    <label class="status-card merah">
                        <input type="radio" name="status" value="merah" {{ old('status')=='merah'?'checked':'' }} required>
                        <div class="card-content">
                            <span class="status-dot red"></span>
                            <div>
                                <strong>Merah</strong>
                                <p>tampilan tidak sesuai, muncul error, terkena serangan/hack, tidak tampil apapun</p>
                            </div>
                        </div>
                    </label>
                </div>

                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            {{-- TANGGAL --}}
            <div class="form-group">
                <label for="tanggal">Tanggal & Waktu *</label>
                <input type="datetime-local" id="tanggal" name="tanggal" readonly required>
            </div>

            {{-- FOTO --}}
            <div class="form-group">
                <label>Foto</label>
                <input type="file" id="file" name="file[]" multiple accept="image/*">
                <div class="help-text">Max 2MB / foto</div>
                <div id="filePreview"></div>
            </div>

            {{-- DESKRIPSI --}}
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi">{{ old('deskripsi') }}</textarea>
            </div>

            <button class="btn-submit">Simpan Data</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const now = new Date();

    // Koreksi ke WIB (UTC +7)
    const wibTime = new Date(now.getTime() + (7 * 60 * 60 * 1000));

    const year   = wibTime.getUTCFullYear();
    const month  = String(wibTime.getUTCMonth() + 1).padStart(2, '0');
    const day    = String(wibTime.getUTCDate()).padStart(2, '0');
    const hour   = String(wibTime.getUTCHours()).padStart(2, '0');
    const minute = String(wibTime.getUTCMinutes()).padStart(2, '0');

    document.getElementById('tanggal').value =
        `${year}-${month}-${day}T${hour}:${minute}`;
});

// preview foto
document.getElementById('file').addEventListener('change', e => {
    const preview = document.getElementById('filePreview');
    preview.innerHTML = '';
    [...e.target.files].forEach(file => {
        if (file.type.startsWith('image')) {
            const r = new FileReader();
            r.onload = ev => {
                const img = document.createElement('img');
                img.src = ev.target.result;
                preview.appendChild(img);
            };
            r.readAsDataURL(file);
        }
    });
});
</script>

@endsection
