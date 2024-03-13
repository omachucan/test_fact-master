<?php

namespace App\Traits;
use App\Models\Tenant\Payment;


trait PaymentTrait
{
    use AccountTrait;

    public function savePayment($table_name, $table_id, $customer_id, $payment_method_id, $currency_type_id, $account_id, $description, $total) {
        
        $register = Payment::create([
            'table_name' => $table_name,
            'table_id' => $table_id,
            'customer_id' => $customer_id,
            'payment_method_id' => $payment_method_id,
            'date_of_issue' => date('Y-m-d'),
            'currency_type_id' => $currency_type_id,
            'account_id' => $account_id,
            'description' => $description,
            'total' => $total
        ]);

        $this->updateBalance($account_id, $total);

        return $register;
    }

    public function deletePayment($payment_id) {
        
        $payment = Payment::findOrFail($payment_id);
        
        $this->updateBalance($payment->account_id, $payment->total, '-');

        $payment->delete();

        return $payment;
    }
}