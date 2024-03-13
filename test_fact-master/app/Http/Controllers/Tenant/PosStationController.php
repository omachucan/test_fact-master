<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\PosStationRequest;
use App\Models\Tenant\PosStation;
use App\Http\Resources\Tenant\PosStationCollection;
use App\Http\Resources\Tenant\PosStationResource;
use App\Models\Tenant\Establishment;
use Modules\Inventory\Models\Warehouse;

class PosStationController extends Controller
{
    public function index()
    {
        return view('tenant.pos_stations.index');
    }

    public function tables()
    {
        $establishments = Establishment::get();
        $warehouses = Warehouse::get();
        $user_id = auth()->id();

        return compact('establishments', 'warehouses', 'user_id');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre'
        ];
    }

    public function totals()
    {
        return [];
    }

    public function records(Request $request)
    {
        if(auth()->user()->hasRole('administrador'))
        {
            $records = PosStation::where($request->column, 'like', "%{$request->value}%");
        }
        else
        {
            $records = PosStation::where($request->column, 'like', "%{$request->value}%")->where('establishment_id', auth()->user()->establishment_id);
        }

        return new PosStationCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function record($id)
    {
        $record = new PosStationResource(PosStation::findOrFail($id));

        return $record;
    }

    public function register()
    {
        return view('tenant.pos.register');
    }

    public function store(PosStationRequest $request)
    {
        $id = $request->input('id');
        $pos_station = PosStation::firstOrNew(['id' => $id]);
        $pos_station->fill($request->all());
        $pos_station->save();

        return [
            'success' => true,
            'message' => ($request->input('id')) ? 'Terminal editado con éxito' : 'Terminal registrado con éxito',
            'id' => $pos_station->id
        ];
    }

    public function destroy($id)
    {
        $pos_station = PosStation::findOrFail($id);
        $pos_station->delete();

        return [
            'success' => true,
            'message' => 'Terminal eliminado con éxito'
        ];
    }
}
