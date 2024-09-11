<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class Gruppo extends Model
{
    protected $table = 'gruppi';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function utenti() {
        return $this->belongsToMany('App\Models\Utente', 'gruppo_utente', 'gruppo_id', 'utente_id');
    }
}
