<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Inventory\Exports\KardexExport;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Inventory\Models\InventoryKardex;
use Modules\Inventory\Models\Warehouse;
use App\Traits\ReportTrait;


class ReportKardexController extends Controller
{
    use ReportTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $models = [
        "App\Models\Tenant\Document",
        "App\Models\Tenant\Purchase",
        "App\Models\Tenant\SaleNote",
        "Modules\Inventory\Models\Inventory"
    ];

    public function index() {

        $items = $this->getItems();
        $warehouses = Warehouse::all();
        $warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();
        $warehouse_td = is_null($warehouse)?null:$warehouse->id;

        return view('inventory::reports.kardex.index', compact('items', 'warehouses', "warehouse_td"));
    }

    /**
     * Search
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {

        $balance = 0;

        $items = $this->getItems();
        $warehouses = Warehouse::all();

        $warehouse_td = $this->getWarehouse($request->warehouse);
        $item_td = $this->getItem($request->item);
        $item = $request->item;

        $reports = $this->getRecords($warehouse_td, $item_td);

        $models = $this->models;

        return view('inventory::reports.kardex.index', compact('items', 'reports', 'balance','models', 'warehouses', 'warehouse_td', 'item_td', 'item'));
    }

    /**
     * PDF
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request) {

        $balance = 0;
        $company = Company::first();
        $establishment = Establishment::first();

        $warehouse_id = $request->warehouse_td;
        $item_id = $request->item_td;
        $item = $request->item;
        $warehouse = Warehouse::find($warehouse_id);
        $establishment = Establishment::where('id', $warehouse->establishment_id)->first();

        $reports = $this->getRecords($warehouse_id, $item_id);

        $models = $this->models;

        $pdf = PDF::loadView('inventory::reports.kardex.report_pdf', compact("reports", "company", "establishment", "balance","models", "item"));
        $filename = 'Reporte_Kardex'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }

    /**
     * Excel
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request) {

        $balance = 0;
        $company = Company::first();
        $establishment = Establishment::first();

        $warehouse_id = $request->warehouse_td;
        $item_id = $request->item_td;
        $item = $request->item;
        $warehouse = Warehouse::find($warehouse_id);
        $establishment = Establishment::where('id', $warehouse->establishment_id)->first();

        $records = $this->getRecords($warehouse_id, $item_id);

        $models = $this->models;

        return (new KardexExport)
            ->balance($balance)
            ->records($records)
            ->models($models)
            ->company($company)
            ->establishment($establishment)
            ->item($item)
            ->download('ReporteKar'.Carbon::now().'.xlsx');
    }

    public function getRecords($warehouse_id, $item_id){
        $records = InventoryKardex::with(['inventory_kardexable'])
                                    ->where([['item_id', $item_id],['warehouse_id', $warehouse_id]])
                                    ->orderBy('id')
                                    ->get();
        return $records;
    }
}
