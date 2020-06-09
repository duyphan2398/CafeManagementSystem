<?php


namespace App\Http\Controllers\Web\Warehouse;


use App\Http\Controllers\WebBaseController;
use App\Models\Material;
use App\Models\Receipt;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class StatisticController extends WebBaseController
{

    public function index(){
        if (\Illuminate\Support\Facades\Gate::allows('statistics')){
            return view('workspace.warehouse.statistic');
        }
        return response()->json(['error' => 'Not authorized.'],403);
    }

    public function dataDiagram1(){
        if (\Illuminate\Support\Facades\Gate::allows('statistics')){
        $data = [];
        $countMonth = Carbon::today()->subMonths(12);

        for ($i = 1; $i<=13; $i++){
            $receipts = Receipt::whereYear('created_at', '=', $countMonth->year)
                             ->whereMonth('created_at', '=', $countMonth->month)
                             ->get();
            $sale_excluded_total = 0;
            $sale_included_total = 0;
            foreach ($receipts as $receipt){
                $sale_excluded_total+= $receipt->sale_excluded_price;
                $sale_included_total+=$receipt->sale_included_price;
            }
            array_push($data, [
                'month' => $countMonth->format('M/Y'),
                'total_receipts' => $receipts->count(),
                'sale_excluded_total' =>$sale_excluded_total,
                'sale_included_total' => $sale_included_total,
            ]);
            $countMonth->addMonth();
        }

        return $data;
        }
        return response()->json(['error' => 'Not authorized.'],403);
    }

    public function dataDiagram2(){
        if (\Illuminate\Support\Facades\Gate::allows('statistics')){
        $data = [];
        $total = 0;
        $receipts = Receipt::query()->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->get();
        foreach ($receipts as $receipt){
            foreach ($receipt->products as $product){
                if (array_key_exists($product->name, $data)){
                    $data[$product->name] += $product->pivot->quantity;
                    $total+=  $product->pivot->quantity;
                }else {
                    $data[$product->name] = $product->pivot->quantity;
                    $total+=  $product->pivot->quantity;
                }
            }
        }
        $result = [];
        arsort($data);
        foreach ($data as $productName => $productTotal){
            array_push($result, [
                'name'                      => $productName,
                'total_product'             => $productTotal,
                'total_product_percent'     => round(($productTotal/$total)* 100, 2)
            ]);
        }
        return response()->json([
            'data'  => $result,
            'total' => $total
        ],200);
        }
        return response()->json(['error' => 'Not authorized.'],403);
    }

    public function dataDiagram3(){
        if (\Illuminate\Support\Facades\Gate::allows('statistics')){
        $data = [];
        $countMonth = Carbon::today()->subMonths(12);

        for ($i = 1; $i<=13; $i++){
            $schedules = Schedule::whereYear('date', '=', $countMonth->year)
                ->whereMonth('date', '=', $countMonth->month)
                ->get();
            $total_time = 0;
            foreach ($schedules as $schedule){
                $total_time+= $schedule->total_time;

            }
            array_push($data, [
                'month' => $countMonth->format('M/Y'),
                'total_schedules' => $total_time,
                'total' => $schedules->count()
            ]);
            $countMonth->addMonth();
        }
        return $data;
    }
    return response()->json(['error' => 'Not authorized.'],403);
    }


    public function dataDiagram4_1(Request $request){
        $results = collect([]);
        $total_month = 0;
        $receipts = Receipt::query()->whereMonth('created_at', '=', Carbon::today()->month)->get();

        foreach (Material::query()->orderBy('name')->get() as $material) {
            $result = [
                'id'                        => null,
                'name'                      => null,
                'unit'                      => null,
                'total_last_month'     => 0
            ];
            $result['id'] = $material->id;
            $result['name'] = $material->name;
            $result['unit'] = $material->unit;
            foreach ($receipts as $receipt){
                $products = $receipt->products()->whereHas('materials', function ($query) use ($material){
                    $query->where('id', $material->id);
                })->get();
                if ($products->count() > 0) {
                    foreach ($products as $product){
                        $quantity_material = $product->materials()->whereKey($material->id)->first()->pivot->quantity;
                        $total_month += $quantity_material*$product->pivot->quantity;
                    }
                }
            }
            $result['total_month'] = $total_month;
            $results->push($result);
        }
        return $this->paginate($results, 10, $request->page);
        dd($this->paginate($results, 10, 2));
    }

    public function dataDiagram4_2(Request $request){
        $results = collect([]);
        $total_last_month = 0;
        $receipts = Receipt::query()->whereMonth('created_at', '=', Carbon::today()->subMonth(1)->month)->get();

        foreach (Material::query()->orderBy('name')->get() as $material) {
            $result = [
                'id'                        => null,
                'name'                      => null,
                'unit'                      => null,
                'total_last_month'     => 0
            ];
            $result['id'] = $material->id;
            $result['name'] = $material->name;
            $result['unit'] = $material->unit;
            foreach ($receipts as $receipt){
                $products = $receipt->products()->whereHas('materials', function ($query) use ($material){
                    $query->where('id', $material->id);
                })->get();
                if ($products->count() > 0) {
                    foreach ($products as $product){
                        $quantity_material = $product->materials()->whereKey($material->id)->first()->pivot->quantity;
                        $total_last_month += $quantity_material*$product->pivot->quantity;
                    }
                }
            }
            $result['total_last_month'] = $total_last_month;
            $results->push($result);
        }
        return $this->paginate($results, 10, $request->page);
    }

    public function dataDiagram4_3(Request $request){
        $results = collect([]);
        $total_last_last_month = 0;
        $receipts = Receipt::query()->whereMonth('created_at', '=', Carbon::today()->subMonth(2)->month)->get();

        foreach (Material::query()->orderBy('name')->get() as $material) {
            $result = [
                'id'                        => null,
                'name'                      => null,
                'unit'                      => null,
                'total_last_last_month'     => 0
            ];
            $result['id'] = $material->id;
            $result['name'] = $material->name;
            $result['unit'] = $material->unit;
            foreach ($receipts as $receipt){
                $products = $receipt->products()->whereHas('materials', function ($query) use ($material){
                    $query->where('id', $material->id);
                })->get();
                if ($products->count() > 0) {
                    foreach ($products as $product){
                        $quantity_material = $product->materials()->whereKey($material->id)->first()->pivot->quantity;
                        $total_last_last_month += $quantity_material*$product->pivot->quantity;
                    }
                }
            }
            $result['total_last_last_month'] = $total_last_last_month;
            $results->push($result);
        }
        return $this->paginate($results, 10, $request->page);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
