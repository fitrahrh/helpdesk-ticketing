<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Kategori;
use App\Models\Skpd;
use App\Models\User;
use App\Models\History;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display tickets with "Baru" status
     */
    public function indexBaru()
    {
        return view('layouts.admin.ticket.baru.index');
    }

    /**
     * Get data for tickets with "Baru" status for DataTables
     */
    public function dataTicketBaru()
    {
        $tickets = Ticket::with(['user', 'kategori.skpd'])
                    ->where('status', 'Baru')
                    ->get();

        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('pelapor', function($row) {
                return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '-';
            })
            ->addColumn('kategori', function($row) {
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('skpd', function($row) {
                return $row->kategori && $row->kategori->skpd ? $row->kategori->skpd->name : '-';
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('ticket.ticket.show', $row->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Display tickets with "Diproses" status
     */
    public function indexDiproses()
    {
        return view('layouts.admin.ticket.diproses.index');
    }

    /**
     * Get data for tickets with "Diproses" status for DataTables
     */
    public function dataTicketDiproses()
    {
        $tickets = Ticket::with(['user', 'kategori.skpd', 'approvedBy'])
                    ->where('status', 'Diproses')
                    ->get();

        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('pelapor', function($row) {
                return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '-';
            })
            ->addColumn('kategori', function($row) {
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('skpd', function($row) {
                return $row->kategori && $row->kategori->skpd ? $row->kategori->skpd->name : '-';
            })
            ->addColumn('disetujui_oleh', function($row) {
                return $row->approvedBy ? $row->approvedBy->first_name . ' ' . $row->approvedBy->last_name : '-';
            })
            ->addColumn('waktu_disetujui', function($row) {
                return $row->approved_at ? Carbon::parse($row->approved_at)->format('d M Y H:i') : '-';
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('ticket.ticket.show', $row->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Display tickets with "Disposisi" status
     */
    public function indexDisposisi()
    {
        $skpds = Skpd::with('kategoris')->get(); // Fetch SKPDs with their categories
        return view('layouts.admin.ticket.disposisi.index', compact('skpds'));
    }

    /**
     * Get data for tickets with "Disposisi" status for DataTables
     */
    public function dataTicketDisposisi()
    {
        $tickets = Ticket::with(['user', 'kategori.skpd'])
                    ->where('status', 'Disposisi')
                    ->get();

        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('pelapor', function($row) {
                return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '-';
            })
            ->addColumn('kategori', function($row) {
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('skpd', function($row) {
                return $row->kategori && $row->kategori->skpd ? $row->kategori->skpd->name : '-';
            })
            ->addColumn('disposisi_oleh', function($row) {
                // Get last history entry with status 'disposisi'
                $history = History::where('ticket_id', $row->id)
                            ->where('status', 'status_changed')
                            ->whereJsonContains('new_values->status', 'Disposisi')
                            ->with('user')
                            ->latest()
                            ->first();
                            
                return $history && $history->user ? $history->user->first_name . ' ' . $history->user->last_name : '-';
            })
            ->addColumn('action', function($row) {
                return '<button type="button" class="btn btn-sm btn-warning btn-disposisi" data-id="'.$row->id.'">Disposisi</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Update ticket kategori and status after disposisi
     */
    public function updateDisposisi(Request $request, $id)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $ticket = Ticket::findOrFail($id);
        $oldKategori = $ticket->kategori ? $ticket->kategori->name : '-';
        $newKategori = Kategori::find($request->kategori_id)->name;
        
        // Store old values for history
        $oldValues = [
            'kategori' => $oldKategori,
            'status' => $ticket->status
        ];
        
        // Update ticket with new kategori and set status back to Baru
        $ticket->kategori_id = $request->kategori_id;
        $ticket->status = 'Baru';
        $ticket->save();
        
        // Record history
        History::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'kategori changed', // Spasi daripada underscore
            'old_values' => $oldValues,
            'new_values' => [
                'kategori' => $newKategori, // Nama kategori bukan ID
                'status' => 'Baru'
            ],
            'keterangan' => 'Tiket didisposisi ulang ke kategori '.$newKategori
        ]);
        
        return response()->json([
            'status' => true, 
            'message' => 'Tiket berhasil didisposisi'
        ]);
    }

    /**
     * Display tickets with "Selesai" status
     */
    public function indexSelesai()
    {
        return view('layouts.admin.ticket.selesai.index');
    }

    /**
     * Get data for tickets with "Selesai" status for DataTables
     */
    public function dataTicketSelesai()
    {
        $tickets = Ticket::with(['user', 'kategori.skpd', 'closedBy'])
                    ->where('status', 'Selesai')
                    ->get();

        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('pelapor', function($row) {
                return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '-';
            })
            ->addColumn('kategori', function($row) {
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('skpd', function($row) {
                return $row->kategori && $row->kategori->skpd ? $row->kategori->skpd->name : '-';
            })
            ->addColumn('ditutup_oleh', function($row) {
                return $row->closedBy ? $row->closedBy->first_name . ' ' . $row->closedBy->last_name : '-';
            })
            ->addColumn('waktu_penyelesaian', function($row) {
                if ($row->created_at && $row->closed_at) {
                    $createdAt = Carbon::parse($row->created_at);
                    $closedAt = Carbon::parse($row->closed_at);
                    $diffInHours = $createdAt->diffInHours($closedAt);
                    return $diffInHours . ' jam';
                }
                return '-';
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('ticket.ticket.show', $row->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    /**
     * Display ticket details
     */
    public function show($id)
    {
        $ticket = Ticket::with(['user', 'kategori.skpd', 'approvedBy', 'assignedTo', 'closedBy', 'comments.user'])
                    ->findOrFail($id);
        
        // Get ticket history
        $histories = History::where('ticket_id', $id)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('layouts.admin.ticket.show', compact('ticket', 'histories'));
    }
}