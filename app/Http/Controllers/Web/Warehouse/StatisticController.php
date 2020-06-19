<?php


namespace App\Http\Controllers\Web\Warehouse;


use App\Http\Controllers\WebBaseController;
use App\Models\Receipt;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class StatisticController extends WebBaseController
{
    public function __construct()
    {
        if (Gate::denies('')) {
            return response()->json(['error' => 'Not authorized.'], 403);
        }
    }

    public function index(){
        return view('workspace.warehouse.statistic');
    }

    public function dataDiagram1(){
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

    public function dataDiagram2(){
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

    public function dataDiagram3(){
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
}
