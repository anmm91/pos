<?php

namespace App\Http\Controllers\Admin\Client;

use App\Category;
use App\Client;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create_orders')->only('create');
//        $this->middleware('permission:update_orders')->only('edit');
//        $this->middleware('permission:read_orders')->only('index');
//        $this->middleware('permission:destroy_orders')->only('destroy');
    }

    public function index()
    {

    }
    public function create(Client $client){
        $categories=Category::with('products')->get();
        return view('dashboard.clients.orders.create',compact('categories','client'));
    }

    public function store(Request $request,Client $client){
        //validate

//        $rules=[
//            'products'=>'required|array',
//            'quantities'=>'required|array',
//        ];
//        $this->validate($request,$rules);
//
//        //create order for each client
//        $order=$client->orders()->create([]);
//        //loop for products id
//        $total_price=0;
//        foreach ($request->product_ids as $index=>$product_id){
//
//            $product=Product::findOrFail($product_id);
//            $total_price+=$product->sale_price;
//            $order->products()->attach($product_id,['quantity'=>$request->quantities[$index]]);
//            $product->update([
//                'stock'=>$product->stock - $request->quantities[$index]
//            ]);
//        }
//        $order->update([
//            'total_price'=>$total_price
//        ]);
//=========================================================================================
//==============================================

        //validate
        $rules=[
            'products'=>'required|array'
        ];
        $this->validate($request,$rules);

        //create order for each client
        $order=$client->orders()->create([]);
        $order->products()->attach($request->products);
        $total_price=0;
        foreach ($request->products as $id=>$quantity){
            $product=Product::findOrFail($id);
            $total_price+=$quantity['quantity'] * $product->sale_price;
            $product->update([
               'stock'=>$product->stock - $quantity['quantity']
            ]);
        }

        $order->update([
            'total_price'=>$total_price
        ]);

        //success
        session()->flash('success',__('site.added_successfully'));
        //redirect
        return redirect()->route('dashboard.orders.index');
    }
    public function edit(Client $client,Order $order){


    }
    public function update(Request $request ,Client $client ,Order $order){

    }
    public function destroy(Client $client ,Order $order){

    }
}
