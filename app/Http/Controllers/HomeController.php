<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini di-import
use App\Models\Ticket; // Pastikan ini di-import jika digunakan di method index
use App\Models\Kategori; // Pastikan ini di-import jika digunakan di method index
use App\Models\Comment; // Pastikan ini di-import jika digunakan di method index
use App\Models\Feedback; // Pastikan ini di-import jika digunakan di method index

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // Tambahkan pengecekan hak akses 'dashboard' di sini
        if (!Auth::user()->hasPermission('dashboard')) {
            // Jika user tidak memiliki hak akses 'dashboard', kembalikan ke halaman sebelumnya
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // ... existing code ...
        // Tambahkan kode untuk mengambil data dashboard di sini
        $totalTickets = Ticket::count();
        $pendingCount = Ticket::where('status', 'pending')->count();
        $diprosesCount = Ticket::where('status', 'diproses')->count();
        $disposisiCount = Ticket::where('status', 'disposisi')->count();
        $selesaiCount = Ticket::where('status', 'selesai')->count();

        $topCategories = Kategori::withCount('tickets')
            ->orderByDesc('tickets_count')
            ->limit(5)
            ->get()
            ->map(function($kategori) {
                return [
                    'name' => $kategori->name,
                    'count' => $kategori->tickets_count
                ];
            });

        $urgencyCounts = [
            Ticket::where('urgensi', 'Rendah')->count(),
            Ticket::where('urgensi', 'Sedang')->count(),
            Ticket::where('urgensi', 'Tinggi')->count(),
            Ticket::where('urgensi', 'Mendesak')->count(),
        ];


        return view('home', compact(
            'totalTickets',
            'pendingCount',
            'diprosesCount',
            'disposisiCount',
            'selesaiCount',
            'topCategories',
            'urgencyCounts'
        ));
    }

    public function blank()
    {
        return view('layouts.blank-page');
    }
}