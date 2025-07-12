<?php

namespace App\Http\Controllers\Bidangs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Models\SKPD;
use Yajra\DataTables\Facades\DataTables;

class BidangController extends Controller
{
    public function index()
    {
        $skpds = SKPD::all();
        return view('layouts.admin.bidang.index', compact('skpds'));
    }

    public function databidang(Request $request)
    {
        $data = Bidang::with('skpd')->get();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('skpd_name', function($row){
                return $row->skpd ? $row->skpd->name : '-';
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" class="btn btn-sm btn-warning btn-edit" data-id="'.$row->id.'"><i class="fas fa-edit"></i></button> ';
                $actionBtn .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteBidang(\''.$row->id.'\')"><i class="fas fa-trash"></i></button>';
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

        if (Bidang::create($request->all())) {
            $response = ['status' => TRUE, 'message' => 'Bidang berhasil disimpan'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Bidang gagal disimpan']);
    }

    public function edit($id)
    {
        $bidang = Bidang::findOrFail($id);
        return response()->json($bidang);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'skpd_id' => 'required|exists:skpds,id'
        ]);

        $bidang = Bidang::findOrFail($id);
        
        if ($bidang->update($request->all())) {
            $response = ['status' => TRUE, 'message' => 'Bidang berhasil diperbarui'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Bidang gagal diperbarui']);
    }

    public function destroy($id)
    {
        $bidang = Bidang::findOrFail($id);
        
        if ($bidang->delete()) {
            $response = ['status' => TRUE, 'message' => 'Bidang berhasil dihapus'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Bidang gagal dihapus']);
    }
}