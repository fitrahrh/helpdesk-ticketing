<?php

namespace App\Http\Controllers\Skpds;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skpd;
use Yajra\DataTables\Facades\DataTables;

class SKPDController extends Controller
{
    public function index()
    {
        return view('layouts.admin.skpd.index');
    }

    public function dataskpd(Request $request)
    {
        $data = SKPD::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                return '<button type="button" data-id="'.$row->id.'" class="btn btn-sm btn-warning btn-edit"><i class="fas fa-edit"></i></button>
                      <button type="button" onclick="deleteSKPD('.$row->id.')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'singkatan' => 'required',
        ]);

        if (SKPD::create($request->all())) {
            $response = ['status' => TRUE, 'message' => 'Data berhasil disimpan'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Data gagal disimpan']);
    }

    public function edit($id)
    {
        $skpd = SKPD::findOrFail($id);
        return response()->json($skpd);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'singkatan' => 'required|string|max:50',
        ]);

        $skpd = SKPD::findOrFail($id);
        
        if ($skpd->update($request->all())) {
            $response = ['status' => TRUE, 'message' => 'Data berhasil diperbarui'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Data gagal diperbarui']);
    }

    public function destroy($id)
    {
        $skpd = SKPD::findOrFail($id);
        if ($skpd->delete()) {
            $response = ['status' => TRUE, 'message' => 'Data berhasil dihapus'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Data gagal dihapus']);
    }
}