<?php

namespace App\Http\Controllers\History;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HistoryController extends Controller
{
    /**
     * Display history index
     */
    public function index()
    {
        return view('layouts.admin.history.index');
    }

    /**
     * Get history data for DataTables
     */

public function data(Request $request)
{
    $query = History::with(['ticket', 'user']);

    if ($request->no_tiket) {
        $query->whereHas('ticket', function($q) use ($request) {
            $q->where('no_tiket', 'like', '%' . $request->no_tiket . '%');
        });
    }
    if ($request->pengguna) {
        $query->whereHas('user', function($q) use ($request) {
            $q->where('first_name', 'like', '%' . $request->pengguna . '%')
              ->orWhere('last_name', 'like', '%' . $request->pengguna . '%');
        });
    }
    if ($request->status) {
        $query->where('status', $request->status);
    }
    if ($request->tanggal) {
        $query->whereDate('created_at', $request->tanggal);
    }

    $histories = $query->get();

    return DataTables::of($histories)
        ->addIndexColumn()
        ->addColumn('no_tiket', function($row) {
            return $row->ticket ? $row->ticket->no_tiket : '-';
        })
        ->addColumn('pelapor', function($row) {
            return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '-';
        })
        ->make(true);
}
}