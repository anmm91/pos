<?php

Route::group(['namespace'=>'Test'],function (){
   Route::resource('ahmed','TestController');
   Route::view('ahmed','test');
   Route::get('point',function (){
       $user = \App\User::first();
       $amount = 10; // (Double) Can be a negative value
       $message = "The reason for this transaction";
       $transaction = $user->addPoints($amount,$message);
   });
});

