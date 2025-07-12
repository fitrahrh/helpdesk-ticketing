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
    public function data()
    {
        $histories = History::with(['ticket', 'user'])->get();
        
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