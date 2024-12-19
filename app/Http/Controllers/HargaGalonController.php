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
    
    public function edit(){

    }

    public function create(){

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'benefit' => 'required|array', 
            'benefit.*' => 'string|max:255', 
        ]);

        try {
            DB::table('harga_galon')->insert([
                'nama_paket' => $validated['nama_paket'],
                'price' => $validated['price'],
                'description' => $validated['description'],
                'benefit' => json_encode($validated['benefit']), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Data berhasil disimpan.',
                'data' => $validated,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'benefit' => 'required|array',
            'benefit.*' => 'string|max:255', 
        ]);

        try {
            $hargaGalon = DB::table('harga_galon')->where('id', $id)->first();

            if (!$hargaGalon) {
                return response()->json([
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }

            DB::table('harga_galon')->where('id', $id)->update([
                'nama_paket' => $validated['nama_paket'],
                'price' => $validated['price'],
                'description' => $validated['description'],
                'benefit' => json_encode($validated['benefit']), 
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Data berhasil diperbarui.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui data.',
                'error' => $e->getMessage(),
            ], 500);
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
