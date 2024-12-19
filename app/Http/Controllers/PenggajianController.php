<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenggajianController extends Controller
{
    // public function index(Request $request)
    // {
    //     $tanggalMulai = $request->input('tanggal_mulai', '2024-11-15');
    //     $tanggalAkhir = $request->input('tanggal_akhir', '2024-11-22');

    //     $pendapatanHarian = DB::table('transactions')
    //         ->select(DB::raw('DATE(transaction_date) as tanggal'), DB::raw('SUM(total_price) as totalPendapatan'))
    //         ->whereBetween('transaction_date', [$tanggalMulai, $tanggalAkhir])
    //         ->groupBy('tanggal')
    //         ->get();

    //     $pengeluaranHarian = DB::table('expenses')
    //         ->select(DB::raw('DATE(date) as tanggal'), DB::raw('SUM(amount) as totalPengeluaran'))
    //         ->whereBetween('date', [$tanggalMulai, $tanggalAkhir])
    //         ->groupBy('tanggal')
    //         ->get()
    //         ->keyBy('tanggal');

    //     $jumlahKaryawan = DB::table('pegawai')->count();

    //     $gajiPerPegawai = [];
    //     foreach ($pendapatanHarian as $pendapatan) {
    //         $tanggal = $pendapatan->tanggal;
    //         $totalPendapatan = $pendapatan->totalPendapatan;
    //         $totalPengeluaran = $pengeluaranHarian[$tanggal]->totalPengeluaran ?? 0;

    //         $totalGalon = DB::table('transactions')
    //             ->whereDate('transaction_date', $tanggal)
    //             ->sum('galon_out');

    //         $gajiTotal = ($totalPendapatan - $totalPengeluaran - ($totalGalon * 1000)) * 0.35;

    //         $absensi = DB::table('absensi')
    //             ->join('pegawai', 'absensi.pegawai_id', '=', 'pegawai.id')
    //             ->select('pegawai.nama', 'pegawai.id')
    //             ->whereDate('tanggal', $tanggal)
    //             ->get();

    //         foreach ($absensi as $row) {
    //             $gajiPerHari = $jumlahKaryawan > 0 ? $gajiTotal / $jumlahKaryawan : 0;
    //             if (!isset($gajiPerPegawai[$row->nama])) {
    //                 $gajiPerPegawai[$row->nama] = 0;
    //             }
    //             $gajiPerPegawai[$row->nama] += $gajiPerHari;
    //         }
    //     }

    //     return view('penggajian.index', compact('gajiPerPegawai'));
    // }

    public function index(){
        $pegawai = DB::table('pegawai')->get();
        return view('penggajian.index', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'gaji' => 'required|numeric|min:0',
        ]);

        try {
            DB::table('pegawai')->where('id', $id)->update([
                'gaji' => $validated['gaji'],
                'updated_at' => now(),
            ]);

            return redirect()->route('penggajian.index')->with('success', 'Gaji berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('penggajian.index')->with('error', 'Terjadi kesalahan saat memperbarui gaji.');
        }
    }
}