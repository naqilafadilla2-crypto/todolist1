@extends('layouts.sidebar')

@section('title', 'Edit User')

@section('content')
<style>
    .page-container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .page-header h2 {
        color: #2c2f7e;
        font-size: 24px;
        margin: 0 0 10px 0;
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

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    input, select {
        width: 100%;
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
    }

    input:focus, select:focus {
        border-color: #2c2f7e;
    }

    .error {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }

    .btn-submit {
        background: #2c2f7e;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
    }

    .btn-submit:hover {
        background: #1f2258;
    }

    .help-text {
        font-size: 12px;
        color: #666;
        margin-top: 4px;
    }
</style>

<div class="page-container">
    <div class="page-header">
        <a href="{{ route('user.index') }}" class="btn-back">← Kembali</a>
        <h2>Edit Pengguna</h2>
    </div>

    <div class="card">
        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password">
                <div class="help-text">Kosongkan jika tidak ingin mengubah password</div>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Perbarui Pengguna</button>
        </form>
    </div>
</div>
@endsection
