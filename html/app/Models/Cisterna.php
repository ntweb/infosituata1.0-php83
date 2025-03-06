<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class Cisterna extends Model
{
    protected $table = 'cisterne';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

}
