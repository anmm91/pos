<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:create_products')->only('create');
        $this->middleware('permission:read_products')->only('index');
        $this->middleware('permission:update_products')->only('edit');
        $this->middleware('permission:delete_products')->only('destroy');
    }

    public function index(Request $request)
    {

        $products=Product::when($request->search,function ($q) use($request){
            return $q->whereTranslationLike('name','%' . $request->search . '%');
        })->when(is_numeric($request->category_id),function ($q) use($request){
            return $q->where('category_id',$request->category_id);
        })->latest()->paginate(2);
        $categories=Category::all();
        return view('dashboard.products.index',compact('products','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories=Category::all();
        return view('dashboard.products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate
        $rules=[
            'category_id'=>'required|exists:categories,id'
        ];
        foreach (config('translatable.locales') as $locale){
            $rules+=[$locale.'.name'=>'required|unique:product_translations,name'];
            $rules+=[$locale.'.description'=>'required'];
        }
        $rules+=[
            "purchase_price"=>'required',
            "sale_price"=>'required',
            "stock"=>'required',
        ];
        $this->validate($request,$rules);
        //create
        $request_data=$request->except('image');
        if($request->image){
            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/product_images/'.$request->image->hashName()));

            $request_data['image']=$request->image->hashName();
        }
            Product::create($request_data);
        //success
        session()->flash('success',__('site.added_successfully'));
        //redirect
        return redirect()->route('dashboard.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories=Category::all();
        return view('dashboard.products.edit',compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // validate
        $rules=[
            'category_id'=>'required|exists:categories,id',
            'purchase_price'=>'required',
            'sale_price'=>'required',
            'stock'=>'required',
        ];
        //foreach for array
        foreach (config('translatable.locales') as $locale){
//            $rules+=[$locale.'.name'=>'required|unique:product_translations,name,'.$product->category_id];
            $rules+=[$locale.'.name'=>['required',Rule::unique('product_translations','name')->ignore($product->id,'product_id')]];
            $rules+=[$locale.'.description'=>'required'];

        }
        $this->validate($request,$rules);
        //update
        $request_data=$request->except('image');
        if($request->image){
            if($product->image != 'default.png'){

                //delete old image
                Storage::disk('public_uploads')->delete('/product_images/'.$product->image->hashName());
            }
            //upload new image
            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/product_images/'.$request->image->hashName()));

            //save in database
            $request_data['image']=$request->image->hashName();
        }

        //update
        $product->update($request_data);

        //success
        session()->flash('success',__('site.updated_successfully'));

        //redirect
        return redirect()->route('dashboard.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //delete image
        if($product->image != 'default.png'){
            Storage::disk('public_uploads')->delete('/product_images/'.$product->image);
        }
        //delete item
        $product->delete();
        //success
        session()->flash('success',__('site.deleted_successfully'));
        //redirect
        return redirect('dashboard.products.index');
    }
}
