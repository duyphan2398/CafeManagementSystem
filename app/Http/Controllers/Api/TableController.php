<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Models\Table;

class TableController extends ApiBaseController
{
    public function index(){
        return response()->json([
            'tables'    => Table::all(),
            'message'   =>'success'
        ],200);
    }
}
