<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfosituataModuleDetailScadenza extends Model
{
    protected $table = 'infosituata_moduli_details_scadenze';

    public function moduleDetail() {
        return $this->belongsTo('App\Models\InfosituataModuleDetail', 'infosituata_moduli_details_id');
    }
}
