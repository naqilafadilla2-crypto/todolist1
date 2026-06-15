@extends('layouts.sidebar')

@section('title', 'Log Aktivitas')

@section('content')
<div style="padding: 30px;">
    <!-- Header Section -->
    <div style="background: linear-gradient(135deg, #0a978e 0%, #0d9488 100%); border-radius: 15px; padding: 25px; margin-bottom: 30px; box-shadow: 0 8px 20px rgba(10, 151, 142, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 28px; font-weight: bold; color: white; margin: 0; margin-bottom: 8px;">Log Aktivitas Sistem</h1>
                <p style="color: #e6e8ff; margin: 0; font-size: 14px;">Pantau semua aktivitas yang terjadi dalam sistem</p>
            </div>
            @if($logs->count() > 0)
                <form action="{{ route('activity-log.clear-all') }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" style="background: linear-gradient(135deg, #ff4d4d, #c0392b); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 6px 15px rgba(255, 77, 77, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(255, 77, 77, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 15px rgba(255, 77, 77, 0.3)';" onclick="return confirm('Apakah Anda yakin ingin menghapus SEMUA log aktivitas?')">
                    Hapus Semua Log
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    @if($logs->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-left: 4px solid #0a978e;">
                <p style="color: #7a8696; font-size: 13px; margin: 0 0 8px 0; text-transform: uppercase; font-weight: 600;">Total Log</p>
                <p style="font-size: 28px; font-weight: bold; color: #0a978e; margin: 0;">{{ $logs->total() }}</p>
            </div>
            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-left: 4px solid #f1c40f;">
                <p style="color: #7a8696; font-size: 13px; margin: 0 0 8px 0; text-transform: uppercase; font-weight: 600;">Per Halaman</p>
                <p style="font-size: 28px; font-weight: bold; color: #f39c12; margin: 0;">{{ $logs->perPage() }}</p>
            </div>
            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-left: 4px solid #3498db;">
                <p style="color: #7a8696; font-size: 13px; margin: 0 0 8px 0; text-transform: uppercase; font-weight: 600;">Halaman Aktif</p>
                <p style="font-size: 28px; font-weight: bold; color: #3498db; margin: 0;">{{ $logs->currentPage() }} / {{ $logs->lastPage() }}</p>
            </div>
        </div>
    @endif

    <!-- Table Section -->
    <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); overflow: hidden;">
        @if($logs->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: linear-gradient(to right, #0a978e, #0d9488); color: white;">
                            <th style="border: none; padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">No</th>
                            <th style="border: none; padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Aktivitas</th>
                            <th style="border: none; padding: 16px; text-align: left; font-weight: 600; font-size: 14px;">Waktu</th>
                            <th style="border: none; padding: 16px; text-align: center; font-weight: 600; font-size: 14px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $index => $log)
                            <tr style="border-bottom: 1px solid #e8eef7; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#f8fafb';" onmouseout="this.style.backgroundColor='transparent';">
                                <td style="padding: 14px 16px; color: #666; font-weight: 600;">{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}</td>
                                <td style="padding: 14px 16px; color: #333;">
                                    <span style="display: inline-block; background: #f0f7f6; color: #0a978e; padding: 6px 12px; border-radius: 6px; font-size: 13px;">{{ $log->aktivitas }}</span>
                                </td>
                                <td style="padding: 14px 16px; color: #666; font-size: 13px; white-space: nowrap;">
                                    <strong>{{ optional($log->created_at)->format('d M Y') ?? '-' }}</strong><br>
                                    <span style="color: #999;">{{ optional($log->created_at)->format('H:i:s') ?? '-' }}</span>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <form action="{{ route('activity-log.destroy', $log->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: #ff6b6b; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; transition: all 0.2s ease; font-weight: 600;" onmouseover="this.style.backgroundColor='#ff5252';" onmouseout="this.style.backgroundColor='#ff6b6b';" onclick="return confirm('Hapus log ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 25px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                @if($logs->onFirstPage())
                    <span style="padding: 8px 12px; background: #e8eef7; color: #999; border-radius: 6px; font-size: 13px; cursor: not-allowed;">« Sebelumnya</span>
                @else
                    <a href="{{ $logs->previousPageUrl() }}" style="padding: 8px 12px; background: #0a978e; color: white; border-radius: 6px; font-size: 13px; text-decoration: none; font-weight: 600; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#0d9488';" onmouseout="this.style.backgroundColor='#0a978e';">« Sebelumnya</a>
                @endif

                @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                    @if($page == $logs->currentPage())
                        <span style="padding: 8px 12px; background: #0a978e; color: white; border-radius: 6px; font-size: 13px; font-weight: 600;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 8px 12px; background: #e8eef7; color: #0a978e; border-radius: 6px; font-size: 13px; text-decoration: none; font-weight: 600; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#d8e6f3';" onmouseout="this.style.backgroundColor='#e8eef7';">{{ $page }}</a>
                    @endif
                @endforeach

                @if($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}" style="padding: 8px 12px; background: #0a978e; color: white; border-radius: 6px; font-size: 13px; text-decoration: none; font-weight: 600; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#0d9488';" onmouseout="this.style.backgroundColor='#0a978e';">Berikutnya »</a>
                @else
                    <span style="padding: 8px 12px; background: #e8eef7; color: #999; border-radius: 6px; font-size: 13px; cursor: not-allowed;">Berikutnya »</span>
                @endif
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <p style="font-size: 48px; margin: 0 0 15px 0;">📭</p>
                <p style="font-size: 18px; font-weight: 600; color: #333; margin: 0 0 10px 0;">Belum Ada Log Aktivitas</p>
                <p style="color: #999; margin: 0; font-size: 14px;">Log aktivitas akan muncul ketika ada aktivitas dalam sistem</p>
            </div>
        @endif
    </div>
</div>
@endsection
