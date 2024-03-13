@extends('tenant.layouts.app')

@section('content')
    @can('tenant.configuration.shifts')
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <tenant-pos-configurations-index></tenant-pos-configurations-index>
            </div>
        </div>
    @endcan
@endsection
