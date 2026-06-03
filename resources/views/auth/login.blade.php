<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Vendor Portal JMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        :root {
            --sky-50:  #f0f9ff;
            --sky-100: #e0f2fe;
            --sky-200: #bae6fd;
            --sky-400: #38bdf8;
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
            --emerald-500: #10b981;
            --emerald-600: #059669;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--slate-50);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            color: var(--slate-800);
        }

        /* ── LAYOUT ─────────────────────────────────── */
        .login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ── LEFT PANEL ──────────────────────────────── */
        .panel-left {
            flex: 1.1;
            background-color: var(--sky-600);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 56px;
            position: relative;
            overflow: hidden;
        }

        /* decorative circles */
        .panel-left::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 420px; height: 420px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }
        .panel-left::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            pointer-events: none;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 14px;
            animation: fadeUp 0.6s ease both;
        }
        .brand-icon {
            width: 46px; height: 46px;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-icon svg { width: 24px; height: 24px; stroke: #fff; fill: none; stroke-width: 1.8; }
        .brand-name {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.02em;
            line-height: 1.2;
        }
        .brand-name span {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: rgba(255,255,255,0.65);
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .panel-hero { position: relative; z-index: 1; animation: fadeUp 0.7s 0.1s ease both; }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            color: rgba(255,255,255,0.9);
            font-size: 12px; font-weight: 600;
            letter-spacing: 0.05em; text-transform: uppercase;
            padding: 6px 14px; border-radius: 20px;
            margin-bottom: 24px;
        }
        .hero-eyebrow::before {
            content: '';
            display: block; width: 6px; height: 6px;
            background: var(--sky-200); border-radius: 50%;
            animation: pulse 2s ease infinite;
        }
        @keyframes pulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.5; transform: scale(1.4); }
        }

        .hero-title {
            font-family: 'DM Serif Display', serif;
            font-size: 38px;
            line-height: 1.2;
            color: #fff;
            margin-bottom: 16px;
        }
        .hero-desc {
            font-size: 15px;
            line-height: 1.75;
            color: rgba(255,255,255,0.75);
            max-width: 400px;
        }

        .panel-stats {
            display: flex; gap: 0;
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 14px;
            overflow: hidden;
            background: rgba(255,255,255,0.07);
            position: relative; z-index: 1;
            animation: fadeUp 0.7s 0.2s ease both;
        }
        .stat-item {
            flex: 1;
            padding: 18px 22px;
            border-right: 1px solid rgba(255,255,255,0.15);
        }
        .stat-item:last-child { border-right: none; }
        .stat-val {
            font-size: 24px; font-weight: 700; color: #fff; line-height: 1;
            margin-bottom: 6px;
        }
        .stat-lbl { font-size: 12px; color: rgba(255,255,255,0.6); font-weight: 500; }

        /* ── RIGHT PANEL ─────────────────────────────── */
        .panel-right {
            flex: 0.9;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 56px;
        }

        .form-card {
            width: 100%;
            max-width: 400px;
            animation: fadeUp 0.65s 0.15s ease both;
        }

        .form-logo {
            margin-bottom: 36px;
        }
        .form-logo img {
            max-width: 150px; height: auto;
        }
        .form-logo-placeholder {
            display: inline-flex; align-items: center; gap: 10px;
        }
        .logo-badge {
            width: 42px; height: 42px;
            background: var(--sky-600); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-badge svg { width: 22px; height: 22px; stroke: #fff; fill: none; stroke-width: 2; }
        .logo-text { font-size: 16px; font-weight: 700; color: var(--slate-900); line-height: 1.2; }
        .logo-text span { font-size: 12px; font-weight: 500; color: var(--slate-500); display: block; }

        .form-heading { font-size: 24px; font-weight: 700; color: var(--slate-900); margin-bottom: 6px; }
        .form-sub { font-size: 14px; color: var(--slate-500); margin-bottom: 32px; line-height: 1.5; }

        .alert-box {
            background: #fff1f1;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-box svg { width: 16px; height: 16px; stroke: #b91c1c; flex-shrink: 0; }

        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 13px; font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 7px;
        }
        .input-wrap { position: relative; }
        .input-wrap svg {
            position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
            width: 16px; height: 16px; stroke: var(--slate-400); fill: none; stroke-width: 1.8;
            pointer-events: none;
        }
        .form-input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1.5px solid var(--slate-200);
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--slate-800);
            background: var(--slate-50);
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--sky-500);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(14,165,233,0.12);
        }
        .form-input::placeholder { color: var(--slate-400); }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: var(--sky-600);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            margin-top: 6px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-login:hover { background: var(--sky-700); }
        .btn-login:active { transform: scale(0.98); }
        .btn-login svg { width: 18px; height: 18px; stroke: #fff; fill: none; stroke-width: 2; }

        .form-footer {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--slate-100);
            text-align: center;
        }
        .footer-roles {
            display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;
        }
        .role-tag {
            padding: 5px 13px;
            border-radius: 20px;
            font-size: 12px; font-weight: 600;
        }
        .role-tag.jms { background: var(--sky-50); color: var(--sky-700); border: 1px solid var(--sky-200); }
        .role-tag.nms { background: var(--emerald-50); color: var(--emerald-600); border: 1px solid #a7f3d0; }
        .role-tag-label { font-size: 12px; color: var(--slate-400); margin-bottom: 8px; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 860px) {
            .panel-left { display: none; }
            .panel-right { padding: 36px 28px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- LEFT: BRAND PANEL -->
    <div class="panel-left">
        <div class="brand-mark">
            <img
                src="{{ asset('images/PT JMS Kediri.png') }}"
                alt="Logo PT JMS Kediri"
                style="max-width: 160px; max-height: 52px; width: auto; height: auto; object-fit: contain; filter: brightness(0) invert(1);"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            >
            <div style="display:none; align-items:center; gap:12px;">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div class="brand-name">
                    PT JMS Kediri
                    <span>Sistem Alur Pembayaran HD</span>
                </div>
            </div>
        </div>

        <div class="panel-hero">
            <div class="hero-eyebrow">Sistem Terintegrasi</div>
            <h1 class="hero-title">Pemantauan Alur Dana Alat HD</h1>
            <p class="hero-desc">
                Sinergi operasional dan manajemen kontrak alat cuci darah JMS &amp; NMS secara transparan dan real-time dalam satu platform.
            </p>
        </div>

        <div class="panel-stats">
            <div class="stat-item">
                <div class="stat-val">HD</div>
                <div class="stat-lbl">Alat Hemodialisis</div>
            </div>
            <div class="stat-item">
                <div class="stat-val">JMS</div>
                <div class="stat-lbl">Vendor Utama</div>
            </div>
            <div class="stat-item">
                <div class="stat-val">NMS</div>
                <div class="stat-lbl">Partner Operasional</div>
            </div>
        </div>
    </div>

    <!-- RIGHT: FORM PANEL -->
    <div class="panel-right">
        <div class="form-card">

            <div class="form-logo">
                <img src="{{ asset('images/PT JMS Kediri.png') }}" alt="Logo PT JMS Kediri"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="form-logo-placeholder" style="display:none;">
                    <div class="logo-badge">
                        <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    </div>
                    <div class="logo-text">PT JMS Kediri <span>Vendor Portal</span></div>
                </div>
            </div>

            <h1 class="form-heading">Selamat Datang</h1>
            <p class="form-sub">Masuk menggunakan akun Anda untuk mengelola data.</p>

            @if($errors->any())
                <div class="alert-box">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Username</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" id="email" name="email" class="form-input"
                               placeholder="contoh: admin.jms@mail.com"
                               value="{{ old('email') }}" required autocomplete="email">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <input type="password" id="password" name="password" class="form-input"
                               placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Masuk ke Sistem
                </button>
            </form>

            <div class="form-footer">
                <p class="role-tag-label">Akses tersedia untuk:</p>
                <div class="footer-roles">
                    <span class="role-tag jms">PT JMS </span>
                    <span class="role-tag nms">PT NMS </span>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>