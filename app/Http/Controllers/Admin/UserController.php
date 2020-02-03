<?php

namespace App\Http\Controllers\Admin;
use App\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:read_users')->only('index');
        $this->middleware('permission:update_users')->only('edit');
        $this->middleware('permission:create_users')->only('create');
        $this->middleware('permission:delete_users')->only('destroy');
    }

    public function index(Request $request)
    {
//        if($request->search){    //search has value not empty
//
//            $users=User::where('first_name','like','%'.$request->search.'%')
//                ->orWhere('last_name','like','%'.$request->search.'%')->get();
//        }else{
//
//            $users=User::whereRoleIs('admin')->get();
//        }
        //====================================================== strong search technique=========
        $users=User::whereRoleIs('admin')->where(function ($q) use($request){
            return $q->when($request->search,function ($query) use($request){
               return $query->where('first_name','like','%'.$request->search . '%')
               ->orWhere('last_name','like','%'.$request->search . '%');
            });

        })->latest()->paginate(3);
        //=================================================edit for seaech code===
//        $users=User::whereRoleIs('admin')->when($request->search,function ($q) use($request){
//            return $q->where('first_name','like','%'.$request->search . '%')
//              ->orWhere('last_name','like','%'.$request->search . '%');
//        })->latest()->paginate(3);
        return view('dashboard.users.index',compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate data
        $rules=[
            "first_name"=>'required',
            "last_name"=>'required',
            "email"=>'required|unique:users',
            "password"=>'required|confirmed',
            "image"   =>'required|image',
            "permissions"=>'required|min:1',
        ];

        $this->validate($request,$rules);

        // create user
        $request_data=$request->except(['password','password_confirmation','permissions','image']);
        $request_data['password']=bcrypt($request->password);
        //image using intervention
        if($request->image){
//            Image::make($request->image)->resize(300, null, function ($constraint) {
//                $constraint->aspectRatio();
                Image::make($request->image)->fit(100)
                    ->save(public_path('uploads/user_images/'.$request->image->hashName()));;
//            })
        }
        //insert into database
        $request_data['image']=$request->image->hashName();
//        $request_data['image']=
        $user=User::create($request_data);
        // put role
        $user->attachRole('admin');
        $user->syncPermissions($request->permissions);

        //success
        session()->flash('success',__('site.added_successfully'));
        //redirect
        return redirect()->route('dashboard.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=User::findOrfail($id);
        return view('dashboard.users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate
        $rules=[
            "first_name"=>'required',
            "last_name"=>'required',
//            "email"=>'required|unique:users,id,'.$id,
            "email"=>'required|unique:users,email,'.$id,
            "permissions"=>'required|min:1',

        ];

        $this->validate($request,$rules);

        // get user
        $user=User::findOrFail($id);
        $request_data=$request->except(['permissions','image']);
        if($request->image){
            if($user->image != 'default.jpg'){
                //delete this image
                Storage::disk('public_uploads')->delete('/user_images/'.$user->image);
            }
            //upload
            Image::make($request->image)->fit(100)
            ->save(public_path('uploads/user_images/'.$request->image->hashName()));

            //insert
            $request_data['image']=$request->image->hashName();

        }
        $user->update($request_data);
        $user->syncPermissions($request->permissions);

        //success
        session()->flash('success',__('site.updated_successfully'));
        //redirect
        return redirect()->route('dashboard.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user=User::findOrFail($id);
        if($user->image != 'default.jpg'){
            Storage::disk('public_uploads')->delete('/user_images/' . $user->image);
        }

        $user->delete();
        //success
        session()->flash('success',__('site.deleted_successfully'));
        //redirect

        return redirect()->route('dashboard.users.index');
    }
}
