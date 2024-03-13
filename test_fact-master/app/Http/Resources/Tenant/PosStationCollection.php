<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PosStationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    private $decimal;

    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {

            return [
                'id' => $row->id,
                'name' => $row->name,
                'description' => $row->description,
                'establishment_description' => is_null($row->establishment_id)?'':$row->establishment->description,
                'warehouse_description' => is_null($row->warehouse_id)?'':$row->warehouse->description
            ];
        });
    }
}