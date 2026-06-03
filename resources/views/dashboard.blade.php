<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Alur Pembayaran JMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sky-50:  #f0f9ff;
            --sky-100: #e0f2fe;
            --sky-200: #bae6fd;
            --sky-300: #7dd3fc;
            --sky-500: #0ea5e9;
            --sky-600: #0284c7;
            --sky-700: #0369a1;
            --sky-800: #075985;
            --sky-900: #0c4a6e;
            --slate-50:  #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --emerald-50:  #ecfdf5;
            --emerald-100: #d1fae5;
            --emerald-500: #10b981;
            --emerald-600: #059669;
            --emerald-700: #047857;
            --amber-50: #fffbeb;
            --amber-100: #fef3c7;
            --amber-500: #f59e0b;
            --amber-600: #d97706;
            --red-50: #fef2f2;
            --red-500: #ef4444;
            --red-600: #dc2626;
            --sidebar-w: 260px;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--slate-100);
            color: var(--slate-800);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar { width: 280px; background-color: #ffffff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; height: 100vh; position: sticky; top: 0; }
        .sidebar-brand { padding: 24px; border-bottom: 1px solid #e2e8f0; text-align: center; }
        .sidebar-brand img { max-width: 140px; }
        .sidebar-menu { padding: 20px 14px; flex: 1; overflow-y: auto; }
        .menu-category { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; font-weight: 700; margin: 20px 0 8px 10px; }
        .menu-item { display: flex; align-items: center; padding: 10px 14px; color: #475569; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.95rem; margin-bottom: 4px; transition: all 0.2s; }
        .menu-item:hover { background-color: #f8fafc; color: #0284c7; }
        .menu-item.active { background-color: #e0f2fe; color: #0369a1; font-weight: 600; }

        /* ════════════════════════════════════════
           MAIN AREA
        ════════════════════════════════════════ */
        .main-area { flex: 1; display: flex; flex-direction: column; min-width: 0; }

        /* ── TOPBAR ───────────────────────── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--slate-200);
            height: 62px;
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky; top: 0; z-index: 10;
        }

        .topbar-left {
            display: flex; align-items: center; gap: 8px;
            font-size: 14px; color: var(--slate-500);
        }
        .topbar-left svg { width: 16px; height: 16px; stroke: var(--sky-500); fill: none; stroke-width: 2; }
        .topbar-active { color: var(--sky-700); font-weight: 600; }
        .topbar-sep { color: var(--slate-300); }

        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .btn-logout {
            display: flex; align-items: center; gap: 6px;
            padding: 7px 14px;
            background: none;
            border: 1px solid var(--slate-200);
            border-radius: 8px;
            font-size: 13px; font-weight: 500;
            color: var(--slate-600);
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.15s;
        }
        .btn-logout svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2; }
        .btn-logout:hover { background: var(--red-50); color: var(--red-600); border-color: #fca5a5; }

        /* ── CONTENT ──────────────────────── */
        .page-content {
            padding: 28px 32px;
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* ── WELCOME ──────────────────────── */
        .welcome-card {
            background: #fff;
            border: 1px solid var(--slate-200);
            border-radius: 16px;
            padding: 26px 28px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 18px;
        }
        .wc-icon-wrap {
            width: 48px; height: 48px; border-radius: 12px;
            background: var(--sky-50); border: 1px solid var(--sky-100);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .wc-icon-wrap svg { width: 24px; height: 24px; stroke: var(--sky-600); fill: none; stroke-width: 1.8; }
        .wc-title { font-size: 18px; font-weight: 700; color: var(--slate-900); margin-bottom: 6px; }
        .wc-desc { font-size: 14px; color: var(--slate-500); line-height: 1.7; max-width: 680px; }

        /* ── STATUS BANNER ────────────────── */
        .status-banner {
            background: var(--sky-50);
            border: 1px solid var(--sky-200);
            border-radius: 12px;
            padding: 13px 18px;
            margin-bottom: 20px;
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px;
        }
        .sb-text {
            display: flex; align-items: center; gap: 8px;
            font-size: 14px; color: var(--sky-800);
        }
        .sb-text svg { width: 17px; height: 17px; stroke: var(--sky-600); fill: none; stroke-width: 2; flex-shrink: 0; }
        .sb-text strong { font-weight: 600; }
        .btn-change-rs {
            font-size: 12px; font-weight: 600; color: var(--sky-700);
            background: #fff; border: 1px solid var(--sky-200);
            padding: 6px 14px; border-radius: 8px;
            cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
            white-space: nowrap; transition: background 0.15s;
        }
        .btn-change-rs:hover { background: var(--sky-50); }

        /* ── HOSPITAL BOX ─────────────────── */
        .hospital-card {
            background: #fff;
            border: 1px solid var(--slate-200);
            border-radius: 16px;
            padding: 24px 28px;
        }
        .hc-head {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 18px;
        }
        .hc-head-icon {
            width: 36px; height: 36px; border-radius: 9px;
            background: var(--emerald-50); border: 1px solid var(--emerald-100);
            display: flex; align-items: center; justify-content: center;
        }
        .hc-head-icon svg { width: 18px; height: 18px; stroke: var(--emerald-600); fill: none; stroke-width: 2; }
        .hc-title { font-size: 15px; font-weight: 700; color: var(--slate-900); }
        .hc-sub   { font-size: 13px; color: var(--slate-500); }
        .nms-note {
            background: var(--amber-50); border: 1px solid var(--amber-100);
            color: #92400e;
            padding: 10px 14px; border-radius: 8px;
            font-size: 13px; display: flex; align-items: center; gap: 8px;
            margin-bottom: 18px;
        }
        .nms-note svg { width: 16px; height: 16px; stroke: var(--amber-600); fill: none; stroke-width: 2; flex-shrink: 0; }

        .hc-btn-row { display: flex; gap: 12px; flex-wrap: wrap; }
        .hc-btn {
            flex: 1; min-width: 180px;
            display: flex; align-items: center; justify-content: center; gap: 9px;
            padding: 12px 20px;
            border: none; border-radius: 10px;
            font-size: 14px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: background 0.15s, transform 0.15s;
        }
        .hc-btn:active { transform: scale(0.98); }
        .hc-btn svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; }
        .hc-btn.primary { background: var(--sky-600); color: #fff; }
        .hc-btn.primary:hover { background: var(--sky-700); }
        .hc-btn.success { background: var(--emerald-600); color: #fff; }
        .hc-btn.success:hover { background: var(--emerald-700); }
        .hc-btn.success:disabled,
        .hc-btn.success[disabled] {
            background: var(--slate-200); color: var(--slate-400);
            cursor: not-allowed;
        }
        .hc-btn.success:disabled:hover,
        .hc-btn.success[disabled]:hover { background: var(--slate-200); }

        /* ════════════════════════════════════════
           MODALS
        ════════════════════════════════════════ */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(3px);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
            opacity: 0; pointer-events: none;
            transition: opacity 0.25s ease;
        }
        .modal-overlay.show { opacity: 1; pointer-events: auto; }

        .modal-box {
            background: #fff;
            width: 100%; max-width: 480px;
            border-radius: 16px;
            border: 1px solid var(--slate-200);
            overflow: hidden;
            transform: translateY(-16px) scale(0.98);
            transition: transform 0.25s ease;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        .modal-overlay.show .modal-box { transform: translateY(0) scale(1); }

        .modal-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--slate-100);
            display: flex; align-items: center; justify-content: space-between;
            background: var(--slate-50);
        }
        .modal-title-row { display: flex; align-items: center; gap: 10px; }
        .modal-icon {
            width: 32px; height: 32px; border-radius: 8px;
            background: var(--sky-50);
            display: flex; align-items: center; justify-content: center;
        }
        .modal-icon svg { width: 16px; height: 16px; stroke: var(--sky-600); fill: none; stroke-width: 2; }
        .modal-title { font-size: 15px; font-weight: 700; color: var(--slate-900); }
        .modal-close {
            width: 30px; height: 30px; border-radius: 7px;
            background: none; border: 1px solid var(--slate-200);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            color: var(--slate-500); transition: all 0.15s;
        }
        .modal-close:hover { background: var(--slate-100); color: var(--slate-800); }
        .modal-close svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2; }

        .modal-body { padding: 20px 22px; }

        /* Search input in modal */
        .search-input-wrapper {
            position: relative; margin-bottom: 15px;
        }
        .search-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            width: 16px; height: 16px; color: var(--slate-400); pointer-events: none; stroke-width: 2.2;
        }
        .search-rs-input {
            width: 100%; padding: 10px 12px 10px 36px;
            border: 1.5px solid var(--slate-200); border-radius: 9px;
            font-size: 14px; font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--slate-800); background: var(--slate-50);
            outline: none; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-rs-input:focus {
            border-color: var(--sky-500);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(14,165,233,0.1);
        }
        .search-rs-input::placeholder { color: var(--slate-400); }

        .modal-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--slate-100);
            display: flex; justify-content: flex-end; gap: 10px;
            background: var(--slate-50);
        }

        /* RS List inside modal */
        .rs-list { display: flex; flex-direction: column; gap: 8px; max-height: 380px; overflow-y: auto; }
        .rs-list::-webkit-scrollbar { width: 4px; }
        .rs-list::-webkit-scrollbar-thumb { background: var(--slate-200); border-radius: 4px; }

        .rs-item {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            padding: 12px 14px;
            border: 1px solid var(--slate-200);
            border-radius: 10px;
            background: var(--slate-50);
            transition: border-color 0.15s;
        }
        .rs-item:hover { border-color: var(--sky-200); }
        .rs-info { flex: 1; cursor: pointer; min-width: 0; }
        .rs-name { font-size: 14px; font-weight: 600; color: var(--slate-800); }
        .rs-city { font-size: 12px; color: var(--slate-500); margin-top: 2px; }
        .rs-actions { display: flex; gap: 6px; flex-shrink: 0; }

        .btn-rs {
            display: flex; align-items: center; gap: 5px;
            padding: 6px 12px; border-radius: 7px;
            font-size: 12px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer; border: none; transition: background 0.15s, transform 0.1s;
        }
        .btn-rs:active { transform: scale(0.96); }
        .btn-rs svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2.2; }
        .btn-rs.pick   { background: var(--sky-600); color: #fff; }
        .btn-rs.pick:hover   { background: var(--sky-700); }
        .btn-rs.edit   { background: var(--amber-100); color: #92400e; }
        .btn-rs.edit:hover   { background: #fde68a; }
        .btn-rs.hapus  { background: #fee2e2; color: var(--red-600); }
        .btn-rs.hapus:hover  { background: #fecaca; }

        .empty-state {
            text-align: center; padding: 32px 20px;
            color: var(--slate-400); font-size: 14px;
        }
        .empty-state svg { width: 40px; height: 40px; stroke: var(--slate-300); fill: none; stroke-width: 1.5; margin: 0 auto 12px; display: block; }

        /* Modal Form Fields */
        .mf-group { margin-bottom: 15px; }
        .mf-label {
            display: block; font-size: 13px; font-weight: 600;
            color: var(--slate-700); margin-bottom: 6px;
        }
        .mf-input, .mf-textarea {
            width: 100%; padding: 10px 13px;
            border: 1.5px solid var(--slate-200); border-radius: 9px;
            font-size: 14px; font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--slate-800); background: var(--slate-50);
            outline: none; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .mf-input:focus, .mf-textarea:focus {
            border-color: var(--sky-500);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(14,165,233,0.1);
        }
        .mf-textarea { resize: vertical; min-height: 80px; }

        /* Buttons in modal footer */
        .btn-secondary {
            padding: 9px 18px;
            background: #fff; border: 1px solid var(--slate-200);
            border-radius: 8px; font-size: 14px; font-weight: 600;
            color: var(--slate-600); cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background 0.15s;
        }
        .btn-secondary:hover { background: var(--slate-50); }
        .btn-primary-modal {
            padding: 9px 20px;
            background: var(--sky-600); border: none;
            border-radius: 8px; font-size: 14px; font-weight: 600;
            color: #fff; cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background 0.15s;
        }
        .btn-primary-modal:hover { background: var(--sky-700); }
    </style>
</head>
<body>

<!-- ══════════════ SIDEBAR ══════════════ -->
<div class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('images/PT JMS Kediri.png') }}" alt="Logo" onerror="this.onerror=null; this.src='https://placehold.co/120x45?text=PT+JMS';">
    </div>

    <div class="sidebar-menu">
        <a href="{{ route('dashboard') }}" class="menu-item">← Beranda Utama</a>

        @if(session('selected_hospital'))
            <div class="menu-category">Operational</div>
            <a href="{{ route('kontrak.index') }}" class="menu-item {{ Request::is('menu/kendali-kontrak') ? 'active' : '' }}">Kendali Kontrak</a>
            <a href="{{ route('menu.view', 'rab-pra-operasional') }}" class="menu-item {{ Request::is('menu/rab-pra-operasional') ? 'active' : '' }}">RAB Pra Operasional</a>
            <a href="{{ route('menu.view', 'time-table') }}" class="menu-item {{ Request::is('menu/time-table') ? 'active' : '' }}">Time Table</a>

            <div class="menu-category">Payment</div>
            <a href="{{ route('menu.view', 'payment-schedule') }}" class="menu-item {{ Request::is('menu/payment-schedule') ? 'active' : '' }}">Payment Schedule</a>
            <a href="{{ route('menu.view', 'outstanding-payment') }}" class="menu-item {{ Request::is('menu/outstanding-payment') ? 'active' : '' }}">Outstanding Payment</a>
            <a href="{{ route('menu.view', 'payment-jurnal') }}" class="menu-item {{ Request::is('menu/payment-jurnal') ? 'active' : '' }}">Payment Jurnal</a>
        @else
            <div class="menu-category">Operational</div>
            <span class="menu-item" style="color:#94a3b8; pointer-events:none;">Pilih RS dahulu</span>
            <div class="menu-category">Payment</div>
            <span class="menu-item" style="color:#94a3b8; pointer-events:none;">Pilih RS dahulu</span>
        @endif
    </div>

    <div style="padding:16px 14px; border-top:1px solid #e2e8f0; display:flex; gap:10px; align-items:center;">
        <div style="width:34px;height:34px;border-radius:50%;background:#e0f2fe;display:flex;align-items:center;justify-content:center;font-weight:700;color:#0369a1;">
            {{ strtoupper(substr(session('user_name', 'G'), 0, 1)) }}
        </div>
        <div style="min-width:0;">
            <div style="font-weight:600; font-size:13px; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ session('user_name', 'Guest') }}</div>
            <div style="font-size:11px; margin-top:4px;">
                <span style="padding:2px 8px;border-radius:10px;display:inline-block;background:#eff6ff;color:#1e40af;font-weight:600;">{{ session('user_role', 'JMS') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════ MAIN AREA ══════════════ -->
<div class="main-area">

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-left">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            @if(session('selected_hospital'))
                <span>Aktif:</span>
                <span class="topbar-active">{{ session('selected_hospital') }}</span>
            @else
                <span>Sistem Pemantauan Distribusi Alur Dana Alat HD</span>
            @endif
        </div>
        <div class="topbar-right">
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="page-content">

        <!-- WELCOME -->
        <div class="welcome-card">
            <div class="wc-icon-wrap">
                <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            </div>
            <div>
                <div class="wc-title">Halo, Semangat Untuk Hari Ini 👋</div>
                <p class="wc-desc">
                    Pilih rumah sakit kerja sama untuk melanjutkan.
                </p>
            </div>
        </div>

        <!-- STATUS BANNER (jika sudah pilih RS) -->
        @if(session('selected_hospital'))
            <div class="status-banner">
                <div class="sb-text">
                    <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.77 1.17 2 2 0 012.77 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L7.91 7.91a16 16 0 006.17 6.17l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                    Rumah Sakit Terpilih: <strong>{{ session('selected_hospital') }}</strong>. Anda dapat melanjutkan ke menu berikutnya.
                </div>
                <form action="{{ route('set.hospital') }}" method="POST">
                    @csrf
                    <input type="hidden" name="hospital_select" value="">
                    <button type="submit" class="btn-change-rs">Ganti Rumah Sakit</button>
                </form>
            </div>
        @endif

        <!-- HOSPITAL BOX -->
        <div class="hospital-card">
            <div class="hc-head">
                <div class="hc-head-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div>
                    <div class="hc-title">Kelola Rumah Sakit Kerja Sama</div>
                    <div class="hc-sub">Pilih, tambah, atau ubah data rumah sakit mitra</div>
                </div>
            </div>

            <div class="hc-btn-row">
                <button type="button" id="btnPilihRS" class="hc-btn primary">
                    <svg viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                    Pilih Rumah Sakit
                </button>
                @if(session('user_role') != 'NMS')
                    <button type="button" id="btnTambahRS" class="hc-btn success">
                        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah RS Baru
                    </button>
                @endif
            </div>
        </div>

    </main>
</div>


<!-- ══════════════ MODAL 1: PILIH RS ══════════════ -->
<div id="modalPilihRS" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title-row">
                <div class="modal-icon">
                    <svg viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                </div>
                <span class="modal-title">Pilih Rumah Sakit</span>
            </div>
            <button class="modal-close" onclick="closePilihRSModal()">
                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="search-input-wrapper">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" id="searchRSInput" class="search-rs-input" placeholder="Cari rumah sakit...">
            </div>
            <div id="daftarRSContainer" class="rs-list"></div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closePilihRSModal()">Tutup</button>
        </div>
    </div>
</div>

<!-- ══════════════ MODAL 2: TAMBAH RS ══════════════ -->
<div id="modalTambahRS" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title-row">
                <div class="modal-icon">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <span class="modal-title">Tambah Rumah Sakit Baru</span>
            </div>
            <button class="modal-close" onclick="closeTambahRSModal()">
                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="mf-group">
                <label class="mf-label" for="modal_nama_rs">Nama Rumah Sakit</label>
                <input type="text" id="modal_nama_rs" class="mf-input" placeholder="Contoh: RS Umum Kediri">
            </div>
            <div class="mf-group">
                <label class="mf-label" for="modal_kota">Kota / Kabupaten</label>
                <input type="text" id="modal_kota" class="mf-input" placeholder="Contoh: Kediri">
            </div>
            <div class="mf-group">
                <label class="mf-label" for="modal_alamat">Alamat Lengkap</label>
                <textarea id="modal_alamat" class="mf-textarea" placeholder="Contoh: Jl. Jaksa Agung Suprapto No. 2"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeTambahRSModal()">Batal</button>
            <button class="btn-primary-modal" onclick="submitTambahRS()">Simpan Rumah Sakit</button>
        </div>
    </div>
</div>

<!-- ══════════════ MODAL 3: EDIT RS ══════════════ -->
<div id="modalEditRS" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title-row">
                <div class="modal-icon">
                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <span class="modal-title">Edit Rumah Sakit</span>
            </div>
            <button class="modal-close" onclick="closeEditRSModal()">
                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="edit_rs_id">
            <div class="mf-group">
                <label class="mf-label" for="edit_nama_rs">Nama Rumah Sakit</label>
                <input type="text" id="edit_nama_rs" class="mf-input" placeholder="Nama RS">
            </div>
            <div class="mf-group">
                <label class="mf-label" for="edit_kota">Kota / Kabupaten</label>
                <input type="text" id="edit_kota" class="mf-input" placeholder="Kota">
            </div>
            <div class="mf-group">
                <label class="mf-label" for="edit_alamat">Alamat Lengkap</label>
                <textarea id="edit_alamat" class="mf-textarea" placeholder="Alamat lengkap"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeEditRSModal()">Batal</button>
            <button class="btn-primary-modal" onclick="submitEditRS()">Simpan Perubahan</button>
        </div>
    </div>
</div>


<script>
    const daftarRumahSakit = @json($daftarRumahSakit);
    const userRole         = @json(session('user_role'));

    const modalPilihRS  = document.getElementById('modalPilihRS');
    const modalTambahRS = document.getElementById('modalTambahRS');
    const modalEditRS   = document.getElementById('modalEditRS');

    // Close on overlay click
    [modalPilihRS, modalTambahRS, modalEditRS].forEach(m => {
        m.addEventListener('click', function(e) {
            if (e.target === m) m.classList.remove('show');
        });
    });

    document.getElementById('btnPilihRS').addEventListener('click', openPilihRSModal);
    document.getElementById('btnTambahRS').addEventListener('click', openTambahRSModal);

    // Event listener untuk search input
    document.getElementById('searchRSInput').addEventListener('input', function(e) {
        filterRumahSakit(e.target.value);
    });

    /* ── PILIH RS ─── */
    function openPilihRSModal() {
        renderDaftarRS();
        modalPilihRS.classList.add('show');
        // Focus search input untuk UX yang lebih baik
        setTimeout(() => document.getElementById('searchRSInput')?.focus(), 100);
    }
    function closePilihRSModal() { modalPilihRS.classList.remove('show'); }

    // Fungsi untuk filter rumah sakit berdasarkan pencarian
    function filterRumahSakit(searchQuery) {
        if (!searchQuery.trim()) {
            renderDaftarRS([]);
            return;
        }
        const filtered = daftarRumahSakit.filter(rs => 
            rs.nama_rs.toLowerCase().includes(searchQuery.toLowerCase()) || 
            (rs.kota || '').toLowerCase().includes(searchQuery.toLowerCase())
        );
        renderDaftarRS(filtered, searchQuery);
    }

    function renderDaftarRS(filteredList = null, searchQuery = '') {
        const container = document.getElementById('daftarRSContainer');
        container.innerHTML = '';

        // Jika ada search query, gunakan filtered list, otherwise gunakan semua
        const toDisplay = searchQuery ? (filteredList || []) : daftarRumahSakit;

        if (!toDisplay || toDisplay.length === 0) {
            let emptyMsg = 'Belum ada rumah sakit terdaftar';
            if (searchQuery) {
                emptyMsg = `Tidak ada rumah sakit yang cocok dengan "${searchQuery}"`;
            }
            container.innerHTML = `
                <div class="empty-state">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    ${emptyMsg}
                </div>`;
            return;
        }

        toDisplay.forEach(rs => {
            const item = document.createElement('div');
            item.className = 'rs-item';

            const info = document.createElement('div');
            info.className = 'rs-info';
            info.innerHTML = `<div class="rs-name">${rs.nama_rs}</div><div class="rs-city">${rs.kota || '—'}</div>`;
            info.addEventListener('click', () => selectHospital(rs.nama_rs));

            const actions = document.createElement('div');
            actions.className = 'rs-actions';

            const btnPilih = document.createElement('button');
            btnPilih.className = 'btn-rs pick';
            btnPilih.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="20 6 9 17 4 12"/></svg> Pilih`;
            btnPilih.addEventListener('click', () => selectHospital(rs.nama_rs));
            actions.appendChild(btnPilih);

            if (userRole === 'JMS') {
                const btnEdit = document.createElement('button');
                btnEdit.className = 'btn-rs edit';
                btnEdit.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit`;
                btnEdit.addEventListener('click', () => openEditRSModal(rs));
                actions.appendChild(btnEdit);

                const btnHapus = document.createElement('button');
                btnHapus.className = 'btn-rs hapus';
                btnHapus.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg> Hapus`;
                btnHapus.addEventListener('click', () => deleteHospital(rs.id, rs.nama_rs));
                actions.appendChild(btnHapus);
            }

            item.appendChild(info);
            item.appendChild(actions);
            container.appendChild(item);
        });
    }

    function selectHospital(namaRS) {
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = '{{ route('set.hospital') }}';
        f.innerHTML = `@csrf<input type="hidden" name="hospital_select" value="${namaRS}">`;
        document.body.appendChild(f);
        f.submit();
    }

    /* ── TAMBAH RS ─── */
    function openTambahRSModal() {
        ['modal_nama_rs','modal_kota','modal_alamat'].forEach(id => document.getElementById(id).value = '');
        modalTambahRS.classList.add('show');
    }
    function closeTambahRSModal() { modalTambahRS.classList.remove('show'); }

    function submitTambahRS() {
        const namaRS = document.getElementById('modal_nama_rs').value.trim();
        const kota   = document.getElementById('modal_kota').value.trim();
        const alamat = document.getElementById('modal_alamat').value.trim();
        if (!namaRS || !kota || !alamat) { alert('Mohon lengkapi semua kolom (Nama RS, Kota, dan Alamat)!'); return; }
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = '{{ route('set.hospital') }}';
        f.innerHTML = `@csrf<input type="hidden" name="hospital_input" value="${namaRS}"><input type="hidden" name="kota" value="${kota}"><input type="hidden" name="alamat" value="${alamat}">`;
        document.body.appendChild(f);
        f.submit();
    }

    /* ── EDIT RS ─── */
    function openEditRSModal(rs) {
        document.getElementById('edit_rs_id').value   = rs.id;
        document.getElementById('edit_nama_rs').value = rs.nama_rs;
        document.getElementById('edit_kota').value    = rs.kota   || '';
        document.getElementById('edit_alamat').value  = rs.alamat || '';
        modalEditRS.classList.add('show');
    }
    function closeEditRSModal() { modalEditRS.classList.remove('show'); }

    function submitEditRS() {
        const id     = document.getElementById('edit_rs_id').value;
        const namaRS = document.getElementById('edit_nama_rs').value.trim();
        const kota   = document.getElementById('edit_kota').value.trim();
        const alamat = document.getElementById('edit_alamat').value.trim();
        if (!namaRS || !kota || !alamat) { alert('Mohon lengkapi semua kolom!'); return; }

        const f = document.createElement('form');
        f.method = 'POST';
        f.action = '{{ route('hospital.update') }}';

        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = '{{ csrf_token() }}';
        f.appendChild(token);

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PUT';
        f.appendChild(method);

        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id';
        inputId.value = id;
        f.appendChild(inputId);

        const inputNama = document.createElement('input');
        inputNama.type = 'hidden';
        inputNama.name = 'nama_rs';
        inputNama.value = namaRS;
        f.appendChild(inputNama);

        const inputKota = document.createElement('input');
        inputKota.type = 'hidden';
        inputKota.name = 'kota';
        inputKota.value = kota;
        f.appendChild(inputKota);

        const inputAlamat = document.createElement('input');
        inputAlamat.type = 'hidden';
        inputAlamat.name = 'alamat';
        inputAlamat.value = alamat;
        f.appendChild(inputAlamat);

        document.body.appendChild(f);
        f.submit();
    }

    /* ── HAPUS RS ─── */
    function deleteHospital(id, namaRS) {
        if (!confirm(`Yakin ingin menghapus "${namaRS}"?\nData ini tidak dapat dikembalikan.`)) return;

        const f = document.createElement('form');
        f.method = 'POST';
        f.action = '{{ route('hospital.delete') }}';

        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = '{{ csrf_token() }}';
        f.appendChild(token);

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        f.appendChild(method);

        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id';
        inputId.value = id;
        f.appendChild(inputId);

        document.body.appendChild(f);
        f.submit();
    }
</script>

</body>
</html>