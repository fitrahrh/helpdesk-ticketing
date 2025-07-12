<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\Kategori;
use App\Models\Comment;
use App\Models\Feedback;

class TicketController extends Controller
{
    private function getTicketsByStatus($status)
    {
        return Ticket::where('user_id', Auth::id())
                     ->where('status', $status)
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
    }

    public function indexPending()
    {
        $tickets = $this->getTicketsByStatus('pending');
        return view('layouts.user.ticket.pending', compact('tickets'));
    }

    public function indexDiproses()
    {
        $tickets = $this->getTicketsByStatus('diproses');
        return view('layouts.user.ticket.diproses', compact('tickets'));
    }

    public function indexDisposisi()
    {
        $tickets = $this->getTicketsByStatus('disposisi');
        return view('layouts.user.ticket.disposisi', compact('tickets'));
    }

    public function indexSelesai()
    {
        $tickets = $this->getTicketsByStatus('selesai');
        return view('layouts.user.ticket.selesai', compact('tickets'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('layouts.user.ticket.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'required|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('lampiran_tiket', 'public');
        }

        Ticket::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
            'prioritas' => 'medium', // Default priority
            'lampiran' => $lampiranPath,
        ]);

        return redirect()->route('user.ticket.pending')->with('success', 'Tiket berhasil dibuat.');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['user', 'kategori', 'comments.user', 'feedback'])->findOrFail($id);

        // Ensure the user is authorized to see the ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        return view('layouts.user.detail.index', compact('ticket'));
    }
}
