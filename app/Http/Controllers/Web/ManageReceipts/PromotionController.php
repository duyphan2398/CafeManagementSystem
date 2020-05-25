<?php


namespace App\Http\Controllers\Web\ManageReceipts;


use App\Http\Controllers\WebBaseController;
use App\Http\Requests\CreatePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;
use App\Transformers\PromotionTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromotionController extends WebBaseController
{
    public function index(Request $request){
        $this->authorize('index', Promotion::class);
        if ($request->ajax) {
            return response()->json([
                'promotions' => Promotion::all(),
                'messages'   => 'success'
            ],200);
        }
        return view('workspace.manageReceipts.promotion');
    }

    public function destroy(Promotion $promotion){
        $this->authorize('destroy', Promotion::class);
        DB::beginTransaction();
        try {
            Product::where('promotion_id', $promotion->id)->update([
                'promotion_id' => null
            ]);
            $promotion->delete();

            DB::commit();
            return response([
                'status' => 'success'
            ],200);
        }
        catch (\Exception $exception){
            DB::rollBack();
            return response([
                'status' => 'fail'
            ],400);
        }

    }

    public function create(CreatePromotionRequest $request){
        $this->authorize('create', Promotion::class);
        $promotion = new  Promotion();
        $promotion->fill($request->only(['name', 'description']));
        $promotion->start_at = Carbon::parse($request->start_at);
        $promotion->end_at  = Carbon::parse($request->end_at);
        $promotion->sale_percent = $request->sale_percent / 100;
        $promotion->days = json_encode($request->days);
        if ($promotion->save()){
            return response()->json([
                'status' => 'success'
            ],201);
        }
        return response()->json([
            'status' => 'fails'
        ],422);
    }

    public function show(Promotion $promotion){
        $this->authorize('show', Promotion::class);
        return response()->json([
            'promotion' => $promotion,
            'status'    =>  'success'
        ],200);
    }

    public function update(UpdatePromotionRequest $request, Promotion $promotion){
        $this->authorize('update', Promotion::class);
        $promotion->update([
            'name'                  => $request->name,
            'description'           => $request->description,
            'start_at'              => Carbon::parse($request->start_at),
            'end_at'                => Carbon::parse($request->end_at),
            'sale_percent'          => ($request->sale_percent)/ 100,
            'days'                  => json_encode($request->days)
        ]);
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function showProducts(Request $request, Promotion $promotion){
        $product_other =  Product::whereDoesntHave('promotions',function($query) use ($promotion) {
            $query->where('promotion_id', $promotion->id);
        })->get();

        return response()->json([
            'promotion'         => (new PromotionTransformer)->transform($promotion),
            'product_orther'    => $product_other
        ], 200);
    }

    public function updateProducts(Request $request, Promotion $promotion){
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'required|integer|exists:products,id',
        ]);
        $promotion->products()->sync($request->products);
        return response()->json([
            'message' => 'success'
        ],200);
    }
}
