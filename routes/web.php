<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PaymentScheduleController;
use App\Http\Controllers\TahapPembayaranController;

Route::get('/payment-schedule', [TahapPembayaranController::class, 'index'])->name('timetable.index');
Route::post('/pajak-tahap/store', [TahapPembayaranController::class, 'store'])->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('pajak-tahap.store');
Route::get('/payment-schedule', [PaymentScheduleController::class, 'index'])->name('payment-schedule.controller.index');
Route::put('/payment-schedule/update', [PaymentScheduleController::class, 'update'])->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('payment-schedule.controller.update');
/*
|--------------------------------------------------------------------------
| 1. Web Routes - Autentikasi Login & Session
|--------------------------------------------------------------------------
*/

// Halaman Login
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Proses Login (Mendukung pengecekan input Username ataupun Email)
Route::post('/login', function (Request $request) {
    $loginInput = $request->username ?? $request->email;
    $password   = $request->password;

    if ((($loginInput == 'jms' || $loginInput == 'admin.jms@gmail.com') && $password == 'passwordjms')
        || ($loginInput == 'admin@gmail.com' && $password == 'passwordadmin')) {
        session(['user_role' => 'JMS', 'user_name' => 'Admin PT JMS']);
        session()->forget('selected_hospital');
        return redirect()->route('dashboard');
    }

    // Special case: grant read-only access to guest NMS account
    if ($loginInput == 'guest@gmail.com' && $password == 'passwordnms') {
        session(['user_role' => 'NMS', 'user_name' => 'Guest PT NMS']);
        session()->forget('selected_hospital');
        return redirect()->route('dashboard');
    }

    // Default NMS login remains view-only for other NMS accounts
    if ($loginInput == 'nms' && $password == 'passwordnms') {
        session(['user_role' => 'NMS', 'user_name' => 'Admin PT NMS']);
        session()->forget('selected_hospital');
        return redirect()->route('dashboard');
    }

    return back()->withErrors(['msg' => 'Username/Email atau Password salah!']);
})->name('login.post');

// Proses Logout
Route::post('/logout', function () {
    session()->forget(['user_role', 'user_name', 'selected_hospital']);
    return redirect()->route('login');
})->name('logout');


