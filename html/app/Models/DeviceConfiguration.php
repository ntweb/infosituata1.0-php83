<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;

class DeviceConfiguration extends Model
{
    protected $table = 'devices_configuration';
    protected $fillable = ['azienda_id', 'device_id'];

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

}
