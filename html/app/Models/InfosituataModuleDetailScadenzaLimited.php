<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class InfosituataModuleDetailScadenzaLimited extends Model
{
    protected $table = 'infosituata_moduli_details_scadenze';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function moduleDetail() {
        return $this->belongsTo('App\Models\InfosituataModuleDetail', 'infosituata_moduli_details_id');
    }
}
