<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table = 'sedi';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function utenti() {
        return $this->belongsToMany('App\Models\Utente', 'sede_item', 'sede_id', 'item_id');
    }
}
