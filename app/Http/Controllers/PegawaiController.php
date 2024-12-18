<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pegawai;
class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get query parameters
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search', null);
    
            // Retrieve all records initially
            $allPegawai = Pegawai::all();
    
            // Start the query for search and pagination
            $query = Pegawai::query();
    
            // Apply search filter if search is not empty
            if (!is_null($search) && trim($search) !== '') {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%");
            }
    
            // Paginate the results, sorted by ascending ID
            $pegawai = $query->orderBy('id', 'asc')->paginate($perPage);
    
            // Metadata for pagination
            $from = $pegawai->firstItem() ?? 0;
            $to = $pegawai->lastItem() ?? 0;
    
            // Pass data to the view
            return view('datapegawai.index', [
                'pegawai' => $pegawai,       // Filtered and paginated records
                'allPegawai' => $allPegawai, // All records (unfiltered)
                'perPage' => $perPage,
                'totalData' => $pegawai->total(),
                'from' => $from,
                'to' => $to,
                'search' => $search, // Include search term for view persistence
            ]);
        } catch (\Exception $e) {
            // Log the error and display a user-friendly message
            Log::error('Error in index method: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengambil data.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'no_hp' => 'required|string|max:15',
            ]);

            DB::table('pegawai')->insert([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pegawai berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);
    
            return response()->json([
                'success' => true,
                'data' => $pegawai,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Pegawai not found with ID: $id");
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {

            Log::error('Error in edit method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'no_hp' => 'required|string|max:15',
            ]);
    
            $pegawai = Pegawai::findOrFail($id); 
            $isUpdated = $pegawai->update($validatedData);
    
            if (!$isUpdated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada perubahan data',
                ], 200);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil diupdate',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error in update method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $result = DB::table('pegawai')->where('id', $id)->delete();

            if (!$result) {
                throw new \Exception('Gagal menghapus data');
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in destroy method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}