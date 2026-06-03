<!DOCTYPE html> 
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Schedule & Pajak KSO</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Segoe UI', sans-serif; 
        }
        
        body { 
            display: flex; 
            background-color: #f1f5f9; 
            color: #1e293b; 
            min-height: 100vh; 
        }

        /* Sidebar */
        .sidebar { 
            width: 280px; 
            background-color: #ffffff; 
            border-right: 1px solid #e2e8f0; 
            display: flex; 
            flex-direction: column; 
            height: 100vh; 
            position: sticky; 
            top: 0; 
        }
        
        .sidebar-brand { 
            padding: 24px; 
            border-bottom: 1px solid #e2e8f0; 
            text-align: center; 
        }
        
        .sidebar-brand img { 
            max-width: 140px; 
        }
        
        .sidebar-menu { 
            padding: 20px 14px; 
            flex: 1; 
            overflow-y: auto; 
        }
        
        .menu-category { 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            color: #94a3b8; 
            font-weight: 700; 
            margin: 20px 0 8px 10px; 
        }
        
        .menu-item { 
            display: flex; 
            align-items: center; 
            padding: 10px 14px; 
            color: #475569; 
            text-decoration: none; 
            border-radius: 8px; 
            font-weight: 500; 
            font-size: 0.95rem; 
            margin-bottom: 4px; 
        }
        
        .menu-item:hover { 
            background-color: #f8fafc; 
            color: #0284c7; 
        }
        
        .menu-item.active { 
            background-color: #e0f2fe; 
            color: #0369a1; 
            font-weight: 600; 
        }

        /* Main Content & Layout */
        .main-content { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
        }
        
        .navbar { 
            height: 70px; 
            background-color: #ffffff; 
            border-bottom: 1px solid #e2e8f0; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 0 40px; 
        }
        
        .content-body { 
            padding: 40px; 
            width: 100%; 
            margin: 0 auto; 
        }

        .header-title-block { 
            margin-bottom: 25px; 
            border-bottom: 2px solid #e2e8f0; 
            padding-bottom: 15px; 
        }
        
        .header-title-block h1 { 
            font-size: 1.6rem; 
            color: #0f172a; 
            font-weight: 700; 
        }
        .header-title-block h2 { 
            font-size: 1.3rem; 
            color: #0284c7; 
            font-weight: 700; 
            margin-top: 4px; 
        }

        /* Tabel & Tahap Container */
        .tahap-container { 
            background: white; 
            border: 1px solid #e2e8f0; 
            border-radius: 12px; 
            padding: 24px; 
            margin-bottom: 25px; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); 
        }
        
        .tahap-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 14px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .tahap-header-left {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tahap-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
        }

        .tahap-eta {
            color: #0f5c9f;
            font-size: 0.95rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .tahap-badges {
            display: inline-flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .tahap-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-secondary {
            background-color: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .badge-info {
            background-color: #f0f9ff;
            color: #0c4a6e;
            border: 1px solid #bae6fd;
        }

        .badge { 
            padding: 4px 10px; 
            border-radius: 9999px; 
            font-size: 0.78rem; 
            font-weight: 700; 
            display: inline-block; 
        }

        /* Blok Potongan Pajak Tahap */
        .pajak-tahap-box { 
            background-color: #f8fafc; 
            border: 1px solid #cbd5e1; 
            border-radius: 8px; 
            padding: 16px; 
            margin-bottom: 20px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            flex-wrap: wrap; 
            gap: 15px; 
        }
        
        .pajak-tahap-title { 
            font-size: 0.9rem; 
            font-weight: 700; 
            color: #334155; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
        }
        
        .pajak-tahap-summary { 
            display: flex; 
            gap: 20px; 
            font-size: 0.85rem; 
            background: #ffffff; 
            padding: 10px 16px; 
            border-radius: 6px; 
            border: 1px solid #e2e8f0; 
        }

        /* Schedule Table */
        .schedule-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 0.85rem; 
            margin-top: 10px; 
        }
        
        .schedule-table th, 
        .schedule-table td { 
            border: 1px solid #e2e8f0; 
            padding: 10px; 
            text-align: left; 
            vertical-align: middle; 
        }
        
        .schedule-table th { 
            background-color: #e0f2fe; 
            color: #0f172a; 
            font-weight: 700; 
            border-bottom: 2px solid #bae6fd;
        }

        /* Badges */
        .badge { 
            padding: 4px 8px; 
            border-radius: 6px; 
            font-size: 0.75rem; 
            font-weight: 600; 
            display: inline-block; 
        }
        
        .badge-success { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-warning { background-color: #fef9c3; color: #a16207; border: 1px solid #fef08a; }
        .badge-danger  { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

        /* Modal Overlay & Box */
        .modal-overlay { 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(15, 23, 42, 0.4); 
            backdrop-filter: blur(4px); 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            z-index: 9999; 
            opacity: 0; 
            pointer-events: none; 
            transition: all 0.3s ease; 
        }
        
        .modal-overlay.show { 
            opacity: 1; 
            pointer-events: auto; 
        }
        
        .modal-box { 
            background: white; 
            width: 100%; 
            max-width: 850px; 
            border-radius: 16px; 
            overflow: hidden; 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); 
        }
        
        .modal-header { 
            padding: 18px 24px; 
            border-bottom: 1px solid #e2e8f0; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            background: #f8fafc; 
        }
        
        .modal-body { 
            padding: 24px; 
            max-height: 75vh; 
            overflow-y: auto; 
        }

        /* Preview for proof images */
        .modal-preview { display: grid; gap: 14px; }
        .image-preview { width: 100%; max-height: 420px; object-fit: contain; border: 1px solid #cbd5e1; border-radius: 12px; background: #f8fafc; }
        
        .modal-footer { 
            padding: 16px 24px; 
            border-top: 1px solid #e2e8f0; 
            display: flex; 
            justify-content: flex-end; 
            gap: 12px; 
            background: #f8fafc; 
        }

        /* Form Components */
        .form-row { 
            display: grid; 
            grid-template-columns: repeat(2, 1fr); 
            gap: 16px; 
            margin-bottom: 14px; 
        }
        
        .form-group { 
            display: flex; 
            flex-direction: column; 
        }
        
        .form-group label { 
            font-size: 0.8rem; 
            font-weight: 600; 
            color: #475569; 
            margin-bottom: 6px; 
        }
        
        .form-group input, 
        .form-group select { 
            padding: 9px 12px; 
            border: 1px solid #cbd5e1; 
            border-radius: 8px; 
            font-size: 0.88rem; 
            width: 100%; 
        }
        
        .form-group input:disabled { 
            background-color: #e2e8f0; 
            color: #64748b; 
            cursor: not-allowed; 
        }

        .section-divider { 
            margin: 20px 0 12px 0; 
            padding-bottom: 6px; 
            border-bottom: 1px dashed #cbd5e1; 
            font-size: 0.9rem; 
            font-weight: 700; 
            color: #0284c7; 
        }

        /* Buttons */
        .btn-action { 
            padding: 6px 12px; 
            background-color: #0284c7; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            font-weight: 600; 
            cursor: pointer; 
            font-size: 0.8rem; 
        }
        
        .btn-action:hover { 
            background-color: #0369a1; 
        }
        
        .btn-action.btn-pajak-tahap { 
            background-color: #2563eb; 
            font-size: 0.85rem; 
            padding: 8px 16px; 
        }
        
        .btn-action.btn-pajak-tahap:hover { 
            background-color: #1d4ed8; 
        }
        
        .btn-save { 
            padding: 10px 20px; 
            background-color: #16a34a; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-weight: 600; 
            cursor: pointer; 
        }
        
        .btn-save:hover { 
            background-color: #15803d; 
        }
        
        .btn-close { 
            padding: 10px 16px; 
            background-color: #94a3b8; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-weight: 600; 
            cursor: pointer; 
        }

        /* Additional Information Components */
        .info-panel { 
            background: #f8fafc; 
            padding: 14px 18px; 
            border-radius: 10px; 
            border: 1px solid #e2e8f0; 
            margin-bottom: 18px; 
            font-size: 0.9rem; 
        }

        /* Grand Total Footer Panel */
        .grand-total-bar { 
            background: #1e293b; 
            color: white; 
            border-radius: 10px; 
            padding: 16px 24px; 
            margin-top: 10px; 
            display: flex; 
            gap: 24px; 
            flex-wrap: wrap; 
        }
        
        .grand-total-bar .gt-item { 
            display: flex; 
            flex-direction: column; 
        }
        
        .grand-total-bar .gt-label { 
            font-size: 0.72rem; 
            color: #94a3b8; 
            font-weight: 600; 
            text-transform: uppercase; 
            margin-bottom: 4px; 
        }
        
        .grand-total-bar .gt-value { 
            font-size: 0.95rem; 
            font-weight: 700; 
        }
        .summary-card.summary-card-primary { background: #e0f2fe; border-color: #7dd3fc; }
        /* Proof preview image sizing: keep compact for single visible view */
        .modal-preview .image-preview {
            width: 100%;
            max-height: 320px;
            object-fit: contain;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
        }

        .modal-preview .file-status { padding: 12px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/PT JMS Kediri.png') }}" alt="Logo" onerror="this.onerror=null; this.src='https://placehold.co/120x45?text=PT+JMS';">
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item">← Beranda Utama</a>
            <div class="menu-category">Operational</div>
            <a href="{{ route('kontrak.index') }}" class="menu-item">Kendali Kontrak</a>
            <a href="{{ route('rab.index') }}" class="menu-item">RAB Pra Operasional</a>
            <a href="{{ route('timetable.index') }}" class="menu-item">Time Table</a>
            <div class="menu-category">Payment</div>
            <a href="{{ route('payment-schedule.index') }}" class="menu-item active">Payment Schedule</a>
            <a href="{{ route('outstanding-payment.index') }}" class="menu-item {{ Request::is('menu/outstanding-payment') ? 'active' : '' }}">Outstanding Payment</a>
            <a href="{{ route('payment-jurnal.index') }}" class="menu-item {{ Request::is('menu/payment-jurnal') ? 'active' : '' }}">Payment Jurnal</a>
        </div>
    </div>

<div class="main-content">
    <div class="navbar">
        <div style="font-weight: 600; color: #1e293b;">
            Rumah Sakit Aktif: <span style="color: #0284c7;">{{ $current_rs ?? 'Belum Pilih Rumah Sakit' }}</span>
        </div>
        <div>
            <span class="badge badge-success" style="padding: 6px 12px;">{{ session('user_name', 'Admin PT JMS') }}</span>
        </div>
    </div>

    <div class="content-body">
        @if(session('success'))
            <div style="background-color: #dcfce7; color: #15803d; padding: 12px 16px; border-radius: 8px; border: 1px solid #bbf7d0; margin-bottom: 20px; font-size: 0.9rem; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif

        <div class="header-title-block">
            <h1>Jadwal Pembayaran & Manajemen Potongan Pajak</h1>
            <h2>Rumah Sakit: {{ $current_rs ?? 'Belum Pilih Rumah Sakit' }}</h2>
        </div>

        @php
            $grand_nominal = $rincian_rab->sum('nominal');
            $grand_dpp     = $rincian_rab->sum(fn($i) => $i->dpp ?? 0);
            $grand_ppn     = $rincian_rab->sum(fn($i) => $i->ppn ?? 0);
            $grand_pph23   = $rincian_rab->sum(fn($i) => $i->pph23 ?? 0);
            $grand_pph4    = $rincian_rab->sum(fn($i) => $i->pph_pasal4 ?? 0);
            $grand_net     = $rincian_rab->sum(fn($i) => $i->net_payment ?? 0);
            
            // Persentase alokasi untuk setiap tahap
            $persen_tahap = [1 => 30, 2 => 30, 3 => 40];
        @endphp

        @foreach([1 => '30%', 2 => '30%', 3 => '40%'] as $tahap => $porsi)
            @php
                $data_tahap_db = $data_tahap_master->where('tahap_ke', $tahap)->first();

                $db_id           = $data_tahap_db->id ?? '';
                $pajak_tahap_ori = $data_tahap_db->nominal_sebelum_pajak ?? 0;
                $pajak_tahap_23  = $data_tahap_db->pph23 ?? 0;
                $pajak_tahap_4   = $data_tahap_db->pph_pasal4 ?? 0;
                
                // Menghitung net payment tahap tanpa menyertakan PPN
                $pajak_tahap_net = $pajak_tahap_ori - $pajak_tahap_23 - $pajak_tahap_4;
                $rincian_tahap   = $rincian_rab->where('tahap', $tahap);
                $estimasi_jatuh_tempo = $rincian_tahap->count()
                    ? $rincian_tahap->first()->jatuh_tempo_tahap
                    : null;

                $stage_total_nominal = $rincian_tahap->sum(fn($item) => $item->nominal ?? 0);
                $stage_total_dpp     = $rincian_tahap->sum(fn($item) => $item->dpp ?? 0);
                $stage_total_ppn     = $rincian_tahap->sum(fn($item) => $item->ppn ?? 0);
                $stage_total_pph23   = $rincian_tahap->sum(fn($item) => $item->pph23 ?? 0);
                $stage_total_pph4    = $rincian_tahap->sum(fn($item) => $item->pph_pasal4 ?? 0);
                $stage_total_net     = $rincian_tahap->sum(fn($item) => $item->net_payment ?? 0);
            @endphp

            <div class="tahap-container">
                <div class="tahap-header">
                    <div class="tahap-header-left">
                        <div class="tahap-title">Pembayaran Tahap {{ $tahap }}</div>
                        <div class="tahap-badges">
                            <span class="tahap-badge badge-secondary">Porsi {{ $porsi }}</span>
                            <span class="tahap-badge badge-info">Total: Rp {{ $stage_total_nominal ? number_format($stage_total_nominal, 0, ',', '.') : '-' }}</span>
                        </div>
                    </div>
                    <div class="tahap-eta">Estimasi Jatuh Tempo: {{ $estimasi_jatuh_tempo ? date('d-m-Y', strtotime($estimasi_jatuh_tempo)) : '-' }}</div>
                </div>

                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align:center;">No</th>
                            <th style="width: 16%;">Penerima</th>
                            <th style="width: 15%;">Deskripsi Kebutuhan</th>
                            <th style="width: 13%;">Transfer To</th>
                            <th style="text-align: right; width: 10%;">Nominal (Ori)</th>
                            <th style="text-align: right; width: 8%;">DPP</th>
                            <th style="text-align: right; width: 8%;">PPN</th>
                            <th style="text-align: right; width: 8%;">PPh 23</th>
                            <th style="text-align: right; width: 8%;">PPh Ps 4(2)</th>
                            <th style="text-align: right; width: 10%;">Net Payment</th>
                            <th style="text-align: center; width: 6%;">Status</th>
                            <th style="text-align: center; width: 6%;">Receipt</th>
                            @if(session('user_role') !== 'NMS')
                                <th style="text-align: center; width: 5%;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if($rincian_tahap->count() > 0)
                            @foreach($rincian_tahap as $item)
                                @php
                                    $item_dpp   = $item->dpp        ?? 0;
                                    $item_ppn   = $item->ppn        ?? 0;
                                    $item_pph23 = $item->pph23      ?? 0;
                                    $item_pph4  = $item->pph_pasal4 ?? 0;
                                    $item_net   = $item->net_payment ?? 0;
                                @endphp
                                <tr>
                                    <td style="text-align:center;">{{ $loop->iteration }}</td>
                                    <td><b>{{ $item->penerima_alokasi }}</b></td>
                                    <td>{{ $item->deskripsi }}</td>
                                    <td>{{ property_exists($item, 'transfer_to') && $item->transfer_to ? $item->transfer_to : '-' }}</td>
                                    <td style="text-align: right; font-weight: 500;">
                                        {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: right; color:#475569;">
                                        {{ $item_dpp ? number_format($item_dpp, 0, ',', '.') : '-' }}
                                    </td>
                                    <td style="text-align: right; color:#2563eb;">
                                        {{ $item_ppn ? number_format($item_ppn, 0, ',', '.') : '-' }}
                                    </td>
                                    <td style="text-align: right; color:#b91c1c;">
                                        {{ $item_pph23 ? number_format($item_pph23, 0, ',', '.') : '-' }}
                                    </td>
                                    <td style="text-align: right; color:#b91c1c;">
                                        {{ $item_pph4 ? number_format($item_pph4, 0, ',', '.') : '-' }}
                                    </td>
                                    <td style="text-align: right; font-weight:700; color:#16a34a;">
                                        {{ number_format($item_net, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: center;">
                                        @if(isset($item->status_bayar) && ($item->status_bayar == 'Terbayar' || $item->status_bayar == 'Lunas'))
                                            <span class="badge badge-success">✓ Terbayar</span>
                                        @elseif(isset($item->status_bayar) && $item->status_bayar == 'URGENT')
                                            <span class="badge badge-danger">⚠️ URGENT</span>
                                        @elseif(isset($item->status_bayar) && $item->status_bayar == 'Suspend')
                                            <span class="badge badge-danger">⏸ Suspend</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn-action" style="background:#1d4ed8; padding:6px 10px;" onclick="openProofModal({{ $loop->iteration }}, {{ $item->id }})">Lihat Bukti</button>
                                    </td>
                                    @if(session('user_role') !== 'NMS')
                                        <td style="text-align: center;">
                                            <button class="btn-action" 
                                                    onclick="openPaymentModal(
                                                        '{{ $item->id }}',
                                                        '{{ addslashes($item->penerima_alokasi) }}',
                                                        '{{ addslashes($item->deskripsi) }}',
                                                        {{ $item->nominal }},
                                                        {{ $item_dpp }},
                                                        {{ $item_ppn }},
                                                        {{ $item_pph23 }},
                                                        {{ $item_pph4 }},
                                                        '{{ property_exists($item, 'transfer_to') ? addslashes($item->transfer_to) : '' }}',
                                                        '{{ $item->no_invoice        ?? '' }}',
                                                        '{{ $item->status_bayar      ?? 'Belum Terbayar' }}',
                                                        '{{ $item->tanggal_realisasi ?? '' }}',
                                                        '{{ $item->no_faktur_pajak   ?? '' }}',
                                                        '{{ $item->no_bukti_potong   ?? '' }}',
                                                        {{ $item_net }}
                                                    )">
                                                Kelola
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr style="background: #eef2ff; border-top: 2px solid #0f172a; font-weight:700;">
                                <td colspan="4" style="text-align:center; padding-top: 12px;">Total Tahap {{ $tahap }}</td>
                                <td style="text-align:right;">{{ $stage_total_nominal ? number_format($stage_total_nominal, 0, ',', '.') : '-' }}</td>
                                <td colspan="2"></td>
                                <td style="text-align:right;">{{ $stage_total_pph23 ? number_format($stage_total_pph23, 0, ',', '.') : '-' }}</td>
                                <td style="text-align:right;">{{ $stage_total_pph4 ? number_format($stage_total_pph4, 0, ',', '.') : '-' }}</td>
                                <td style="text-align:right; color:#16a34a;">{{ number_format($stage_total_net, 0, ',', '.') }}</td>
                                <td colspan="{{ session('user_role') === 'NMS' ? 2 : 3 }}"></td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="{{ session('user_role') === 'NMS' ? 12 : 13 }}" style="text-align: center; color: #94a3b8; padding: 20px;">
                                    Belum ada alokasi dana untuk struktur perencanaan Tahap {{ $tahap }}.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @endforeach

        @if($rincian_rab->count() > 0)
            <div class="grand-total-bar">
                <div class="gt-item">
                    <div class="gt-label">Total Nominal</div>
                    <div class="gt-value">Rp {{ number_format($grand_nominal, 0, ',', '.') }}</div>
                </div>
                <div class="gt-item">
                    <div class="gt-label">Total DPP</div>
                    <div class="gt-value">Rp {{ number_format($grand_dpp, 0, ',', '.') }}</div>
                </div>
                <div class="gt-item">
                    <div class="gt-label">Total PPN</div>
                    <div class="gt-value" style="color: #93c5fd;">Rp {{ number_format($grand_ppn, 0, ',', '.') }}</div>
                </div>
                <div class="gt-item">
                    <div class="gt-label">Total PPh 23</div>
                    <div class="gt-value" style="color: #fca5a5;">Rp {{ number_format($grand_pph23, 0, ',', '.') }}</div>
                </div>
                <div class="gt-item">
                    <div class="gt-label">Total PPh Ps 4(2)</div>
                    <div class="gt-value" style="color: #fca5a5;">Rp {{ number_format($grand_pph4, 0, ',', '.') }}</div>
                </div>
                <div class="gt-item" style="margin-left: auto;">
                    <div class="gt-label">GRAND NET PAYMENT</div>
                    <div class="gt-value" style="color: #86efac; font-size: 1.1rem;">Rp {{ number_format($grand_net, 0, ',', '.') }}</div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL 2: KELOLA VENDOR ITEM --}}
<div id="paymentModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div style="font-weight:700; color:#0f172a; font-size:1.1rem;">
                Atur Komponen Pajak &amp; Berkas Administrasi
            </div>
            <button type="button" style="background:none; border:none; font-size:1.5rem; cursor:pointer;" onclick="closeModal('paymentModal')">&times;</button>
        </div>

        <form id="formPayment" action="{{ route('payment-schedule.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="id" id="modal_item_id">
            <input type="hidden" name="target_type" value="item">
            <input type="hidden" name="no_invoice" id="pm_no_invoice">

            <div class="modal-body">
                <div class="info-panel">
                    <p><strong>Penerima:</strong> <span id="text_penerima" style="color:#0284c7; font-weight:600;">-</span></p>
                    <p style="margin-top:6px;"><strong>Deskripsi:</strong> <span id="text_deskripsi">-</span></p>
                </div>

                <div class="section-divider">Nilai Pokok & Komponen Pajak</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nominal Pokok (Sebelum Pajak)</label>
                        <input type="text" id="pm_nominal_awal" disabled style="font-weight:700;">
                    </div>
                    <div class="form-group">
                        <label>DPP (Dasar Pengenaan Pajak)</label>
                        <input type="text" id="pm_dpp" name="dpp" placeholder="0" oninput="formatAndCalculate(this);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>PPN (Pertambahan Nilai)</label>
                        <input type="text" id="pm_ppn" name="ppn" placeholder="0" oninput="formatAndCalculate(this);">
                    </div>
                    <div class="form-group">
                        <label>PPh 23 (Potongan Jasa / Sewa)</label>
                        <input type="text" id="pm_pph23" name="pph23" placeholder="0" style="border: 2px solid #3b82f6;" oninput="formatAndCalculate(this);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>PPh Pasal 4 Ayat 2</label>
                        <input type="text" id="pm_pph_pasal4" name="pph_pasal4" placeholder="0" style="border: 2px solid #3b82f6;" oninput="formatAndCalculate(this);">
                    </div>
                    <div class="form-group">
                        <label>Transfer to</label>
                        <input type="text" id="pm_transfer_to" name="transfer_to" placeholder="Transfer to">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>NET PAYMENT (Nominal Akhir)</label>
                        <input type="text" id="pm_net_payment_display" readonly style="font-weight:700; color:#16a34a; background-color:#f0fdf4;">
                        <input type="hidden" id="pm_net_payment" name="net_payment">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tanggal Realisasi</label>
                        <input type="date" id="pm_tgl_realisasi" name="tanggal_realisasi">
                    </div>
                    <div class="form-group">
                        <label>Upload Invoice</label>
                        <input type="file" name="file_invoice">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Upload Faktur Pajak</label>
                        <input type="file" name="file_faktur_pajak">
                    </div>
                    <div class="form-group">
                        <label>Upload Bukti Potong</label>
                        <input type="file" name="file_bukti_potong">
                    </div>
                </div>

                <div class="section-divider">Status Pembayaran</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Status Pembayaran</label>
                        <select name="status_bayar" id="pm_status_bayar">
                            <option value="Belum Terbayar">Belum Terbayar (Pending)</option>
                            <option value="Terbayar">✓ Terbayar (Lunas)</option>
                            <option value="Suspend" style="color:#dc2626;">⏸ Suspend</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-close" onclick="closeModal('paymentModal')">Batal</button>
                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    let pmNominalAwal = 0;
    let pmNetPaymentOriginal = 0;

    const currentHospital = @json($current_rs);


    function openPaymentModal(id, penerima, deskripsi, nominalAwal, dpp, ppn, pph23, pph4, transferTo,
                               noInvoice, statusBayar, tglRealisasi, noFaktur, noBupot, netPaymentOriginal) {
        pmNominalAwal = nominalAwal;
        pmNetPaymentOriginal = netPaymentOriginal || 0;
        
        setTimeout(() => {
            document.getElementById('formPayment').reset();
            document.getElementById('modal_item_id').value      = id;
            document.getElementById('text_penerima').innerText  = penerima;
            document.getElementById('text_deskripsi').innerText = deskripsi;

            document.getElementById('pm_nominal_awal').value    = 'Rp ' + nominalAwal.toLocaleString('id-ID');
            document.getElementById('pm_dpp').value             = dpp     ? dpp.toLocaleString('id-ID')     : '';
            document.getElementById('pm_ppn').value             = ppn     ? ppn.toLocaleString('id-ID')     : '';
            document.getElementById('pm_pph23').value           = pph23   ? pph23.toLocaleString('id-ID')   : '';
            document.getElementById('pm_pph_pasal4').value      = pph4    ? pph4.toLocaleString('id-ID')    : '';
            document.getElementById('pm_transfer_to').value     = transferTo || '';
            document.getElementById('pm_no_invoice').value      = noInvoice     || '';
            document.getElementById('pm_status_bayar').value    = statusBayar   || 'Belum Terbayar';
            document.getElementById('pm_tgl_realisasi').value   = tglRealisasi  || '';
            
            // Set hidden net_payment dengan nilai original dari database
            document.getElementById('pm_net_payment').value = netPaymentOriginal || 0;
            document.getElementById('pm_net_payment_display').value = 'Rp ' + (netPaymentOriginal || 0).toLocaleString('id-ID');
        }, 50);
        
        document.getElementById('paymentModal').classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    function formatAndCalculate(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) {
            input.value = parseInt(value, 10).toLocaleString('id-ID');
        } else {
            input.value = '';
        }
        setTimeout(kalkulasiNetPayment, 10);
    }

    function formatRupiah(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) {
            input.value = parseInt(value, 10).toLocaleString('id-ID');
        } else {
            input.value = '';
        }
    }

    function parseCurrency(str) {
        if (!str) return 0;
        return parseInt(str.replace(/[^0-9]/g, ''), 10) || 0;
    }

    function kalkulasiNetPayment() {
        let nominalAwal = parseCurrency(document.getElementById('pm_nominal_awal').value);
        let dpp         = parseCurrency(document.getElementById('pm_dpp').value);
        let ppn         = parseCurrency(document.getElementById('pm_ppn').value);
        let pph23       = parseCurrency(document.getElementById('pm_pph23').value);
        let pph4        = parseCurrency(document.getElementById('pm_pph_pasal4').value);
        
        // Cek apakah ada perubahan pada field pajak
        let hasChanges = (dpp > 0 || ppn > 0 || pph23 > 0 || pph4 > 0);
        
        // Jika tidak ada perubahan pada field pajak, gunakan nilai original
        if (!hasChanges) {
            document.getElementById('pm_net_payment').value = pmNetPaymentOriginal;
            document.getElementById('pm_net_payment_display').value = 'Rp ' + pmNetPaymentOriginal.toLocaleString('id-ID');
        } else {
            // Jika ada perubahan, hitung ulang net_payment
            let base = (dpp > 0 || ppn > 0) ? (dpp + ppn) : nominalAwal;
            let net = base - pph23 - pph4;

            document.getElementById('pm_net_payment').value = net;
            document.getElementById('pm_net_payment_display').value = 'Rp ' + net.toLocaleString('id-ID');
        }
    }
</script>

<!-- PROOF MODAL (Lihat Bukti Pembayaran) -->
<div id="proofModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="proofModalTitle">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <div id="proofModalTitle" style="font-weight:700; font-size:1.1rem; color:#0f172a;">Lihat Bukti Pembayaran</div>
                <div style="margin-top:6px; color:#64748b; font-size:0.95rem;">Pilih invoice, faktur pajak, atau bukti potong yang sudah di-upload di Payment Schedule.</div>
            </div>
            <button type="button" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#475569;" onclick="closeProofModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group proof-select">
                <label for="proofTypeSelect" style="font-weight:700; color:#334155; margin-bottom:8px; display:block;">Pilih Bukti</label>
                <select id="proofTypeSelect" onchange="refreshProofPreview()" style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px 12px; font-size:0.95rem; background:#f8fafc;">
                    <option value="invoice">Invoice</option>
                    <option value="faktur">Faktur Pajak</option>
                    <option value="bupot">Bukti Potong</option>
                </select>
            </div>
            <div class="modal-preview">
                <img id="proofPreviewImage" class="image-preview" src="" alt="Preview Bukti" style="display:none;">
                <div id="proofEmptyMessage" class="file-status" style="display:none;">Data tidak ada untuk bukti yang dipilih.</div>
                <a id="proofDownloadLink" href="#" target="_blank" style="display:none; color:#1d4ed8; font-weight:700;">Buka gambar di tab baru</a>
            </div>
        </div>
        <div class="modal-actions" style="padding:16px; display:flex; justify-content:flex-end; gap:12px; background:#f8fafc; border-top:1px solid #e2e8f0;">
            <button type="button" class="btn-close" onclick="closeProofModal()">Tutup</button>
        </div>
    </div>
</div>

<script>
    const proofBaseUrl = "{{ asset('storage') }}";
    let currentProofFiles = { invoice: null, faktur: null, bupot: null };

    function openProofModal(index, allocationId) {
        currentProofFiles = { invoice: null, faktur: null, bupot: null };
        document.getElementById('proofModalTitle').innerText = 'Lihat Bukti Pembayaran #' + index;
        document.getElementById('proofTypeSelect').value = 'invoice';

        fetch("{{ url('/api/rab-alokasi') }}\/" + allocationId + "/proofs")
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(data => {
                currentProofFiles.invoice = data.file_invoice || null;
                currentProofFiles.faktur = data.file_faktur_pajak || null;
                currentProofFiles.bupot = data.file_bukti_potong || null;
                refreshProofPreview();
                document.getElementById('proofModal').classList.add('show');
            })
            .catch(() => {
                currentProofFiles = { invoice: null, faktur: null, bupot: null };
                refreshProofPreview();
                document.getElementById('proofModal').classList.add('show');
            });
    }

    function closeProofModal() {
        document.getElementById('proofModal').classList.remove('show');
        document.getElementById('proofPreviewImage').src = '';
    }

    function refreshProofPreview() {
        const selectedType = document.getElementById('proofTypeSelect').value;
        const filePath = selectedType === 'invoice'
            ? currentProofFiles.invoice
            : selectedType === 'faktur'
                ? currentProofFiles.faktur
                : currentProofFiles.bupot;
        const previewImage = document.getElementById('proofPreviewImage');
        const downloadLink = document.getElementById('proofDownloadLink');
        const emptyMessage = document.getElementById('proofEmptyMessage');

        if (filePath) {
            const cacheBuster = 't=' + Date.now();
            const url = proofBaseUrl + '/' + filePath + (filePath.includes('?') ? '&' : '?') + cacheBuster;
            previewImage.src = url;
            previewImage.style.display = 'block';
            downloadLink.href = url;
            downloadLink.style.display = 'inline-block';
            emptyMessage.style.display = 'none';
        } else {
            previewImage.style.display = 'none';
            previewImage.src = '';
            downloadLink.style.display = 'none';
            emptyMessage.innerText = selectedType === 'invoice'
                ? 'Tidak ada file invoice yang di-upload.'
                : selectedType === 'faktur'
                    ? 'Tidak ada file faktur pajak yang di-upload.'
                    : 'Tidak ada file bukti potong yang di-upload.';
            emptyMessage.style.display = 'block';
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeProofModal();
        }
    });
</script>

</body>
</html>