<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAB Pra Operasional</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { display: flex; background-color: #f1f5f9; color: #1e293b; min-height: 100vh; }
        
        /* Sidebar Layout */
        .sidebar { width: 280px; background-color: #ffffff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; height: 100vh; position: sticky; top: 0; }
        .sidebar-brand { padding: 24px; border-bottom: 1px solid #e2e8f0; text-align: center; }
        .sidebar-brand img { max-width: 140px; }
        .sidebar-menu { padding: 20px 14px; flex: 1; overflow-y: auto; }
        .menu-category { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; font-weight: 700; margin: 20px 0 8px 10px; }
        .menu-item { display: flex; align-items: center; padding: 10px 14px; color: #475569; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.95rem; margin-bottom: 4px; }
        .menu-item:hover { background-color: #f8fafc; color: #0284c7; }
        .menu-item.active { background-color: #e0f2fe; color: #0369a1; font-weight: 600; }
        
        /* Main Panel Canvas */
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .navbar { height: 70px; background-color: #ffffff; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; }
        .role-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; border: 1px solid #bbf7d0; background-color: #f0fdf4; color: #166534; }
        
        .content-body { padding: 40px; width: 100%; margin: 0 auto; }
        .header-title-block { margin-bottom: 25px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
        .header-title-block h1 { font-size: 1.6rem; color: #0f172a; font-weight: 700; }
        .header-title-block h2 { font-size: 1.3rem; color: #0284c7; font-weight: 700; margin-top: 4px; }

        /* Dashboard Metric Cards */
        .card-info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .card-info { background: white; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card-info span { font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; }
        .card-info p { font-size: 1.3rem; font-weight: 700; color: #0f172a; margin-top: 5px; }

        /* Main View Table Stages */
        .tahap-container { background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        .tahap-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 15px; }
        .tahap-title { font-size: 1.1rem; font-weight: 700; color: #0f172a; }
        .tahap-badge-percent { padding: 2px 8px; background: #e0f2fe; border-radius: 4px; font-size: 0.8rem; color: #0369a1; font-weight: bold; margin-left: 8px; border: 1px solid #bae6fd; }
        .tahap-badge-nominal { padding: 2px 8px; background: #f1f5f9; border-radius: 4px; font-size: 0.85rem; color: #1e293b; font-weight: 700; margin-left: 8px; border: 1px solid #cbd5e1; }
        .tahap-date { font-size: 0.85rem; color: #0284c7; font-weight: 600; }

        .view-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.9rem; }
        .view-table th, .view-table td { border: 1px solid #e2e8f0; padding: 12px; text-align: left; }
        .view-table th { background-color: #f8fafc; color: #475569; font-weight: 600; }

        /* Modal Architecture */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); display: flex; justify-content: center; align-items: center; z-index: 9999; opacity: 0; pointer-events: none; transition: all 0.3s ease; }
        .modal-overlay.show { opacity: 1; pointer-events: auto; }
        .modal-box { background: white; width: 100%; max-width: 900px; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;}
        .modal-body { padding: 24px; max-height: 70vh; overflow-y: auto; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }

        /* Form Utilities */
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-group input { width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; }
        
        .sub-block-tahap { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; margin-bottom: 20px; }
        
        /* Dynamic Inner Table Box Inside Modal */
        .modal-inner-table { width: 100%; border-collapse: collapse; background: white; font-size: 0.85rem; margin-top: 12px; }
        .modal-inner-table th { padding: 10px; background: #e2e8f0; border: 1px solid #cbd5e1; color: #334155; font-weight: 600; text-align: left; }
        .modal-inner-table td { padding: 8px; border: 1px solid #cbd5e1; }
        .modal-inner-table input { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.85rem; }

        /* Balance Indicator Box */
        .balance-box { padding: 10px 18px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; max-width: 50%; line-height: 1.4; }
        .balance-ok { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .balance-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .balance-error { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        
        .btn-blue { padding: 10px 20px; background-color: #0284c7; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.9rem; }
        .btn-blue:hover { background-color: #0369a1; }
        .btn-blue:disabled { background-color: #cbd5e1; color: #94a3b8; cursor: not-allowed; }
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
            <a href="{{ route('kontrak.index') }}" class="menu-item {{ Request::is('menu/kendali-kontrak') ? 'active' : '' }}">Kendali Kontrak</a>
            <a href="{{ route('rab.index') }}" class="menu-item {{ Request::is('menu/rab-pra-operasional') ? 'active' : '' }}">RAB Pra Operasional</a>
            <a href="{{ route('timetable.index') }}" class="menu-item {{ Request::is('menu/time-table') ? 'active' : '' }}">Time Table</a>

            <div class="menu-category">Payment</div>
            <a href="{{ route('menu.view', 'payment-schedule') }}" class="menu-item {{ Request::is('menu/payment-schedule') ? 'active' : '' }}">Payment Schedule</a>
            <a href="{{ route('outstanding-payment.index') }}" class="menu-item {{ Request::is('menu/outstanding-payment') ? 'active' : '' }}">Outstanding Payment</a>
            <a href="{{ route('payment-jurnal.index') }}" class="menu-item {{ Request::is('menu/payment-jurnal') ? 'active' : '' }}">Payment Jurnal</a>
        </div>
    </div>

    <div class="main-content">
        <div class="navbar">
            <div style="font-weight: 600; color: #1e293b;">
                Rumah Sakit Aktif: <span style="color: #0284c7;">{{ $current_rs }}</span>
            </div>
            <div class="user-profile">
                <span class="role-badge">{{ session('user_name') }}</span>
            </div>
        </div>

        <div class="content-body">
            <div class="header-title-block">
                <h1>RAB Dana Pra Operasional Mesin Hemodialisis</h1>
                <h2>Rumah Sakit: {{ $current_rs }}</h2>
            </div>

            @if(!$rab)
                <div style="background: white; border: 1px solid #e2e8f0; border-radius:12px; padding: 60px 40px; text-align:center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <div style="font-size: 3.5rem; margin-bottom: 15px;">📊</div>
                    <p style="color:#64748b; margin-bottom: 20px; font-size: 0.95rem;">Belum ada data struktur anggaran RAB Pra Operasional yang terbuat untuk rumah sakit ini.</p>
                    @if(session('user_role') == 'JMS')
                        <button class="btn-blue" onclick="toggleModal(true)">+ Buat Struktur RAB Baru</button>
                    @endif
                </div>
            @else
                <div style="display:flex; justify-content:space-between; align-items: center; margin-bottom:20px; background: white; padding: 15px 20px; border-radius: 10px; border: 1px solid #e2e8f0;">
                    <div>
                        <p style="font-size: 0.95rem; color:#1e293b;"><b>No Kontrak:</b> <span style="font-family: monospace; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">{{ $rab->no_kontrak }}</span> | <b>Tanggal Kontrak:</b> {{ date('d-m-Y', strtotime($rab->tgl_kontrak)) }}</p>
                        <p style="margin-top:6px; font-size:0.85rem; color:#64748b;">⚠️ Info Tambahan: Terbaca {{ $rab->jumlah_mesin_cadangan }} Unit Mesin Cadangan.</p>
                    </div>
                    @if(session('user_role') == 'JMS')
                        <div style="display:flex; gap:10px; align-items:center;">
                            <button type="button" class="btn-blue" onclick="openEditModal()">Edit RAB</button>
                            <form action="{{ route('rab.delete', $rab->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset ulang seluruh data anggaran RAB ini?')">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" style="padding: 8px 16px; background:#ef4444; color:white; border:none; border-radius:8px; font-weight:600; cursor:pointer; font-size:0.85rem;">Reset Data RAB</button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="card-info-grid">
                    <div class="card-info"><span>Jumlah Mesin Aktif</span><p>{{ $rab->jumlah_mesin }} Unit</p></div>
                    <div class="card-info"><span>Dana / Mesin</span><p>Rp {{ number_format($rab->dana_operasional_per_mesin, 0, ',', '.') }}</p></div>
                    <div class="card-info"><span>Total Dana Operasional (100%)</span><p style="color:#0284c7;">Rp {{ number_format($rab->total_dana_operasional, 0, ',', '.') }}</p></div>
                    <div class="card-info"><span>Sisa Dana Operasional</span><p style="color:#166534;">Rp {{ number_format($rab->total_dana_operasional - $total_terpakai, 0, ',', '.') }}</p></div>
                </div>

                @php
                    $persen_tahap = [1 => 30, 2 => 30, 3 => 40];
                @endphp
                @for($t = 1; $t <= 3; $t++)
                    @php 
                        $list = ${"tahap".$t}; 
                        $subtotal_tahap = $list->sum('nominal'); // Menjumlahkan nominal record per tahap
                    @endphp
                    <div class="tahap-container">
                        <div class="tahap-header">
                            <div style="display: flex; align-items: center; gap:8px;">
                                <div class="tahap-title">Pembayaran Tahap {{ $t }}</div>
                                <span class="tahap-badge-percent">Porsi {{ $persen_tahap[$t] }}%</span>
                                @php $expected_tahap = isset($rab->total_dana_operasional) ? round($rab->total_dana_operasional * $persen_tahap[$t] / 100) : 0; @endphp
                                <span class="tahap-badge-nominal" title="Presentase berdasarkan Total Dana Operasional (100%) murni">Total ({{ $persen_tahap[$t] }}% dari Total Dana Operasional): Rp {{ number_format($expected_tahap, 0, ',', '.') }}</span>
                                <span class="tahap-badge-nominal">Realisasi: Rp {{ number_format($subtotal_tahap, 0, ',', '.') }}</span>
                            </div>
                            <div class="tahap-date">Estimasi Jatuh Tempo: {{ count($list) > 0 ? date('d-m-Y', strtotime($list->first()->jatuh_tempo_tahap)) : '-' }}</div>
                        </div>
                        <table class="view-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 35%;">Penerima Alokasi</th>
                                    <th style="width: 40%;">Deskripsi Kebutuhan Pokok</th>
                                    <th style="width: 20%; text-align: right;">Nominal Anggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sub_no = 1; @endphp
                                @forelse($list as $item)
                                    <tr>
                                        <td style="text-align:center; font-weight:bold; color:#64748b;">{{ $sub_no++ }}</td>
                                        <td><b>{{ $item->penerima_alokasi }}</b></td>
                                        <td>{{ $item->deskripsi }}</td>
                                        <td style="text-align: right; font-weight: 600; color:#0f172a;">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="color:#94a3b8; text-align:center; padding: 20px;">
                                            Belum ada entri rincian alokasi pada pembayaran tahap ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endfor
            @endif
        </div>
    </div>

    <div id="rabModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <div style="font-weight:700; color:#0f172a; font-size:1.1rem;">Formulir Penyusunan Anggaran RAB Baru (Aturan 30% - 30% - 40%)</div>
                <button type="button" style="background:none; border:none; font-size:1.5rem; cursor:pointer;" onclick="toggleModal(false)">&times;</button>
            </div>
            <form action="{{ route('rab.store') }}" method="POST" id="formRab">
                @csrf
                <input type="hidden" name="_method" value="POST" id="form_method">
                <div class="modal-body">
                    
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                        <div class="form-group"><label>No Kontrak</label><input type="text" id="no_kontrak" name="no_kontrak" placeholder="Contoh: 04/NMS-KSO/V/2026" required></div>
                        <div class="form-group"><label>Tanggal Kontrak</label><input type="date" id="tgl_kontrak" name="tgl_kontrak" required></div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:15px;">
                        <div class="form-group"><label>Jumlah Mesin Aktif</label><input type="number" id="qty_mesin" name="jumlah_mesin" placeholder="0" oninput="hitungPagu()" required></div>
                        <div class="form-group"><label>Dana Operasional / Mesin</label><input type="text" id="dana_mesin" name="dana_operasional_per_mesin" placeholder="0" oninput="formatRupiahInput(this); hitungPagu();" required></div>
                        <div class="form-group"><label>Jumlah Mesin Cadangan</label><input type="number" id="jumlah_mesin_cadangan" name="jumlah_mesin_cadangan" value="0" min="0"></div>
                    </div>

                    <div style="background:#e0f2fe; padding:14px; border-radius:8px; font-weight:700; margin-bottom:20px; color:#0369a1; font-size:0.95rem; border: 1px solid #bae6fd;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span>TOTAL DANA OPERASIONAL (100%):</span>
                            <span id="label_pagu">Rp 0</span>
                        </div>
                        <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #bae6fd; font-size: 0.85rem;">
                            <div>🎯 Target Tahap 1 (30%): <br><span id="target_t1" style="color:#0f172a;">Rp 0</span></div>
                            <div>🎯 Target Tahap 2 (30%): <br><span id="target_t2" style="color:#0f172a;">Rp 0</span></div>
                            <div>🎯 Target Tahap 3 (40%): <br><span id="target_t3" style="color:#0f172a;">Rp 0</span></div>
                        </div>
                    </div>

                    @php $p_label = [1 => "Tahap 1 (Wajib 30%)", 2 => "Tahap 2 (Wajib 30%)", 3 => "Tahap 3 (Wajib 40%)"]; @endphp
                    @for($t = 1; $t <= 3; $t++)
                        <div class="sub-block-tahap">
                            <div style="display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid #e2e8f0; padding-bottom:8px; margin-bottom:10px;">
                                <h4 style="font-weight:700; color:#0f172a;">{{ $p_label[$t] }}</h4>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <label style="font-size:0.8rem; font-weight:600; color:#475569;">Banyak Alokasi Dana: </label>
                                    <input id="input_count_tahap_{{ $t }}" type="number" min="0" placeholder="0" style="width:65px; padding:6px; border-radius:6px; border:1px solid #cbd5e1; text-align:center; font-weight:bold;" oninput="generateAlokasi(this, {{ $t }})">
                                </div>
                            </div>
                            <div id="wadah_alokasi_tahap_{{ $t }}"></div>
                        </div>
                    @endfor

                </div>
                <div class="modal-footer">
                    <div id="balance_indicator" class="balance-box balance-error">Sistem mendeteksi sisa alokasi anggaran belum seimbang.</div>
                    <button type="submit" id="btn_simpan" class="btn-blue" disabled>Simpan Struktur Anggaran</button>
                </div>
            </form>
        </div>
    </div>

    @php
        $rabEditData = null;
        if ($rab) {
            $rabEditData = [
                'id' => $rab->id,
                'no_kontrak' => $rab->no_kontrak,
                'tgl_kontrak' => $rab->tgl_kontrak,
                'jumlah_mesin' => $rab->jumlah_mesin,
                'dana_operasional_per_mesin' => $rab->dana_operasional_per_mesin,
                'jumlah_mesin_cadangan' => $rab->jumlah_mesin_cadangan,
                'alokasi' => [
                    1 => $tahap1->map(function ($row) {
                        return ['id' => $row->id, 'penerima' => $row->penerima_alokasi, 'deskripsi' => $row->deskripsi, 'nominal' => $row->nominal];
                    })->values()->all(),
                    2 => $tahap2->map(function ($row) {
                        return ['id' => $row->id, 'penerima' => $row->penerima_alokasi, 'deskripsi' => $row->deskripsi, 'nominal' => $row->nominal];
                    })->values()->all(),
                    3 => $tahap3->map(function ($row) {
                        return ['id' => $row->id, 'penerima' => $row->penerima_alokasi, 'deskripsi' => $row->deskripsi, 'nominal' => $row->nominal];
                    })->values()->all(),
                ],
            ];
        }
    @endphp

    <script>
        let paguTotal = 0;
        let targetT1 = 0;
        let targetT2 = 0;
        let targetT3 = 0;
        let isEditMode = false;
        const rabEditData = {!! json_encode($rabEditData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};

        function formatCurrencyString(value) {
            if (value === null || value === undefined || value === '') return '';
            return Number(value).toLocaleString('id-ID');
        }

        function normalizeNominalValue(value) {
            if (value === null || value === undefined || value === '') return 0;
            const digitsOnly = value.toString().replace(/[^\d]/g, '');
            return digitsOnly === '' ? 0 : parseInt(digitsOnly, 10);
        }

        function openEditModal() {
            if (!rabEditData || !rabEditData.id) {
                alert('Tidak ada data RAB untuk diedit.');
                return;
            }

            toggleModal(true);
            const form = document.getElementById('formRab');
            form.action = '/menu/rab-pra-operasional/update/' + rabEditData.id;
            document.getElementById('form_method').value = 'PUT';
            document.getElementById('no_kontrak').value = rabEditData.no_kontrak || '';
            document.getElementById('tgl_kontrak').value = rabEditData.tgl_kontrak || '';
            document.getElementById('qty_mesin').value = rabEditData.jumlah_mesin || '';
            document.getElementById('dana_mesin').value = formatCurrencyString(rabEditData.dana_operasional_per_mesin);
            document.getElementById('jumlah_mesin_cadangan').value = rabEditData.jumlah_mesin_cadangan || 0;

            for (let tahap = 1; tahap <= 3; tahap++) {
                const rows = Array.isArray(rabEditData.alokasi[tahap]) ? rabEditData.alokasi[tahap] : [];
                document.getElementById('input_count_tahap_' + tahap).value = rows.length;
                populateAlokasiRows(tahap, rows);
            }

            isEditMode = true;
            document.getElementById('btn_simpan').innerText = 'Perbarui Struktur Anggaran';
            hitungPagu();
        }

        function resetFormMode() {
            const form = document.getElementById('formRab');
            form.action = '{{ route('rab.store') }}';
            document.getElementById('form_method').value = 'POST';
            document.getElementById('btn_simpan').innerText = 'Simpan Struktur Anggaran';
            isEditMode = false;
        }

        function populateAlokasiRows(tahapNum, rows) {
            const container = document.getElementById('wadah_alokasi_tahap_' + tahapNum);
            container.innerHTML = '';

            if (!rows || rows.length === 0) {
                cekKeseimbanganDana();
                return;
            }

            let table = document.createElement('table');
            table.className = 'modal-inner-table';
            let bodyHtml = `
                <thead>
                    <tr>
                        <th style="width: 6%; text-align: center;">No</th>
                        <th style="width: 34%;">Penerima Alokasi</th>
                        <th style="width: 35%;">Deskripsi / Kebutuhan Pokok</th>
                        <th style="width: 25%; text-align: right;">Nominal Anggaran (Rp)</th>
                    </tr>
                </thead>
                <tbody>
            `;

            rows.forEach((row, index) => {
                const nominalNumber = normalizeNominalValue(row.nominal);
                const nominalFormatted = nominalNumber.toLocaleString('id-ID');
                bodyHtml += `
                    <tr>
                        <td style="text-align: center; font-weight: bold; color: #64748b;">${index + 1}</td>
                        <td>
                            <input type="hidden" name="alokasi[${tahapNum}][${index}][id]" value="${row.id ?? ''}">
                            <input type="text" name="alokasi[${tahapNum}][${index}][penerima]" placeholder="Masukkan nama penerima..." value="${row.penerima ?? ''}" required>
                        </td>
                        <td>
                            <input type="text" name="alokasi[${tahapNum}][${index}][deskripsi]" placeholder="Contoh: Pengadaan Alat Kesehatan" value="${row.deskripsi ?? ''}">
                        </td>
                        <td>
                            <input type="text" name="alokasi[${tahapNum}][${index}][nominal]" placeholder="0" class="hitung-alokasi-tahap-${tahapNum}" oninput="formatRupiahInput(this); cekKeseimbanganDana();" style="text-align: right;" value="${nominalFormatted}" required>
                        </td>
                    </tr>
                `;
            });

            bodyHtml += `</tbody>`;
            table.innerHTML = bodyHtml;
            container.appendChild(table);
            cekKeseimbanganDana();
        }

        function toggleModal(show) {
            const m = document.getElementById('rabModal');
            if(show) m.classList.add('show');
            else { 
                m.classList.remove('show'); 
                document.getElementById('formRab').reset(); 
                for(let t=1; t<=3; t++) {
                    document.getElementById('wadah_alokasi_tahap_' + t).innerHTML = '';
                    document.getElementById('input_count_tahap_' + t).value = '';
                }
                resetFormMode();
                hitungPagu();
            }
        }

        function formatRupiahInput(el) {
            let val = el.value.replace(/[^,\d]/g, '').toString();
            let split = val.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            el.value = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        }

        function hitungPagu() {
            let qty = parseInt(document.getElementById('qty_mesin').value) || 0;
            let dana = parseInt(document.getElementById('dana_mesin').value.replace(/\./g, '')) || 0;
            paguTotal = qty * dana;

            targetT1 = Math.round(paguTotal * 0.3);
            targetT2 = Math.round(paguTotal * 0.3);
            targetT3 = Math.round(paguTotal * 0.4);

            document.getElementById('label_pagu').innerText = "Rp " + paguTotal.toLocaleString('id-ID');
            document.getElementById('target_t1').innerText = "Rp " + targetT1.toLocaleString('id-ID');
            document.getElementById('target_t2').innerText = "Rp " + targetT2.toLocaleString('id-ID');
            document.getElementById('target_t3').innerText = "Rp " + targetT3.toLocaleString('id-ID');
            
            cekKeseimbanganDana();
        }

        function generateAlokasi(inputEl, tahapNum) {
            const totalBaris = parseInt(inputEl.value) || 0;
            const container = document.getElementById('wadah_alokasi_tahap_' + tahapNum);
            const currentRows = [];

            container.querySelectorAll('tbody tr').forEach(tr => {
                const penerimaInput = tr.querySelector('input[name$="[penerima]"]');
                const deskripsiInput = tr.querySelector('input[name$="[deskripsi]"]');
                const nominalInput = tr.querySelector('input[name$="[nominal]"]');

                currentRows.push({
                    penerima: penerimaInput ? penerimaInput.value : '',
                    deskripsi: deskripsiInput ? deskripsiInput.value : '',
                    nominal: nominalInput ? normalizeNominalValue(nominalInput.value).toString() : '0'
                });
            });

            const rows = [];
            for (let i = 0; i < totalBaris; i++) {
                rows.push(currentRows[i] || { penerima: '', deskripsi: '', nominal: '0' });
            }

            populateAlokasiRows(tahapNum, rows);
        }

        function cekKeseimbanganDana() {
            let totalT1 = 0;
            document.querySelectorAll('.hitung-alokasi-tahap-1').forEach(inp => {
                totalT1 += parseInt(inp.value.replace(/\./g, '')) || 0;
            });

            let totalT2 = 0;
            document.querySelectorAll('.hitung-alokasi-tahap-2').forEach(inp => {
                totalT2 += parseInt(inp.value.replace(/\./g, '')) || 0;
            });

            let totalT3 = 0;
            document.querySelectorAll('.hitung-alokasi-tahap-3').forEach(inp => {
                totalT3 += parseInt(inp.value.replace(/\./g, '')) || 0;
            });

            let selisihT1 = targetT1 - totalT1;
            let selisihT2 = targetT2 - totalT2;
            let selisihT3 = targetT3 - totalT3;

            const indikator = document.getElementById('balance_indicator');
            const btn = document.getElementById('btn_simpan');

            if (paguTotal === 0) {
                indikator.innerText = "Silakan tentukan jumlah mesin & dana operasional terlebih dahulu.";
                indikator.className = "balance-box balance-error";
                btn.disabled = true;
                return;
            }

            if (selisihT1 === 0 && selisihT2 === 0 && selisihT3 === 0) {
                indikator.innerText = "✓ Keseimbangan Sempurna! Selisih tiap tahap Rp 0. Data aman disimpan.";
                indikator.className = "balance-box balance-ok";
                btn.disabled = false;
            } else {
                let msg = "Perhatian: alokasi belum seimbang. Selisih saat ini -> ";
                if(selisihT1 !== 0) msg += `Tahap 1 (${selisihT1 > 0 ? 'Kurang' : 'Over'} Rp ${Math.abs(selisihT1).toLocaleString('id-ID')}) | `;
                if(selisihT2 !== 0) msg += `Tahap 2 (${selisihT2 > 0 ? 'Kurang' : 'Over'} Rp ${Math.abs(selisihT2).toLocaleString('id-ID')}) | `;
                if(selisihT3 !== 0) msg += `Tahap 3 (${selisihT3 > 0 ? 'Kurang' : 'Over'} Rp ${Math.abs(selisihT3).toLocaleString('id-ID')})`;
                
                if(msg.endsWith(' | ')) msg = msg.slice(0, -3);

                indikator.innerText = msg;
                indikator.className = "balance-box balance-warning";
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>