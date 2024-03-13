<?php

namespace App\Traits;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Person;
use App\Models\Tenant\User;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\Warehouse;

/**
 *
 */
trait ReportTrait
{
    /**
     * Get type doc
     * @param  string $documentType
     * @return int
     */
    public function getTypeDoc($documentType) {

        foreach (DocumentType::all() as $item) {
            if (mb_strtoupper($item->description) == $documentType) return $item->id;
        }

        return null;
    }

    public function getUser($user) {

        $user = User::where('name', $user)->first();

        return (is_null($user)?null:$user->id);
    }

    public function getPerson($person, $type = 'customers') {

        $item_split = explode('-', $person);
        $number = trim($item_split[0]);

        $person = Person::where('number', $number)->where('type', $type)->first();

        return (is_null($person)?null:$person->id);
    }

    public function getEstablishment($establishment) {

        $establishment = Establishment::where('description', $establishment)->first();

        return (is_null($establishment)?null:$establishment->id);
    }

    public function getWarehouse($warehouse) {

        $warehouse = Warehouse::where('description', $warehouse)->first();

        return (is_null($warehouse)?null:$warehouse->id);
    }

    public function getItem($item) {

        $item_split = explode('::', $item);

        $i = (substr_count($item, '::') > 0)?1:0;

        if($i == 1)
        {
            $item = Item::where('internal_id', trim($item_split[0]))->first();
        }
        else
        {
            $item = Item::where('description', trim($item_split[0]))->first();
        }

        return (is_null($item)?null:$item->id);
    }

    public function getItems()
    {
        $items = Item::where('item_type_id', '01')->orderBy('description')->get()->transform(function ($row) {
            $description = (is_null($row->internal_id)?'':$row->internal_id . ' :: ').''.$row->description;
            return [
                'id' => $row->id,
                'description' => $description
            ];
        });

        return $items;
    }
}
