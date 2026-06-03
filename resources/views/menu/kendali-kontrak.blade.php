<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Kendali Pelaksana KSO - HD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { display: flex; background-color: #f1f5f9; color: #1e293b; min-height: 100vh; }
        
        /* Sidebar Layout */
        .sidebar { width: 280px; background-color: #ffffff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; height: 100vh; position: sticky; top: 0; }
        .sidebar-brand { padding: 24px; border-bottom: 1px solid #e2e8f0; text-align: center; }
        .sidebar-brand img { max-width: 140px; }
        .sidebar-menu { padding: 20px 14px; flex: 1; overflow-y: auto; }
        .menu-category { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; font-weight: 700; margin: 20px 0 8px 10px; }
        .menu-item { display: flex; align-items: center; padding: 10px 14px; color: #475569; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.95rem; margin-bottom: 4px; transition: all 0.2s; }
        .menu-item:hover { background-color: #f8fafc; color: #0284c7; }
        .menu-item.active { background-color: #e0f2fe; color: #0369a1; font-weight: 600; }
        
        /* Main Workspace */
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .navbar { height: 70px; background-color: #ffffff; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; }
        .user-profile { display: flex; align-items: center; gap: 12px; }
        .role-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; border: 1px solid #bbf7d0; background-color: #f0fdf4; color: #166534; }
        .role-badge.jms { background-color: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
        
        .content-body { padding: 40px; width: 100%; margin: 0 auto; }
        
        /* Header Title */
        .header-title-block { margin-bottom: 25px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
        .header-title-block h1 { font-size: 1.6rem; color: #0f172a; font-weight: 700; }
        .header-title-block h2 { font-size: 1.2rem; color: #475569; font-weight: 600; margin-top: 4px; }
        .header-title-block h3 { font-size: 1.3rem; color: #0284c7; font-weight: 700; margin-top: 4px; }

        /* Action Top Utilities */
        .action-bar { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .btn-add { padding: 10px 20px; background-color: #0284c7; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; gap: 8px; }
        .btn-add:hover { background-color: #0369a1; }
        
        /* Responsive Table Design */
        .table-container { background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        th { background-color: #f8fafc; color: #475569; font-weight: 600; padding: 14px 16px; border-bottom: 2px solid #e2e8f0; }
        td { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; color: #334155; vertical-align: top; line-height: 1.4; }
        tr:hover { background-color: #f8fafc; }
        
        /* Action Buttons Inside Table */
        .btn-edit { padding: 6px 12px; background-color: #eab308; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.8rem; margin-right: 4px; }
        .btn-edit:hover { background-color: #ca8a04; }
        .btn-delete { padding: 6px 12px; background-color: #ef4444; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.8rem; }
        .btn-delete:hover { background-color: #dc2626; }
        
        /* Modal Overlay Configuration */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); display: flex; justify-content: center; align-items: center; z-index: 9999; opacity: 0; pointer-events: none; transition: all 0.3s ease; }
        .modal-overlay.show { opacity: 1; pointer-events: auto; }
        .modal-box { background: white; width: 100%; max-width: 650px; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); transform: translateY(-20px); transition: all 0.3s ease; overflow: hidden; }
        .modal-overlay.show .modal-box { transform: translateY(0); }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;}
        .modal-title { font-size: 1.2rem; font-weight: 700; color: #0f172a; }
        .modal-close { background: none; border: none; font-size: 1.5rem; color: #94a3b8; cursor: pointer; }
        .modal-body { padding: 24px; max-height: 70vh; overflow-y: auto; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 12px; background: #f8fafc; }
        
        /* Form Field Components */
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; }
        .form-group textarea { resize: vertical; min-height: 70px; }
        .btn-secondary { padding: 10px 18px; background: white; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 600; color: #475569; cursor: pointer; }
        .btn-secondary:hover { background: #f1f5f9; }
        .btn-submit { padding: 10px 18px; background-color: #0284c7; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background-color: #0369a1; }
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
            <a href="{{ route('kontrak.index') }}" class="menu-item active">Kendali Kontrak</a>
            <a href="{{ route('menu.view', 'rab-pra-operasional') }}" class="menu-item">RAB Pra Operasional</a>
            <a href="{{ route('menu.view', 'time-table') }}" class="menu-item">Time Table</a>
            
            <div class="menu-category">Payment</div>
            <a href="{{ route('menu.view', 'payment-schedule') }}" class="menu-item">Payment Schedule</a>
            <a href="{{ route('menu.view', 'outstanding-payment') }}" class="menu-item">Outstanding Payment</a>
            <a href="{{ route('menu.view', 'payment-jurnal') }}" class="menu-item">Payment Jurnal</a>
        </div>
    </div>

    <div class="main-content">
        <div class="navbar">
            <div style="font-weight: 600; color: #1e293b;">
                Rumah Sakit Aktif: <span style="color: #0284c7;">{{ $current_rs }}</span>
            </div>
            <div class="user-profile">
                <span class="role-badge {{ session('user_role') == 'JMS' ? 'jms' : '' }}">
                    {{ session('user_name') }}
                </span>
            </div>
        </div>

        <div class="content-body">
            
            <div class="header-title-block">
                <h1>Master Kendali Pelaksana KSO - HD</h1>
                <h2>PT. Niaga Mutuprima Sejati - Jakarta</h2>
                <h3>Rumah Sakit: {{ $current_rs }}</h3>
            </div>

            <div class="action-bar">
                @if(session('user_role') == 'JMS')
                    <button class="btn-add" onclick="openAddModal()">+ Tambah Data Kontrak</button>
                @endif
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tempat & Kedudukan Mitra RS</th>
                            <th>KSO (Kerjasama Operasional)</th>
                            <th>Lama Kontrak</th>
                            <th>Hak & Kewajiban RS</th>
                            <th>Mekanisme & Prosentase Kerja Sama</th>
                            <th>Elemen Wanprestasi</th>
                            <th>Potensi Wanprestasi</th>
                            @if(session('user_role') == 'JMS')
                                <th style="width: 150px; text-align: center;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $index => $contract)
                            <tr>
                                <td>{{ $contract->mitra_rs }}</td>
                                <td>{{ $contract->kso }}</td>
                                <td>{{ $contract->lama_kontrak }}</td>
                                <td>{!! nl2br(e($contract->hak_kewajiban)) !!}</td>
                                <td>{!! nl2br(e($contract->mekanisme_prosentase)) !!}</td>
                                <td>{!! nl2br(e($contract->elemen_wanprestasi)) !!}</td>
                                <td>{!! nl2br(e($contract->potensi_wanprestasi)) !!}</td>
                                @if(session('user_role') == 'JMS')
                                    <td style="text-align: center;">
                                        <button class="btn-edit" onclick='openEditModal({!! json_encode($contract) !!})'>Ubah</button>
                                        <form action="{{ route('kontrak.delete', $contract->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">Hapus</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ session('user_role') == 'JMS' ? 8 : 7 }}" style="text-align: center; padding: 40px; color: #94a3b8;">
                                    Belum ada data kendali kontrak untuk rumah sakit ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="contractModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title" id="modalTitle">Tambah Data Kendali Kontrak</div>
                <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form id="contractForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="nama_rs" value="{{ $current_rs }}">

                    <div class="form-group">
                        <label>Tempat & Kedudukan Mitra RS</label>
                        <input type="text" name="mitra_rs" id="field_mitra_rs" required placeholder="-">
                    </div>

                    <div class="form-group">
                        <label>KSO (Kerjasama Operasional)</label>
                        <input type="text" name="kso" id="field_kso" required placeholder="-">
                    </div>

                    <div class="form-group">
                        <label>Lama Kontrak</label>
                        <input type="text" name="lama_kontrak" id="field_lama_kontrak" required placeholder="-">
                    </div>

                    <div class="form-group">
                        <label>Hak & Kewajiban RS</label>
                        <textarea name="hak_kewajiban" id="field_hak_kewajiban" required placeholder="-"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Mekanisme & Prosentase Kerja Sama</label>
                        <textarea name="mekanisme_prosentase" id="field_mekanisme_prosentase" required placeholder="-"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Elemen Wanprestasi</label>
                        <textarea name="elemen_wanprestasi" id="field_elemen_wanprestasi" placeholder="-"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Potensi Wanprestasi</label>
                        <textarea name="potensi_wanprestasi" id="field_potensi_wanprestasi" placeholder="-"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-submit" id="btnSubmitModal">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('contractModal');
        const form = document.getElementById('contractForm');
        const modalTitle = document.getElementById('modalTitle');
        const btnSubmitModal = document.getElementById('btnSubmitModal');

        function openAddModal() {
            modalTitle.innerText = "Tambah Data Kendali Kontrak";
            btnSubmitModal.innerText = "Simpan Data Baru";
            form.action = "{{ route('kontrak.store') }}";
            
            // Bersihkan semua form field
            document.getElementById('field_mitra_rs').value = "";
            document.getElementById('field_kso').value = "";
            document.getElementById('field_lama_kontrak').value = "";
            document.getElementById('field_hak_kewajiban').value = "";
            document.getElementById('field_mekanisme_prosentase').value = "";
            document.getElementById('field_elemen_wanprestasi').value = "";
            document.getElementById('field_potensi_wanprestasi').value = "";

            // Hapus input spoofing method-override jika ada sisa dari modal edit
            const oldMethod = document.getElementById('method_override');
            if(oldMethod) oldMethod.remove();

            modal.classList.add('show');
        }

        function openEditModal(data) {
            modalTitle.innerText = "Ubah Data Kendali Kontrak";
            btnSubmitModal.innerText = "Simpan Perubahan";
            
            // Set action URL dinamis mengarah ke rute update ID data terkait
            form.action = `/menu/kendali-kontrak/update/${data.id}`;

            // Isi nilai form fields berdasarkan data baris tabel yang diklik
            document.getElementById('field_mitra_rs').value = data.mitra_rs;
            document.getElementById('field_kso').value = data.kso;
            document.getElementById('field_lama_kontrak').value = data.lama_kontrak;
            document.getElementById('field_hak_kewajiban').value = data.hak_kewajiban;
            document.getElementById('field_mekanisme_prosentase').value = data.mekanisme_prosentase;
            document.getElementById('field_elemen_wanprestasi').value = data.elemen_wanprestasi;
            document.getElementById('field_potensi_wanprestasi').value = data.potensi_wanprestasi;

            modal.classList.add('show');
        }

        function closeModal() {
            modal.classList.remove('show');
        }
    </script>
</body>
</html>