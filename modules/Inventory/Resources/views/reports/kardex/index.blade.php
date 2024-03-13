@extends('tenant.layouts.app')
@php
    use App\Helpers\Functions;
@endphp
@section('content')
    @can('tenant.inventory.report.kardex.index')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta kardex</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('reports.kardex.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                            {{csrf_field()}}
                            <tenant-report-filter :warehouses="{{json_encode($warehouses)}}" :items="{{json_encode($items)}}" warehouse_td="{{$warehouse_td ?? ''}}" item_td="{{$item_td ?? ''}}"></tenant-report-filter>
                        </form>
                    </div>
                    @if(!empty($reports) && $reports->count())
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                @if(isset($reports))
                                    <form action="{{route('reports.kardex.pdf')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" value="{{$warehouse_td}}" name="warehouse_td">
                                        <input type="hidden" value="{{$item_td}}" name="item_td">
                                        <input type="hidden" value="{{$item}}" name="item">
                                        <button class="btn btn-custom  mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                    </form>
                                <form action="{{route('reports.kardex.report_excel')}}" class="d-inline" method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" value="{{$warehouse_td}}" name="warehouse_td">
                                    <input type="hidden" value="{{$item_td}}" name="item_td">
                                    <input type="hidden" value="{{$item}}" name="item">
                                    <button class="btn btn-custom mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                </form>
                                @endif
                            </div>
                            <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha y hora</th>
                                        <th>Tipo transacción</th>
                                        <th>Número</th>
                                        <th>Entrada</th>
                                        <th>Salida</th>
                                        <th>Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $key => $value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->date_of_issue}} {{ date('H:m', strtotime($value->created_at)) }}</td>
                                        <td>
                                            @switch($value->inventory_kardexable_type)
                                                @case($models[0])
                                                    {{(Functions::formaterDecimal($value->quantity) < 0) ? "Venta":"Anulación"}}
                                                    @break
                                                @case($models[1])
                                                    {{"Compra"}}
                                                    @break

                                                @case($models[2])
                                                    {{"Nota de venta"}}
                                                    @break

                                                @case($models[3])
                                                    {{$value->inventory_kardexable->description}}
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($value->inventory_kardexable_type)
                                                @case($models[0])
                                                    {{ optional($value->inventory_kardexable)->series.'-'.optional($value->inventory_kardexable)->number }}
                                                    @break
                                                @case($models[1])
                                                    {{ optional($value->inventory_kardexable)->series.'-'.optional($value->inventory_kardexable)->number }}
                                                    @break
                                                @case($models[2])
                                                    {{ optional($value->inventory_kardexable)->series.'-'.optional($value->inventory_kardexable)->number }}
                                                    @break
                                                @case($models[3])
                                                    {{"-"}}
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($value->inventory_kardexable_type)
                                                @case($models[0])
                                                    {{ (Functions::formaterDecimal($value->quantity) > 0) ?  Functions::formaterDecimal($value->quantity):"-"}}
                                                    @break
                                                @case($models[1])
                                                    {{  Functions::formaterDecimal($value->quantity) }}
                                                    @break
                                                @case($models[3])
                                                    {{-- {{ ($value->inventory_kardexable->type == 1 || $value->inventory_kardexable->type == 4) ? Functions::formaterDecimal($value->quantity) : "-" }} --}}
                                                    @if($value->inventory_kardexable->type == 1 || $value->inventory_kardexable->type == 4)
                                                        @if($value->quantity > 0)
                                                            {{  Functions::formaterDecimal($value->quantity) }}
                                                        @else
                                                            {{"-"}}
                                                        @endif
                                                    @elseif($value->inventory_kardexable->type == 2)
                                                        @if($value->quantity > 0)
                                                            {{  Functions::formaterDecimal($value->quantity) }}
                                                        @endif
                                                    @else
                                                        {{"-"}}
                                                    @endif

                                                    @break
                                                @default
                                                    {{"-"}}
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($value->inventory_kardexable_type)
                                                @case($models[0])
                                                    {{ (Functions::formaterDecimal($value->quantity) < 0) ?  Functions::formaterDecimal($value->quantity):"-" }}
                                                    @break
                                                @case($models[2])
                                                    {{  Functions::formaterDecimal($value->quantity) }}
                                                    @break
                                                @case($models[3])
                                                    @if($value->inventory_kardexable->type == 2 || $value->inventory_kardexable->type == 3)
                                                        @if($value->quantity < 0)
                                                            {{  Functions::formaterDecimal($value->quantity) }}
                                                        @else
                                                            {{"-"}}
                                                        @endif
                                                    @else
                                                        {{"-"}}
                                                    @endif
                                                    @break
                                                @default
                                                    {{"-"}}
                                                    @break
                                            @endswitch
                                        </td>
                                        @php
                                            $balance += Functions::formaterDecimal($value->quantity);
                                        @endphp
                                        <td>{{Functions::formaterDecimal($balance)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper">
                                {{-- {{ $reports->appends(['search' => Session::get('form_document_list')])->render()  }} --}}
                                {{-- {{$reports->links()}} --}}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="box box-body no-padding">
                        <strong>No se encontraron registros</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endcan
@endsection

@push('scripts')
    <script></script>
@endpush
