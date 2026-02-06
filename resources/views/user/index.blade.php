@extends('layouts.sidebar')

@section('title', 'User Management')

@section('content')
<style>
    .page-container {
        max-width: 1200px;
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
        color: #2c2f7e;
        font-size: 24px;
        margin: 0;
    }

    .btn-add {
        background: #2c2f7e;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.3s;
    }

    .btn-add:hover {
        background: #1f2258;
    }

    .alert {
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    table {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    thead {
        background: #2c2f7e;
        color: white;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    tbody tr {
        border-bottom: 1px solid #eee;
    }

    tbody tr:hover {
        background: #f8f9fa;
    }

    .badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-admin {
        background: #ffc107;
        color: #000;
    }

    .badge-user {
        background: #6c757d;
        color: white;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        margin-right: 5px;
        display: inline-block;
    }

    .btn-edit {
        background: #28a745;
        color: white;
    }

    .btn-edit:hover {
        background: #218838;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
        border: none;
        cursor: pointer;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #999;
    }
</style>

<div class="page-container">
    <div class="page-header">
        <h2>User</h2>
        <a href="{{ route('user.create') }}" class="btn-add">+ Tambah User</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                </td>
                <td>{{ $user->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('user.edit', $user->id) }}" class="btn-action btn-edit">Edit</a>
                    
                    @if($user->id != auth()->id())
                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete">Hapus</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="no-data">Belum ada user</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
