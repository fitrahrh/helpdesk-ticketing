<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Kategori;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function harian(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $status = $request->status;
        $kategori_id = $request->kategori_id;
        
        $query = Ticket::with('kategori')
                ->whereDate('created_at', $tanggal);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($kategori_id) {
            $query->where('kategori_id', $kategori_id);
        }
        
        $tickets = $query->get();
        $kategoris = Kategori::all();
        
        return view('layouts.admin.laporan.harian.index', compact('tickets', 'kategoris'));
    }
    
    public function bulanan(Request $request)
    {
        $bulan = $request->bulan ?? date('n');
        $tahun = $request->tahun ?? date('Y');
        $status = $request->status;
        $kategori_id = $request->kategori_id;
        
        $query = Ticket::with('kategori')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($kategori_id) {
            $query->where('kategori_id', $kategori_id);
        }
        
        $tickets = $query->get();
        $kategoris = Kategori::all();
        
        return view('layouts.admin.laporan.bulanan.index', compact('tickets', 'kategoris'));
    }
    
    public function berjangka(Request $request)
    {
        $tanggal_mulai = $request->tanggal_mulai ?? date('Y-m-01');
        $tanggal_akhir = $request->tanggal_akhir ?? date('Y-m-t');
        $status = $request->status;
        $kategori_id = $request->kategori_id;
        
        $query = Ticket::with('kategori')
                ->whereBetween('created_at', [
                    Carbon::parse($tanggal_mulai)->startOfDay(),
                    Carbon::parse($tanggal_akhir)->endOfDay()
                ]);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($kategori_id) {
            $query->where('kategori_id', $kategori_id);
        }
        
        $tickets = $query->get();
        $kategoris = Kategori::all();
        
        return view('layouts.admin.laporan.berjangka.index', compact('tickets', 'kategoris'));
    }
}