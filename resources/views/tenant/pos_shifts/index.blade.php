@extends('tenant.layouts.pos')

@section('content')
    @can('tenant.pos.index')
        <tenant-pos-shifts-index :pos_station_id="{{ json_encode($pos_station->id) }}"></tenant-pos-shifts-index>
    @endcan
@endsection

@push('scripts')
@endpush
