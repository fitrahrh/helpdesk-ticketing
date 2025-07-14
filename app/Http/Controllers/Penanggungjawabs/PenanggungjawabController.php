<?php

namespace App\Http\Controllers\Penanggungjawabs;

use App\Http\Controllers\Controller;
use App\Models\Penanggungjawab;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Skpd;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PenanggungjawabController extends Controller
{
    public function index()
    {
        $penanggungjawabs = Penanggungjawab::with(['user', 'kategori'])->get();
        $users = User::where('role_id', 3)->get(); // Role_id 3 untuk teknisi
        $skpds = SKPD::with('kategoris')->get(); // Dapatkan SKPD dengan relasi kategoris
        $kategoris = Kategori::with('skpd')->get(); // Tetap ambil semua kategori untuk kebutuhan lain
        
        return view('layouts.admin.penanggungjawab.index', compact('penanggungjawabs', 'users', 'skpds', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kategori_id' => 'required|exists:kategoris,id'
        ]);

        // Check if the penanggungjawab already exists for this kategori
        $exists = Penanggungjawab::where('kategori_id', $request->kategori_id)
            ->where('user_id', $request->user_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => false, 
                'message' => 'Penanggungjawab untuk kategori ini sudah ada'
            ]);
        }

        if (Penanggungjawab::create($request->all())) {
            $response = ['status' => TRUE, 'message' => 'Penanggungjawab berhasil disimpan'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Penanggungjawab gagal disimpan']);
    }

    public function edit($id)
    {
        $penanggungjawab = Penanggungjawab::findOrFail($id);
        return response()->json($penanggungjawab);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kategori_id' => 'required|exists:kategoris,id'
        ]);

        // Check if the updated data conflicts with existing records
        $exists = Penanggungjawab::where('kategori_id', $request->kategori_id)
            ->where('user_id', $request->user_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => false, 
                'message' => 'Penanggungjawab untuk kategori ini sudah ada'
            ]);
        }

        $penanggungjawab = Penanggungjawab::findOrFail($id);
        
        if ($penanggungjawab->update($request->all())) {
            $response = ['status' => TRUE, 'message' => 'Penanggungjawab berhasil diperbarui'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Penanggungjawab gagal diperbarui']);
    }

    public function destroy($id)
    {
        $penanggungjawab = Penanggungjawab::findOrFail($id);
        
        if ($penanggungjawab->delete()) {
            $response = ['status' => TRUE, 'message' => 'Penanggungjawab berhasil dihapus'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Penanggungjawab gagal dihapus']);
    }

    // Gunakan method ini yang mendukung filter
    public function datapenanggungjawab(Request $request)
    {
        $query = Penanggungjawab::with(['user', 'kategori.skpd']);
        
        // Apply filters if provided
        if ($request->filled('filter_skpd')) {
            $query->whereHas('kategori', function($q) use ($request) {
                $q->where('skpd_id', $request->filter_skpd);
            });
        }
        
        if ($request->filled('filter_kategori')) {
            $query->where('kategori_id', $request->filter_kategori);
        }
        
        if ($request->filled('filter_teknisi')) {
            $query->where('user_id', $request->filter_teknisi);
        }
        
        $data = $query->get();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user_name', function($row){
                return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '-';
            })
            ->addColumn('kategori_name', function($row){
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('skpd_name', function($row){
                return $row->kategori && $row->kategori->skpd ? $row->kategori->skpd->name : '-';
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" class="btn btn-sm btn-warning btn-edit" data-id="'.$row->id.'"><i class="fas fa-edit"></i></button> ';
                $actionBtn .= '<button type="button" class="btn btn-sm btn-danger" onclick="deletePenanggungjawab(\''.$row->id.'\')"><i class="fas fa-trash"></i></button>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Add new route to get categories by SKPD
    public function getKategoriBySkpd($skpd_id)
    {
        $kategoris = Kategori::where('skpd_id', $skpd_id)->get();
        return response()->json($kategoris);
    }
}