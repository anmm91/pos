<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_orders')->only('index');
        $this->middleware('permission:update_orders')->only('edit');
        $this->middleware('permission:delete_orders')->only('destroy');
    }

    public function index(Request $request){
//        $orders=Order::paginate(5);
//        return view('dashboard.orders.index',compact('orders'));
        $orders=Order::whereHas('client',function ($q) use($request){
           return $q->where('name','like','%'.$request->search.'%');
        })->paginate(5);

        return view('dashboard.orders.index',compact('orders'));
    }

    public function products(Order $order){

        $products=$order->products;
        return view('dashboard.orders._product',compact('products','order'));
    }

    public function destroy(Order $order){
        foreach ($order->products as $product){
            $product->update([
               'stock'=>$product->stock + $product->pivot->quantity,
            ]);
        }
        $order->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect()->route('dashboard.orders.index');
    }
}
