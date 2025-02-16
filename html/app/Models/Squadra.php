<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class Squadra extends Model
{

    protected $table = 'squadre';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function elements() {
        return $this->hasMany('App\Models\SquadraItem', 'squadre_id');
    }

}
