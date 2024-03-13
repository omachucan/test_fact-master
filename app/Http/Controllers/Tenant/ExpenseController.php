<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Expense;
use App\Models\Tenant\Establishment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ExpenseRequest;
use App\Http\Resources\Tenant\ExpenseCollection;
use App\Http\Resources\Tenant\ExpenseResource;
use App\Models\Tenant\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        return view('tenant.expenses.index');
    }

    public function columns()
    {
        return [
            'description' => 'Descripción'
        ];
    }

    public function records(Request $request)
    {
        $records = Expense::where($request->column, 'like', "%{$request->value}%")
            ->orderBy('date_of_issue');

        return new ExpenseCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function totals(Request $request)
    {
        $totalPEN = 

        $totalUSD = $this->total($request);
        $totalUSD = $this->total($request, 'USD');

        $totalPEN = [
            'quantity' => $totalPEN->quantity,
            'total' => is_null($totalPEN->total) ? '0.00': $totalPEN->total 
        ];

        $totalUSD = [
            'quantity' => $totalUSD->quantity,
            'total' => is_null($totalUSD->total) ? '0.00' : $totalUSD->total 
        ];
        
        $data = [
            'totalPEN' => $totalPEN,
            'totalUSD' => $totalUSD
        ];

        return compact('data');
    }

    public function create()
    {
        return view('tenant.items.form');
    }

    public function tables()
    {
        $currency_types = CurrencyType::whereActive()->orderByDescription()->get();
        $accounts = Account::get();

        return compact('currency_types', 'accounts');
    }

    public function record($id)
    {
        $record = new ExpenseResource(Expense::findOrFail($id));

        return $record;
    }

    public function store(ExpenseRequest $request)
    {
        $expense = DB::connection('tenant')->transaction(function () use ($request){
            
            $expense_id = $request->input('id');
            $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();

            $expense = Expense::firstOrNew(['id' => $expense_id]);

            if($request->has_voucher)
            {
                $detail_voucher = [
                    'company_number' => $request->detail_voucher['company_number'],
                    'company_name' => $request->detail_voucher['company_name'],
                    'document_type' => $request->detail_voucher['document_type'],
                    'document_number' => $request->detail_voucher['document_number']
                ];

                $detail_voucher = json_encode($detail_voucher);
                $expense->detail_voucher  = $detail_voucher;
            }
            
            $expense->user_id = auth()->id();
            $expense->establishment_id = $establishment->id;
            $expense->fill($request->all());
            $expense->save();

            $account = Account::findOrFail($request->input('account_id'));
            $account->current_balance -= $request->input('total');
            $account->save();

            return $expense;
        });

        return [
            'success' => true,
            'message' => ($request->input('id')) ? 'Gasto editado con éxito' : 'Gasto registrado con éxito',
            'id' => $expense->id
        ];
        
    }

    public function destroy(Expense $expense)
    {
        $expense = DB::connection('tenant')->transaction(function () use ($expense){
            //$expense = Expense::findOrFail($id);
            //$account_id = $expense->account_id;
            //$total = $expense->total;
            
            $account = Account::findOrFail($expense->account_id);
            $account->current_balance += $expense->total;
            $account->save();

            $expense->delete();
        });

        return [
            'success' => true,
            'message' => 'Gasto eliminado con éxito'
        ];
    }

    public function total($request, $currency_type_id = 'PEN')
    {
        $total = DB::connection('tenant')
                        ->table('expenses')
                        ->select(DB::raw('COUNT(*) as quantity'), DB::raw('SUM(total) as total'))
                        ->where($request->column, 'like', "%{$request->value}%")
                        ->where('currency_type_id', $currency_type_id)
                        ->first();

        return $total;
    }
}