/*
|--------------------------------------------------------------------------
| 2. Halaman Dashboard & Pengaturan Rumah Sakit Aktif
|--------------------------------------------------------------------------
*/
Route::middleware([])->group(function () {

    Route::get('/dashboard', function () {
        $daftarRumahSakit = DB::table('rumah_sakit')->select('id', 'nama_rs', 'kota', 'alamat')->orderBy('nama_rs', 'asc')->get();
        return view('dashboard', compact('daftarRumahSakit'));
    })->name('dashboard');

    // Proses Set Rumah Sakit Aktif & Simpan Baru Jika Belum Ada
    Route::post('/set-hospital', function (Request $request) {
        $hospitalName = null;

        // Prioritaskan pilihan dropdown jika ada
        if ($request->filled('hospital_select')) {
            $hospitalName = trim($request->input('hospital_select')) ?: null;
        }

        // Jika input manual (popup), hanya izinkan user JMS menambah
        if (!$hospitalName && $request->filled('hospital_input') && session('user_role') == 'JMS') {
            $hospitalName = trim($request->input('hospital_input')) ?: null;

            if ($hospitalName) {
                $exists = DB::table('rumah_sakit')->where('nama_rs', $hospitalName)->exists();
                if (!$exists) {
                    $kota   = $request->input('kota')   ? trim($request->input('kota'))   : null;
                    $alamat = $request->input('alamat') ? trim($request->input('alamat')) : null;

                    DB::table('rumah_sakit')->insert([
                        'nama_rs'    => $hospitalName,
                        'kota'       => $kota,
                        'alamat'     => $alamat,
                        'created_at' => now()
                    ]);
                }
            }
        }

        if ($hospitalName) {
            session(['selected_hospital' => $hospitalName]);
            return redirect()->route('dashboard')->with('success', 'Rumah sakit dipilih: ' . $hospitalName);
        }

        session()->forget('selected_hospital');
        return redirect()->route('dashboard')->with('error', 'Tidak ada rumah sakit dipilih.');
    })->name('set.hospital');

    // Proses Update Rumah Sakit (hanya untuk JMS)
    Route::put('/hospital/update', function (Request $request) {
        if (session('user_role') != 'JMS') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengubah data rumah sakit.');
        }

        $id = $request->input('id');
        $namaRSLama = DB::table('rumah_sakit')->where('id', $id)->value('nama_rs');
        $namaRSBaru = trim($request->input('nama_rs')) ?: null;
        $kota = trim($request->input('kota')) ?: null;
        $alamat = trim($request->input('alamat')) ?: null;

        if (!$namaRSBaru || !$kota || !$alamat) {
            return redirect()->route('dashboard')->with('error', 'Semua data rumah sakit harus diisi!');
        }

        // Update data (tanpa updated_at karena kolom belum ada)
        DB::table('rumah_sakit')->where('id', $id)->update([
            'nama_rs'    => $namaRSBaru,
            'kota'       => $kota,
            'alamat'     => $alamat
        ]);

        // Update session jika rumah sakit yang sedang aktif diubah namanya
        if (session('selected_hospital') === $namaRSLama) {
            session(['selected_hospital' => $namaRSBaru]);
        }

        return redirect()->route('dashboard')->with('success', 'Data rumah sakit berhasil diubah.');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('hospital.update');

    // Proses Delete Rumah Sakit (hanya untuk JMS)
    Route::delete('/hospital/delete', function (Request $request) {
        if (session('user_role') != 'JMS') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk menghapus data rumah sakit.');
        }

        $id = $request->input('id');
        $namaRS = DB::table('rumah_sakit')->where('id', $id)->value('nama_rs');

        // Hapus data rumah sakit
        DB::table('rumah_sakit')->where('id', $id)->delete();

        // Hapus session jika rumah sakit yang sedang aktif dihapus
        if (session('selected_hospital') === $namaRS) {
            session()->forget('selected_hospital');
        }

        return redirect()->route('dashboard')->with('success', 'Rumah sakit berhasil dihapus.');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('hospital.delete');
});


/*
|--------------------------------------------------------------------------
| 3. MANAJEMEN CRUD: RUTE KHUSUS KENDALI KONTRAK
|--------------------------------------------------------------------------
*/
Route::middleware([])->group(function () {

    // 1. TAMPILKAN DATA (READ)
    Route::get('/menu/kendali-kontrak', function () {
        $current_rs = session('selected_hospital');

        if (!$current_rs) {
            return redirect()->route('dashboard')->with('error', 'Silakan pilih rumah sakit terlebih dahulu.');
        }

        $contracts = DB::table('kendali_kontrak')->where('nama_rs', $current_rs)->get();
        return view('menu.kendali-kontrak', compact('contracts', 'current_rs'));
    })->name('kontrak.index');

    // 2. TAMBAH DATA BARU (CREATE)
    Route::post('/menu/kendali-kontrak/store', function (Request $request) {
        $nama_rumah_sakit = $request->input('nama_rs') ?? session('selected_hospital');

        if (!$nama_rumah_sakit) {
            return back()->with('error', 'Gagal menyimpan, identitas Rumah Sakit tidak terbaca.');
        }

        DB::table('kendali_kontrak')->insert([
            'nama_rs'              => $nama_rumah_sakit,
            'mitra_rs'             => $request->mitra_rs,
            'kso'                  => $request->kso,
            'lama_kontrak'         => $request->lama_kontrak,
            'hak_kewajiban'        => $request->hak_kewajiban,
            'mekanisme_prosentase' => $request->mekanisme_prosentase,
            'elemen_wanprestasi'   => $request->elemen_wanprestasi,
            'potensi_wanprestasi'  => $request->potensi_wanprestasi,
            'created_at'           => now()
        ]);

        return redirect()->route('kontrak.index')->with('success', 'Data kendali kontrak berhasil ditambahkan.');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('kontrak.store');

    // 3. SIMPAN PERUBAHAN DATA (UPDATE)
    Route::post('/menu/kendali-kontrak/update/{id}', function (Request $request, $id) {
        DB::table('kendali_kontrak')->where('id', $id)->update([
            'mitra_rs'             => $request->mitra_rs,
            'kso'                  => $request->kso,
            'lama_kontrak'         => $request->lama_kontrak,
            'hak_kewajiban'        => $request->hak_kewajiban,
            'mekanisme_prosentase' => $request->mekanisme_prosentase,
            'elemen_wanprestasi'   => $request->elemen_wanprestasi,
            'potensi_wanprestasi'  => $request->potensi_wanprestasi,
        ]);

        return redirect()->route('kontrak.index')->with('success', 'Data kendali kontrak berhasil diperbarui.');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('kontrak.update');

    // 4. HAPUS DATA (DELETE)
    Route::delete('/menu/kendali-kontrak/delete/{id}', function ($id) {
        DB::table('kendali_kontrak')->where('id', $id)->delete();
        return redirect()->route('kontrak.index')->with('success', 'Data kendali kontrak berhasil dihapus.');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('kontrak.delete');
});


/*
|--------------------------------------------------------------------------
| 4. MANAJEMEN CRUD: RUTE KHUSUS TIME TABLE
|--------------------------------------------------------------------------
*/
Route::middleware([])->group(function () {

    // 1. TAMPILKAN TIMELINE
    Route::get('/menu/time-table', function () {
        $current_rs = session('selected_hospital');
        if (!$current_rs) {
            return redirect()->route('dashboard')->with('error', 'Silakan pilih rumah sakit terlebih dahulu.');
        }

        $raw_data    = DB::table('time_table_pekerjaan')->where('nama_rs', $current_rs)->get();
        $min_month   = DB::table('time_table_pekerjaan')->where('nama_rs', $current_rs)->min('bulan') ?? 5;
        $max_month   = DB::table('time_table_pekerjaan')->where('nama_rs', $current_rs)->max('bulan') ?? 9;
        $tahun_aktif = DB::table('time_table_pekerjaan')->where('nama_rs', $current_rs)->value('tahun') ?? date('Y');

        $list_bulan = [
            1 => 'JANUARI',  2 => 'FEBRUARI', 3 => 'MARET',     4 => 'APRIL',
            5 => 'MEI',      6 => 'JUNI',     7 => 'JULI',      8 => 'AGUSTUS',
            9 => 'SEPTEMBER',10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];

        $timeline = [];
        foreach ($raw_data as $data) {
            $timeline[$data->jenis_pekerjaan][$data->bulan][$data->minggu] = true;
        }

        return view('menu.time-table', compact('timeline', 'current_rs', 'min_month', 'max_month', 'list_bulan', 'tahun_aktif'));
    })->name('timetable.index');

    // 2. SIMPAN DATA BARU
    Route::post('/menu/time-table/store', function (Request $request) {
        $current_rs      = session('selected_hospital');
        $jenis_pekerjaan = $request->jenis_pekerjaan;
        $data_bulan      = $request->bulan;

        if (!$data_bulan || count($data_bulan) == 0) {
            return back()->with('error', 'Gagal memproses data, isian bulan tidak terdeteksi.');
        }

        foreach ($data_bulan as $item) {
            $bulan_val  = $item['id'];
            $tahun_val  = $item['tahun'];
            $minggu_list = $item['minggu'] ?? [];

            foreach ($minggu_list as $mg) {
                $isExists = DB::table('time_table_pekerjaan')
                    ->where('nama_rs', $current_rs)
                    ->where('jenis_pekerjaan', $jenis_pekerjaan)
                    ->where('tahun', $tahun_val)
                    ->where('bulan', $bulan_val)
                    ->where('minggu', $mg)
                    ->exists();

                if (!$isExists) {
                    DB::table('time_table_pekerjaan')->insert([
                        'nama_rs'         => $current_rs,
                        'jenis_pekerjaan' => $jenis_pekerjaan,
                        'tahun'           => $tahun_val,
                        'bulan'           => $bulan_val,
                        'minggu'          => $mg
                    ]);
                }
            }
        }

        return redirect()->route('timetable.index')->with('success', 'Jadwal time table berhasil dikonfigurasi.');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('timetable.store');

    // 3. PERBARUI / EDIT SATU JENIS PEKERJAAN
    Route::put('/menu/time-table/update', function (Request $request) {
        $current_rs         = session('selected_hospital');
        $original_pekerjaan = $request->original_jenis_pekerjaan;
        $jenis_pekerjaan    = $request->jenis_pekerjaan;
        $data_bulan         = $request->bulan;

        if (!$data_bulan || count($data_bulan) == 0) {
            return back()->with('error', 'Gagal memproses data, isian bulan tidak terdeteksi.');
        }

        DB::transaction(function () use ($current_rs, $original_pekerjaan, $jenis_pekerjaan, $data_bulan) {
            DB::table('time_table_pekerjaan')
                ->where('nama_rs', $current_rs)
                ->where('jenis_pekerjaan', $original_pekerjaan)
                ->delete();

            foreach ($data_bulan as $item) {
                $bulan_val  = $item['id'];
                $tahun_val  = $item['tahun'];
                $minggu_list = $item['minggu'] ?? [];

                foreach ($minggu_list as $mg) {
                    DB::table('time_table_pekerjaan')->insert([
                        'nama_rs'         => $current_rs,
                        'jenis_pekerjaan' => $jenis_pekerjaan,
                        'tahun'           => $tahun_val,
                        'bulan'           => $bulan_val,
                        'minggu'          => $mg
                    ]);
                }
            }
        });

        return redirect()->route('timetable.index')->with('success', 'Jadwal time table berhasil diperbarui.');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('timetable.update');

    // 4. RESET / HAPUS SATU JENIS PEKERJAAN
    Route::delete('/menu/time-table/delete', function (Request $request) {
        $current_rs = session('selected_hospital');
        DB::table('time_table_pekerjaan')
            ->where('nama_rs', $current_rs)
            ->where('jenis_pekerjaan', $request->jenis_pekerjaan)
            ->delete();

        return redirect()->route('timetable.index')->with('success', 'Pekerjaan berhasil dihapus dari jadwal.');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('timetable.delete');
});


/*
|--------------------------------------------------------------------------
| 5. MANAJEMEN CRUD: RUTE KHUSUS RAB PRA OPERASIONAL
|--------------------------------------------------------------------------
*/
Route::middleware([])->group(function () {

    // 1. TAMPILKAN HALAMAN RAB
    Route::get('/menu/rab-pra-operasional', function () {
        $current_rs = session('selected_hospital');
        if (!$current_rs) {
            return redirect()->route('dashboard')->with('error', 'Silakan pilih rumah sakit terlebih dahulu.');
        }

        $rab   = DB::table('rab_pra_operasional')->where('nama_rs', $current_rs)->first();
        $tahap1 = collect(); $tahap2 = collect(); $tahap3 = collect();
        $total_terpakai = 0;

        if ($rab) {
            $alokasi        = DB::table('rab_alokasi_dana')->where('rab_id', $rab->id)->get();
            $tahap1         = $alokasi->where('tahap', 1);
            $tahap2         = $alokasi->where('tahap', 2);
            $tahap3         = $alokasi->where('tahap', 3);
            $total_terpakai = $alokasi->sum('nominal');
        }

        return view('menu.rab-pra-operasional', compact('rab', 'current_rs', 'tahap1', 'tahap2', 'tahap3', 'total_terpakai'));
    })->name('rab.index');

    // 2. SIMPAN DATA RAB BARU DAN ALOKASI DANA
    Route::post('/menu/rab-pra-operasional/store', function (Request $request) {
        $current_rs      = session('selected_hospital');
        $tgl_kontrak     = $request->tgl_kontrak;
        $jumlah_mesin    = (int) $request->jumlah_mesin;
        $dana_per_mesin  = (int) str_replace('.', '', $request->dana_operasional_per_mesin ?? 0);
        $total_dana      = $jumlah_mesin * $dana_per_mesin;

        $jt_tahap1 = date('Y-m-d', strtotime($tgl_kontrak . ' + 7 days'));
        $jt_tahap2 = date('Y-m-d', strtotime($jt_tahap1  . ' + 1 month'));
        $jt_tahap3 = date('Y-m-d', strtotime($jt_tahap2  . ' + 2 months'));

        $rab_id = DB::table('rab_pra_operasional')->insertGetId([
            'nama_rs'                   => $current_rs,
            'no_kontrak'                => $request->no_kontrak,
            'tgl_kontrak'               => $tgl_kontrak,
            'jumlah_mesin'              => $jumlah_mesin,
            'dana_operasional_per_mesin'=> $dana_per_mesin,
            'total_dana_operasional'    => $total_dana,
            'jumlah_mesin_cadangan'     => $request->jumlah_mesin_cadangan ?? 0,
            'created_at'                => now()
        ]);

        $jt_list = [1 => $jt_tahap1, 2 => $jt_tahap2, 3 => $jt_tahap3];

        for ($tahap = 1; $tahap <= 3; $tahap++) {
            if (isset($request->alokasi[$tahap])) {
                foreach ($request->alokasi[$tahap] as $row) {
                    if (!empty($row['penerima'])) {
                        DB::table('rab_alokasi_dana')->insert([
                            'rab_id'              => $rab_id,
                            'tahap'               => $tahap,
                            'jatuh_tempo_tahap'   => $jt_list[$tahap],
                            'penerima_alokasi'    => $row['penerima'],
                            'deskripsi'           => $row['deskripsi'] ?? '-',
                            'nominal'             => (int) str_replace('.', '', $row['nominal'])
                        ]);
                    }
                }
            }
        }

        return redirect()->route('rab.index')->with('success', 'Data RAB Berhasil Disimpan.');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('rab.store');

    // 2b. UPDATE RAB
    Route::put('/menu/rab-pra-operasional/update/{id}', function (\Illuminate\Http\Request $request, $id) {
        $jumlah_mesin   = (int) $request->jumlah_mesin;
        $dana           = (int) str_replace('.', '', $request->dana_operasional_per_mesin ?? '0');
        $total_dana     = $jumlah_mesin * $dana;

        $jt_tahap1 = date('Y-m-d', strtotime($request->tgl_kontrak . ' + 7 days'));
        $jt_tahap2 = date('Y-m-d', strtotime($jt_tahap1 . ' + 1 month'));
        $jt_tahap3 = date('Y-m-d', strtotime($jt_tahap2 . ' + 2 months'));
        $jt_list   = [1 => $jt_tahap1, 2 => $jt_tahap2, 3 => $jt_tahap3];

        DB::table('rab_pra_operasional')->where('id', $id)->update([
            'no_kontrak'                => $request->no_kontrak,
            'tgl_kontrak'               => $request->tgl_kontrak,
            'jumlah_mesin'              => $jumlah_mesin,
            'dana_operasional_per_mesin'=> $dana,
            'total_dana_operasional'    => $total_dana,
            'jumlah_mesin_cadangan'     => (int) ($request->jumlah_mesin_cadangan ?? 0),
            'updated_at'                => now()
        ]);

        $existingAllocationIds = DB::table('rab_alokasi_dana')->where('rab_id', $id)->pluck('id')->toArray();
        $incomingAllocationIds = [];

        for ($tahap = 1; $tahap <= 3; $tahap++) {
            if (isset($request->alokasi[$tahap])) {
                foreach ($request->alokasi[$tahap] as $row) {
                    if (empty($row['penerima'])) {
                        continue;
                    }

                    $data = [
                        'rab_id'            => $id,
                        'tahap'             => $tahap,
                        'jatuh_tempo_tahap' => $jt_list[$tahap],
                        'penerima_alokasi'  => $row['penerima'],
                        'deskripsi'         => $row['deskripsi'] ?? '-',
                        'nominal'           => (int) str_replace('.', '', $row['nominal'])
                    ];

                    if (!empty($row['id']) && in_array((int) $row['id'], $existingAllocationIds, true)) {
                        $allocationId = (int) $row['id'];
                        DB::table('rab_alokasi_dana')->where('id', $allocationId)->update($data);
                        $incomingAllocationIds[] = $allocationId;
                    } else {
                        $incomingAllocationIds[] = DB::table('rab_alokasi_dana')->insertGetId($data);
                    }
                }
            }
        }

        // When updating allocations, do not delete rows that have already been paid
        // (status_bayar = 'Terbayar') to avoid losing payment/journal history.
        if (!empty($incomingAllocationIds)) {
            DB::table('rab_alokasi_dana')
                ->where('rab_id', $id)
                ->whereNotIn('id', $incomingAllocationIds)
                ->where(function ($q) {
                    $q->whereNull('status_bayar')
                      ->orWhere('status_bayar', '!=', 'Terbayar');
                })
                ->delete();
        } else {
            DB::table('rab_alokasi_dana')
                ->where('rab_id', $id)
                ->where(function ($q) {
                    $q->whereNull('status_bayar')
                      ->orWhere('status_bayar', '!=', 'Terbayar');
                })
                ->delete();
        }

        return redirect()->route('rab.index')->with('success', 'Data RAB berhasil diperbarui.');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('rab.update');

    // 3. HAPUS RAB
    Route::delete('/menu/rab-pra-operasional/delete/{id}', function ($id) {
        DB::table('rab_pra_operasional')->where('id', $id)->delete();
        return redirect()->route('rab.index')->with('success', 'Data RAB berhasil di-reset.');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('rab.delete');
});


/*
|--------------------------------------------------------------------------
| 6. MANAJEMEN CRUD: RUTE KHUSUS PAYMENT SCHEDULE & PAJAK
|--------------------------------------------------------------------------
| LOGIKA BARU (sesuai gambar referensi):
|   - PPh23 & PPh Ps4(2) diisi PER BARIS VENDOR (tabel rab_alokasi_dana)
|   - Baris hijau "Total Alokasi" = agregasi otomatis dari sum vendor
|   - Tabel tahap_pembayaran hanya menyimpan status_bayar per tahap
|--------------------------------------------------------------------------
*/
Route::middleware([])->group(function () {

    // 1. TAMPILKAN DATA PAYMENT SCHEDULE
    Route::get('/menu/payment-schedule', function () {
        $current_rs = session('selected_hospital');
        if (!$current_rs) {
            return redirect()->route('dashboard')->with('error', 'Silakan pilih rumah sakit terlebih dahulu.');
        }

        $rab         = DB::table('rab_pra_operasional')->where('nama_rs', $current_rs)->first();
        $rincian_rab = collect();

        if ($rab) {
            $rincian_rab = DB::table('rab_alokasi_dana')->where('rab_id', $rab->id)->get();
        }

        // Ambil data tahap_pembayaran per rumah sakit aktif, termasuk PPh dan status tahap
        $daftarRumahSakit = DB::table('rumah_sakit')->orderBy('nama_rs', 'asc')->get();
        $data_tahap_master = DB::table('tahap_pembayaran')->where('rumahsakit', $current_rs)->get();

        if ($data_tahap_master->isEmpty()) {
            $data_tahap_master = collect([
                (object)['tahap_ke' => 1, 'status_bayar' => 'Belum Terbayar'],
                (object)['tahap_ke' => 2, 'status_bayar' => 'Belum Terbayar'],
                (object)['tahap_ke' => 3, 'status_bayar' => 'Belum Terbayar'],
            ]);
        }

        return view('menu.payment-schedule', compact('rincian_rab', 'current_rs', 'data_tahap_master', 'daftarRumahSakit'));
    })->name('payment-schedule.index');

    // 2. UPDATE DATA VENDOR (PPh, DPP, PPN, Berkas, Status per item)
    //    target_type = 'item' — update tabel rab_alokasi_dana
    Route::put('/menu/payment-schedule/update', function (Request $request) {
        $request->validate([
            'id'                    => 'required',
            'target_type'           => 'required|in:tahap_master,item',
            'file_invoice'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_faktur_pajak'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_bukti_potong'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_bayar'          => 'nullable|in:Belum Terbayar,Terbayar,URGENT,Suspend',
            'nominal_sebelum_pajak' => 'nullable|string',
            'transfer_to'           => 'nullable|string|max:255',
        ]);

        $cleanNumber = function ($value) {
            if (!$value) return 0;
            return (int) str_replace('.', '', $value);
        };

        if ($request->target_type === 'tahap_master') {
            $request->validate([
                'rumahsakit' => 'required|string',
            ]);

            DB::table('tahap_pembayaran')->updateOrInsert(
                [
                    'tahap_ke'   => (int) $request->id,
                    'rumahsakit' => $request->rumahsakit,
                ],
                [
                    'nominal_sebelum_pajak' => $cleanNumber($request->nominal_sebelum_pajak),
                    'pph23'                 => $cleanNumber($request->pph23),
                    'pph_pasal4'            => $cleanNumber($request->pph_pasal4),
                    'net_payment'           => $cleanNumber($request->net_payment),
                    'status_bayar'          => $request->status_bayar ?? 'Belum Terbayar',
                    'updated_at'            => now(),
                ]
            );
        } else {
            $updateData = [
                'dpp'               => $cleanNumber($request->dpp),
                'ppn'               => $cleanNumber($request->ppn),
                'pph23'             => $cleanNumber($request->pph23),
                'pph_pasal4'        => $cleanNumber($request->pph_pasal4),
                'net_payment'       => $cleanNumber($request->net_payment),
                'transfer_to'       => $request->transfer_to,
                'no_invoice'        => $request->no_invoice,
                'no_faktur_pajak'   => $request->no_faktur_pajak,
                'no_bukti_potong'   => $request->no_bukti_potong,
                'status_bayar'      => $request->status_bayar,
                'tanggal_realisasi' => $request->tanggal_realisasi,
            ];

            if ($request->hasFile('file_invoice')) {
                $updateData['file_invoice'] = $request->file('file_invoice')->store('uploads/invoice', 'public');
            }
            if ($request->hasFile('file_faktur_pajak')) {
                $updateData['file_faktur_pajak'] = $request->file('file_faktur_pajak')->store('uploads/faktur', 'public');
            }
            if ($request->hasFile('file_bukti_potong')) {
                $updateData['file_bukti_potong'] = $request->file('file_bukti_potong')->store('uploads/bupot', 'public');
            }

            DB::table('rab_alokasi_dana')->where('id', $request->id)->update($updateData);
        }

        return redirect()->back()->with('success', 'Data Payment Schedule berhasil diperbarui!');
    })->middleware(\App\Http\Middleware\CheckNmsReadOnly::class)->name('payment-schedule.update');

    // 3. OUTSTANDING PAYMENT
    Route::get('/menu/outstanding-payment', function (Request $request) {
        $current_rs = session('selected_hospital');
        if (!$current_rs) {
            return redirect()->route('dashboard')->with('error', 'Silakan pilih rumah sakit terlebih dahulu.');
        }

        $daftarRumahSakit = DB::table('rumah_sakit')->orderBy('nama_rs', 'asc')->get();
        $selectedHospital = $request->query('rumah_sakit', $current_rs);
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        $outstanding = collect();
        $rabQuery = DB::table('rab_pra_operasional');

        if ($selectedHospital !== 'all') {
            $rabQuery->where('nama_rs', $selectedHospital);
        }

        $rabIds = $rabQuery->pluck('id');

        if ($rabIds->isNotEmpty()) {
            $query = DB::table('rab_alokasi_dana')
                ->join('rab_pra_operasional', 'rab_alokasi_dana.rab_id', '=', 'rab_pra_operasional.id')
                ->whereIn('rab_id', $rabIds)
                ->whereIn('status_bayar', ['Belum Terbayar', 'URGENT', 'Suspend'])
                ->select('rab_alokasi_dana.*', 'rab_pra_operasional.nama_rs');

            if ($tanggalMulai) {
                $query->where('jatuh_tempo_tahap', '>=', $tanggalMulai);
            }
            if ($tanggalSelesai) {
                $query->where('jatuh_tempo_tahap', '<=', $tanggalSelesai);
            }

            $outstanding = $query->orderBy('jatuh_tempo_tahap', 'asc')->get();
        }

        return view('menu.outstanding-payment', compact(
            'outstanding', 'current_rs', 'daftarRumahSakit', 'selectedHospital', 'tanggalMulai', 'tanggalSelesai'
        ));
    })->name('outstanding-payment.index');

    // 4. PAYMENT JURNAL
    Route::get('/menu/payment-jurnal', function (Request $request) {
        $current_rs = session('selected_hospital');
        if (!$current_rs) {
            return redirect()->route('dashboard')->with('error', 'Silakan pilih rumah sakit terlebih dahulu.');
        }

        $daftarRumahSakit = DB::table('rumah_sakit')->orderBy('nama_rs', 'asc')->get();
        $selectedHospital = $request->query('rumah_sakit', $current_rs);
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        $paymentHistory = collect();
        $rabQuery = DB::table('rab_pra_operasional');

        if ($selectedHospital !== 'all') {
            $rabQuery->where('nama_rs', $selectedHospital);
        }

        $rabIds = $rabQuery->pluck('id');

        if ($rabIds->isNotEmpty()) {
            $query = DB::table('rab_alokasi_dana')
                ->join('rab_pra_operasional', 'rab_alokasi_dana.rab_id', '=', 'rab_pra_operasional.id')
                ->whereIn('rab_id', $rabIds)
                ->where('status_bayar', 'Terbayar')
                ->select('rab_alokasi_dana.*', 'rab_pra_operasional.nama_rs');

            if ($tanggalMulai) {
                $query->where('tanggal_realisasi', '>=', $tanggalMulai);
            }
            if ($tanggalSelesai) {
                $query->where('tanggal_realisasi', '<=', $tanggalSelesai);
            }

            $paymentHistory = $query->orderBy('tanggal_realisasi', 'desc')->get();
        }

        return view('menu.payment-jurnal', compact(
            'paymentHistory', 'current_rs', 'daftarRumahSakit', 'selectedHospital', 'tanggalMulai', 'tanggalSelesai'
        ));
    })->name('payment-jurnal.index');

    // API: get latest proof file paths for a rab_alokasi_dana row
    Route::get('/api/rab-alokasi/{id}/proofs', function ($id) {
        $row = DB::table('rab_alokasi_dana')->where('id', $id)->select('file_invoice', 'file_faktur_pajak', 'file_bukti_potong')->first();
        if (!$row) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json($row);
    });
});


/*
|--------------------------------------------------------------------------
| 7. RUTE WILDCARD TEMPLATE KOSONG (Wajib Berada di Paling Bawah File)
|--------------------------------------------------------------------------
*/
Route::middleware([])->group(function () {
    Route::get('/menu/{slug}', function ($slug) {
        if (!session('selected_hospital')) {
            return redirect()->route('dashboard');
        }
        $menuName = ucwords(str_replace('-', ' ', $slug));
        return view('template-page', compact('menuName'));
    })->name('menu.view');
});