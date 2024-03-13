<?php
namespace App\Http\Controllers\Tenant\Api;

use App\CoreDevPro\DevPro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetentionController extends Controller
{
    public function __construct()
    {
        $this->middleware('input.request:retention,api', ['only' => ['store']]);
    }

    public function store(Request $request)
    {
        $fact = DB::connection('tenant')->transaction(function () use($request) {
            $DevPro = new DevPro();
            $DevPro->save($request->all());
            $DevPro->createXmlUnsigned();
            $DevPro->signXmlUnsigned();
            $DevPro->createPdf();
            return $DevPro;
        });

        $fact->sendEmail();
        $fact->senderXmlSignedBill();
        $document = $fact->getDocument();
        $response = $fact->getResponse();

        return [
            'success' => true,
            'data' => [
                'number' => $document->number_full,
                'filename' => $document->filename,
                'external_id' => $document->external_id,
            ],
            'links' => [
                'xml' => $document->download_external_xml,
                'pdf' => $document->download_external_pdf,
                'cdr' => $document->download_external_cdr,
            ],
            'response' => array_except($response, 'sent')
        ];
    }
}