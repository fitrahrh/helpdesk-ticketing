<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Ticket;

class CommentController extends Controller
{
    public function store(Request $request, $ticket_id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($ticket_id);

        // Authorization check
        if ($ticket->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment = new Comment();
        $comment->ticket_id = $ticket_id;
        $comment->user_id = Auth::id();
        $comment->body = $request->body;
        $comment->save();

        // Optionally, you can return the created comment with user info
        $comment->load('user');

        return response()->json($comment);
    }
}
