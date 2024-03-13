<?php

namespace App\Http\Controllers\Tenant;

use App\CoreDevPro\DevPro;
use App\CoreDevPro\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\VoidedCollection;
use App\Models\Tenant\Voided;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoidedController extends Controller
{
    use StorageDocument;

    public function __construct()
    {
        $this->middleware('input.request:voided,web', ['only' => ['store']]);
    }

    public function index()
    {
        return view('tenant.voided.index');
    }

    public function columns()
    {
        return [
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $voided = DB::connection('tenant')
                    ->table('voided')
                    ->select(DB::raw("id, external_id, date_of_reference, date_of_issue, ticket, identifier, state_type_id, 'voided' AS 'type'"));

        $summaries = DB::connection('tenant')
                        ->table('summaries')
                        ->select(DB::raw("id, external_id, date_of_reference, date_of_issue, ticket, identifier, state_type_id, 'summaries' AS 'type'"))
                        ->where('summary_status_type_id', '3');

        return new VoidedCollection($voided->union($summaries)->orderBy('date_of_issue', 'desc')->orderBy('id', 'desc')->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function store(Request $request)
    {
        $fact = DB::connection('tenant')->transaction(function () use($request) {
            $inputs =  $request->all();
            $inputs['actions'] = ['format_pdf'=> 'a4' ];
            $DevPro = new DevPro();
            $DevPro->save($inputs);
            $DevPro->createXmlUnsigned();
            $DevPro->signXmlUnsigned();
            $DevPro->createPdf();
            return $DevPro;
        });

        $fact->senderXmlSignedSummary();
        $document = $fact->getDocument();
        //$response = $fact->getResponse();

        return [
            'success' => true,
            'message' => "La anulación {$document->identifier} fue creado correctamente",
        ];
    }

    public function status($voided_id)
    {
        $document = Voided::find($voided_id);

        $fact = DB::connection('tenant')->transaction(function () use($document) {
            $DevPro = new DevPro();
            $DevPro->setDocument($document);
            $DevPro->setType('voided');
            $DevPro->statusSummary($document->ticket);
            return $DevPro;
        });

        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }
}
