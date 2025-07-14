<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentRead;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * Store a new comment
     */
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:ticket,id',
            'pesan' => 'required|string',
            'lampiran.*' => 'nullable|file|max:5120' // 5MB limit per file
        ]);
        
        $ticket = Ticket::findOrFail($request->ticket_id);
        $user = Auth::user();
        
        // Check authorization based on user role
        if ($user->role_id == 3) { // Role teknisi
            // Verify that technician is responsible for this ticket category
            $isAssigned = $user->penanggungjawabs()
                         ->where('kategori_id', $ticket->kategori_id)
                         ->exists();
            
            if (!$isAssigned) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak ditugaskan untuk kategori tiket ini'
                ], 403);
            }
        } else { // Role user biasa
            // Check if the user is the ticket owner
            if ($ticket->user_id != $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak diizinkan menambahkan komentar pada tiket ini'
                ], 403);
            }
        }
        
        // Handle file uploads
        $lampiranPaths = [];
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $lampiranPaths[] = $file->store('comments', 'public');
            }
        }
        
        // Create the comment
        $comment = Comment::create([
            'ticket_id' => $request->ticket_id,
            'user_id' => $user->id,
            'pesan' => $request->pesan,
            'lampiran' => !empty($lampiranPaths) ? $lampiranPaths : null,
        ]);
        
        // Update ticket's last_comment_at
        $ticket->last_comment_at = now();
        $ticket->save();
        
        // Kirim notifikasi kepada user pelapor jika memiliki telegram_id
        if ($user->role_id == 3 && $ticket->user && $ticket->user->telegram_id) {
            $kategoriName = $ticket->kategori ? $ticket->kategori->name : '-';
            $ticketUrl = route('ticket.ticket.show', $ticket->id); // Pastikan route ini benar untuk user
            $userMessage = "💬 *Tiket kamu telah di respon* 💬\n\n" .
                "• *No. Tiket*: `$ticket->no_tiket`\n" .
                "• *Judul*: `{$ticket->judul}`\n" .
                "• *Kategori*: `{$kategoriName}`\n" .
                "• *Urgensi*: `{$ticket->urgensi}`\n\n" .
                "Teknisi telah merespons tiket Anda. Silakan baca dan cek detailnya di [detail tiket]($ticketUrl) untuk " .
                "melihat komentar lebih lanjut serta membalas melalui sistem Helpdesk.";

            try {
                \Telegram::sendMessage([
                    'chat_id' => $ticket->user->telegram_id,
                    'text' => $userMessage,
                    'parse_mode' => 'Markdown'
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim notifikasi Telegram ke user: ' . $e->getMessage());
            }
        }

        // Mark as read by the comment author
        $this->markSingleCommentAsRead($comment->id);
        
        return response()->json([
            'status' => true,
            'message' => 'Komentar berhasil ditambahkan'
        ]);
    }
    
    /**
     * Private helper method to mark a single comment as read
     */
    private function markSingleCommentAsRead($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment && !$comment->readBy()->where('user_id', Auth::id())->exists()) {
            $comment->readBy()->attach(Auth::id(), ['read_at' => now()]);
        }
    }

    /**
     * Mark comments as read by current user (public route method)
     */
public function markAsRead(Request $request)
{
    $request->validate([
        'comment_ids' => 'required|array',
        'comment_ids.*' => 'exists:comments,id'
    ]);
    
    $user = Auth::user();
    $commentIds = $request->comment_ids;
    $markedCount = 0;
    
    \Log::info('Marking comments as read', ['user_id' => $user->id, 'comment_ids' => $commentIds]);
    
    foreach($commentIds as $commentId) {
        $comment = \App\Models\Comment::find($commentId);
        if ($comment && !$comment->readBy->contains($user->id)) {
            $comment->readBy()->attach($user->id, ['read_at' => now()]);
            $markedCount++;
        }
    }
    
    \Log::info('Comments marked as read', ['count' => $markedCount]);
    
    return response()->json([
        'status' => true,
        'message' => 'Komentar ditandai sebagai dibaca',
        'count' => $markedCount
    ]);
}
}