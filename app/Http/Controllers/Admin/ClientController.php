<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:create_clients')->only('create');
        $this->middleware('permission:read_clients')->only('index');
        $this->middleware('permission:update_clients')->only('edit');
        $this->middleware('permission:delete_clients')->only('destroy');
    }

    public function index(Request $request)
    {
        $clients=Client::when($request->search,function ($q) use($request){
            return $q->where('name','like','%'.$request->search .'%')
                ->orWhere('phone','like','%'.$request->search .'%')
                ->orWhere('address','like','%'.$request->search .'%');
        })->latest()->paginate(5);
        return view('dashboard.clients.index',compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.clients.create');
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
            'name'=>'required',
            'phone'=>'required|array|min:1',
            'phone.0'=>'required',
            'address'=>'required'
        ];
        $this->validate($request,$rules);
        //create
        Client::create($request->all());
        //success
        session()->flash('success',__('site.added_successfully'));
        //redirect
        return redirect()->route('dashboard.clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {

        return view('dashboard.clients.edit',compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //validate
        $rules=[
            'name'=>'required',
            'phone'=>'required|array|min:1',
            'phone.0'=>'required',
            'address'=>'required'
        ];
        $this->validate($request,$rules);

        //validate
        $client->update($request->all());
        //success
        session()->flash('success',__('site.updated_successfully'));
        //redirect
        return redirect()->route('dashboard.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        //success
        session()->flash('success',__('site.deleted_successfully'));
        //redirect
        return redirect()->route('dashboard.clients.index');
    }
}
