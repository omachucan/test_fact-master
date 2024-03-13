<?php

namespace App\Http\ViewComposers\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\PosConfiguration;

class CompanyViewComposer
{
    public function compose($view)
    {
        $view->vc_company = Company::first();
        $view->vs_pos_configuration = PosConfiguration::first();
    }
}