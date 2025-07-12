<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Skpd;
use App\Models\Bidang;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
public function index()
{
    $roles = Role::all();
    $skpds = Skpd::all();
    $bidangs = Bidang::with('skpd')->get();
    \Log::info('Bidang data:', $bidangs->toArray()); // Debugging
    return view('layouts.admin.users.index', compact('roles', 'skpds', 'bidangs'));
}

    public function datausers(Request $request)
    {
        $data = User::with(['role', 'jabatan.bidang.skpd'])->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->first_name . ' ' . $row->last_name;
            })
            ->addColumn('jabatan', function($row) {
                return $row->jabatan ? $row->jabatan->name : '-';
            })
            ->addColumn('bidang', function($row) {
                return $row->jabatan && $row->jabatan->bidang ? $row->jabatan->bidang->name : '-';
            })
            ->addColumn('skpd', function($row) {
                return $row->jabatan && $row->jabatan->bidang && $row->jabatan->bidang->skpd ? $row->jabatan->bidang->skpd->name : '-';
            })
            ->addColumn('action', function($row){
                return '<button type="button" data-id="'.$row->id.'" class="btn btn-sm btn-warning btn-edit"><i class="fas fa-edit"></i></button> 
                        <button type="button" onclick="deleteUser(\''.$row->id.'\')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

public function store(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'role_id' => 'required|exists:roles,id',
        'jabatan_id' => 'nullable|exists:jabatans,id', // Tambahkan validasi untuk jabatan_id
        'nip' => 'nullable|string|max:255',
        'telegram_id' => 'nullable|string|max:255',
        'no_hp' => 'nullable|string|max:20', // Jika ada field ini
    ]);

    try {
        $userData = $request->all();
        $userData['password'] = Hash::make($request->password);
        
        User::create($userData);
        
        return response()->json(['status' => true, 'message' => 'User berhasil disimpan']);
    } catch (\Exception $e) {
        \Log::error('Error creating user: ' . $e->getMessage());
        return response()->json(['status' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
    }
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
        try {
            $user = User::findOrFail($id);
            $user->delete();

            // Return a JSON response
            return response()->json(['status' => true, 'message' => 'User berhasil dihapus'], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error deleting user: ' . $e->getMessage());

            // Return a JSON error response
            return response()->json(['status' => false, 'message' => 'Gagal menghapus user: ' . $e->getMessage()], 500);
        }
    }


    public function getBidangBySkpd($skpd_id)
    {
        $bidangs = Bidang::where('skpd_id', $skpd_id)->get();
        \Log::info("Fetching bidang for SKPD ID: {$skpd_id}. Found: {$bidangs->count()}");
        \Log::info('Bidang data:', $bidangs->toArray());
        return response()->json($bidangs);
    }

    public function getJabatanByBidang($bidang_id)
    {
        $jabatans = Jabatan::where('bidang_id', $bidang_id)->get();
        \Log::info('Jabatan data:', $jabatans->toArray()); // Log untuk debugging
        return response()->json($jabatans);
    }

    public function getJabatanInfo($jabatan_id)
    {
        $jabatan = Jabatan::with('bidang.skpd')->findOrFail($jabatan_id);
        return response()->json($jabatan);
    }

}