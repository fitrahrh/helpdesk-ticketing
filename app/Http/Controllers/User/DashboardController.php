<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = [
            'pending' => Ticket::where('user_id', $user->id)->where('status', 'pending')->count(),
            'diproses' => Ticket::where('user_id', $user->id)->where('status', 'diproses')->count(),
            'disposisi' => Ticket::where('user_id', $user->id)->where('status', 'disposisi')->count(),
            'selesai' => Ticket::where('user_id', $user->id)->where('status', 'selesai')->count(),
        ];

        $recentTickets = Ticket::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('layouts.user.dashboard.index', compact('stats', 'recentTickets'));
    }
}
