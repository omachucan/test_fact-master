<?php

namespace App\Traits;

use App\Models\Tenant\Account;

trait AccountTrait
{   
    public function updateBalance($account_id, $total) {
        
        $account = Account::findOrFail($account_id);
        $account->current_balance = $account->current_balance + $total;
        $account->save();

        return $account;
    }
}