<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Ticket;
use Auth;

class TeknisiCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:ticket,id', // Perbaikan: menggunakan "ticket" (singular)
            'pesan' => 'required',
            'lampiran.*' => 'nullable|file|max:5120', // 5MB max
        ]);
        
        $ticket = Ticket::findOrFail($request->ticket_id);
        
        // Verifikasi bahwa teknisi bertanggung jawab untuk kategori tiket ini
        $isAssigned = Auth::user()->penanggungjawabs()
                         ->where('kategori_id', $ticket->kategori_id)
                         ->exists();
        
        if (!$isAssigned) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak ditugaskan untuk kategori tiket ini'
            ], 403);
        }

        // Upload lampiran jika ada
        $lampiranPaths = [];
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $lampiranPaths[] = $file->store('comments', 'public');
            }
        }
        
        // Buat komentar
        $comment = Comment::create([
            'ticket_id' => $request->ticket_id,
            'user_id' => Auth::id(),
            'pesan' => $request->pesan,
            'lampiran' => !empty($lampiranPaths) ? $lampiranPaths : null,
        ]);
        
        // Update waktu last_comment_at di tiket
        $ticket->last_comment_at = now();
        $ticket->save();
        
        return response()->json([
            'status' => true,
            'message' => 'Komentar berhasil ditambahkan'
        ]);
    }
    
    // Method untuk menandai komentar sebagai dibaca
    public function markAsRead(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array'
        ]);
        
        $user = Auth::user();
        
        foreach($request->comment_ids as $commentId) {
            $comment = Comment::find($commentId);
            if ($comment && !$comment->readBy->contains($user->id)) {
                $comment->readBy()->attach($user->id);
            }
        }
        
        return response()->json([
            'status' => true
        ]);
    }
}