<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    public function password(){
        return view('dashboard.password.index');
    }
    public function changePassword(Request $request){
        //validate
        $rules=[
            'email'=>'required',
            'password'=>'required|confirmed',
        ];
        $this->validate($request,$rules);
        //return user with email
        $user=User::whereRoleIs('admin')->where('email',$request->email)->first();
        if ($user){
           $user->password=bcrypt($request->password);
           $user->save();
        }else{
            return back()->withErrors([

                'message'=>'not allowed'
            ]);
        }
        //change password
        //success
        session()->flash('success',__('site.updated_successfully'));
        //redirect
        return redirect()->route('dashboard.password.index');
    }
}
