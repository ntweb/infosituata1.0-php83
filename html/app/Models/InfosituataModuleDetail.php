<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfosituataModuleDetail extends Model
{
    protected $table = 'infosituata_moduli_details';

    public function module() {
        return $this->belongsTo('App\Models\InfosituataModule', 'infosituata_moduli_id');
    }

    public function scadenze() {
        return $this->hasMany('App\Models\InfosituataModuleDetailScadenza', 'infosituata_moduli_details_id');
    }
}
