<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;

class PosShiftCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {
            return [

                'id' => $row->id,
                'user_id' => $row->user_id,
                'user' => $row->user,
                'open_amount' => $row->open_amount,
                'close_amount' => $row->close_amount,
                'sales_count' => $row->sales_count,
                'balance' => number_format($row->open_amount + $row->close_amount, 2),
                'status' => $row->status,
                'start_date' => Carbon::parse($row->start_date)->format('d/m/Y H:i'),
                'closed_date' => Carbon::parse($row->closed_date)->format('d/m/Y H:i')
            ];
        });
    }
}