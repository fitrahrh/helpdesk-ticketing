
            <div class="shadow-sm mb-4">
                <div class="card-body">        
                    <div class="d-flex align-items-center justify-content-between p-3">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0"> My Ticket Status</h5>
                        </div>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <a href="{{ route('ticket.pending') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('ticket.pending') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Baru') ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fas fa-clock-o me-2 text-dark"></i> 
                                <span class="{{ request()->routeIs('ticket.pending') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Baru') ? 'fw-bold' : '' }}">Pending</span>
                            </div>
                            <span class="badge {{ request()->routeIs('ticket.pending') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Baru') ? 'bg-warning text-dark' : 'bg-light' }} rounded-pill">
                                {{ Auth::user()->tickets()->where('status', 'Baru')->count() }}
                            </span>
                        </a>
                        
                        <a href="{{ route('ticket.disposisi') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('ticket.disposisi') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Disposisi') ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fas fa-exchange-alt me-2 text-dark"></i> 
                                <span class="{{ request()->routeIs('ticket.disposisi') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Disposisi') ? 'fw-bold' : '' }}">Disposisi</span>
                            </div>
                            <span class="badge {{ request()->routeIs('ticket.disposisi') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Disposisi') ? 'bg-danger' : 'bg-light' }} rounded-pill">
                                {{ Auth::user()->tickets()->where('status', 'Disposisi')->count() }}
                            </span>
                        </a>
                        
                        <a href="{{ route('ticket.diproses') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('ticket.diproses') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Diproses') ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fas fa-spinner me-2 text-dark"></i> 
                                <span class="{{ request()->routeIs('ticket.diproses') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Diproses') ? 'fw-bold' : '' }}">Proses</span>
                            </div>
                            <span class="badge {{ request()->routeIs('ticket.diproses') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Diproses') ? 'bg-info' : 'bg-light' }} rounded-pill">
                                {{ Auth::user()->tickets()->where('status', 'Diproses')->count() }}
                            </span>
                        </a>
                        
                        <a href="{{ route('ticket.selesai') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('ticket.selesai') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Selesai') ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fas fa-check me-2 text-dark"></i> 
                                <span class="{{ request()->routeIs('ticket.selesai') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Selesai') ? 'fw-bold' : '' }}">Selesai</span>
                            </div>
                            <span class="badge {{ request()->routeIs('ticket.selesai') || (isset($ticket) && request()->routeIs('ticket.show') && $ticket->status == 'Selesai') ? 'bg-success' : 'bg-light  ' }} rounded-pill">
                                {{ Auth::user()->tickets()->where('status', 'Selesai')->count() }}
                            </span>
                        </a>
                    </div>
</div>
</div>