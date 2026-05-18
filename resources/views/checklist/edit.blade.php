@extends('layouts.sidebar')

@section('title', 'Edit Checklist')

@section('content')
<style>
    .page-container {
        max-width: 800px;
        margin: auto;
        padding: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-header h2 {
        color: #0a978e;
        font-size: 24px;
        margin: 0;
    }

    .form-card {
        background: #fff;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.05);
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #dfe3ea;
        border-radius: 10px;
        font-size: 14px;
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-primary,
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 10px;
        color: #fff;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-primary {
        background: #0a978e;
    }

    .btn-primary:hover {
        background: #08786f;
    }

    .btn-secondary {
        background: #6c757d;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
</style>

<div class="page-container">
    <div class="page-header">
        <h2>Edit Checklist</h2>
        <a href="{{ route('checklist.index') }}" class="btn-secondary">← Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('checklist.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Perangkat</label>
                <input type="text" name="nama_perangkat" value="{{ old('nama_perangkat', $item->nama_perangkat) }}" required>
            </div>

            <div class="form-group">
                <label>Tanggal Kadaluarsa</label>
                <input type="date" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa', $item->tanggal_kadaluarsa) }}" required>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan">{{ old('keterangan', $item->keterangan) }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('checklist.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
