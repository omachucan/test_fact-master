<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Resources\Tenant\PosShiftCollection;
use App\Models\Tenant\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Account;
use App\Models\Tenant\PosCash;
use App\Models\Tenant\PosShift;
use App\Models\Tenant\PosStation;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Traits\PaymentTrait;
use App\Traits\PosTrait;
use Session;

class PosShiftController extends Controller
{
    use PaymentTrait, PosTrait;

    public function index()
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

        return view('tenant.pos_shifts.index', compact('pos_station'));
    }

    public function columns()
    {
        return [
            'user.name' => 'Usuario'
        ];
    }

    public function tables()
    {
        $accounts = Account::get();
        $pos_shift_id = $this->pos_active();

        $user = auth()->user();

        return compact('accounts', 'pos_shift_id', 'user');
    }

    public function totals()
    {
    }

    public function records(Request $request)
    {
        if(auth()->user()->hasRole('administrador'))
        {
            $records = PosShift::where('pos_station_id', Session::get('pos_station_id'))->orderBy('created_at', 'desc');
        }
        else
        {
            $records = PosShift::where('pos_station_id', Session::get('pos_station_id'))
            ->where('user_id', auth()->id())->orderBy('created_at', 'desc');
        }

        $request->column = explode('.', $request->column);

        $records->with([$request->column[0] => function ($q) use ($request) {
            $q->where($request->column[1], 'like', "%{$request->value}%");
        }]);

        return new PosShiftCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function record($id)
    {
        $pos_shift = PosShift::find($id);

        $payments = DB::connection('tenant')
                    ->select("SELECT pam.`description` payment_method_description, SUM(pay.`total`) as total
                    FROM pos_documents pod
                    INNER JOIN payments pay ON pay.`table_name` = pod.`table_name2` AND pay.`table_id` = pod.`table_id2`
                    INNER JOIN cat_payment_methods pam ON pam.`id` = pay.`payment_method_id`
                    WHERE pod.table_name = 'pos_shifts' AND pod.table_id = $id
                    GROUP BY pam.`id`");

        $cash_income = DB::connection('tenant')
                            ->select("SELECT SUM(VALUE) AS total
                            FROM pos_cashs
                            WHERE pos_shift_id = $id AND TYPE = '1'
                            GROUP BY pos_shift_id");

        $cash_withdrawal = DB::connection('tenant')
                            ->select("SELECT SUM(VALUE) AS total
                            FROM pos_cashs
                            WHERE pos_shift_id = $id AND TYPE = '0'
                            GROUP BY pos_shift_id");

        return compact('pos_shift', 'payments', 'cash_income', 'cash_withdrawal');
    }

    public function store(Request $request)
    {
        return PosShift::create($request->toArray());
    }

    public function cash_store(Request $request)
    {
        DB::connection('tenant')->beginTransaction();

        try
        {
            $pos_shift_id = $request->input('pos_shift_id');
            $type = $request->input('type');
            $total = $request->input('value');
            $account_id = $request->input('account_id');

            PosCash::create($request->toArray());

            if($type)
            {
                $pos_shift = PosShift::find($pos_shift_id);
                $pos_shift->close_amount += $total;
                $pos_shift->save();

                $account = Account::find($account_id);
                $account->current_balance += $total;
                $account->save();

                $mensaje = "Ingreso registrado";
            }
            else
            {
                $pos_shift = PosShift::find($pos_shift_id);
                $pos_shift->close_amount -= $total;
                $pos_shift->save();

                $account = Account::find($account_id);
                $account->current_balance -= $total;
                $account->save();

                $mensaje = "Retiro registrado";
            }

            DB::connection('tenant')->commit();

            return [
                'success' => true,
                'message' => $mensaje
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

    public function close()
    {
        $pos_shift_id = $this->pos_active();
        $pos_shift = PosShift::find($pos_shift_id);
        $pos_shift->status = 'close';
        $pos_shift->closed_date = date("Y-m-d H:i:s");
        $pos_shift->save();

        return $pos_shift;
    }

    public function sells($pos_shift_id)
    {
        $sql = "SELECT tab.series, tab.number, tab.total, 'Venta' AS description,  pod.created_at, null as type
                FROM pos_documents pod
                INNER JOIN documents tab ON pod.`table_name` = 'pos_shifts' AND pod.table_name2 = 'documents' AND pod.`table_id2` = tab.id
                WHERE pod.`table_id` = $pos_shift_id
                UNION ALL
                SELECT tab.series, tab.number, tab.total, 'Venta' AS description, pod.created_at, null as type
                FROM pos_documents pod
                INNER JOIN sale_notes tab ON pod.`table_name` = 'pos_shifts' AND pod.table_name2 = 'sale_notes' AND pod.`table_id2` = tab.id
                WHERE pod.`table_id` = $pos_shift_id
                UNION ALL
                SELECT NULL, NULL, VALUE, CONCAT(IF(TYPE = '0', 'Egreso', 'Ingreso'), IF(poc.observations IS NULL, '', CONCAT(': ', poc.observations)))  AS description, created_at, poc.type
                FROM pos_cashs poc
                WHERE poc.pos_shift_id = $pos_shift_id
                ORDER BY created_at";

        $sells = DB::connection('tenant')->select($sql);

        return compact('sells');
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
