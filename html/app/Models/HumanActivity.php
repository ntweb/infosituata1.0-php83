<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;

class HumanActivity extends Model
{
    protected $table = 'humanactivity';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function device() {
        return $this->belongsTo('App\Models\Device', 'device_id');
    }

    public function utente() {
        return $this->belongsTo('App\Models\Item', 'utente_id');
    }

    public function checkedBy() {
        return $this->belongsTo('App\Models\User', 'checked_by');
    }

}
