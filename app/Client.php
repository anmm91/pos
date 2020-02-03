<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable=['name','phone','address'];
    protected $casts=['phone'=>'array'];
    public function orders(){
        return $this->hasMany('App\Order');
    }
    public function getNameAttribute($value){
        return ucfirst($value);
    }
}
