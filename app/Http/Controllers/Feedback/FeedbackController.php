<?php

namespace App\Http\Controllers\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Store a new feedback
     */
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:ticket,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);
        
        // Check if ticket belongs to user and is closed
        $ticket = Ticket::findOrFail($request->ticket_id);
        if ($ticket->user_id != Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak diizinkan memberikan feedback untuk tiket ini'
            ], 403);
        }
        
        if ($ticket->status != 'Selesai') {
            return response()->json([
                'status' => false,
                'message' => 'Hanya tiket yang sudah selesai yang dapat diberi feedback'
            ], 400);
        }
        
        // Check if feedback already exists
        $existingFeedback = Feedback::where('ticket_id', $request->ticket_id)->first();
        if ($existingFeedback) {
            // Update existing feedback
            $existingFeedback->update([
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
            
            return response()->json([
                'status' => true,
                'message' => 'Feedback berhasil diperbarui'
            ]);
        }
        
        // Create new feedback
        Feedback::create([
            'ticket_id' => $request->ticket_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
        
        return response()->json([
            'status' => true,
            'message' => 'Terima kasih atas feedback Anda'
        ]);
    }
}