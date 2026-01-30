@extends('layouts.sidebar')

@section('title', 'Kelola Aplikasi')

@section('content')
<style>
    .page-container { max-width: 900px; margin:auto; padding:20px; }
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
    .page-header h2 { margin:0; color:#2c2f7e; }
    .btn-add { background:#2c2f7e; color:#fff; padding:8px 16px; border-radius:8px; text-decoration:none; font-size:14px; }
    table { width:100%; border-collapse:collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06); }
    th,td { padding:10px 12px; border-bottom:1px solid #eee; text-align:left; }
    thead { background:#2c2f7e; color:#fff; }
    .btn-delete { background:#e74c3c; color:#fff; border:none; padding:6px 12px; border-radius:6px; font-size:13px; cursor:pointer; }
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

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>URL</th>
                <th>Tanggal Dibuat</th>
                <th>Logo</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($apps as $app)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $app->name }}</td>
                <td>{{ $app->url }}</td>
                <td>{{ $app->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}</td>
                <td>
                    @if($app->image)
                        <img src="{{ asset('storage/'.$app->image) }}" alt="{{ $app->name }}" style="width:60px;height:40px;object-fit:cover;border-radius:4px;border:1px solid #ddd;">
                    @else
                        <span style="color:#999;">-</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('applink.destroy', $app->id) }}" method="POST" onsubmit="return confirm('Hapus aplikasi ini dari menu?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#888;padding:20px;">Belum ada aplikasi</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

