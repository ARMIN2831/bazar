<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['fa_title', 'en_title', 'ar_title', 'created_at', 'updated_at'];
    protected $appends = ['title'];


    public function getTitleAttribute()
    {
        $lang = app('request')->header('user_lan', 'ar');
        $localizedField = $lang . '_title';
        return $this->{$localizedField} ?? $this->ar_title;
    }
}
