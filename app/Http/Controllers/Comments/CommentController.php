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
            'attachments.*' => 'nullable|file|max:5120' // 5MB limit per file
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
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('comment-attachments', 'public');
                $attachments[] = [
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
            'attachments' => !empty($attachments) ? $attachments : null
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
     * Mark comment as read by current user
     */
    public function markAsRead($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        
        // Check if already read
        $existingRead = CommentRead::where('comment_id', $commentId)
                        ->where('user_id', Auth::id())
                        ->first();
        
        if (!$existingRead) {
            CommentRead::create([
                'comment_id' => $commentId,
                'user_id' => Auth::id(),
                'read_at' => now()
            ]);
        }
        
        return response()->json(['status' => true]);
    }
}