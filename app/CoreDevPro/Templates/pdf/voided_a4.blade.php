@php 
    $voided = $document;
    $document = $document->document->document;
    $establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;

    $path_style = app_path('CoreDevPro'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);

    $establishment2 = \App\Models\Tenant\Establishment::find($document->establishment_id);
    $customer2 = \App\Models\Tenant\Person::find($document->customer_id);
    $configuration = \App\Models\Tenant\Configuration::first();
    $document_configuration = \App\Models\Tenant\DocumentConfiguration::first();
   // $note = \App\Models\Tenant\Note::ByDocumentId($document->id)->first();
@endphp
<html>
    <head>
        <title>{{ $document_number }}</title>
        <link href="{{ $path_style }}" rel="stylesheet" />
        <style>
            html {
                font-family: sans-serif;
            }
            #voideds {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }

            #voideds td, #voideds th {
            border: 1px solid #ddd;
            padding: 8px;
            }

            #voideds th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            }
        </style>
    </head>
    <body>
        <table class="full-width">
            <tr>
                @if($company->logo)
                    <td width="20%">
                        <div class="company_logo_box">
                            <img src="{{ asset('storage/uploads/logos/'.$company->logo) }}" alt="{{ $company->name }}" class="company_logo" style="max-width: 150px;">
                        </div>
                    </td>
                @else
                    <td width="20%">
                        <img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px">
                    </td>
                @endif
                <td width="45%" class="pl-3">
                    <div class="text-left">
                        <h3 class="">{{ $company->name }}</h3>
                        <h5>{{ $establishment2->description }}</h5>
                        <h6>{{ strtoupper($establishment2->getAddressFullAttribute()) }}</h6>
                        <h6>{{ ($establishment2->telephone !== '-')? $establishment2->telephone : '' }}</h6>
                        <h6>{{ ($establishment2->email !== '-')? $establishment2->email : '' }}</h6>
                        <br>
                        <table>
                            <tr><td><strong>FECHA EMISIÓN:</strong></td><td>{{ $document->date_of_issue->format('d/m/Y') }}</td></tr>
                            <tr><td><strong>FECHA GENERACIÓN:</strong></td><td>{{ $voided->date_of_issue->format('d/m/Y') }}</td></tr>
                        </table>
                    </div>
                </td>
                <td width="35%" class="border-box py-4 px-1 text-center">
                    <h3 class="text-center">{{ 'R.U.C. N° '.$company->number }}</h3>
                    <h4 class="text-center"><strong> COMUNICACIÓN DE BAJA</strong></h4>
                    <h4 class="text-center"><strong>NÚMERO: </strong> {{ str_pad($voided->id, 8, '0', STR_PAD_LEFT) }}</h4>
                    <h6 class="text-center"><strong>NÚMERO DE TICKET: </strong> {{ $voided->ticket }}</h6>
                </td>
            </tr>
        </table><br>

        <div class=" py-4 ">
            <table class="full-width mt-12 mb-10" id="voideds">
                <thead>
                    <tr class="bg-grey">
                        <th class="text-center align-top" >FECHA</th>
                        <th class="text-center align-top" >TIPO DE DOCUMENTO</th>
                        <th class="text-center align-top" >SERIE</th>
                        <th class="text-center align-top" >NÚMERO</th>
                        <th class="text-center align-top" >MOTIVO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center align-top">{{ $document->date_of_issue->format('d/m/Y') }}</td>
                        <td class="text-center align-top">{{ $document->document_type->description }}</td>
                        <td class="text-center align-top">{{ $document->series }}</td>
                        <td class="text-center align-top">{{ str_pad($document->number, 8, '0', STR_PAD_LEFT) }}</td>
                        <td class="text-center align-top">{{ $voided->document->description }}</td>
                    </tr>
                </tbody>
            </table>
            
        </div>
        <br>
        
    </body>
</html>