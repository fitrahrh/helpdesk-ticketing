<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Kategori;
use App\Models\History;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class TeknisiTicketController extends Controller
{
    /**
     * Display tickets with "Baru" status for technician
     */
    public function indexBaru()
    {
        if (!Auth::user()->hasPermission('akses_teknisi')) {
            // Jika user tidak memiliki hak akses 'dashboard', kembalikan ke halaman sebelumnya
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('layouts.teknisi.ticket.baru');
    }
    
    /**
     * Get data for tickets with "Baru" status for DataTables
     */
    public function baruData()
    {

        if (!Auth::user()->hasPermission('akses_teknisi')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Get categories assigned to this technician
        $kategoriIds = Auth::user()->penanggungjawabs()->pluck('kategori_id');
        
        $tickets = Ticket::with(['user', 'kategori'])
                    ->whereIn('kategori_id', $kategoriIds)
                    ->where('status', 'Baru')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('pelapor', function($row) {
                return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '-';
            })
            ->addColumn('kategori', function($row) {
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('created_at', function($row) {
                return $row->created_at->format('d M Y H:i');
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('teknisi.ticket.show', $row->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    /**
     * Display tickets with "Diproses" status
     */
    public function indexDiproses()
    {
        if (!Auth::user()->hasPermission('akses_teknisi')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('layouts.teknisi.ticket.diproses');
    }
    
    /**
     * Get data for tickets with "Diproses" status for DataTables
     */
    public function diprosesData()
    {
        if (!Auth::user()->hasPermission('akses_teknisi')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        // Get categories assigned to this technician
        $kategoriIds = Auth::user()->penanggungjawabs()->pluck('kategori_id');
        
        $tickets = Ticket::with(['user', 'kategori', 'approvedBy'])
                    ->whereIn('kategori_id', $kategoriIds)
                    ->where('status', 'Diproses')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('pelapor', function($row) {
                return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '-';
            })
            ->addColumn('kategori', function($row) {
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('disetujui_oleh', function($row) {
                return $row->approvedBy ? $row->approvedBy->first_name . ' ' . $row->approvedBy->last_name : '-';
            })
            ->addColumn('approved_at', function($row) {
                return $row->approved_at ? Carbon::parse($row->approved_at)->format('d M Y H:i') : '-';
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('teknisi.ticket.show', $row->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    /**
     * Display tickets with "Selesai" status
     */
    public function indexSelesai()
    {
        if (!Auth::user()->hasPermission('akses_teknisi')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('layouts.teknisi.ticket.selesai');
    }
    
    /**
     * Get data for tickets with "Selesai" status for DataTables
     */
    public function selesaiData()
    {
        if (!Auth::user()->hasPermission('akses_teknisi')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        // Get categories assigned to this technician
        $kategoriIds = Auth::user()->penanggungjawabs()->pluck('kategori_id');
        
        $tickets = Ticket::with(['user', 'kategori', 'closedBy'])
                    ->whereIn('kategori_id', $kategoriIds)
                    ->where('status', 'Selesai')
                    ->orderBy('closed_at', 'desc')
                    ->get();
                    
        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('pelapor', function($row) {
                return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : '-';
            })
            ->addColumn('kategori', function($row) {
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('ditutup_oleh', function($row) {
                return $row->closedBy ? $row->closedBy->first_name . ' ' . $row->closedBy->last_name : '-';
            })
            ->addColumn('closed_at', function($row) {
                return $row->closed_at ? $row->closed_at->format('d M Y H:i') : '-';
            })
            ->addColumn('waktu_penyelesaian', function($row) {
                if ($row->created_at && $row->closed_at) {
                    $startDate = $row->created_at;
                    $endDate = $row->closed_at;
                    $diff = $startDate->diff($endDate);
                    
                    $format = [];
                    if ($diff->d > 0) $format[] = $diff->d . ' hari';
                    if ($diff->h > 0) $format[] = $diff->h . ' jam';
                    if ($diff->i > 0) $format[] = $diff->i . ' menit';
                    
                    return count($format) > 0 ? implode(', ', $format) : 'Kurang dari 1 menit';
                }
                
                return '-';
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('teknisi.ticket.show', $row->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    /**
     * Show ticket details for technician
     */
    public function show($id)
    {
        $user = Auth::user();
        // Cek apakah user memiliki hak akses 'kelola_menu_tiket' (untuk Admin/Superadmin)
        $canManageTickets = $user->hasPermission('kelola_menu_tiket');
        $isTechnician = $user->hasPermission('akses_teknisi');

        // Jika user bukan Admin/Superadmin DAN bukan Teknisi, tolak akses
        if (!$canManageTickets && !$isTechnician) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Cari tiket beserta relasi yang diperlukan
        $ticket = Ticket::with(['user', 'kategori', 'approvedBy', 'closedBy', 'comments.user', 'feedback'])
                        ->findOrFail($id);

        // Jika user adalah Teknisi (dan bukan Admin/Superadmin), cek penugasan kategori
        $isAssignedTechnician = false;
        if ($isTechnician && !$canManageTickets) {
            $isAssignedTechnician = $user->penanggungjawabs()
                                        ->where('kategori_id', $ticket->kategori_id)
                                        ->exists();

            // Jika Teknisi tidak ditugaskan ke kategori ini, tolak akses
            if (!$isAssignedTechnician) {
                return redirect()->back()
                    ->with('error', 'Anda tidak ditugaskan untuk kategori tiket ini.');
            }
        }

        // Jika user adalah Admin/Superadmin ATAU Teknisi yang ditugaskan, lanjutkan
        // Ambil riwayat tiket
        $histories = History::where('ticket_id', $id)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Tampilkan view detail tiket teknisi
        // Catatan: View ini mungkin berisi elemen/aksi spesifik untuk teknisi.
        return view('layouts.teknisi.detail-tiket.index', compact('ticket', 'histories'));
    }
    
    /**
     * Approve ticket and change status to "Diproses"
     */
    public function approve(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('akses_teknisi')) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk melakukan aksi ini.'
            ], 403);
        }
        
        $ticket = Ticket::findOrFail($id);
        
        // Check if technician is responsible for this ticket category
        $isAssigned = Auth::user()->penanggungjawabs()
                            ->where('kategori_id', $ticket->kategori_id)
                            ->exists();
        
        if (!$isAssigned) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak ditugaskan untuk kategori tiket ini.'
            ]);
        }
        
        // Save old values for history
        $oldValues = [
            'status' => $ticket->status
        ];

        // Kirim notifikasi ke Telegram user jika ada telegram_id
        $userTelegramId = $ticket->user->telegram_id;
        if ($userTelegramId) {
            $kategoriName = $ticket->kategori ? $ticket->kategori->name : '-';
            $userMessage = "🔔 *Tiket Anda Diproses Teknisi* 🔔\n\n" .
                " *Detail Tiket*\n" .
                " *No. Tiket*: `$ticket->no_tiket`\n" .
                " *Judul*: `{$ticket->judul}`\n" .
                " *Kategori*: `{$kategoriName}`\n" .
                " *Urgensi*: `{$ticket->urgensi}`\n" .
                " *Status*: `Diproses`\n\n" .
                "🚀 Teknisi kami telah mulai menangani tiket Anda. Mohon menunggu konfirmasi lebih lanjut.\n\n" .
                "📌 Anda dapat memantau perkembangan tiket melalui Telegram.\n\n" .
                "💡 Ketik */help* untuk bantuan atau */test* untuk menguji notifikasi.";

            try {
                \Telegram::sendMessage([
                    'chat_id' => $userTelegramId,
                    'text' => $userMessage,
                    'parse_mode' => 'Markdown'
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal kirim notifikasi Telegram ke user: ' . $e->getMessage());
            }
        }

        // Update ticket
        $ticket->status = 'Diproses';
        $ticket->approved_by = Auth::id();
        $ticket->approved_at = now();
        $ticket->save();
        
        // Create history entry
        History::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'status_changed',
            'old_values' => $oldValues,
            'new_values' => ['status' => 'Diproses'],
            'keterangan' => 'Tiket disetujui dan diproses'
        ]);
        
        // Add comment if provided
        if ($request->filled('komentar')) {
            Comment::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'komentar' => $request->komentar
            ]);
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Tiket berhasil disetujui dan diproses'
        ]);
    }
    
    public function disposisi(Request $request, $id)
    {

        if (!Auth::user()->hasPermission('akses_teknisi')) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk melakukan aksi ini.'
            ], 403);
        }
        
        $ticket = Ticket::findOrFail($id);
        
        // Check if technician is responsible for this ticket category
        $isAssigned = Auth::user()->penanggungjawabs()
                            ->where('kategori_id', $ticket->kategori_id)
                            ->exists();
        
        if (!$isAssigned) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak ditugaskan untuk kategori tiket ini.'
            ]);
        }
        
        // Save old values for history
        $oldValues = [
            'status' => $ticket->status
        ];
        
        // Kirim notifikasi ke Telegram user jika ada telegram_id
        $userTelegramId = $ticket->user->telegram_id;
        if ($userTelegramId) {
            $kategoriName = $ticket->kategori ? $ticket->kategori->name : '-';
            $userMessage = "⚠️ *Tiket Anda Salah Disposisi* ⚠️\n\n" .
                " *Detail Tiket*\n" .
                " *No. Tiket*: `$ticket->no_tiket`\n" .
                " *Judul*: `{$ticket->judul}`\n" .
                " *Kategori*: `{$kategoriName}`\n" .
                " *Urgensi*: `{$ticket->urgensi}`\n" .
                " *Status*: `Salah Disposisi`\n\n" .
                "🔄 Tiket Anda telah salah disposisi dan sedang diarahkan ke admin untuk diperbarui kategori yang benar. Harap menunggu konfirmasi lebih lanjut setelah kategori diperbarui.\n\n" .
                "📌 Anda dapat memantau perkembangan tiket melalui Telegram.\n\n" .
                "💡 Ketik */help* untuk bantuan atau */test* untuk menguji notifikasi.";

            try {
                \Telegram::sendMessage([
                    'chat_id' => $userTelegramId,
                    'text' => $userMessage,
                    'parse_mode' => 'Markdown'
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal kirim notifikasi Telegram ke user: ' . $e->getMessage());
            }
        }

        
        // Update ticket
        $ticket->status = 'Disposisi';
        $ticket->save();
        
        // Create history entry
        History::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'status_changed',
            'old_values' => $oldValues,
            'new_values' => [
                'status' => 'Disposisi'
            ],
            'keterangan' => 'Tiket didisposisi untuk pengalihan kategori'
        ]);
        
        // Jangan perlu menambahkan komentar lagi karena tidak ada alasan
        
        return response()->json([
            'status' => true,
            'message' => 'Tiket berhasil didisposisi dan akan ditinjau oleh admin',
            'redirect_url' => route('teknisi.baru')
        ]);
    }
    
    /**
     * Close ticket and change status to "Selesai"
     */
    public function close(Request $request, $id)
    {
        if (!Auth::user()->hasPermission('akses_teknisi')) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk melakukan aksi ini.'
            ], 403);
        }

        $ticket = Ticket::findOrFail($id);

        // Check if technician is responsible for this ticket category
        $isAssigned = Auth::user()->penanggungjawabs()
                            ->where('kategori_id', $ticket->kategori_id)
                            ->exists();

        if (!$isAssigned) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak ditugaskan untuk kategori tiket ini.'
            ]);
        }

        // Save old values for history
        $oldValues = [
            'status' => $ticket->status
        ];

        // Update ticket
        $ticket->status = 'Selesai';
        $ticket->closed_by = Auth::id();
        $ticket->closed_at = now();
        $ticket->save();

        // Kirim notifikasi ke Telegram user jika ada telegram_id
        $userTelegramId = $ticket->user->telegram_id;
        if ($userTelegramId) {
            $kategoriName = $ticket->kategori ? $ticket->kategori->name : '-';
            $ticketUrl = route('ticket.ticket.show', $ticket->id); // Ganti dengan route yang sesuai untuk user
            $userMessage = "✅ *Tiket Anda Telah Selesai!* ✅\n\n" .
                "*Detail Tiket*\n" .
                " *No. Tiket*: `$ticket->no_tiket`\n" .
                " *Judul*: `{$ticket->judul}`\n" .
                " *Kategori*: `{$kategoriName}`\n" .
                " *Urgensi*: `{$ticket->urgensi}`\n" .
                " *Status*: `Selesai`\n\n" .
                "🎉 Tiket Anda telah ditandai selesai oleh teknisi. Bantu kami meningkatkan layanan dengan memberikan rating untuk penanganan tiket ini di [halaman tiket ini]($ticketUrl).\n\n" .
                "💡 Ketik */help* untuk bantuan atau */test* untuk menguji notifikasi.";

            try {
                \Telegram::sendMessage([
                    'chat_id' => $userTelegramId,
                    'text' => $userMessage,
                    'parse_mode' => 'Markdown'
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal kirim notifikasi Telegram ke user: ' . $e->getMessage());
            }
        }

        // Create history entry
        History::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'status_changed',
            'old_values' => $oldValues,
            'new_values' => ['status' => 'Selesai'],
            'keterangan' => 'Tiket ditandai selesai oleh teknisi'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tiket berhasil ditandai selesai'
        ]);
    }
    
    /**
     * Update ticket urgency
     */
    public function updateUrgency(Request $request, $id)
    {

        if (!Auth::user()->hasPermission('akses_teknisi')) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk melakukan aksi ini.'
            ], 403);
        }

        $request->validate([
            'urgensi' => 'required|in:Rendah,Sedang,Tinggi,Mendesak',
        ]);
        
        $ticket = Ticket::findOrFail($id);
        
        // Check if technician is responsible for this ticket category
        $isAssigned = Auth::user()->penanggungjawabs()
                            ->where('kategori_id', $ticket->kategori_id)
                            ->exists();
        
        if (!$isAssigned) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak ditugaskan untuk kategori tiket ini.'
            ]);
        }
        
        // Save old values for history
        $oldValues = [
            'urgensi' => $ticket->urgensi
        ];
        
        // Update ticket
        $ticket->urgensi = $request->urgensi;
        $ticket->save();
        
        // Kirim notifikasi ke Telegram user jika ada telegram_id
        $userTelegramId = $ticket->user->telegram_id;
        if ($userTelegramId) {
            $kategoriName = $ticket->kategori ? $ticket->kategori->name : '-';
            $userMessage = "🔔 *Urgensi Tiket Anda Diubah* 🔔\n\n" .
                " *Detail Tiket*\n" .
                " *No. Tiket*: `$ticket->no_tiket`\n" .
                " *Judul*: `{$ticket->judul}`\n" .
                " *Kategori*: `{$kategoriName}`\n" .
                " *Urgensi Baru*: `{$request->urgensi}`\n\n" .
                "🚀 Urgensi tiket Anda telah diubah oleh teknisi. Mohon menunggu konfirmasi lebih lanjut.\n\n" .
                "📌 Anda dapat memantau perkembangan tiket melalui Telegram.\n\n" .
                "💡 Ketik */help* untuk bantuan atau */test* untuk menguji notifikasi.";

            try {
                \Telegram::sendMessage([
                    'chat_id' => $userTelegramId,
                    'text' => $userMessage,
                    'parse_mode' => 'Markdown'
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal kirim notifikasi Telegram ke user: ' . $e->getMessage());
            }
        }

        // Create history entry
        History::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'urgensi_changed',
            'old_values' => $oldValues,
            'new_values' => ['urgensi' => $request->urgensi],
            'keterangan' => 'Urgensi tiket diubah'
        ]);
        
        // Add comment if provided
        if ($request->filled('alasan')) {
            Comment::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'komentar' => 'Alasan perubahan urgensi: ' . $request->alasan
            ]);
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Urgensi tiket berhasil diubah'
        ]);
    }

    /** 
     * method checkUpdates
     */
    public function checkUpdates($id)
    {
        $ticket = Ticket::findOrFail($id);
        $lastVisit = session('last_visit_ticket_' . $id, 0);
        
        // Cek apakah ada komentar baru atau perubahan status tiket
        $hasUpdates = $ticket->comments()->where('created_at', '>', date('Y-m-d H:i:s', $lastVisit))->exists()
            || $ticket->updated_at->timestamp > $lastVisit;
        
        // Set last visit time
        session(['last_visit_ticket_' . $id => time()]);
        
        return response()->json([
            'has_updates' => $hasUpdates
        ]);
    }
}