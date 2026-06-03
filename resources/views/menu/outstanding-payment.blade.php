<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outstanding Payment - Portal Kerja Sama</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { display: flex; background-color: #f1f5f9; color: #1e293b; min-height: 100vh; }
        .sidebar { width: 280px; background-color: #ffffff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; height: 100vh; position: sticky; top: 0; }
        .sidebar-brand { padding: 24px; border-bottom: 1px solid #e2e8f0; text-align: center; }
        .sidebar-brand img { max-width: 140px; }
        .sidebar-menu { padding: 20px 14px; flex: 1; overflow-y: auto; }
        .menu-category { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; font-weight: 700; margin: 20px 0 8px 10px; }
        .menu-item { display: flex; align-items: center; padding: 10px 14px; color: #475569; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.95rem; margin-bottom: 4px; transition: all 0.2s; }
        .menu-item:hover { background-color: #f8fafc; color: #0284c7; }
        .menu-item.active { background-color: #e0f2fe; color: #0369a1; font-weight: 600; }
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .navbar { height: 70px; background-color: #ffffff; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; }
        .content-body { padding: 40px; max-width: 1200px; width: 100%; margin: 0 auto; }
        .breadcrumb { font-size: 0.85rem; color: #64748b; margin-bottom: 8px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 16px; color: #0f172a; }
        .form-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-bottom: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-size: 0.9rem; color: #334155; }
        .form-group input, .form-group select { border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 0.95rem; background: #f8fafc; }
        .btn-primary { padding: 12px 18px; background-color: #0284c7; color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
        .btn-primary:hover { background-color: #0369a1; }
        .summary-row { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
        .summary-card { flex: 1 1 200px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 12px; padding: 18px; }
        .summary-card.summary-card-primary { background: #e0f2fe; border-color: #7dd3fc; }
        .summary-card strong { display: block; margin-bottom: 8px; color: #334155; }
        .summary-card span { font-size: 1.35rem; font-weight: 700; color: #0f172a; }
        .data-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        .data-table th, .data-table td { border: 1px solid #e2e8f0; padding: 12px; text-align: left; vertical-align: top; }
        .data-table th { background: #f8fafc; color: #334155; font-weight: 700; }
        .badge { display: inline-flex; align-items: center; padding: 6px 10px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-success { background: #dcfce7; color: #166534; }
        .empty-state { padding: 40px; text-align: center; color: #475569; }
        .btn-secondary { padding: 10px 14px; background: #1d4ed8; color: #ffffff; border: none; border-radius: 10px; cursor: pointer; transition: background 0.2s; font-weight: 700; }
        .btn-secondary:hover { background: #1e40af; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.55); display: none; align-items: center; justify-content: center; padding: 16px; z-index: 50; }
        .modal-overlay.active { display: flex; }
        .modal-box { width: min(100%, 720px); background: #ffffff; border-radius: 16px; padding: 24px; box-shadow: 0 24px 80px rgba(15, 23, 42, 0.18); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .modal-body { display: grid; gap: 16px; }
        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
        .modal-preview { display: grid; gap: 14px; }
        .proof-select { max-width: 280px; }
        .image-preview { width: 100%; max-height: 480px; object-fit: contain; border: 1px solid #cbd5e1; border-radius: 12px; background: #f8fafc; }
        .file-status { padding: 16px; border: 1px dashed #cbd5e1; border-radius: 12px; text-align: center; color: #475569; background: #f8fafc; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/PT JMS Kediri.png') }}" alt="Logo" onerror="this.onerror=null;this.src='https://placehold.co/120x45?text=PT+JMS';">
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item">← Beranda Utama</a>
            <div class="menu-category">Operational</div>
            <a href="{{ route('kontrak.index') }}" class="menu-item {{ Request::is('menu/kendali-kontrak') ? 'active' : '' }}">Kendali Kontrak</a>
            <a href="{{ route('menu.view', 'rab-pra-operasional') }}" class="menu-item {{ Request::is('menu/rab-pra-operasional') ? 'active' : '' }}">RAB Pra Operasional</a>
            <a href="{{ route('menu.view', 'time-table') }}" class="menu-item {{ Request::is('menu/time-table') ? 'active' : '' }}">Time Table</a>
            <div class="menu-category">Payment</div>
            <a href="{{ route('menu.view', 'payment-schedule') }}" class="menu-item {{ Request::is('menu/payment-schedule') ? 'active' : '' }}">Payment Schedule</a>
            <a href="{{ route('outstanding-payment.index') }}" class="menu-item {{ Request::is('menu/outstanding-payment') ? 'active' : '' }}">Outstanding Payment</a>
            <a href="{{ route('payment-jurnal.index') }}" class="menu-item {{ Request::is('menu/payment-jurnal') ? 'active' : '' }}">Payment Jurnal</a>
        </div>
    </div>

    <div class="main-content">
        <div class="navbar">
            <div style="font-weight: 600; color: #1e293b;">Rumah Sakit Aktif: <span style="color: #0284c7;">{{ session('selected_hospital') }}</span></div>
        </div>

        <div class="content-body">
            <div class="breadcrumb">Sub Menu / {{ $selectedHospital === 'all' ? 'Semua Rumah Sakit' : session('selected_hospital') }} / Outstanding Payment</div>
            <div class="page-header">
                <h1>Outstanding Payment</h1>
            </div>

            <div class="card">
                <div class="card-title">Filter Data</div>
                <form method="GET" action="{{ route('outstanding-payment.index') }}">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Rumah Sakit</label>
                            <select name="rumah_sakit">
                                <option value="all" {{ $selectedHospital === 'all' ? 'selected' : '' }}>Semua Rumah Sakit</option>
                                @foreach($daftarRumahSakit as $rs)
                                    <option value="{{ $rs->nama_rs }}" {{ $rs->nama_rs === $selectedHospital ? 'selected' : '' }}>
                                        {{ $rs->nama_rs }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Mulai Jatuh Tempo</label>
                            <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Selesai Jatuh Tempo</label>
                            <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Terapkan Filter</button>
                </form>
            </div>

            <div class="summary-row">
                <div class="summary-card summary-card-primary">
                    <strong>Total Item Outstanding</strong>
                    <span>{{ $outstanding->count() }}</span>
                </div>
                <div class="summary-card">
                    <strong>Total Nominal</strong>
                    <span>Rp {{ number_format($outstanding->sum('nominal'), 0, ',', '.') }}</span>
                </div>
                <div class="summary-card">
                    <strong>Total Net Payment</strong>
                    <span>Rp {{ number_format($outstanding->sum('net_payment'), 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Daftar Outstanding Payment</div>
                @if($outstanding->isEmpty())
                    <div class="empty-state">
                        Tidak ada outstanding payment yang dimuat. Coba ubah filter tanggal atau periksa rumah sakit yang dipilih.
                    </div>
                @else
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rumah Sakit</th>
                                <th>Jatuh Tempo</th>
                                <th>Tahap</th>
                                <th>Penerima Alokasi</th>
                                <th>Transfer To</th>
                                <th>Deskripsi</th>
                                <th>Nominal</th>
                                <th>Net Payment</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($outstanding as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row->nama_rs }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->jatuh_tempo_tahap)->format('d M Y') }}</td>
                                    <td>{{ $row->tahap }}</td>
                                    <td>{{ $row->penerima_alokasi }}</td>
                                    <td>{{ $row->transfer_to ? $row->transfer_to : '-' }}</td>
                                    <td>{{ $row->deskripsi }}</td>
                                    <td>Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($row->net_payment ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        @if($row->status_bayar === 'URGENT')
                                            <span class="badge badge-danger">URGENT</span>
                                        @elseif($row->status_bayar === 'Suspend')
                                            <span class="badge badge-danger">Suspend</span>
                                        @elseif($row->status_bayar === 'Belum Terbayar')
                                            <span class="badge badge-warning">Belum Terbayar</span>
                                        @else
                                            <span class="badge badge-success">{{ $row->status_bayar }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn-secondary" onclick='openProofModal({{ $index + 1 }}, {{ $row->id }})'>
                                            Lihat Bukti
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

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
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeProofModal()">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        const proofBaseUrl = "{{ asset('storage') }}";
        let currentProofFiles = { invoice: null, faktur: null, bupot: null };

        function openProofModal(index, allocationId) {
            // reset before fetch
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
                    document.getElementById('proofModal').classList.add('active');
                })
                .catch(() => {
                    // still open modal but show empty message
                    currentProofFiles = { invoice: null, faktur: null, bupot: null };
                    refreshProofPreview();
                    document.getElementById('proofModal').classList.add('active');
                });
        }

        function closeProofModal() {
            document.getElementById('proofModal').classList.remove('active');
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
