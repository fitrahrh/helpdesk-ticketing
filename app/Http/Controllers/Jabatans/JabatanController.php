<?php

namespace App\Http\Controllers\Jabatans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\SKPD;
use Yajra\DataTables\Facades\DataTables;

class JabatanController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::with('skpd')->get();
        return view('layouts.admin.jabatan.index', compact('bidangs'));
    }

    public function datajabatan(Request $request)
    {
        $data = Jabatan::with('bidang.skpd')->get();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('bidang_name', function($row){
                return $row->bidang ? $row->bidang->name : '-';
            })
            ->addColumn('skpd_name', function($row){
                return $row->bidang && $row->bidang->skpd ? $row->bidang->skpd->name : '-';
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" class="btn btn-sm btn-warning btn-edit" data-id="'.$row->id.'"><i class="fas fa-edit"></i></button> ';
                $actionBtn .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteJabatan(\''.$row->id.'\')"><i class="fas fa-trash"></i></button>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bidang_id' => 'required|exists:bidangs,id'
        ]);

        if (Jabatan::create($request->all())) {
            $response = ['status' => TRUE, 'message' => 'Jabatan berhasil disimpan'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Jabatan gagal disimpan']);
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return response()->json($jabatan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bidang_id' => 'required|exists:bidangs,id'
        ]);

        $jabatan = Jabatan::findOrFail($id);
        
        if ($jabatan->update($request->all())) {
            $response = ['status' => TRUE, 'message' => 'Jabatan berhasil diperbarui'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Jabatan gagal diperbarui']);
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        
        if ($jabatan->delete()) {
            $response = ['status' => TRUE, 'message' => 'Jabatan berhasil dihapus'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Jabatan gagal dihapus']);
    }
}