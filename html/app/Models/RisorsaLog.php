<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsRisorsaScope;
use Illuminate\Database\Eloquent\Model;

class RisorsaLog extends Model
{
    protected $table = 'risorse_logs';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function utente() {
        return $this->belongsTo('App\Models\Utente', 'utente_id');
    }

    public function risorsa() {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }
}
