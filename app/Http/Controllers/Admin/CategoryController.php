<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:read_categories')->only('index');
        $this->middleware('permission:create_categories')->only('create');
        $this->middleware('permission:update_categories')->only('edit');
        $this->middleware('permission:delete_categories')->only('destroy');
    }

    public function index(Request $request)
    {
        $categories=Category::when($request->search,function ($q) use ($request){
//            return $q->where('name','like','%'.$request->search.'%');
            return $q->whereTranslationLike('name', '%' . $request->search . '%');
        })->latest()->paginate(5);
        return view('dashboard.categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.categories.create');
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
//            'ar.name'=>'required|unique:category_translations,name',
        ];
        //loop on array
        foreach (config('translatable.locales') as $local){
            $rules+=[$local.'.name'=>['required',Rule::unique('category_translations','name')]];
        }
        $this->validate($request,$rules);

        //create
        Category::create($request->all());
        //success
        session()->flash('success',__('site.added_successfully'));
        //redirect
        return redirect()->route('dashboard.categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('dashboard.categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //validate
        $rules=[
//            'name'=>'required|unique:categories,name,'.$category->id,
        ];
        foreach (config('translatable.locales') as $locale){
            $rules+=[$locale.'.name'=>['required',Rule::unique('category_translations','name')->ignore($category->id,'category_id')]];
        }
        $this->validate($request,$rules);

        //update
        $category->update($request->all());
        //success
        session()->flash('success',__('site.updated_successfully'));
        //redirect
        return redirect()->route('dashboard.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->products->count() > 0){

            return redirect()->route('dashboard.products.index')->withErrors([
                'message'=>'plz delete the product first',
            ]);
        }
        $category->delete();
        //success
        session()->flash('success',__('site.deleted_successfully'));
        //redirect
        return redirect()->route('dashboard.categories.index');
    }
}
