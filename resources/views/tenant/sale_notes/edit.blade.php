@extends('tenant.layouts.app')

@push('styles')
    <style type="text/css">
        .v-modal {
            opacity: 0.2 !important;
        }
        .border-custom {
            border-color: rgba(0,136,204, .5) !important;
        }
        @media only screen and (min-width: 768px) { 
        	.inner-wrapper {
			    padding-top: 60px !important;
			}
        }
        .card-header {
		    border-radius: 0px 0px 0px !important;
		}
    </style>
@endpush

@section('content')
    @can('tenant.sale-notes.update')
    <tenant-quotations-edit :quotation_id="{{ json_encode($quotation_id) }}"></tenant-quotations-edit>
    @endcan

@endsection

@push('scripts')
<script type="text/javascript">
	var count = 0;
	$(document).on("click", "#card-click", function(event){
		count = count + 1;
		if (count == 1) {
			$("#card-section").removeClass("card-collapsed");
		}		
	});
</script>
@endpush