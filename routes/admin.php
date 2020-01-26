<?php
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){

    Route::group(['prefix'=>'dashboard','namespace'=>'Admin','as'=>'dashboard.'],function (){

        Route::get('index','DashboardController@index')->name('index');;

    });

});


