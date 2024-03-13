<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\PosConfigurationRequest;
use App\Http\Resources\Tenant\PosConfigurationResource;
use App\Models\Tenant\PosConfiguration;

class PosConfigurationController extends Controller
{
    public function index()
    {
        return view('tenant.pos_configurations.index');
    }
        
    public function record() {

        $pos_configuration = PosConfiguration::first();
        $record = new PosConfigurationResource($pos_configuration);

        return $record;
    }

    public function store(PosConfigurationRequest $request) {

        $id = $request->input('id');
        $pos_configuration = PosConfiguration::find($id);
        $pos_configuration->fill($request->all());
        $pos_configuration->save();
        
        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];

    }
    
}
