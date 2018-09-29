<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];


    public function get_image_url(){
        if ($this->image){
            $img_url = asset('uploads/images/'.$this->image);
        }else{
            $img_url = asset('assets/images/placeholder.png');
        }
        return $img_url;
    }
    
    public function campaigns(){
        return $this->hasMany(Campaign::class)->whereStatus(1);
    }
}
