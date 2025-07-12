<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;
use App\Models\Ticket;

class FeedbackController extends Controller
{
    public function store(Request $request, $ticket_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $ticket = Ticket::findOrFail($ticket_id);

        // Authorization check
        if ($ticket->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if feedback already exists for this ticket
        if ($ticket->feedback) {
            return redirect()->back()->with('error', 'Anda sudah memberikan feedback untuk tiket ini.');
        }

        Feedback::create([
            'ticket_id' => $ticket_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('user.ticket.show', $ticket_id)->with('success', 'Terima kasih atas feedback Anda.');
    }
}
