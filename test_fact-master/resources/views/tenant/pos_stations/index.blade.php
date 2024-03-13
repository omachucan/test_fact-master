@extends('tenant.layouts.app')

@section('content')
    @can('tenant.pos-stations.index')
        <tenant-pos-stations-index></tenant-pos-stations-index>
    @endcan
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function () {
            'use strict';
            $(".tableScrollTop,.tableWide-wrapper").scroll(function () {
                $(".tableWide-wrapper,.tableScrollTop")
                    .scrollLeft($(this).scrollLeft());
            });
        });
    </script>
@endpush
