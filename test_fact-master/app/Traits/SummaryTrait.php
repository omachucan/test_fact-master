<?php

namespace App\Traits;

use App\CoreDevPro\DevPro;
use App\Models\Tenant\Summary;
use DB;

trait SummaryTrait
{
    public function save($request) {
        $fact = DB::connection('tenant')->transaction(function () use($request) {
            $DevPro = new DevPro();
            $DevPro->save($request->all());
            $DevPro->createXmlUnsigned();
            $DevPro->signXmlUnsigned();
            return $DevPro;
        });
        
        $fact->senderXmlSignedSummary();
        
        $document = $fact->getDocument();
        
        return [
            'success' => true,
            'message' => "El resumen {$document->identifier} fue creado correctamente",
        ];
    }
    
    public function query($id) {
        $document = Summary::find($id);
        
        $fact = DB::connection('tenant')->transaction(function () use($document) {
            $DevPro = new DevPro();
            $DevPro->setDocument($document);
            $DevPro->setType('summary');
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
