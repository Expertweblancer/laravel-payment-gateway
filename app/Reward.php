<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    
    
    public function payments(){
        return $this->hasMany(Payment::class);
    }
    
    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }
    
}
