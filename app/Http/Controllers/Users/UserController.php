<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('layouts.admin.users.index', compact('roles'));
    }

    public function datausers(Request $request)
    {
        $data = User::with('role')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->first_name . ' ' . $row->last_name;
            })
            ->addColumn('action', function($row){
                return '<button type="button" data-id="'.$row->id.'" class="btn btn-sm btn-warning btn-edit"><i class="fas fa-edit"></i></button>
                      <button type="button" onclick="deleteUser('.$row->id.')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role_id' => 'required',
        ]);

        $userData = $request->all();
        $userData['password'] = Hash::make($request->password);

        if (User::create($userData)) {
            $response = ['status' => TRUE, 'message' => 'Data berhasil disimpan'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Data gagal disimpan']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role_id' => 'required',
        ]);

        $user = User::findOrFail($id);
        $userData = $request->all();
        
        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        } else {
            unset($userData['password']);
        }
        
        if ($user->update($userData)) {
            $response = ['status' => TRUE, 'message' => 'Data berhasil diperbarui'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Data gagal diperbarui']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->delete()) {
            $response = ['status' => TRUE, 'message' => 'Data berhasil dihapus'];
        }
        return response()->json($response ?? ['status' => FALSE, 'message' => 'Data gagal dihapus']);
    }
}