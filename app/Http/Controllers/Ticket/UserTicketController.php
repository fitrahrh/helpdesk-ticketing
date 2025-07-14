<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Kategori;
use App\Models\History;
use App\Models\Skpd;
use App\Models\User;
use App\Models\Penanggungjawab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserTicketController extends Controller
{
    /**
     * Display the user home with ticket statistics
     */
public function index()
{
    if (!Auth::user()->hasPermission('akses_pelapor')) {
        // Jika user tidak memiliki hak akses 'akses_pelapor', kembalikan ke halaman sebelumnya
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
    $userId = Auth::id();

    // Fetch only SKPDs that have categories
    $skpds = Skpd::whereHas('kategoris')->with('kategoris')->get();

    // Get counts for each ticket status for the current user
    $pendingCount = Ticket::where('user_id', $userId)->where('status', 'Baru')->count();
    $diprosesCount = Ticket::where('user_id', $userId)->where('status', 'Diproses')->count();
    $disposisiCount = Ticket::where('user_id', $userId)->where('status', 'Disposisi')->count();
    $selesaiCount = Ticket::where('user_id', $userId)->where('status', 'Selesai')->count();

    // Get recent tickets for the current user
    $recentTickets = Ticket::where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    return view('layouts.user.index', compact(
        'pendingCount',
        'diprosesCount',
        'disposisiCount',
        'selesaiCount',
        'recentTickets',
        'skpds'
    ));
}
    
    /**
     * Display form to create a new ticket
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('user.ticket.create', compact('kategoris'));
    }
    
    /**
     * Store a new ticket
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'urgensi' => 'required|in:Rendah,Sedang,Tinggi,Mendesak',
            'masalah' => 'required|string',
            'lampiran' => 'nullable|file|max:10240', // Max 10MB
        ]);
        
        // Generate ticket number
        $ticketNumber = 'TIK-' . strtoupper(Str::random(6));
        
        // Handle file upload if present
        $lampiran = null;
        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran')->store('lampiran/ticket', 'public');
        }
        
        // Get kategori name for human-readable history
        $kategori = Kategori::find($request->kategori_id);
        $kategoriName = $kategori ? $kategori->name : 'Tidak diketahui';
        
        // Kirim notifikasi ke Telegram user jika ada telegram_id
        $userTelegramId = Auth::user()->telegram_id;
        if ($userTelegramId) {
            $userMessage = "🔔 *Tiket Berhasil Dibuat!* 🔔\n\n" .
                " *No. Tiket*:  `$ticketNumber`\n" .
                " *Judul*:  `{$request->judul}`\n" .
                " *Kategori*:  `{$kategoriName}`\n" .
                " *Urgensi*:  `{$request->urgensi}`\n" .
                " *Status*:  `Baru`\n\n" .
                "✅ *Tiket Anda telah berhasil dibuat dan akan segera diproses oleh tim kami.*\n\n" .
                "📌 *Pantau perkembangan tiket melalui Telegram!* \n" .
                "Ketik */help* untuk bantuan atau */test* untuk menguji notifikasi.";

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

        // Create the ticket
        $ticket = Ticket::create([
            'no_tiket' => $ticketNumber,
            'judul' => $request->judul,
            'masalah' => $request->masalah,
            'kategori_id' => $request->kategori_id,
            'urgensi' => $request->urgensi,
            'status' => 'Baru',
            'user_id' => Auth::id(),
            'lampiran' => $lampiran,
        ]);
        
        // Kirim notifikasi ke teknisi yang bertanggung jawab
        $teknisiIds = Penanggungjawab::where('kategori_id', $ticket->kategori_id)->pluck('user_id');
        $teknisiList = User::whereIn('id', $teknisiIds)->whereNotNull('telegram_id')->get();

        foreach ($teknisiList as $teknisi) {
            $kategoriName = $kategoriName ?? ($ticket->kategori ? $ticket->kategori->name : '-');
            $ticketUrl = route('teknisi.ticket.show', $ticket->id); // Ganti dengan route detail teknisi
            $teknisiMessage = "👨🏻‍🔧 *Tugas Baru Tiket Helpdesk* 👨🏻‍🔧\n\n" .
                " *No. Tiket*: `$ticket->no_tiket`\n" .
                " *Judul*: `{$ticket->judul}`\n" .
                " *Kategori*: `{$kategoriName}`\n" .
                " *Urgensi*: `{$ticket->urgensi}`\n\n" .
                "Klik [detail tiket]($ticketUrl) untuk mulai menangani tiket ini.";

            try {
                \Telegram::sendMessage([
                    'chat_id' => $teknisi->telegram_id,
                    'text' => $teknisiMessage,
                    'parse_mode' => 'Markdown'
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal kirim notifikasi Telegram ke teknisi: ' . $e->getMessage());
            }
        }

        // Create history entry with human-readable values
        History::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'tiket dibuat', // Spasi daripada underscore
            'old_values' => null,
            'new_values' => [
                'status' => 'Baru',
                'kategori' => $kategoriName, // Nama kategori, bukan ID
                'urgensi' => $request->urgensi
            ],
            'keterangan' => 'Tiket baru dibuat'
        ]);
        
        return redirect()
            ->route('ticket.ticket.show', $ticket->id)
            ->with('success', 'Tiket berhasil dibuat.');
    }
    
    /**
     * Show ticket details
     */
    public function show($id)
    {
        $ticket = Ticket::with(['user', 'kategori.skpd', 'approvedBy', 'closedBy'])->findOrFail($id);
        
        // Make sure users can only view their own tickets
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $histories = History::with('user')
                    ->where('ticket_id', $id)
                    ->orderBy('created_at', 'asc')
                    ->get();
        
        return view('layouts.user.detail-tiket.index', compact('ticket', 'histories'));
    }
    
    /**
     * Add a comment to a ticket
     */
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string'
        ]);
        
        $ticket = Ticket::findOrFail($id);
        
        // Make sure users can only comment on their own tickets
        if ($ticket->user_id !== Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }
        
        // Create history entry for the comment
        History::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => 'comment',
            'keterangan' => $request->komentar
        ]);
        
        return response()->json([
            'status' => true,
            'message' => 'Komentar berhasil ditambahkan'
        ]);
    }
    
    /**
     * Display tickets with "Baru" status for the authenticated user
     */
    public function indexPending()
    {
        return view('layouts.user.ticket.pending');
    }
    
    /**
     * Get data for tickets with "Baru" status for DataTables
     */
    public function pendingData()
    {
        $tickets = Ticket::with(['kategori'])
                    ->where('user_id', Auth::id())
                    ->where('status', 'Baru')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('kategori', function($row) {
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('created_at', function($row) {
                return $row->created_at->format('d M Y H:i');
            })
            ->make(true);
    }
    
    /**
     * Display tickets with "Diproses" status for the authenticated user
     */
    public function indexDiproses()
    {
        return view('layouts.user.ticket.diproses');
    }
    
    /**
     * Get data for tickets with "Diproses" status for DataTables
     */
    public function diprosesData()
    {
        $tickets = Ticket::with(['kategori', 'approvedBy'])
                    ->where('user_id', Auth::id())
                    ->where('status', 'Diproses')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('kategori', function($row) {
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('disetujui_oleh', function($row) {
                return $row->approvedBy ? $row->approvedBy->first_name . ' ' . $row->approvedBy->last_name : '-';
            })
            ->addColumn('approved_at', function($row) {
                $history = History::where('ticket_id', $row->id)
                            ->where('status', 'status_changed')
                            ->whereJsonContains('new_values->status', 'Diproses')
                            ->first();
                            
                return $history ? $history->created_at->format('d M Y H:i') : '-';
            })
            ->make(true);
    }
    
    /**
     * Display tickets with "Disposisi" status for the authenticated user
     */
    public function indexDisposisi()
    {
        return view('layouts.user.ticket.disposisi');
    }
    
    /**
     * Get data for tickets with "Disposisi" status for DataTables
     */
    public function disposisiData()
    {
        $tickets = Ticket::with(['kategori'])
                    ->where('user_id', Auth::id())
                    ->where('status', 'Disposisi')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('kategori', function($row) {
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('updated_at', function($row) {
                $history = History::where('ticket_id', $row->id)
                            ->where('status', 'status_changed')
                            ->whereJsonContains('new_values->status', 'Disposisi')
                            ->first();
                            
                return $history ? $history->created_at->format('d M Y H:i') : $row->updated_at->format('d M Y H:i');
            })
            ->make(true);
    }
    
    /**
     * Display tickets with "Selesai" status for the authenticated user
     */
    public function indexSelesai()
    {
        return view('layouts.user.ticket.selesai');
    }
    
    /**
     * Get data for tickets with "Selesai" status for DataTables
     */
    public function selesaiData()
    {
        $tickets = Ticket::with(['kategori', 'closedBy'])
                    ->where('user_id', Auth::id())
                    ->where('status', 'Selesai')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('kategori', function($row) {
                return $row->kategori ? $row->kategori->name : '-';
            })
            ->addColumn('ditutup_oleh', function($row) {
                return $row->closedBy ? $row->closedBy->first_name . ' ' . $row->closedBy->last_name : '-';
            })
            ->addColumn('closed_at', function($row) {
                // Format the closed_at date properly
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
            ->make(true);
    }
    
    /**
     * Update an existing ticket
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'masalah' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);

        // Ensure the user can only update their own tickets
        if ($ticket->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $ticket->update([
            'judul' => $request->judul,
            'masalah' => $request->masalah,
        ]);

        return response()->json(['message' => 'Tiket berhasil diperbarui']);
    }
    /**
     * Close a ticket
     */
    public function close(Request $request, $id)
    {

        $ticket = Ticket::findOrFail($id);

        // Ensure the user can only close their own tickets
        if ($ticket->user_id !== Auth::id()) {
            return response()->json([
                'status' => false, // Tambahkan status false untuk unauthorized
                'message' => 'Unauthorized'
            ], 403);
        }

        // Prevent closing if already closed
        if ($ticket->status === 'Selesai') {
             return response()->json([
                'status' => false, // Tambahkan status false jika sudah selesai
                'message' => 'Tiket ini sudah selesai.'
            ]);
        }

        // Update ticket status to 'Selesai'
        $ticket->update([
            'status' => 'Selesai',
            'closed_at' => Carbon::now(),
            'closed_by' => Auth::id(),
        ]);
        // Get old values for history
        $oldValues = [
            'status' => $ticket->status // Ambil status sebelum diupdate
        ];

        // Send Telegram notification to user
        $userTelegramId = Auth::user()->telegram_id;
        if ($userTelegramId) {
            $userMessage = "🔔 *Tiket Anda Telah Ditutup!* 🔔\n\n" .
                " *No. Tiket*:  `{$ticket->no_tiket}`\n" .
                " *Judul*:  `{$ticket->judul}`\n" .
                " *Kategori*:  `{$ticket->kategori->name}`\n" .
                " *Urgensi*:  `{$ticket->urgensi}`\n" .
                " *Status*:  `Selesai`\n\n" .
                "✅ *Tiket Anda telah berhasil ditutup. Terima kasih atas partisipasi Anda!*";

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
            'keterangan' => 'Tiket ditutup oleh pengguna'
        ]);

        // Ubah respons untuk menyertakan status: true
        return response()->json([
            'status' => true,
            'message' => 'Tiket berhasil ditutup'
        ]);
    }
}