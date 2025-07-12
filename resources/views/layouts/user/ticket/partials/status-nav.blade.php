
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 me-3"><i class="fa fa-ticket text-primary me-2"></i> My Tiket Status</h4>
                </div>
            </div>
            <hr>
        <div class="mb-4">
            <div class="list-group list-group-flush">
                <a href="{{ route('ticket.pending') }}" class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('ticket.pending') ? 'active' : '' }}">
                    <div><i class="fa fa-clock-o me-2"></i> Menunggu</div>
                    <span class="badge {{ request()->routeIs('ticket.pending') ? 'bg-white text-primary' : 'bg-secondary' }} rounded-pill">{{ Auth::user()->tickets()->where('status', 'Baru')->count() }}</span>
                </a>
                
                <a href="{{ route('ticket.disposisi') }}" class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('ticket.disposisi') ? 'active' : '' }}">
                    <div><i class="fa fa-exchange me-2"></i> Disposisi</div>
                    <span class="badge {{ request()->routeIs('ticket.disposisi') ? 'bg-white text-primary' : 'bg-secondary' }} rounded-pill">{{ Auth::user()->tickets()->where('status', 'Disposisi')->count() }}</span>
                </a>
                
                <a href="{{ route('ticket.diproses') }}" class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('ticket.diproses') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Diproses') ? 'active' : '' }}">
                    <div><i class="fa fa-spinner me-2"></i> Diproses</div>
                    <span class="badge {{ request()->routeIs('ticket.diproses') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Diproses') ? 'bg-white text-primary' : 'bg-secondary' }} rounded-pill">{{ Auth::user()->tickets()->where('status', 'Diproses')->count() }}</span>
                </a>
                
                <a href="{{ route('ticket.selesai') }}" class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('ticket.selesai') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Selesai') ? 'active' : '' }}">
                    <div><i class="fa fa-check me-2"></i> Selesai</div>
                    <span class="badge {{ request()->routeIs('ticket.selesai') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Selesai') ? 'bg-white text-primary' : 'bg-secondary' }} rounded-pill">{{ Auth::user()->tickets()->where('status', 'Selesai')->count() }}</span>
                </a>
            </div>
        </div>