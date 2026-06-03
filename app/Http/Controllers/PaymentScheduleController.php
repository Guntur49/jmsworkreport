<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentScheduleController extends Controller
{
    /**
     * Menampilkan halaman Payment Schedule dengan perhitungan agregasi otomatis.
     */
    public function index()
    {
        $current_rs = session('selected_hospital');

        if (!$current_rs) {
            return redirect()->route('dashboard')
                ->with('error', 'Silakan pilih rumah sakit terlebih dahulu.');
        }

        // 1. Ambil data RAB berdasarkan RS aktif
        $rab = DB::table('rab_pra_operasional')->where('nama_rs', $current_rs)->first();
        
        // DEBUG: Log nilai $current_rs dan hasil query
        \Log::info('PaymentSchedule Debug', [
            'current_rs' => $current_rs,
            'rab_found' => $rab ? 'YES' : 'NO',
            'rab_data' => $rab
        ]);
        
        $rincian_rab = collect();

        if ($rab) {
            $rincian_rab = DB::table('rab_alokasi_dana')->where('rab_id', $rab->id)->get();
        } else {
            // Jika tidak ada RAB, buat object kosong agar view tidak error
            $rab = (object) ['total_dana_operasional' => 0];
        }

        // 2. Ambil master tahap_pembayaran untuk status_bayar
        $tahap_pembayaran_db = DB::table('tahap_pembayaran')->get()->keyBy('tahap_ke');

        // 3. LOGIKA BARU: Gunakan data master tahap_pembayaran jika tersedia,
        //    kalau tidak, gunakan agregasi vendor dari rab_alokasi_dana.
        $data_tahap_master = collect([1, 2, 3])->map(function ($tahap) use ($rincian_rab, $tahap_pembayaran_db) {
            $vendor_per_tahap = $rincian_rab->where('tahap_ke', $tahap);

            $default_nominal = $vendor_per_tahap->sum('dpp');
            $default_pph23   = $vendor_per_tahap->sum('pph23');
            $default_pph4    = $vendor_per_tahap->sum('pph_pasal4');
            $default_net     = $vendor_per_tahap->sum('net_payment');

            $stage = $tahap_pembayaran_db[$tahap] ?? null;
            $useStageValues = $stage && ($stage->nominal_sebelum_pajak || $stage->pph23 || $stage->pph_pasal4 || $stage->net_payment);

            return (object) [
                'tahap_ke'              => $tahap,
                'nominal_sebelum_pajak' => $useStageValues ? $stage->nominal_sebelum_pajak : $default_nominal,
                'pph23'                 => $useStageValues ? $stage->pph23 : $default_pph23,
                'pph_pasal4'            => $useStageValues ? $stage->pph_pasal4 : $default_pph4,
                'net_payment'           => $useStageValues ? $stage->net_payment : $default_net,
                'status_bayar'          => $stage ? $stage->status_bayar : 'Belum Terbayar'
            ];
        });

        return view('menu.payment-schedule', compact('rincian_rab', 'current_rs', 'data_tahap_master', 'rab'));
    }

    /**
     * Memproses update data dari Modal Kelola.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id'                => 'required',
            'target_type'       => 'required|in:tahap_master,item',
            'file_invoice'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_faktur_pajak' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_bukti_potong' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $clean = fn($val) => $val ? (int) str_replace('.', '', $val) : 0;

        if ($request->target_type === 'tahap_master') {
            // ---------------------------------------------------------------
            // MODE TAHAP MASTER: Tetap simpan nominal inputan ke database agar terisi
            // ---------------------------------------------------------------
            $existingStatus = DB::table('tahap_pembayaran')
                ->where('tahap_ke', (int) $request->id)
                ->value('status_bayar')
                ?: 'Belum Terbayar';

            DB::table('tahap_pembayaran')->updateOrInsert(
                ['tahap_ke' => (int) $request->id],
                [
                    'nominal_sebelum_pajak' => $clean($request->nominal_sebelum_pajak),
                    'pph23'                 => $clean($request->pph23),
                    'pph_pasal4'            => $clean($request->pph_pasal4),
                    'net_payment'           => $clean($request->net_payment),
                    'status_bayar'          => $request->status_bayar ?? $existingStatus,
                    'updated_at'            => now(),
                ]
            );

        } else {
            // ---------------------------------------------------------------
            // MODE ITEM VENDOR: update per baris vendor
            // ---------------------------------------------------------------
            $updateData = [
                'dpp'               => $clean($request->dpp),
                'ppn'               => $clean($request->ppn),
                'pph23'             => $clean($request->pph23),
                'pph_pasal4'        => $clean($request->pph_pasal4),
                'net_payment'       => $clean($request->net_payment),
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
    }

    
}