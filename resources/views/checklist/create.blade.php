@extends('layouts.sidebar')

@section('title', 'Tambah Checklist')

@section('content')
<style>
    .page-container {
        max-width: 900px;
        margin: auto;
        padding: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-header h2 {
        color: #0a978e;
        font-size: 26px;
        margin: 0;
    }

    .form-card {
        background: #fff;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2f3a56;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #dce3ef;
        border-radius: 12px;
        font-size: 14px;
        color: #223254;
        background: #f8fbff;
        transition: border-color 0.2s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #0a978e;
        background: #fff;
    }

    .form-group textarea {
        min-height: 140px;
        resize: vertical;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .btn-primary,
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        border-radius: 12px;
        border: none;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .btn-primary {
        background: #0a978e;
    }

    .btn-primary:hover {
        background: #08786f;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #6c757d;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }

    .alert-error {
        background: #fbe8e8;
        color: #842029;
        border: 1px solid #f5c2c7;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .page-container {
            padding: 16px;
        }

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .page-header h2 {
            font-size: 22px;
        }

        .btn-secondary {
            width: 100%;
            justify-content: center;
        }

        .form-card {
            padding: 20px;
        }

        .form-group input,
        .form-group textarea {
            padding: 12px 14px;
            font-size: 15px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
        }
    }
</style>

<div class="page-container">
    <div class="page-header">
        <h2>Tambah Checklist Expired Date</h2>
        <a href="{{ route('checklist.index') }}" class="btn-secondary">← Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('checklist.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Perangkat</label>
                <input type="text" name="nama_perangkat" value="{{ old('nama_perangkat') }}" required>
            </div>

            <div class="form-group">
                <label>Tanggal Kadaluarsa</label>
                <input type="date" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa') }}" required>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan">{{ old('keterangan') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Simpan Checklist</button>
                <a href="{{ route('checklist.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
