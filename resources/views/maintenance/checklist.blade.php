@extends('layouts.sidebar')

@section('title', 'Checklist Perawatan Perangkat')

@section('content')

<style>
    .maintenance-container {
        max-width: 1400px;
        margin: auto;
        padding: 30px 20px;
    }

    .maintenance-header {
        background: linear-gradient(135deg, #2c2f7e 0%, #4a55d4 100%);
        color: white;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 8px 24px rgba(44, 47, 126, 0.15);
    }

    .maintenance-header h1 {
        font-size: 28px;
        margin: 0 0 8px 0;
        font-weight: 700;
    }

    .maintenance-header .subtitle {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }

    .checklist-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-left: 4px solid #2c2f7e;
    }

    .checklist-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c2f7e;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checklist-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    .checklist-table thead {
        background:#2c2f7e; 
;
    }

    .checklist-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #fdfdfe;
        border-bottom: 2px solid #e0e0e0;
        font-size: 13px;
    }

    .checklist-table td {
        padding: 12px;
        border-bottom: 1px solid #e0e0e0;
        font-size: 13px;
    }

    .checklist-table tbody tr:hover {
        background: #f8f9fa;
    }

    .status-cell {
        text-align: center;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .status-belum {
        background: #ffebee;
        color: #c62828;
    }

    .status-proses {
        background: #fff8e1;
        color: #f57f17;
    }

    .status-selesai {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .status-dropdown {
        display: none;
        position: absolute;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 8px;
        z-index: 100;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        min-width: 120px;
    }

    .status-dropdown a {
        display: block;
        padding: 8px 12px;
        color: #2c2f7e;
        text-decoration: none;
        border-radius: 4px;
        font-size: 12px;
        transition: background 0.2s ease;
    }

    .status-dropdown a:hover {
        background: #f8f9fa;
    }

    .keterangan-cell {
        position: relative;
    }

    .keterangan-text {
        font-size: 12px;
        color: #666;
        font-style: italic;
        max-width: 200px;
        word-wrap: break-word;
    }

    .edit-keterangan-btn {
        background: #f0f0f0;
        border: 1px solid #ddd;
        padding: 4px 8px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 11px;
        color: #666;
        margin-top: 5px;
        transition: all 0.2s ease;
    }

    .edit-keterangan-btn:hover {
        background: #2c2f7e;
        color: white;
        border-color: #2c2f7e;
    }

    .link-foto {
        color: #2c2f7e;
        text-decoration: none;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
        background: #f0f0f0;
        display: inline-block;
        transition: all 0.2s ease;
    }

    .link-foto:hover {
        background: #2c2f7e;
        color: white;
    }

    .edit-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
        padding: 4px 8px;
        transition: all 0.2s ease;
    }

    .edit-btn:hover {
        transform: scale(1.2);
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 4px;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }

    .modal-content h3 {
        margin-top: 0;
        color: #2c2f7e;
        font-weight: 700;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #2c2f7e;
        font-size: 13px;
    }

    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-family: inherit;
        font-size: 13px;
        resize: vertical;
        min-height: 100px;
    }

    .form-group textarea:focus {
        outline: none;
        border-color: #2c2f7e;
        box-shadow: 0 0 0 3px rgba(44,47,126,0.1);
    }

    .modal-buttons {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .modal-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .modal-btn-cancel {
        background: #f0f0f0;
        color: #666;
    }

    .modal-btn-cancel:hover {
        background: #e0e0e0;
    }

    .modal-btn-save {
        background: linear-gradient(135deg, #2c2f7e 0%, #4a55d4 100%);
        color: white;
    }

    .modal-btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44,47,126,0.3);
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c3e6cb;
    }

    .summary-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border-top: 3px solid;
    }

    .summary-card.belum {
        border-top-color: #ff6b6b;
    }

    .summary-card.proses {
        border-top-color: #ffd93d;
    }

    .summary-card.selesai {
        border-top-color: #2ecc71;
    }

    .summary-card-value {
        font-size: 32px;
        font-weight: 700;
        color: #2c2f7e;
        margin-bottom: 5px;
    }

    .summary-card-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
        .maintenance-container {
            padding: 15px 10px;
        }

        .maintenance-header {
            padding: 20px;
        }

        .maintenance-header h1 {
            font-size: 20px;
        }

        .checklist-table {
            font-size: 11px;
        }

        .checklist-table th,
        .checklist-table td {
            padding: 8px;
        }
    }
