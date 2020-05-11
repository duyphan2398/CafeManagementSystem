<?php


namespace App\Http\Controllers\Web\Warehouse;


use App\Http\Controllers\WebBaseController;
use App\Http\Requests\NewMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Material;
use Illuminate\Http\Request;


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
           $material = Material::query()->create($request->validated());
            if ($material){
                return response()->json([
                    'status' => 'success',
                    'material' => $material
                ],201);
            }
            return response()->json([
                'status' => 'fail',
            ],500);

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
        if ($material && $material->update($request->except('unit'))){
            return response()->json([
                'status' => 'success',
                'material' => $material
            ],201);
        }
        return response()->json([
            'status' => 'fail',
        ],404);
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
