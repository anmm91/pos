<?php

Route::group(['namespace'=>'Test'],function (){
    Route::get('test',function (){

        return view('dashboard.clients.orders.edit');
    });
});

