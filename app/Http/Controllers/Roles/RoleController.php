<?php
namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        return view('layouts.admin.roles.index');
    }

public function dataroles(Request $request)
{
    $data = Role::all();
    return DataTables::of($data)
        ->addIndexColumn() // Menambahkan kolom index/nomor
        ->addColumn('action', function($row){
            return '<button type="button" data-id="'.$row->id.'" class="btn btn-sm btn-warning btn-edit"><i class="fas fa-edit"></i></button>
                   <button type="button" onclick="deleteRole('.$row->id.')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
        })
        ->rawColumns(['action'])
        ->make(true);
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'hak_akses' => 'required',
        ]);

        if (Role::create($request->all())) {
            $response = ['status' => TRUE, 'message' => 'Data berhasil disimpan'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Data gagal disimpan']);
    }
public function edit($id)
{
    $role = Role::findOrFail($id);
    
    // Ensure hak_akses is properly formatted
    if ($role->hak_akses === null) {
        $role->hak_akses = [];
    }
    
    return response()->json($role);
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'hak_akses' => 'required',
    ]);

    $role = Role::findOrFail($id);
    if ($role->update($request->all())) {
        $response = ['status' => TRUE, 'message' => 'Data berhasil diperbarui'];
    }
    return response()->json($response ?? ['status' => FALSE, 'message' => 'Data gagal diperbarui']);
}

public function destroy($id)
{
    $role = Role::findOrFail($id);
    if ($role->delete()) {
        $response = ['status' => TRUE, 'message' => 'Data berhasil dihapus'];
    }
    return response()->json($response ?? ['status' => FALSE, 'message' => 'Data gagal dihapus']);
}
}