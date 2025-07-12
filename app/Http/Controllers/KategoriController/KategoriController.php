<?php

namespace App\Http\Controllers\KategoriController;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\SKPD;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index()
    {
        $skpds = SKPD::all();
        return view('layouts.admin.kategori.index', compact('skpds'));
    }

    public function datakategori(Request $request)
    {
        $data = Kategori::with('skpd')->get();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('skpd_name', function($row){
                return $row->skpd ? $row->skpd->name : '-';
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" class="btn btn-sm btn-warning btn-edit" data-id="'.$row->id.'"><i class="fas fa-edit"></i></button> ';
                $actionBtn .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteKategori(\''.$row->id.'\')"><i class="fas fa-trash"></i></button>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'skpd_id' => 'required|exists:skpds,id'
        ]);

        if (Kategori::create($request->all())) {
            $response = ['status' => TRUE, 'message' => 'Kategori berhasil disimpan'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Kategori gagal disimpan']);
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return response()->json($kategori);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'skpd_id' => 'required|exists:skpds,id'
        ]);

        $kategori = Kategori::findOrFail($id);
        
        if ($kategori->update($request->all())) {
            $response = ['status' => TRUE, 'message' => 'Kategori berhasil diperbarui'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Kategori gagal diperbarui']);
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        
        if ($kategori->delete()) {
            $response = ['status' => TRUE, 'message' => 'Kategori berhasil dihapus'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Kategori gagal dihapus']);
    }
}