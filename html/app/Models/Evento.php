<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'items_eventi';
    protected $fillable = ['timbrature_permessi_id'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function item() {
        return $this->belongsTo('App\Models\Item', 'items_id');
    }

}
