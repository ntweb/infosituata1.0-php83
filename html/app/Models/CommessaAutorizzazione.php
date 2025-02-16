<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommessaAutorizzazione extends Model
{
    protected $table = 'commesse_autorizzazioni';

    protected $fillable = ['commesse_root_id', 'autorizzazione'];

    protected static function boot()
    {
        parent::boot();
    }

    public function commessa() {
        return $this->belongsTo('App\Models\Commessa', 'commesse_root_id');
    }
}
