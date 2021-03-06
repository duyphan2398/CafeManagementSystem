<?php


namespace App\Http\Controllers\Web\ManageReceipts;


use App\Events\ChangeStateTableEvent;
use App\Http\Controllers\WebBaseController;
use App\Http\Requests\CreateTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Models\Receipt;
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

    public function changeUserUsing(Table $table){
        $table->user_id = null;
        $table->save();
        event(new ChangeStateTableEvent('Change state User_using to null'));
        return response()->json([
            'status' =>  'success',
        ], 200);
    }
    public function changeStatus(Table $table){
        $receipt = Receipt::where('table_id', '=', $table->id)->where('status', '<>', 3)->first();
        $receipt->products()->detach();
        $receipt->delete();
        $table->status = 'Empty';
        $table->save();
        event(new ChangeStateTableEvent('Change table status to Empty'));
        return response()->json([
            'status' =>  'success',
        ], 200);
    }
}
