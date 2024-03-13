@extends('tenant.layouts.pos', array('pos_station'=> $pos_station))

@section('content')
    @if($cash_shifts && !$pos_shift)
        <div class="row m-2">
            <div class="col-md-6">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">TURNO CERRADO</h4>
                    <p>Es necesario abrir un <a href="{{ route('tenant.pos_shifts.index') }}" class="alert-link">turno</a>  para poder realizar las ventas</p>
                </div>
            </div>
        </div>
        {{-- @if($pos_shift)
            <tenant-pos-register :pos_station_id="{{ json_encode($pos_station->id) }}" :pos_shift_id="{{ json_encode($pos_shift) }}"></tenant-pos-register>
        @else  
            
        @endif --}}
    @else
        <tenant-pos-register :pos_station="{{ json_encode($pos_station) }}" :pos_shift_id="{{ json_encode($pos_shift) }}"></tenant-pos-register>
    @endif
    
@endsection

@push('scripts')
@endpush