<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class SidebarComposer
{
    public function compose(View $view)
    {
        $ticketCounts = [
            'baru' => Ticket::where('status', 'Baru')->count(),
            'diproses' => Ticket::where('status', 'Diproses')->count(),
            'disposisi' => Ticket::where('status', 'Disposisi')->count(),
            'selesai' => Ticket::where('status', 'Selesai')->count(),
        ];

        $view->with('ticketCounts', $ticketCounts);
    }
}