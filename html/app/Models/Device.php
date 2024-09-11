<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function utente() {
        return $this->belongsTo('App\Models\Item', 'utente_id');
    }

    public function type() {
        return $this->belongsTo('App\Models\DeviceType', 'device_type_id');
    }

    public function configuration() {
        return $this->hasOne('App\Models\DeviceConfiguration', 'device_id');
    }

}
