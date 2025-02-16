<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class Rapportino extends Model
{
    protected $table = 'rapportini';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'users_id');
    }

    public function item() {
        return $this->belongsTo('App\Models\Item', 'items_id');
    }

}
