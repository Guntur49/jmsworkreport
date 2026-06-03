<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menuName }} - Portal Kerja Sama</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { display: flex; background-color: #f1f5f9; color: #1e293b; min-height: 100vh; }
        
        /* Sidebar Styles */
        .sidebar { width: 280px; background-color: #ffffff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; height: 100vh; position: sticky; top: 0; }
        .sidebar-brand { padding: 24px; border-bottom: 1px solid #e2e8f0; text-align: center; }
        .sidebar-brand img { max-width: 140px; }
        .sidebar-menu { padding: 20px 14px; flex: 1; overflow-y: auto; }
        .menu-category { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; font-weight: 700; margin: 20px 0 8px 10px; }
        .menu-item { display: flex; align-items: center; padding: 10px 14px; color: #475569; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.95rem; margin-bottom: 4px; transition: all 0.2s; }
        .menu-item:hover { background-color: #f8fafc; color: #0284c7; }
        .menu-item.active { background-color: #e0f2fe; color: #0369a1; font-weight: 600; }
        
        /* Main Section Layout */
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .navbar { height: 70px; background-color: #ffffff; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; }
        .user-profile { display: flex; align-items: center; gap: 12px; }
        .role-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; border: 1px solid #bbf7d0; background-color: #f0fdf4; color: #166534; }
        .role-badge.jms { background-color: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
        
        .content-body { padding: 40px; max-width: 1200px; width: 100%; margin: 0 auto; }
        
        /* Breadcrumb & Header Title */
        .breadcrumb { font-size: 0.85rem; color: #64748b; margin-bottom: 8px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
        
        /* Empty State Canvas Box */
        .empty-placeholder { background: white; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 60px; text-align: center; color: #64748b; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .icon-box { font-size: 3.5rem; margin-bottom: 15px; color: #94a3b8; }
        
        /* Action Button Simulator */
        .action-container { margin-top: 20px; }
        .btn-action { padding: 10px 20px; background-color: #0284c7; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-action:hover { background-color: #0369a1; }
        
        .badge-info-role { display: inline-block; padding: 6px 14px; border-radius: 6px; font-size: 0.9rem; margin-top: 15px; font-weight: 500; }
        .badge-jms-info { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .badge-nms-info { background-color: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
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
            <a href="{{ route('menu.view', 'rab-pra-operasional') }}" class="menu-item {{ Request::is('menu/rab-pra-operasional') ? 'active' : '' }}">RAB Pra Operasional</a>
            <a href="{{ route('menu.view', 'time-table') }}" class="menu-item {{ Request::is('menu/time-table') ? 'active' : '' }}">Time Table</a>
            
            <div class="menu-category">Payment</div>
            <a href="{{ route('menu.view', 'payment-schedule') }}" class="menu-item {{ Request::is('menu/payment-schedule') ? 'active' : '' }}">Payment Schedule</a>
            <a href="{{ route('menu.view', 'outstanding-payment') }}" class="menu-item {{ Request::is('menu/outstanding-payment') ? 'active' : '' }}">Outstanding Payment</a>
            <a href="{{ route('menu.view', 'payment-jurnal') }}" class="menu-item {{ Request::is('menu/payment-jurnal') ? 'active' : '' }}">Payment Jurnal</a>
        </div>
    </div>

    <div class="main-content">
        <div class="navbar">
            <div style="font-weight: 600; color: #1e293b;">
                Rumah Sakit Aktif: <span style="color: #0284c7;">{{ session('selected_hospital') }}</span>
            </div>
            <div class="user-profile">
                <span class="role-badge {{ session('user_role') == 'JMS' ? 'jms' : '' }}">
                    {{ session('user_name') }}
                </span>
            </div>
        </div>

        <div class="content-body">
            <div class="breadcrumb">Sub Menu / {{ session('selected_hospital') }} / {{ $menuName }}</div>
            
            <div class="page-header">
                <h1>{{ $menuName }}</h1>
            </div>

            <div class="empty-placeholder">
                <div class="icon-box">📊</div>
                <h2>Halaman {{ $menuName }} (Kosongan)</h2>
                <p style="margin-top: 8px; max-width: 600px; margin-left: auto; margin-right: auto;">
                    Halaman ini dikonfigurasi untuk menampilkan data dari <strong>{{ session('selected_hospital') }}</strong>. Saat ini komponen form dan tabel inputan masih dikosongkan.
                </p>

                @if(session('user_role') == 'JMS')
                    <div class="badge-info-role badge-jms-info">
                        Akses Terbuka: Anda login sebagai <strong>PT JMS</strong>. Anda memiliki izin penuh untuk menambah, mengedit, dan menghapus data pada halaman ini nanti.
                    </div>
                    <div class="action-container">
                        <button class="btn-action" onclick="alert('Simulasi: Komponen form baru untuk {{ $menuName }} akan muncul di sini.')">+ Tambah Data Baru (Simulasi)</button>
                    </div>
                @else
                    <div class="badge-info-role badge-nms-info">
                        Mode Lihat Saja: Anda login sebagai <strong>PT NMS</strong>. Hak akses Anda adalah <strong>Read-Only</strong>. Anda tidak dapat mengubah data pada sub-menu ini.
                    </div>
                @endif
            </div>

        </div>
    </div>

</body>
</html>