<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TahapPembayaran;

class PaymentScheduleController extends Controller
{
    /**
     * Menampilkan halaman Payment Schedule.
     * Mengambil data asli dari database tabel `tahap_pembayaran` berdasarkan RS aktif.
     */
    public function index()
    {
        // Menggunakan session bawaan sistem Anda untuk mendeteksi Rumah Sakit terpilih
        $current_rs = session('selected_hospital');

        if (!$current_rs) {
            return redirect()->route('dashboard')
                ->with('error', 'Silakan pilih rumah sakit terlebih dahulu.');
        }

        // 1. Ambil data master RAB berdasarkan RS Aktif
        $rab = DB::table('rab_pra_operasional')->where('nama_rs', $current_rs)->first();
        $rincian_rab = collect();

        if ($rab) {
            $rincian_rab = DB::table('rab_alokasi_dana')->where('rab_id', $rab->id)->get();
        }

        // 2. Ambil data nominal pajak murni dari tabel `tahap_pembayaran` berdasarkan RS Aktif
        $tahap_pembayaran_db = DB::table('tahap_pembayaran')
            ->where('rumahsakit', $current_rs)
            ->get()
            ->keyBy('tahap_ke');

        // 3. Petakan ke dalam 3 Tahap Utama (Tahap 1, 2, 3)
        $data_tahap_master = collect([1, 2, 3])->map(function ($tahap) use ($tahap_pembayaran_db) {
            // Jika data sudah tercetak di database (gambar 2)
            if (isset($tahap_pembayaran_db[$tahap])) {
                return (object) [
                    'id'                    => $tahap_pembayaran_db[$tahap]->id,
                    'tahap_ke'              => $tahap,
                    'nominal_sebelum_pajak' => $tahap_pembayaran_db[$tahap]->nominal_sebelum_pajak,
                    'pph23'                 => $tahap_pembayaran_db[$tahap]->pph23,
                    'pph_pasal4'            => $tahap_pembayaran_db[$tahap]->pph_pasal4,
                    'net_payment'           => $tahap_pembayaran_db[$tahap]->net_payment,
                    'status_bayar'          => $tahap_pembayaran_db[$tahap]->status_bayar ?? 'Belum Terbayar'
                ];
            }

            // Fallback default jika data di database masih kosong (0)
            return (object) [
                'id'                    => '',
                'tahap_ke'              => $tahap,
                'nominal_sebelum_pajak' => 0,
                'pph23'                 => 0,
                'pph_pasal4'            => 0,
                'net_payment'           => 0,
                'status_bayar'          => 'Belum Terbayar'
            ];
        });

        return view('menu.payment-schedule', compact('rincian_rab', 'current_rs', 'data_tahap_master'));
    }

    public function store(Request $request) 
    {
        // 1. Validasi input
        $request->validate([
            'nominal_sebelum_pajak' => 'required|numeric',
            // ... validasi lainnya
        ]);

        // 2. Simpan ke database
        try {
            TahapPembayaran::create([
                'rumahsakit' => session('selected_hospital'),
                'nominal_sebelum_pajak' => $request->nominal_sebelum_pajak,
                'pph23' => $request->pph23,
                'pph_pasal4' => $request->pph_pasal4,
                'net_payment' => $request->nominal_sebelum_pajak - $request->pph23 - $request->pph_pasal4,
                // tambahkan field lainnya
            ]);
            
            return back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Memproses update data dari Modal Kelola (Gambar 1) ke Database (Gambar 2).
     */
    public function update(Request $request)
    {
        // Validasi minimal agar tidak memicu eror crash query
        $request->validate([
            'target_type' => 'required|in:tahap_master,item',
        ]);

        // Helper untuk membersihkan format titik ribuan separator Rupiah menjadi Integer murni
        $clean = fn($val) => $val ? (int) str_replace('.', '', $val) : 0;
        $current_rs = session('selected_hospital');

        if ($request->target_type === 'tahap_master') {
            
            // Ambil informasi nomor tahap yang dikirim dari form modal
            $tahap_ke = (int) $request->input('tahap_ke');
            if ($tahap_ke < 1) {
                return redirect()->back()->with('error', 'Nomor tahap tidak valid.');
            }

            // Ambil angka murni hasil inputan user (Gambar 1)
            $nominal_awal = $clean($request->nominal_sebelum_pajak);
            $pph23        = $clean($request->pph23);
            $pph_pasal4   = $clean($request->pph_pasal4);
            
            // Hitung Net Payment: Pokok - PPh23 - PPh4
            $net_payment  = $nominal_awal - $pph23 - $pph_pasal4;

            // Eksekusi penyimpanan aman: Jika kombinasi RS + Tahap sudah ada -> UPDATE. Jika belum -> INSERT baru.
            DB::table('tahap_pembayaran')->updateOrInsert(
                [
                    'rumahsakit' => $current_rs,
                    'tahap_ke'   => $tahap_ke
                ],
                [
                    'nominal_sebelum_pajak' => $nominal_awal,
                    'pph23'                 => $pph23,
                    'pph_pasal4'            => $pph_pasal4,
                    'net_payment'           => $net_payment,
                    'status_bayar'          => $request->status_bayar ?? 'Belum Terbayar',
                    'updated_at'            => now(),
                ]
            );

        } else {
            // MODE ITEM VENDOR (Modal Kelola Baris Tabel Individu)
            $request->validate([
                'id' => 'required'
            ]);

            $updateData = [
                'dpp'               => $clean($request->dpp),
                'ppn'               => $clean($request->ppn),
                'pph23'             => $clean($request->pph23),
                'pph_pasal4'        => $clean($request->pph_pasal4),
                'net_payment'       => $clean($request->net_payment),
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

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }
}