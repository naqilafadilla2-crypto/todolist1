<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Checklist Expired Date</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .page {
            padding: 12px;
        }

        .header {
            margin-bottom: 12px;
        }

        .title {
            font-size: 22px;
            margin-bottom: 4px;
            color: #0a978e;
            font-weight: bold;
        }

        .subtitle {
            font-size: 12px;
            color: #555;
            margin: 0;
        }

        .filters {
            margin: 16px 0;
            font-size: 12px;
            color: #444;
        }

        /* SUMMARY */
        .summary {
            width: 100%;
            margin-bottom: 20px;
        }

      .summary-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 24px 14px;
}

.summary-table td {
    border: none;
    padding: 0;
}

.summary-card {
    width: 100%;
    color: #fff;
    border-radius: 10px;
    padding: 18px 12px;
    text-align: center;
    box-sizing: border-box;
    margin: 2px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

/* TOTAL ITEM */
.summary-card.total {
    background: #0f766e;
}

/* STATUS HIJAU */
.summary-card.hijau {
    background: #16a34a;
}

/* STATUS KUNING */
.summary-card.kuning {
    background: #f1c40f;
    color: #111;
}

/* STATUS MERAH */
.summary-card.merah {
    background: #e74c3c;
}

        .summary-card h4 {
            margin: 0 0 8px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .summary-card p {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 11px;
        }

        th {
            background: #0a978e;
            color: #fff;
        }

        .status-label {
            padding: 4px 8px;
            border-radius: 8px;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
        }

        .HIJAU {
            background: #0a972d;
        }

        .KUNING {
            background: #f39c12;
            color: #111;
        }

        .MERAH {
            background: #e74c3c;
        }
    </style>
</head>

<body>

    <div class="page">

        <div class="header">
            <div class="title">
                Laporan Checklist Expired Date
            </div>

            <div class="subtitle">
                Dicetak:
                {{
                    Carbon\Carbon::now('Asia/Jakarta')->format('d/m/Y H:i')
                }}
                WIB
            </div>
        </div>

        @if($filterNama || $filterTanggalDari || $filterTanggalSampai)

            <div class="filters">

                @if($filterNama)
                    Nama Perangkat: {{ $filterNama }};
                @endif

                @if($filterTanggalDari)
                    Tanggal Dari: {{ $filterTanggalDari }};
                @endif

                @if($filterTanggalSampai)
                    Tanggal Sampai: {{ $filterTanggalSampai }};
                @endif

            </div>

        @endif


        <!-- SUMMARY -->
        <div class="summary">

            <table class="summary-table">

                <tr>

                    <td>
                        <div class="summary-card total">
                            <h4>Total Item</h4>
                            <p>{{ $total }}</p>
                        </div>
                    </td>

                    <td>
                        <div class="summary-card hijau">
                            <h4>Status Hijau</h4>
                            <p>{{ $hijau }}</p>
                        </div>
                    </td>

                    <td>
                        <div class="summary-card kuning">
                            <h4>Status Kuning</h4>
                            <p>{{ $kuning }}</p>
                        </div>
                    </td>

                    <td>
                        <div class="summary-card merah">
                            <h4>Status Merah</h4>
                            <p>{{ $merah }}</p>
                        </div>
                    </td>

                </tr>

            </table>

        </div>


        <!-- TABLE -->
        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perangkat</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Status</th>
                    <th>Sisa Hari</th>
                    <th>Peringatan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>

                @forelse($data as $item)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->nama_perangkat }}
                        </td>

                        <td>
                            {{
                                \Carbon\Carbon::parse(
                                    $item->tanggal_kadaluarsa
                                )->format('d/m/Y')
                            }}
                        </td>

                        <td>
                            <span class="status-label {{ $item->status }}">
                                {{ $item->status }}
                            </span>
                        </td>

                        <td>
                            {{ $item->sisa_hari }}
                        </td>

                        <td>
                            {!! nl2br(
                                e(
                                    str_replace(
                                        ['⚠️', '❌'],
                                        '',
                                        $item->peringatan
                                    )
                                )
                            ) !!}
                        </td>

                        <td>
                            {{ $item->keterangan }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7"
                            style="text-align:center; padding: 18px;">

                            Tidak ada data.

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</body>
</html>