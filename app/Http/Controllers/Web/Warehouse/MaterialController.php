<?php


namespace App\Http\Controllers\Web\Warehouse;


use App\Http\Controllers\WebBaseController;
use App\Http\Requests\NewMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Material;
use App\Transformers\MaterialTransformer;
use App\Transformers\ReceiptTranformer;
use Barryvdh\DomPDF\Facade as PDF2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class MaterialController extends WebBaseController
{
    public function index(){
        $this->authorize('index', Material::class);
        return view('workspace.warehouse.material');
    }

    public function show(){
        $this->authorize('show', Material::class);
        $materials = Material::query()->orderBy('updated_at','desc')->paginate(10);
        return response()->json(['materials' => $materials],200);
    }

    public function create(NewMaterialRequest $request){
        $this->authorize('create', Material::class);
        if ($request->validated()) {
            DB::beginTransaction();
            try {
                $material = Material::query()->create($request->validated());
                //in PDF kèm theo
                $pdf = PDF2::loadView('PDF.material', [
                    'material'           =>(new MaterialTransformer)->transform($material),
                    'diff'               => $request->amount,
                    'user_name'          => Auth::user()->name.'( '.Auth::user()->username.' )'
                ]);
                $url = '\material\\';
                Storage::disk('public')->delete($url.$material->id.'.pdf');
                Storage::disk('public')->put($url.$material->id.'.pdf', $pdf->output());
                DB::commit();
                return response()->json([
                    'url'               => $material->id.'.pdf',
                    'host'              => '/export/pdf/material/',
                    'status'            => 'success',
                    'material'          =>  $material,
                    'type_receipt'      =>  'Import_Receipt',
                ],201);
            }
            catch (\Exception $exception){
                DB::rollBack();
                response()->json([
                    'message'    => $exception->getMessage(),
                ],500);
            }
        }
        else{
            return response()->json([
                "status" =>"fails",
                "validated" =>$request->validated()
            ],422);
        }
    }

    public function getMaterial(Request $request){
        $this->authorize('getMaterial', Material::class);
        $materia = Material::find($request->material_id);
        if ($materia){
            return response()->json([
                'material' => $materia
            ],200);
        }
        return response()->json([
            'status' => 'Could Not Found Material'
        ],404);
    }

    public function update(UpdateMaterialRequest $request){
        $this->authorize('update', Material::class);
        $material = Material::query()->find($request->material_id);
        DB::beginTransaction();
        try {
            $amount = $material->amount;
            //in PDF kèm theo
            $material->update($request->except('unit'));
            $pdf = PDF2::loadView('PDF.material', [
                'material'           => (new MaterialTransformer)->transform($material),
                'diff'               => $material->amount - $amount,
                'user_name'          => Auth::user()->name.'( '.Auth::user()->username.' )'
            ]);
            $url = '\material\\';
            Storage::disk('public')->delete($url.$material->id.'.pdf');
            Storage::disk('public')->put($url.$material->id.'.pdf', $pdf->output());
            return response()->json([
                'url'               => $material->id.'.pdf',
                'host'              => '/export/pdf/material/',
                'status'            => 'success',
                'material'          => $material,
                'type_receipt'      =>  (($material->amount - $amount) > 0 ) ? 'Import_Receipt'  : 'Export_Receipt',
            ],201);
        }
        catch (\Exception $exception){
            DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage(),
            ],500);
        }
    }

    public function delete(Request $request){
        $this->authorize('delete', Material::class);
        $material = Material::query()->find($request->material_id_delete);
        $material->products()->detach();
        if ($material && $material->delete()){
            return response()->json([
                'status' => 'success',
            ],200);
        }
        return response()->json([
            'status' => 'fail',
        ],404);
    }

    public function search(Request $request){
        $this->authorize('search', Material::class);
        $materials = Material::query()->whereLike(['name','id'],$request->search)->get();
        if (count($materials) > 0){
            return response()->json([
                'status' => 'success',
                'materials' => $materials
            ],200);
        }
        return response()->json([
            'status' => 'fails'
        ],404);
    }
}
