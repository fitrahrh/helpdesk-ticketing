<?php

namespace App\Http\Controllers\Penanggungjawabs;

use App\Http\Controllers\Controller;
use App\Models\Penanggungjawab;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PenanggungjawabController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role_id', [3])->get(); // Assume role_id 3 is for Teknisi
        $kategoris = Kategori::all();
        return view('layouts.admin.penanggungjawab.index', compact('users', 'kategoris'));
    }

    public function datapenanggungjawab(Request $request)
    {
        $data = Penanggungjawab::with(['user', 'kategori.skpd'])->get();
        
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
}