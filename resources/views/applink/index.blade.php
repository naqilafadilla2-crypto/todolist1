@extends('layouts.sidebar')

@section('title', 'Kelola Aplikasi')

@section('content')
<style>
    .page-container { max-width: 1120px; margin: auto; padding: 24px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 16px; }
    .page-header h2 { margin: 0; color: #0a978e; font-size: 26px; }
    .btn-add { background: #0a978e; color: #fff; padding: 10px 18px; border-radius: 12px; text-decoration: none; font-size: 14px; box-shadow: 0 10px 24px rgba(10,151,142,0.12); transition: transform 0.2s ease, background 0.2s ease; }
    .btn-add:hover { background: #08786f; transform: translateY(-1px); }
    .table-wrapper { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 16px 40px rgba(15,23,42,0.08); }
    th, td { padding: 14px 16px; border-bottom: 1px solid #eef2f6; text-align: left; vertical-align: middle; }
    thead { background: #0a978e; color: #fff; }
    tbody tr { transition: background 0.2s ease; }
    tbody tr:hover { background: #f7fbff; }
    .url-column { max-width: 360px; }
    .url-link { display: inline-block; max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #1d1d1d; text-decoration: none; }
    .url-link:hover { text-decoration: underline; }
    .logo-cell img { width: 60px; height: 42px; object-fit: cover; border-radius: 10px; border: 1px solid #e1e8f0; }
    .logo-placeholder { display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 42px; border-radius: 10px; background: #f2f4f8; color: #7a7a7a; font-size: 12px; border: 1px solid #e1e8f0; }
    .btn-edit, .btn-delete { display: inline-flex; align-items: center; justify-content: center; padding: 8px 14px; border-radius: 10px; font-size: 13px; color: #fff; text-decoration: none; border: none; cursor: pointer; transition: transform 0.2s ease, background 0.2s ease; }
    .btn-edit { background: #3498db; }
    .btn-edit:hover { background: #2980b9; transform: translateY(-1px); }
    .btn-delete { background: #e74c3c; }
    .btn-delete:hover { background: #c0392b; transform: translateY(-1px); }
    .action-buttons { display: flex; flex-wrap: wrap; gap: 8px; }
    .no-data { text-align: center; color: #7a7a7a; padding: 24px 0; }
</style>

<div class="page-container">
    <div class="page-header">
        <h2>Kelola Aplikasi di Menu</h2>
        <a href="{{ route('applink.create') }}" class="btn-add">+ Tambah Aplikasi</a>
    </div>

    @if(session('success'))
        <div style="background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:10px 14px;border-radius:8px;margin-bottom:15px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>URL</th>
                    <th>Terakhir Diperbarui</th>
                    <th>Logo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($apps as $app)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $app->name }}</td>
                    <td class="url-column">
                        <a href="{{ $app->url }}" target="_blank" rel="noopener noreferrer" class="url-link" title="{{ $app->url }}">{{ $app->url }}</a>
                    </td>
                    <td>{{ $app->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}</td>
                    <td class="logo-cell">
                        @if($app->image)
                            <img src="{{ asset('storage/'.$app->image) }}" alt="{{ $app->name }}">
                        @else
                            <span class="logo-placeholder">No Logo</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('applink.edit', $app->id) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('applink.destroy', $app->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Hapus aplikasi ini dari menu?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="no-data">Belum ada aplikasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

