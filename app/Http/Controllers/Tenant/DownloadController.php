<?php
namespace App\Http\Controllers\Tenant;

use App\CoreDevPro\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\CoreDevPro\DevPro;
use App\CoreDevPro\Template;
use App\Models\Tenant\Company;
use App\Models\Tenant\Quotation;
use App\Models\Tenant\Establishment;
use Mpdf\Mpdf;
use Exception;
use Illuminate\Support\Facades\DB;

class DownloadController extends Controller
{
    use StorageDocument;
    
    public function downloadExternal($model, $type, $external_id, $format = null) {

        $modelName = $model;
        $model = "App\\Models\\Tenant\\".ucfirst($model);
        //var_dump($model);
        $document = $model::where('external_id', $external_id)->first();
        
        if (!$document) throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        
        if ($format != null) $this->reloadPDF($document, 'invoice', $format);
        try{
           
            return $this->download($type, $document);
       
        }catch(\Exception $e){
            if($modelName == 'voided' )
            {
                $this->updateFiles($modelName,$document->id);
             return    $this->downloadExternal($modelName, $type, $external_id, $format);
            }else{
                return  $e->getMessage();
            }
           
        }
        
    }

    public function downloadExternal2($model, $type, $id, $format = null) {
        
        if($model == 'salenote')
        {
            $model = 'SaleNote';
        }

        $model = "App\\Models\\Tenant\\".ucfirst($model);
        $document = $model::where('id', $id)->first();
        
        if (!$document) throw new Exception("El código {$id} es inválido, no se encontro documento relacionado");
        
        if ($format != null) $this->reloadPDF2($document, 'invoice', $format);
        
        return $this->download($type, $document);
    }
    
    public function download($type, $document) {
        switch ($type) {
            case 'pdf':
                $folder = 'pdf';
                break;
            case 'xml':
                $folder = 'signed';
                break;
            case 'cdr':
                $folder = 'cdr';
                break;
            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }
        return $this->downloadStorage($document->filename, $folder);
    }
    
    public function toPrint($model, $external_id, $format = null) {
        $model = "App\\Models\\Tenant\\".ucfirst($model);
        $document = $model::where('external_id', $external_id)->first();
       // $listadoestable = Establishment::all();
        //dd($descripcion);    

        if (!$document) throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        
        if ($format != null) $this->reloadPDF($document, 'invoice', $format);
        
        $temp = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($temp, $this->getStorage($document->filename, 'pdf'));

         //dd($document);
        
        return response()->file($temp);
    }

    public function toPrint2($model, $id, $format = null) {
    
        if($model == 'salenote')
        {
            $model = 'SaleNote';
        }
        
        $model = "App\\Models\\Tenant\\".ucfirst($model);
        $document = $model::where('id', $id)->first();
        
            if (!$document) throw new Exception("El código {$id} es inválido, no se encontro documento relacionado");
                
            if ($format != null) $this->reloadPDF2($document, 'simple', $format);
                
            $temp = tempnam(sys_get_temp_dir(), 'pdf');
            file_put_contents($temp, $this->getStorage($document->filename, 'pdf'));
              
            return response()->file($temp);
        }
    
    /**
     * Reload PDF
     * @param  ModelTenant $document
     * @param  string $format
     * @return void
     */
    private function reloadPDF($document, $type, $format) {
        (new DevPro)->createPdf($document, $type, $format);
    }

    private function reloadPDF2($document,$type, $format){
        (new DevPro)->createPdf2($document, $type, $format);

    }

    public function updateFiles($model,$id) {

        $type = $model;
        $model = "App\\Models\\Tenant\\".ucfirst($model);
        //var_dump($model);
        $document = $model::find($id);
        
        if (!$document) throw new Exception("El código {$id} es inválido, no se encontro documento relacionado");
        
        $fact = DB::connection('tenant')->transaction(function () use ($document,$type) {
            $DevPro = new DevPro();
            $DevPro->createPdf($document,$type,'a4');
            return $DevPro;
        });

        return ['message' => '¡Archivo actualizado correctamente!'];
    }
}
