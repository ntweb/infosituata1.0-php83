<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class RapportiniAutorizzazione extends Model
{
    protected $table = 'rapportini_autorizzazioni';

    protected $fillable = ['azienda_id', 'controller'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

}