</style>

<div class="maintenance-container">
    <!-- HEADER -->
    <div class="maintenance-header">
        <h1>Checklist Perawatan Perangkat</h1>
        <p class="subtitle">Status Perawatan Berkala - Tahun {{ date('Y') }}</p>
    </div>

    <!-- ALERTS -->
    @if($message = session('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <!-- SUMMARY STATS -->
    @php
        $totalPerangkat = $checklists->count();
        $totalQ1Selesai = $checklists->where('checked_q1', true)->count();
        $totalQ2Selesai = $checklists->where('checked_q2', true)->count();
        $totalQ3Selesai = $checklists->where('checked_q3', true)->count();
        $totalQ4Selesai = $checklists->where('checked_q4', true)->count();
    @endphp

    <div class="summary-stats">
        <div class="summary-card selesai">
            <div class="summary-card-value">{{ $totalQ1Selesai }}/{{ $totalPerangkat }}</div>
            <div class="summary-card-label">Q1 Selesai</div>
        </div>
        <div class="summary-card selesai">
            <div class="summary-card-value">{{ $totalQ2Selesai }}/{{ $totalPerangkat }}</div>
            <div class="summary-card-label">Q2 Selesai</div>
        </div>
        <div class="summary-card selesai">
            <div class="summary-card-value">{{ $totalQ3Selesai }}/{{ $totalPerangkat }}</div>
            <div class="summary-card-label">Q3 Selesai</div>
        </div>
        <div class="summary-card selesai">
            <div class="summary-card-value">{{ $totalQ4Selesai }}/{{ $totalPerangkat }}</div>
            <div class="summary-card-label">Q4 Selesai</div>
        </div>
    </div>

    <!-- LEGEND -->
    <div class="legend">
        <div class="legend-item">
            <div class="legend-color" style="background: #ff6b6b;"></div>
            <span>Belum Dilakukan</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background: #2ecc71;"></div>
            <span>Selesai</span>
        </div>
    </div>

    <!-- CHECKLIST TABLE -->
    <div class="checklist-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="checklist-title" style="margin: 0;">📋 Checklist Perawatan {{ date('Y') }}</h2>
            <button type="button" onclick="openAddModal()" style="background: linear-gradient(135deg, #2c2f7e 0%, #4a55d4 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px;">
                Tambah Perangkat
            </button>
        </div>
        
        <table class="checklist-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Perangkat</th>
                    <th style="width: 18%;">Q1 (Jan-Mar)</th>
                    <th style="width: 18%;">Q2 (Apr-Jun)</th>
                    <th style="width: 18%;">Q3 (Jul-Sep)</th>
                    <th style="width: 18%;">Q4 (Oct-Dec)</th>
                    <th style="width: 3%; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($checklists as $checklist)
                    <tr>
                        <td><strong>{{ $checklist->perangkat }}</strong></td>
                        <td class="status-cell">
                            <div style="position: relative; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <form action="{{ route('maintenance.checklist.checkbox', $checklist->id) }}" method="POST" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quarter" value="q1">
                                    <input type="checkbox" {{ $checklist->checked_q1 ? 'checked' : '' }} onchange="this.form.submit()" style="cursor: pointer; width: 18px; height: 18px; accent-color: #2ecc71;">
                                </form>
                                @if(!$checklist->checked_q1)
                                    <div style="font-size: 18px; color: #e74c3c;">❌</div>
                                @else
                                    <div style="font-size: 18px; color: #2ecc71; font-weight: bold;">✓</div>
                                @endif
                                @if($checklist->status_q1 === 'selesai' && $checklist->tanggal_q1)
                                    <div style="font-size: 10px; color: #999;">{{ $checklist->tanggal_q1->format('d/m/Y') }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="status-cell">
                            <div style="position: relative; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <form action="{{ route('maintenance.checklist.checkbox', $checklist->id) }}" method="POST" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quarter" value="q2">
                                    <input type="checkbox" {{ $checklist->checked_q2 ? 'checked' : '' }} onchange="this.form.submit()" style="cursor: pointer; width: 18px; height: 18px; accent-color: #2ecc71;">
                                </form>
                                @if(!$checklist->checked_q2)
                                    <div style="font-size: 18px; color: #e74c3c;">❌</div>
                                @else
                                    <div style="font-size: 18px; color: #2ecc71; font-weight: bold;">✓</div>
                                @endif
                                @if($checklist->status_q2 === 'selesai' && $checklist->tanggal_q2)
                                    <div style="font-size: 10px; color: #999;">{{ $checklist->tanggal_q2->format('d/m/Y') }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="status-cell">
                            <div style="position: relative; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <form action="{{ route('maintenance.checklist.checkbox', $checklist->id) }}" method="POST" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quarter" value="q3">
                                    <input type="checkbox" {{ $checklist->checked_q3 ? 'checked' : '' }} onchange="this.form.submit()" style="cursor: pointer; width: 18px; height: 18px; accent-color: #2ecc71;">
                                </form>
                                @if(!$checklist->checked_q3)
                                    <div style="font-size: 18px; color: #e74c3c;">❌</div>
                                @else
                                    <div style="font-size: 18px; color: #2ecc71; font-weight: bold;">✓</div>
                                @endif
                                @if($checklist->status_q3 === 'selesai' && $checklist->tanggal_q3)
                                    <div style="font-size: 10px; color: #999;">{{ $checklist->tanggal_q3->format('d/m/Y') }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="status-cell">
                            <div style="position: relative; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <form action="{{ route('maintenance.checklist.checkbox', $checklist->id) }}" method="POST" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quarter" value="q4">
                                    <input type="checkbox" {{ $checklist->checked_q4 ? 'checked' : '' }} onchange="this.form.submit()" style="cursor: pointer; width: 18px; height: 18px; accent-color: #2ecc71;">
                                </form>
                                @if(!$checklist->checked_q4)
                                    <div style="font-size: 18px; color: #e74c3c;">❌</div>
                                @else
                                    <div style="font-size: 18px; color: #2ecc71; font-weight: bold;">✓</div>
                                @endif
                                @if($checklist->status_q4 === 'selesai' && $checklist->tanggal_q4)
                                    <div style="font-size: 10px; color: #999;">{{ $checklist->tanggal_q4->format('d/m/Y') }}</div>
                                @endif
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="edit-btn" title="Edit" onclick="openEditPerangkatModal({{ $checklist->id }}, '{{ $checklist->perangkat }}')">
                                ✏️
                            </button>
                            <form action="{{ route('maintenance.checklist.delete', $checklist->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus perangkat {{ $checklist->perangkat }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 14px;" title="Hapus">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- KETERANGAN SECTION -->
    <!-- MAINTENANCE LOGS TABLE -->
    <div class="checklist-card">
        <h2 class="checklist-title">Catatan / Keterangan Perawatan</h2>
        
        @foreach($checklists as $checklist)
            <div style="margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <strong style="color: #2c2f7e; font-size: 15px;">{{ $checklist->perangkat }}</strong>
                    <button type="button" onclick="openLogModal({{ $checklist->id }}, '{{ $checklist->perangkat }}')" class="edit-keterangan-btn">
                        Tambah Logbook
                    </button>
                </div>
                
                @if($checklist->maintenanceLogs->count() > 0)
                    <table class="checklist-table" style="font-size: 12px;">
                        <thead>
     
                            <tr style="background: #2c2f7e;;">
                                <th style="padding: 10px; text-align: center; width: 12%;">TANGGAL</th>
                                <th style="padding: 10px; text-align: center; width: 15%;">PIC</th>
                                <th style="padding: 10px; text-align: center; width: 12%;">FOTO</th>
                                <th style="padding: 10px; text-align: left; width: 50%;">KETERANGAN / KESIMPULAN</th>
                                <th style="padding: 10px; text-align: center; width: 11%;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($checklist->maintenanceLogs as $log)
                                <tr>
                                    <td style="padding: 10px; text-align: center;">{{ $log->tanggal->format('d/m/Y') }}</td>
                                    <td style="padding: 10px; text-align: center;">{{ $log->pic }}</td>
                                    <td style="padding: 10px; text-align: center;">
                                        @if($log->foto)
                                            <img src="{{ asset('storage/' . $log->foto) }}" alt="Foto" style="max-width: 100px; max-height: 100px; border-radius: 6px; cursor: pointer; object-fit: cover;" onclick="openPhotoModal('{{ asset('storage/' . $log->foto) }}', '{{ $log->pic }}', '{{ $log->tanggal->format('d/m/Y') }}')">
                                        @else
                                            <span style="color: #999;">-</span>
                                        @endif
                                    </td>
                                    <td style="padding: 10px;">{{ $log->keterangan_kesimpulan }}</td>
                                    <td style="padding: 10px; text-align: center;">
                                        <button type="button" class="edit-btn" title="Edit"
                                            data-log-id="{{ $log->id }}"
                                            data-checklist-id="{{ $checklist->id }}"
                                            data-perangkat="{{ $checklist->perangkat }}"
                                            data-tanggal="{{ $log->tanggal->format('Y-m-d') }}"
                                            data-pic="{{ $log->pic }}"
                                            data-keterangan="{{ $log->keterangan_kesimpulan }}"
                                            onclick="openEditLogModal(this)">
                                            ✏️
                                        </button>
                                        <form action="{{ route('maintenance.log.delete', [$checklist->id, $log->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus log ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 14px;" title="Hapus">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 8px; color: #999;">
                        Belum ada logbook perawatan untuk perangkat ini
                    </div>
                @endif
            </div>
        @endforeach
    </div>

</div>

<!-- MODAL LOG PERAWATAN -->
<div id="logModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <h3>Tambah Log Perawatan</h3>
        <p id="logPerangkatName" style="color: #666; font-size: 13px; margin-bottom: 15px;"></p>
        
        <form id="logForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px;">
            </div>
            <div class="form-group">
                <label for="pic">PIC (Person In Charge):</label>
                <input type="text" id="pic" name="pic" required placeholder="Nama orang yang melakukan perawatan" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px;">
            </div>
            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" id="foto" name="foto" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px;">
                <small style="color: #999;">Ukuran maksimal: 2MB (JPG, PNG, GIF)</small>
            </div>
            <div class="form-group">
                <label for="logKeterangan">Keterangan / Kesimpulan:</label>
                <textarea id="logKeterangan" name="keterangan_kesimpulan" placeholder="Masukkan hasil perawatan atau kesimpulan..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px; min-height: 100px;"></textarea>
            </div>
            <div class="modal-buttons">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeLogModal()">Batal</button>
                <button type="submit" class="modal-btn modal-btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL FOTO -->
<div id="photoModal" class="modal">
    <div class="modal-content" style="max-width: 700px; background: #000; position: relative;">
        <button type="button" class="modal-close-btn" onclick="closePhotoModal()" style="position: absolute; top: 10px; right: 15px; background: rgba(255,255,255,0.2); border: none; color: white; font-size: 28px; cursor: pointer; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">✕</button>
        <div style="text-align: center; padding: 20px;">
            <img id="photoImage" src="" alt="Foto" style="max-width: 100%; max-height: 600px; border-radius: 8px; object-fit: cover;">
            <div style="color: white; margin-top: 15px; font-size: 13px;">
                <p id="photoInfo" style="margin: 0;"></p>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH/EDIT PERANGKAT -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <h3 id="addModalTitle">➕ Tambah Perangkat Baru</h3>
        
        <form id="addForm" action="{{ route('maintenance.checklist.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="POST" id="formMethod">
            <div class="form-group">
                <label for="perangkat">Nama Perangkat:</label>
                <input type="text" id="perangkat" name="perangkat" required placeholder="Contoh: Genset, Kamera CCTV, dll" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px;">
                @error('perangkat')
                    <div style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="modal-buttons">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeAddModal()">Batal</button>
                <button type="submit" class="modal-btn modal-btn-save" id="addModalBtn">Tambah Perangkat</button>
            </div>
        </form>
    </div>
</div>


<script>
function openAddModal() {
    const form = document.getElementById('addForm');
    form.action = `{{ route('maintenance.checklist.store') }}`;
    document.getElementById('formMethod').value = 'POST';
    
    document.getElementById('addModalTitle').textContent = '➕ Tambah Perangkat Baru';
    document.getElementById('addModalBtn').textContent = 'Tambah Perangkat';
    document.getElementById('perangkat').value = '';
    document.getElementById('perangkat').focus();
    document.getElementById('addModal').classList.add('active');
}

function openEditPerangkatModal(id, perangkat) {
    const form = document.getElementById('addForm');
    form.action = `{{ url('/maintenance-checklist') }}/${id}`;
    document.getElementById('formMethod').value = 'PUT';
    
    document.getElementById('addModalTitle').textContent = '✏️ Edit Perangkat';
    document.getElementById('addModalBtn').textContent = 'Update Perangkat';
    document.getElementById('perangkat').value = perangkat;
    document.getElementById('perangkat').focus();
    document.getElementById('perangkat').select();
    
    document.getElementById('addModal').classList.add('active');
}

function closeAddModal() {
    document.getElementById('addModal').classList.remove('active');
    document.getElementById('perangkat').value = '';
    document.getElementById('addForm').action = `{{ route('maintenance.checklist.store') }}`;
    document.getElementById('formMethod').value = 'POST';
}

function openLogModal(id, perangkat) {
    const form = document.getElementById('logForm');
    form.action = `{{ url('/maintenance-checklist') }}/${id}/logs`;
    form.method = 'POST';
    
    // Remove _method if exists (for create mode)
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();
    
    document.getElementById('logPerangkatName').textContent = `Perangkat: ${perangkat}`;
    document.getElementById('tanggal').value = new Date().toISOString().split('T')[0];
    document.getElementById('pic').value = '';
    document.getElementById('foto').value = '';
    document.getElementById('logKeterangan').value = '';
    
    // Change header and button text for create mode
    document.querySelector('#logModal h3').textContent = '➕ Tambah Log Perawatan';
    document.querySelector('#logModal .modal-btn-save').textContent = 'Simpan';
    
    document.getElementById('logModal').classList.add('active');
}

function openEditLogModal(button) {
    const id = button.dataset.checklistId;
    const logId = button.dataset.logId;
    const perangkat = button.dataset.perangkat;
    const tanggal = button.dataset.tanggal;
    const pic = button.dataset.pic;
    const keterangan = button.dataset.keterangan;
    
    const form = document.getElementById('logForm');
    form.action = `{{ url('/maintenance-checklist') }}/${id}/logs/${logId}`;
    form.method = 'POST';
    
    // Add _method for PUT request
    const methodInput = form.querySelector('input[name="_method"]') || document.createElement('input');
    if (!form.querySelector('input[name="_method"]')) {
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
    } else {
        methodInput.value = 'PUT';
    }
    
    document.getElementById('logPerangkatName').textContent = `Perangkat: ${perangkat}`;
    
    // Change header and button text for edit mode
    document.querySelector('#logModal h3').textContent = '✏️ Edit Log Perawatan';
    document.querySelector('#logModal .modal-btn-save').textContent = 'Update';
    
    // Populate form with data
    document.getElementById('tanggal').value = tanggal;
    document.getElementById('pic').value = pic;
    document.getElementById('logKeterangan').value = keterangan || '';
    
    document.getElementById('logModal').classList.add('active');
}

function closeLogModal() {
    document.getElementById('logModal').classList.remove('active');
    const methodInput = document.getElementById('logForm').querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();
}
function openAddModal() {
    document.getElementById('addModal').classList.add('active');
    document.getElementById('perangkat').focus();
}

function closeAddModal() {
    document.getElementById('addModal').classList.remove('active');
    document.getElementById('perangkat').value = '';
}

document.getElementById('logModal').onclick = function(event) {
    if (event.target === this) {
        closeLogModal();
    }
}

document.getElementById('addModal').onclick = function(event) {
    if (event.target === this) {
        closeAddModal();
    }
}

function openPhotoModal(photoUrl, pic, tanggal) {
    document.getElementById('photoImage').src = photoUrl;
    document.getElementById('photoInfo').textContent = `${pic} - ${tanggal}`;
    document.getElementById('photoModal').classList.add('active');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.remove('active');
}

document.getElementById('photoModal').onclick = function(event) {
    if (event.target === this) {
        closePhotoModal();
    }
}
</script>

@endsection
