<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Table Pekerjaan Pra Operasional</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { display: flex; background-color: #f1f5f9; color: #1e293b; min-height: 100vh; }
        
        /* Sidebar Layout */
        .sidebar { width: 280px; background-color: #ffffff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; height: 100vh; position: sticky; top: 0; }
        .sidebar-brand { padding: 24px; border-bottom: 1px solid #e2e8f0; text-align: center; }
        .sidebar-brand img { max-width: 140px; }
        .sidebar-menu { padding: 20px 14px; flex: 1; overflow-y: auto; }
            /* Sidebar Layout */
            .sidebar { width: 280px; background-color: #ffffff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; height: 100vh; position: sticky; top: 0; }
            .sidebar-brand { padding: 24px; border-bottom: 1px solid #e2e8f0; text-align: center; }
            .sidebar-brand img { max-width: 140px; }
            .sidebar-menu { padding: 20px 14px; flex: 1; overflow-y: auto; }
        .menu-category { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; font-weight: 700; margin: 20px 0 8px 10px; }
        .menu-item { display: flex; align-items: center; padding: 10px 14px; color: #475569; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.95rem; margin-bottom: 4px; }
        .menu-item:hover { background-color: #f8fafc; color: #0284c7; }
        .menu-item.active { background-color: #e0f2fe; color: #0369a1; font-weight: 600; }
        
        /* Main Application Panel */
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .navbar { height: 70px; background-color: #ffffff; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; }
        .role-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; border: 1px solid #bbf7d0; background-color: #f0fdf4; color: #166534; }
        .role-badge.jms { background-color: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
        
        .content-body { padding: 40px; width: 100%; margin: 0 auto; }
        
        /* Spec Title Block */
        .header-title-block { margin-bottom: 25px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
        .header-title-block h1 { font-size: 1.6rem; color: #0f172a; font-weight: 700; }
        .header-title-block h2 { font-size: 1.3rem; color: #0284c7; font-weight: 700; margin-top: 4px; }

        .action-bar { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .btn-add { padding: 8px 16px; background-color: #0284c7; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; }
        .btn-add:hover { background-color: #0369a1; }
        
        /* Matrix Gantt Chart Table Styles */
        .table-container { background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow-x: auto; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        table { width: 100%; border-collapse: collapse; text-align: center; font-size: 0.85rem; min-width: 1000px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px; }
        th { background-color: #f8fafc; color: #475569; font-weight: 600; }
        .align-left { text-align: left; padding-left: 15px; font-weight: 500; }
        
        /* Highlighted Schedule Block */
        .filled-cell { background-color: #fae8ff; border: 1px solid #e9d5ff !important; }
        .btn-danger-sm { padding: 4px 8px; background: #ef4444; color: white; border: none; border-radius: 4px; font-size: 0.75rem; cursor: pointer; }
        .btn-danger-sm:hover { background: #b91c1c; }
        .btn-edit-sm { padding: 4px 8px; background: #0284c7; color: white; border: none; border-radius: 4px; font-size: 0.75rem; cursor: pointer; }
        .btn-edit-sm:hover { background: #0369a1; }

        /* Modal Overlay Configuration */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); display: flex; justify-content: center; align-items: center; z-index: 9999; opacity: 0; pointer-events: none; transition: all 0.3s ease; }
        .modal-overlay.show { opacity: 1; pointer-events: auto; }
        .modal-box { background: white; width: 100%; max-width: 550px; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;}
        .modal-title { font-size: 1.1rem; font-weight: 700; color: #0f172a; }
        .modal-body { padding: 24px; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 12px; background: #f8fafc; }
        
        /* Form Utilities */
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; }
        
        /* Interactive Week Grid Checker */
        .week-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-top: 5px; }
        .week-box { border: 1px solid #cbd5e1; padding: 10px; text-align: center; border-radius: 6px; cursor: pointer; background: #f8fafc; display: block; user-select: none; }
        .week-box input { display: none; }
        .week-box.selected { background: #e0f2fe; border-color: #0284c7; color: #0369a1; font-weight: bold; box-shadow: inset 0 0 0 1px #0284c7; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('images/PT JMS Kediri.png') }}" alt="Logo" onerror="this.onerror=null; this.src='https://placehold.co/120x45?text=PT+JMS';">
            </div>
            <div class="sidebar-menu">
                <a href="{{ route('dashboard') }}" class="menu-item">← Beranda Utama</a>
                <div class="menu-category">Operational</div>
                <a href="{{ route('kontrak.index') }}" class="menu-item {{ Request::is('menu/kendali-kontrak') ? 'active' : '' }}">Kendali Kontrak</a>
                <a href="{{ route('menu.view', 'rab-pra-operasional') }}" class="menu-item {{ Request::is('menu/rab-pra-operasional') ? 'active' : '' }}">RAB Pra Operasional</a>
                <a href="{{ route('timetable.index') }}" class="menu-item active">Time Table</a>
                <div class="menu-category">Payment</div>
                <a href="{{ route('menu.view', 'payment-schedule') }}" class="menu-item {{ Request::is('menu/payment-schedule') ? 'active' : '' }}">Payment Schedule</a>
                <a href="{{ route('outstanding-payment.index') }}" class="menu-item {{ Request::is('menu/outstanding-payment') ? 'active' : '' }}">Outstanding Payment</a>
                <a href="{{ route('payment-jurnal.index') }}" class="menu-item {{ Request::is('menu/payment-jurnal') ? 'active' : '' }}">Payment Jurnal</a>
            </div>
        </div>

    <!-- MAIN APP CANVAS -->
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
            <!-- HEADLINE SESUAI SPESIFIKASI USER -->
            <div class="header-title-block">
                <h1>Time Table Pekerjaan Pra Operasional Mesin Hemodialisis</h1>
                <h2>Rumah Sakit: {{ $current_rs }}</h2>
            </div>

            <div class="action-bar">
                @if(session('user_role') == 'JMS')
                    <button class="btn-add" onclick="toggleModal(true)">+ Atur Timeline Kerja</button>
                @endif
            </div>

            <!-- GANTT MATRIX GRAPHIC TABLE -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 50px;">NO</th>
                            <th rowspan="2" style="width: 350px;">JENIS PEKERJAAN</th>
                            @for($m = $min_month; $m <= $max_month; $m++)
                                <th colspan="4">{{ $list_bulan[$m] }} {{ $tahun_aktif }}</th>
                            @endfor
                            @if(session('user_role') == 'JMS')
                                <th rowspan="2" style="width: 80px;">AKSI</th>
                            @endif
                        </tr>
                        <tr>
                            @for($m = $min_month; $m <= $max_month; $m++)
                                <th>M1</th><th>M2</th><th>M3</th><th>M4</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($timeline as $pekerjaan => $bulan_data)
                            <tr data-schedule='@json($bulan_data)'>
                                <td>{{ $no++ }}</td>
                                <td class="align-left">{{ $pekerjaan }}</td>
                                
                                @for($m = $min_month; $m <= $max_month; $m++)
                                    @for($w = 1; $w <= 4; $w++)
                                        <td class="{{ isset($bulan_data[$m][$w]) ? 'filled-cell' : '' }}"></td>
                                    @endfor
                                @endfor

                                @if(session('user_role') == 'JMS')
                                    <td>
                                        <button type="button" class="btn-edit-sm" style="margin-bottom:8px; width:100%;" onclick='openEditModal(this.closest("tr"))'>Edit</button>
                                        <form action="{{ route('timetable.delete') }}" method="POST" onsubmit="return confirm('Hapus seluruh baris jadwal pekerjaan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="jenis_pekerjaan" value="{{ $pekerjaan }}">
                                            <button type="submit" class="btn-danger-sm">Hapus</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 2 + (($max_month - $min_month + 1) * 4) + (session('user_role') == 'JMS' ? 1 : 0) }}" style="padding: 40px; color: #94a3b8;">
                                    Belum ada data jadwal kegiatan. Klik tombol "Atur Timeline Kerja" di atas untuk mengisi data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL POPUP FORM TIMELINE INTERAKTIF -->
    <div id="timelineModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Input Target Waktu Kerja</div>
                <button type="button" style="background:none; border:none; font-size:1.5rem; cursor:pointer;" onclick="toggleModal(false)">&times;</button>
            </div>
            <form id="timelineForm" action="{{ route('timetable.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="timeline_method" value="POST">
                <input type="hidden" name="original_jenis_pekerjaan" id="original_jenis_pekerjaan" value="">
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto; padding-right: 10px;">
                    
                    <!-- Input Jenis Pekerjaan (Ketik Manual & Datalist) -->
                    <div class="form-group">
                        <label>Jenis Pekerjaan</label>
                        <input type="text" name="jenis_pekerjaan" placeholder="Ketik jenis pekerjaan di sini..." list="pekerjaan_list" required>
                        <datalist id="pekerjaan_list">
                            <option value="Renovasi Ruang HD (Hemodialisis)">
                            <option value="Pelatihan Perawat Dialisis">
                            <option value="Pelatihan Dokter Dialisis">
                            <option value="Pengiriman RO (Reverse Osmosis)">
                            <option value="Instalasi RO">
                            <option value="Pengiriman Mesin HD">
                            <option value="Instalasi & Uji Fungsi Mesin HD">
                            <option value="Uji Fungsi RO Dengan Mesin HD">
                            <option value="Training Perawat & Dokter Ttg Mesin HD">
                            <option value="Berita Acara Serah Terima Ruang HD">
                        </datalist>
                    </div>

                    <!-- Target Berapa Bulan -->
                    <div class="form-group">
                        <label>Target Berapa Bulan?</label>
                        <input type="number" id="jumlah_bulan" name="jumlah_bulan" min="1" max="12" placeholder="Contoh: 3" oninput="generateBulanFields()" required>
                    </div>

                    <!-- Wadah Output Generator Form Bulan Dinamis -->
                    <div id="dynamic_bulan_container"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" style="padding:10px 16px; background:white; border:1px solid #cbd5e1; border-radius:8px; cursor:pointer;" onclick="toggleModal(false)">Batal</button>
                    <button type="submit" id="timeline_submit" style="padding:10px 16px; background:#0284c7; color:white; border:none; border-radius:8px; cursor:pointer;">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MAIN MATRIX APP SCRIPT CONTROLLER -->
    <script>
        const activeYear = {{ $tahun_aktif }};

        function toggleModal(show) {
            const modal = document.getElementById('timelineModal');
            if(show) {
                modal.classList.add('show');
            } else {
                modal.classList.remove('show');
                resetModal();
            }
        }

        function resetModal() {
            document.getElementById('dynamic_bulan_container').innerHTML = '';
            document.getElementById('jumlah_bulan').value = '';
            document.getElementById('original_jenis_pekerjaan').value = '';
            document.getElementById('timeline_method').value = 'POST';
            document.getElementById('timelineForm').action = '{{ route('timetable.store') }}';
            document.getElementById('timeline_submit').textContent = 'Simpan Jadwal';
            document.querySelector('.modal-title').textContent = 'Input Target Waktu Kerja';
        }

        function openEditModal(row) {
            const pekerjaan = row.querySelector('td.align-left').textContent.trim();
            const scheduleJson = row.dataset.schedule || '{}';
            const schedule = JSON.parse(scheduleJson);
            const prefill = Object.keys(schedule).sort((a, b) => a - b).map(month => ({
                id: month,
                tahun: activeYear,
                minggu: Object.keys(schedule[month]).map(Number)
            }));

            document.getElementById('jumlah_bulan').value = prefill.length;
            document.getElementById('original_jenis_pekerjaan').value = pekerjaan;
            document.querySelector('input[name="jenis_pekerjaan"]').value = pekerjaan;
            document.getElementById('timeline_method').value = 'PUT';
            document.getElementById('timelineForm').action = '{{ route('timetable.update') }}';
            document.getElementById('timeline_submit').textContent = 'Perbarui Jadwal';
            document.querySelector('.modal-title').textContent = 'Edit Target Waktu Kerja';
            generateBulanFields(prefill);
            toggleModal(true);
        }

        // Generator Input Dinamis Sesuai Target Jumlah Bulan Berulang
        function generateBulanFields(prefill = null) {
            const container = document.getElementById('dynamic_bulan_container');
            container.innerHTML = ''; // Reset penampung lama

            const namaBulan = {
                1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April', 5: 'Mei', 6: 'Juni',
                7: 'Juli', 8: 'Agustus', 9: 'September', 10: 'Oktober', 11: 'November', 12: 'Desember'
            };

            const jumlahBulan = prefill ? prefill.length : parseInt(document.getElementById('jumlah_bulan').value, 10);
            if (!jumlahBulan || jumlahBulan < 1) return;

            for (let i = 1; i <= jumlahBulan; i++) {
                const monthData = prefill ? prefill[i - 1] : {id: 5, tahun: activeYear, minggu: []};
                const selectedWeeks = new Set((monthData.minggu || []).map(String));

                let section = document.createElement('div');
                section.style.borderTop = "2px dashed #e2e8f0";
                section.style.marginTop = "20px";
                section.style.paddingTop = "15px";

                let htmlContent = `
                    <h4 style="font-size: 0.95rem; color: #0284c7; margin-bottom: 12px; font-weight: 700;">Target Bulan ${i}</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 12px;">
                        <div class="form-group">
                            <label>Bulan</label>
                            <select name="bulan[${i}][id]" required>
                                ${Object.keys(namaBulan).map(num => `<option value="${num}" ${num == monthData.id ? 'selected' : ''}>${namaBulan[num]}</option>`).join('')}
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tahun</label>
                            <input type="number" name="bulan[${i}][tahun]" value="${monthData.tahun}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Pilih Target Minggu Aktif (Bisa pilih lebih dari 1)</label>
                        <div class="week-grid">
                            <label class="week-box" id="lbl_${i}_m1">M1 <input type="checkbox" name="bulan[${i}][minggu][]" value="1" onchange="checkWeekInteractive('${i}_m1')" ${selectedWeeks.has('1') ? 'checked' : ''}></label>
                            <label class="week-box" id="lbl_${i}_m2">M2 <input type="checkbox" name="bulan[${i}][minggu][]" value="2" onchange="checkWeekInteractive('${i}_m2')" ${selectedWeeks.has('2') ? 'checked' : ''}></label>
                            <label class="week-box" id="lbl_${i}_m3">M3 <input type="checkbox" name="bulan[${i}][minggu][]" value="3" onchange="checkWeekInteractive('${i}_m3')" ${selectedWeeks.has('3') ? 'checked' : ''}></label>
                            <label class="week-box" id="lbl_${i}_m4">M4 <input type="checkbox" name="bulan[${i}][minggu][]" value="4" onchange="checkWeekInteractive('${i}_m4')" ${selectedWeeks.has('4') ? 'checked' : ''}></label>
                        </div>
                    </div>
                `;
                section.innerHTML = htmlContent;
                container.appendChild(section);

                ['1','2','3','4'].forEach(week => {
                    if (selectedWeeks.has(week)) {
                        const box = document.getElementById(`lbl_${i}_m${week}`);
                        if (box) box.classList.add('selected');
                    }
                });
            }
        }

        // Handler Efek Warna Tombol Minggu saat Ditekan/Dicentang
        function checkWeekInteractive(id) {
            const box = document.getElementById('lbl_' + id);
            const checkbox = box.querySelector('input');
            if(checkbox.checked) {
                box.classList.add('selected');
            } else {
                box.classList.remove('selected');
            }
        }
    </script>
</body>
</html>