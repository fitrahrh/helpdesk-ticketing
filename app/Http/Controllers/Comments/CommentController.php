<?php

namespace App\Http\Controllers\Comment;

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
        
        // Check if the user is authorized to comment on this ticket
        $ticket = Ticket::findOrFail($request->ticket_id);
        if ($ticket->user_id != Auth::id() && $ticket->assigned_to != Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak diizinkan menambahkan komentar pada tiket ini'
            ], 403);
        }
        
        // Handle file uploads if any
        $lampiran = [];
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $path = $file->store('comment-lampiran', 'public');
                $lampiran[] = [
                    'name' => $file->getClientOriginalName(),
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'path' => $path
                ];
            }
        }
        
        // Create comment
        $comment = Comment::create([
            'ticket_id' => $request->ticket_id,
            'user_id' => Auth::id(),
            'pesan' => $request->pesan,
            'lampiran' => !empty($lampiran) ? $lampiran : null
        ]);
        
        // Update ticket's last comment info
        $ticket->last_comment_by = Auth::id();
        $ticket->last_comment_at = now();
        $ticket->save();
        
        // Mark as read by the comment author
        $this->markAsRead($comment->id);
        
        return response()->json([
            'status' => true,
            'message' => 'Komentar berhasil ditambahkan'
        ]);
    }
    
    /**
     * Get readers for a comment
     */
    public function getReaders($commentId)
    {
        $comment = Comment::with('readBy')->findOrFail($commentId);
        
        // Check if user is allowed to see this comment's read receipts
        $ticket = Ticket::findOrFail($comment->ticket_id);
        if ($ticket->user_id != Auth::id() && $ticket->assigned_to != Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak diizinkan melihat data ini'
            ], 403);
        }
        
        return response()->json([
            'status' => true,
            'readers' => $comment->readBy
        ]);
    }
    
    /**
     * Mark comments as read by current user
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id'
        ]);
        
        foreach ($request->comment_ids as $commentId) {
            $comment = Comment::find($commentId);
            
            // Check if already read
            if (!$comment->readBy->contains(Auth::id())) {
                // Add current user to readers
                $comment->readBy()->attach(Auth::id(), ['read_at' => now()]);
            }
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Comments marked as read'
        ]);
    }

}