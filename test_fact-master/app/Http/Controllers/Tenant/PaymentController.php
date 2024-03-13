<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\PaymentMethod;
use App\Models\Tenant\Account;
use App\Models\Tenant\Document;
use App\Models\Tenant\Payment;
use App\Models\Tenant\SaleNote;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\PaymentRequest;
use App\Http\Resources\Tenant\PaymentCollection;
use Illuminate\Http\Request;
use App\Models\Tenant\Pos;
use App\Traits\PaymentTrait;
use App\Models\Tenant\Purchase;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    use PaymentTrait;

    public function index()
    {
        return view('tenant.payments.index');
    }

    public function columns()
    {
        return [
           // 'payments.description' => 'Descripción'
        ];
    }

    public function records(Request $request)
    {
        //consulta en sql
        // $sql = "SELECT dat.*, symbol, cpm.`description` AS payment_method, acc.`name` AS account FROM
        // (SELECT tab.id, tab.series, tab.number, tab.`currency_type_id`, payment_method_id, account_id, tab.total, 'Venta' AS operation_type, NULL AS detail
        // FROM payments pay
        // INNER JOIN documents tab ON tab.id = pay.document_id
        // UNION ALL
        // SELECT tab.id, tab.series, tab.number, tab.`currency_type_id`, payment_method_id, account_id, tab.total, 'Nota de Venta' AS operation_type, NULL
        // FROM payments pay
        // INNER JOIN sale_notes tab ON tab.id = pay.sale_note_id
        // ) dat
        // INNER JOIN cat_currency_types cur ON cur.id = dat.currency_type_id
        // INNER JOIN cat_payment_methods cpm ON cpm.`id` = dat.payment_method_id
        // INNER JOIN accounts acc ON acc.id = dat.account_id";

        // $payments = DB::connection('tenant')->select($sql);

        // dd($payments);

        $payment_documents = Payment::select('payments.id', 'documents.series', 'documents.number', 'payments.currency_type_id', 'payments.total', 'cat_payment_methods.description AS payment_method',
            'accounts.name AS account', 'symbol', 'persons.name AS customer', 'payments.created_at')
            ->join('documents', function ($join) {
                $join->on('documents.id', '=', 'payments.table_id')
                     ->where('payments.table_name', 'documents');
            })
            ->join('persons', 'persons.id', 'documents.customer_id')
            ->join('cat_payment_methods', 'cat_payment_methods.id', 'payments.payment_method_id')
            ->join('accounts', 'accounts.id', 'payments.account_id')
            ->join('cat_currency_types', 'cat_currency_types.id','payments.currency_type_id');

        $payment_sale_notes = Payment::select('payments.id', 'sale_notes.series', 'sale_notes.number', 'payments.currency_type_id', 'payments.total', 'cat_payment_methods.description AS payment_method',
            'accounts.name AS account', 'symbol', 'persons.name AS customer', 'payments.created_at')
            ->join('sale_notes', function ($join) {
                $join->on('sale_notes.id', 'payments.table_id')
                     ->where('payments.table_name', 'sale_notes');
            })
            ->join('persons', 'persons.id', 'sale_notes.customer_id')
            ->join('cat_payment_methods', 'cat_payment_methods.id', 'payments.payment_method_id')
            ->join('accounts', 'accounts.id', 'payments.account_id')
            ->join('cat_currency_types', 'cat_currency_types.id', 'payments.currency_type_id');

        $records = $payment_documents->union($payment_sale_notes)->orderby('created_at', 'desc');

        return new PaymentCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function totals(Request $request)
    {
        $totalPEN1 = $this->query_total('documents');
        $totalPEN2 = $this->query_total('sale_notes');

        $totalUSD1 = $this->query_total('documents', 'USD');
        $totalUSD2 = $this->query_total('sale_notes', 'USD');

        $totalPEN = [
            'quantity' => $totalPEN1->quantity + $totalPEN2->quantity,
            'total' => is_null($totalPEN1->total) && is_null($totalPEN2->total) ? '0.00': ($totalPEN1->total + $totalPEN2->total)
        ];

        $totalUSD = [
            'quantity' => $totalUSD1->quantity + $totalUSD2->quantity,
            'total' => is_null($totalUSD1->total) && is_null($totalUSD2->total) ? '0.00': ($totalUSD1->total + $totalUSD2->total)
        ];

        $data = [
            'totalPEN' => $totalPEN,
            'totalUSD' => $totalUSD
        ];

        return compact('data');
    }

    public function query_total($table, $currency_type_id='PEN')
    {
        $total = DB::connection('tenant')
                ->select("SELECT SUM(pay.`total`) AS total, COUNT(*) AS quantity
                        FROM payments pay
                        WHERE pay.`table_name` = '".$table."' AND pay.`currency_type_id` = '".$currency_type_id."'");

        return $total[0];
    }

    public function create()
    {
        return view('tenant.items.form');
    }

    public function tables()
    {
        $currency_types = CurrencyType::whereActive()->orderByDescription()->get();
        $payment_methods = PaymentMethod::whereActive()->get();
        $accounts = Account::all();

        return compact('currency_types', 'accounts', 'payment_methods');
    }

    public function record($id)
    {
        $record = new AccountResource(Account::findOrFail($id));

        return $record;
    }

    public function store(PaymentRequest $request)
    {
        if($request->input('total') > $request->input('total_debt'))
        {
            return [
                'success' => false,
                'message' => 'El valor recibido no debe ser mayor a la deuda'
            ];
        }

        if($request->input('total') != 0)
        {
            $fact = DB::connection('tenant')->transaction(function () use ($request){

                $table_name = $request->input('table_name');
                $table_id = $request->input('table_id');
                $payment_method_id = $request->input('payment_method_id');
                $currency_type_id = $request->input('currency_type_id');
                $account_id = $request->input('account_id');
                $description = $request->input('description');
                $description = $request->input('description');
                $total =  $request->input('total');

                $total2 = $total;

                if($table_name == 'sale_notes')
                {
                    $table = SaleNote::find($table_id);
                }
                else if($table_name == 'documents')
                {
                    $table = Document::find($table_id);
                }
                else
                {
                    $table = Purchase::find($table_id);
                    $total2 = $total*-1;
                }

                $table->total_paid += $total;
                $table->save();

                $payment = $this->savePayment($table_name, $table_id, null, $payment_method_id, $currency_type_id, $account_id, $description, $total2);

                return $payment;
            });
        }

        return [
            'success' => true,
            'message' => 'Pago registrado con éxito'
        ];

    }

    // public function destroy($id)
    // {
    //     $fact = DB::connection('tenant')->transaction(function () use ($id){
    //         $payment = Payment::findOrFail($id);
    //         $document_id = $payment->document_id;
    //         $account_id = $payment->account_id;
    //         $sale_note_id = $payment->sale_note_id;
    //         $pos_id = $payment->pos_id;
    //         $total = $payment->total;
    //         $payment->delete();

    //         if(is_null($document_id))
    //         {
    //             $document = SaleNote::find($sale_note_id);
    //             $document->total_paid -= $total;
    //             $document->save();
    //         }
    //         else if(is_null($sale_note_id))
    //         {
    //             $document = Document::find($document_id);
    //             $document->total_paid -= $total;
    //             $document->save();
    //         }

    //         $pos = Pos::find($pos_id);
    //         $pos->close_amount -= $total;
    //         $pos->save();

    //         $account = Account::find($account_id);
    //         $account->current_balance -= $total;
    //         $account->save();
    //     });

    //     return [
    //         'success' => true,
    //         'message' => 'Pago eliminado con éxito'
    //     ];
    // }
}
