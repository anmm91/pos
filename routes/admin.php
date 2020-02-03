<?php


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){

    Route::group(['prefix'=>'dashboard','namespace'=>'Admin','as'=>'dashboard.','middleware'=>['auth']],function (){

        Route::get('index','DashboardController@index')->name('index');
        Route::resource('users','UserController');
        Route::resource('categories','CategoryController');
        Route::resource('products','ProductController');
        Route::resource('clients','ClientController');
        Route::resource('clients.order','Client\OrderController');
        Route::resource('orders','OrderController');
        Route::get('orders/{order}/products','OrderController@products')->name('orders.products');
        Route::get('change/password','PasswordController@password')->name('change.password');
        Route::post('change/password','PasswordController@changePassword')->name('change.password');

    });

});


