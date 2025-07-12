
@extends('layouts.user-layout')

@section('banner-content')
<div class="d-flex flex-column align-items-center justify-content-center center-action">
    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center fade-in" 
        data-bs-toggle="modal" 
        data-bs-target="#ticketModal" 
        style="width:90px;height:90px;cursor:pointer;">
        <i class="fa fa-question fa-3x text-white"></i>
    </div>

    <div class="mt-3 fs-18 fade-in-delay" data-bs-toggle="modal" data-bs-target="#ticketModal" style="cursor: pointer;">
        Kirim <b>Tiket</b>
    </div>
</div>
@endsection

@section('content')

<!-- Ticket Creation Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketModalLabel">Buat Tiket Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data" id="ticketForm">

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection