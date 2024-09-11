<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfosituataModule extends Model
{
    protected $table = 'infosituata_moduli';

    public function details() {
        return $this->hasMany('App\Models\InfosituataModuleDetail', 'infosituata_moduli_id');
    }
}
