<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class Autorizzazione extends Model
{
    protected $table = 'autorizzazioni';

    protected $fillable = ['azienda_id', 'module', 'reference_controller', 'permission'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

}
