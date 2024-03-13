<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte Documentos</strong></h3>
        </div>
        <br>
        <div style="margin-top:20px; margin-bottom:15px;">
            <table>
                <tr>
                    <td>
                        <p><b>Empresa: </b></p>
                    </td>
                    <td align="center">
                        <p><strong>{{$company->name}}</strong></p>
                    </td>
                    <td>
                        <p><strong>Fecha: </strong></p>
                    </td>
                    <td align="center">
                        <p><strong>{{date('Y-m-d')}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Ruc: </strong></p>
                    </td>
                    <td align="center">{{$company->number}}</td>
                    @if(!is_null($establishment))
                    <td>
                            <p><strong>Establecimiento: </strong></p>
                        </td>
                        <td align="center">{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</td>
                    @endif
                </tr>
            </table>
        </div>
        <br>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Establecimiento</th>
                                <th>Número</th>
                                <th>Fecha emisión</th>
                                <th>Cliente</th>
                                <th>RUC</th>
                                <th>Estado</th>
                                <th>Total Gravado</th>
                                <th>Total IGV</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $total = 0;
                                $total_paid = 0;
                                $total2 = 0;
                            @endphp
                            @foreach($records as $key => $value)
                                @php
                                    $subtotal2 = $value->total - $value->total_paid;
                                @endphp
                                <tr>
                                    <td class="celda">{{$i}}</td>
                                    <td class="celda">{{$value->establishment}}</td>
                                    <td class="celda">{{$value->document_type}}</td>
                                    <td class="celda">{{$value->series}}-{{$value->number}}</td>
                                    <td class="celda">{{$value->date_of_issue}}</td>
                                    <td class="celda">{{$value->name}}</td>
                                    <td class="celda">{{$value->person_number}}</td>
                                    <td class="celda">{{$value->total}}</td>
                                    <td class="celda">{{$value->total_paid}}</td>
                                    <td class="celda">{{number_format($subtotal2, 2)}}</td>
                                </tr>
                                @php
                                    $i++;
                                    $total = $value->total + $total;
                                    $total_paid = $value->total_paid + $total_paid;
                                    $total2 = $subtotal2 + $total2;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6"></th>
                                <th class="font-weight-bold">Totales</th>
                                <th class="font-weight-bold">{{number_format($total, 2)}}</th>
                                <th class="font-weight-bold">{{number_format($total_paid, 2)}}</th>
                                <th class="font-weight-bold">{{number_format($total2, 2)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @else
            <div>
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
