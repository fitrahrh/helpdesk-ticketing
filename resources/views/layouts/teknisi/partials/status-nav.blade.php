<div class="h-100 mb-4">
        <div class="list-group list-group-flush">
            <a href="{{ route('teknisi.baru') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('teknisi.baru') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Baru') ? 'active bg-light' : '' }}">
                <div>
                    <i class="fas fa-envelope me-2 text-dark"></i> 
                    <span class="{{ request()->routeIs('teknisi.baru') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Baru') ? 'fw-bold' : '' }}">Tiket Baru</span>
                </div>
                <span class="badge {{ request()->routeIs('teknisi.baru') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Baru') ? 'bg-light text-dark' : 'bg-light' }} rounded-pill ticket-circle">
                    <!-- Mengambil jumlah tiket baru yang ditugaskan ke kategori yang ditangani teknisi ini -->
                    {{ \App\Models\Ticket::whereIn('kategori_id', Auth::user()->penanggungjawabs()->pluck('kategori_id'))->where('status', 'Baru')->count() }}
                </span>
            </a>
            
            <a href="{{ route('teknisi.diproses') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('teknisi.diproses') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Diproses') ? 'active bg-light' : '' }}">
                <div>
                    <i class="fas fa-spinner me-2 text-dark"></i> 
                    <span class="{{ request()->routeIs('teknisi.diproses') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Diproses') ? 'fw-bold' : '' }}">Diproses</span>
                </div>
                <span class="badge {{ request()->routeIs('teknisi.diproses') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Diproses') ? 'bg-light' : 'bg-light' }} rounded-pill ticket-circle">
                    {{ \App\Models\Ticket::whereIn('kategori_id', Auth::user()->penanggungjawabs()->pluck('kategori_id'))->where('status', 'Diproses')->count() }}
                </span>
            </a>
            
            <a href="{{ route('teknisi.selesai') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('teknisi.selesai') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Selesai') ? 'active bg-light' : '' }}">
                <div>
                    <i class="fas fa-check me-2 text-dark"></i> 
                    <span class="{{ request()->routeIs('teknisi.selesai') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Selesai') ? 'fw-bold' : '' }}">Selesai</span>
                </div>
                <span class="badge {{ request()->routeIs('teknisi.selesai') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Selesai') ? 'bg-light' : 'bg-light' }} rounded-pill ticket-circle">
                    {{ \App\Models\Ticket::whereIn('kategori_id', Auth::user()->penanggungjawabs()->pluck('kategori_id'))->where('status', 'Selesai')->count() }}
                </span>
            </a>
        </div>
</div>