<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HargaGalon;
use Illuminate\Support\Facades\DB;

class HargaGalonController extends Controller
{
    public function index()
    {
        // Ambil semua data galon
        $hargaGalon = HargaGalon::all();
    
        // Kirim data ke view
        return view('edit-harga-galon.index', compact('hargaGalon'));
    }
    
    public function edit($id)
    {
        $hargaGalon = DB::table('harga_galon')->where('id', $id)->first();

        if (!$hargaGalon) {
            return redirect()->route('edit-harga-galon.index')->with('error', 'Data tidak ditemukan.');
        }

        return view('edit-harga-galon.edit', compact('hargaGalon'));
    }

    public function create()
    {
        return view('edit-harga-galon.create');
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'benefit' => 'required|string', // Validasi sebagai string
        ]);
    
        try {
            // Proses input benefit: pisahkan string menjadi array
            $benefitArray = array_map('trim', explode(',', $validated['benefit']));
    
            // Simpan data ke database
            DB::table('harga_galon')->insert([
                'nama_paket' => $validated['nama_paket'],
                'price' => $validated['price'],
                'description' => $validated['description'],
                'benefit' => json_encode($benefitArray), // Encode array ke JSON
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            return redirect()->route('edit-harga-galon.index')
                             ->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->route('edit-harga-galon.index')
                             ->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
    

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'benefit' => 'required|string', // Validasi sebagai string
        ]);

        try {
            $hargaGalon = DB::table('harga_galon')->where('id', $id)->first();

            if (!$hargaGalon) {
                return redirect()->route('harga-galon.edit', $id)
                    ->with('error', 'Data tidak ditemukan.');
            }

            // Proses input benefit: pisahkan string menjadi array
            $benefitArray = array_map('trim', explode(',', $validated['benefit']));

            // Update data
            DB::table('harga_galon')->where('id', $id)->update([
                'nama_paket' => $validated['nama_paket'],
                'price' => $validated['price'],
                'description' => $validated['description'],
                'benefit' => json_encode($benefitArray), // Encode array ke JSON
                'updated_at' => now(),
            ]);

            return redirect()->route('edit-harga-galon.index', $id)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('edit-harga-galon.index', $id)
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }



    public function destroy($id)
    {
        try {
            $hargaGalon = DB::table('harga_galon')->where('id', $id)->first();

            if (!$hargaGalon) {
                return redirect()->route('harga-galon.index')->with('error', 'Data tidak ditemukan.');
            }

            DB::table('harga_galon')->where('id', $id)->delete();

            return redirect()->route('edit-harga-galon.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('edit-harga-galon.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
