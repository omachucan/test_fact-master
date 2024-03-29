<?php

namespace App\Providers;

use App\Models\Tenant\Item;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\Document;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\PurchaseItem; 
use App\Models\Tenant\Kardex;
use Illuminate\Support\ServiceProvider;
use App\Traits\KardexTrait;

class KardexServiceProvider extends ServiceProvider
{
    use KardexTrait;
     
    public function boot()
    {        
        $this->sale();
        $this->purchase();
        $this->sale_note();
    }
 
    public function register()
    {
        
    }

    private function sale()
    {
        DocumentItem::created(function ($document_item) {
            $document = Document::whereIn('document_type_id',['01','03'])->find($document_item->document_id);
            if($document){

                $kardex = $this->saveKardex('sale', $document_item->item_id, $document_item->document_id, $document_item->quantity);
                
                if($document->state_type_id != 11){
                    $item = Item::find($document_item->item_id);
                    $item->stock -= $kardex->quantity;
                    $item->save();
                }
                
            }
        });
    }

    private function sale_note()
    {
        SaleNoteItem::created(function ($sale_note_item) {
            
            $sale_note = SaleNote::whereIn('document_type_id',['100'])->find($sale_note_item->sale_note_id);

            if($sale_note){

                $kardex = $this->saveKardex('sale-note', $sale_note_item->item_id, $sale_note_item->sale_note_id, $sale_note_item->quantity);

                $update = $sale_note->establishment_item()->firstOrNew(['item_id' => $sale_note_item->item_id]);
                    $update->quantity -= $kardex->quantity;
                    $update->save();
            }
        });
    }

    private function purchase()
    {
        PurchaseItem::created(function ($purchase_item) {

            $kardex = $this->saveKardex('purchase', $purchase_item->item_id, $purchase_item->purchase_id, $purchase_item->quantity);             

            $item = Item::find($purchase_item->item_id);
            $item->stock += $kardex->quantity;
            $item->save();
        });
    }
}
