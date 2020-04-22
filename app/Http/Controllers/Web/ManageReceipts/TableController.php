<?php


namespace App\Http\Controllers\Web\ManageReceipts;


use App\Http\Controllers\WebBaseController;
use App\Http\Requests\CreateTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends WebBaseController
{
    public function index(Request $request)
    {
        if ($request->ajax){
            $tables = Table::all();
            return response()->json([
                'tables' =>  $tables
            ], 200);
        }
        return view('workspace.manageReceipts.table');
    }

    public function show(Table $table){
        return response()->json([
            'table' => $table
        ]);
    }

    public function update(UpdateTableRequest $request, Table $table){
        $table->update($request->validated());
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function store(CreateTableRequest $request){
        $table = new  Table();
        $table->fill( $request->only(['name', 'note']));
        if ($table->save()){
            return response()->json([
                'status' => 'success'
            ],201);
        }
        return response()->json([
            'status' => 'fails'
        ],422);
    }

    public function destroy(Table $table){
        $table->delete();
        return response()->json([
            'status' =>  'success',
        ], 200);
    }
}
