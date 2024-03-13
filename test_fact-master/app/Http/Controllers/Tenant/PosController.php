<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\Pos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\PosConfiguration;
use App\Models\Tenant\PosDocument;
use App\Models\Tenant\PosShift;
use App\Models\Tenant\PosStation;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\SaleNote;
use App\Traits\PaymentTrait;
use App\Traits\PosTrait;
use Session;

class PosController extends Controller
{
    use PaymentTrait;
    use PosTrait;

    public function index()
    {
        return view('tenant.pos.index');
    }

    public function register()
    {
        $pos_station_id = Session::get('pos_station_id');

        if(is_null($pos_station_id))
        {
            return redirect('pos-stations');
        }

        $pos_station = PosStation::find($pos_station_id);

        if($pos_station->establishment_id != auth()->user()->establishment_id)
        {
            return redirect('pos-stations');
        }

        $cash_shifts = PosConfiguration::first()->cash_shifts;

        $pos_shift = null;

        if($cash_shifts)
        {
            $pos_shift = $this->pos_active();
        }

        return view('tenant.pos.register', compact('pos_station', 'cash_shifts', 'pos_shift'));
    }

    public function init(Request $request)
    {
        Session::put('pos_station_id', $request->input('pos_station_id'));

        return json_encode(true);
    }

    // public function sales(PosStation $pos_station)
    // {
    //     return view('tenant.pos.sales', compact('pos_station'));
    // }

    // public function tables()
    // {
    //     $user = auth()->user();
    //     $pos = $this->pos_active();

    //     return compact('user', 'pos');
    // }

    public function totals()
    {
    }

    public function columns()
    {
        return [
            'establishment.description' => 'Ubicación',
            'user.name' => 'Usuario'
        ];
    }

    public function store(Request $request)
    {
        return PosShift::create($request->toArray());
    }

    public function operations($table_id2, Request $request)
    {
        DB::connection('tenant')->beginTransaction();

        try
        {
            $total = $request->balance['total'];

            $table_name = $request->input('table_name');
            $table_id = $request->input('table_id');
            $table_name2 = $request->input('table_name2');

            PosDocument::create([
                'table_name' => $table_name,
                'table_id' => $table_id,
                'table_name2' => $table_name2,
                'table_id2' => $table_id2
            ]);

            if($table_name == 'pos_shifts')
            {
                $pos_shift = PosShift::find($table_id);
                $pos_shift->sales_count += 1;
                $pos_shift->close_amount += $total;
                $pos_shift->save();
            }

            if($table_name2 == 'documents')
            {
                $document = Document::find($table_id2);
            }
            else
            {
                $document = SaleNote::find($table_id2);
            }

            $document->total_paid += $total;
            $document->save();

            foreach ($request->sale as $detail)
            {
                $this->savePayment($table_name2, $table_id2, null, $detail['tipo'], 'PEN', $detail['cuenta'], '', $detail['monto']);
            }

            DB::connection('tenant')->commit();

            return [
                'success' => true,
                'message' => 'pago registrado'
            ];
        }
        catch (Exception $e)
        {
            DB::connection('tenant')->rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function pdf($pos_id)
    {
        $company = Company::first();
        $pos = $this->details($pos_id);

        $pdf = PDF::loadView('tenant.reports.pos.report_pdf', compact("company", 'pos'));
        $filename = 'Reporte_Pos'.$pos['box']->created_at->format('_Ymd_Hm');

        return $pdf->download($filename.'.pdf');
    }
}
