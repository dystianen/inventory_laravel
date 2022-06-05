<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function item(){
        return $this->hasOne(Item::class,'id','item');
    }
    public function warehouse(){
        return $this->hasOne(Warehouse::class,'id','warehouse');
    }
    public function user(){
        return $this->hasOne(User::class,'id','user');
    }
    public function checker(){
        return $this->hasOne(User::class,'id','checker');
    }
    public function supplier(){
        return $this->hasOne(Supplier::class,'id','supplier_id');
    }
}
