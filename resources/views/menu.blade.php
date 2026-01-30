@extends('layouts.sidebar')

@section('title', 'Menu Utama')

@section('content')

<style>
    .container {
        max-width: 1200px;
        margin: auto;
        background: #fff;
        border-radius: 12px;
        padding: 30px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header h2 {
        color: #2c2f7e;
        margin: 0;
        font-size: 24px;
    }

    .user-info {
        font-weight: 600;
        color: #2c2f7e;
        font-size: 14px;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        margin-top: 30px;
        justify-items: center;
    }

    .menu-item {
        border: 1px solid #e1e4f0;
        border-radius: 12px;
        padding: 30px 20px 20px;
        text-align: center;
        transition: all 0.3s ease;
        width: 100%;
        max-width: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        background: #fafbfc;
    }

    .menu-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(44, 47, 126, 0.15);
        border-color: #2c2f7e;
    }

    .menu-item img {
        width: 70px;
        height: 70px;
        object-fit: contain;
        margin-bottom: 15px;
    }

    .menu-item p {
        font-weight: 600;
        color: #2c2f7e;
        margin: 0 0 15px 0;
        font-size: 16px;
        text-decoration: none;
    }

    .btn-visit {
        background: #2c2f7e;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
        width: 100%;
        border: none;
        cursor: pointer;
    }

    .btn-visit:hover {
        background: #1f2258;
        transform: scale(1.05);
    }

    .menu-actions {
        display: flex;
        gap: 8px;
        margin-top: 10px;
        width: 100%;
    }

    .btn-small {
        flex: 1;
        padding: 8px 10px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        border: none;
        cursor: pointer;
    }

    .btn-create {
        background: #3498db;
        color: #fff;
    }

    .btn-create:hover {
        background: #2980b9;
    }

    .btn-delete {
        background: #e74c3c;
        color: #fff;
    }

    .btn-delete:hover {
        background: #c0392b;
    }

    @media (max-width: 1200px) {
        .menu-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<div class="container">

    <div class="header">
        <h2>Monitoring Pengecekan Website</h2>
        <div class="user-info">Halo, {{ auth()->user()->name }}</div>
    </div>

    <div class="menu-grid">
        @forelse(\App\Models\AppLink::orderBy('name')->get() as $app)
            <div class="menu-item">
                @php
                    $image = $app->image ? asset('storage/'.$app->image) : asset('images/bakti.png');
                @endphp
                <img src="{{ $image }}" alt="{{ $app->name }}">
                <p>{{ $app->name }}</p>
                <a href="{{ $app->url }}" target="_blank" class="btn-visit">Kunjungi Website</a>
            </div>
        @empty
            <p style="grid-column:1 / -1; text-align:center; color:#999;">Belum ada aplikasi. Tambah dari menu \"Kelola Aplikasi\".</p>
        @endforelse
    </div>

</div>

@endsection