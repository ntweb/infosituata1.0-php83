<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Azienda extends Model
{
    protected $table = 'aziende';

    public function user() {
        return $this->hasOne('App\Models\User', 'azienda_id');
    }

    public function package() {
        return $this->belongsTo('App\Models\Package', 'package_id');
    }

    public function devices() {
        return $this->hasMany('App\Models\Device', 'azienda_id');
    }
}
