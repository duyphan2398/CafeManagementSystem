<?php


namespace App\Http\Controllers\Web\Warehouse;


use App\Http\Controllers\WebBaseController;

class MaterialController extends WebBaseController
{
    public function index(){
        return view('workspace.warehouse.material');
    }

}
