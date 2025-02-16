<?php

namespace App\Models;

use App\Scopes\AziendaIdOrNullScope;
use Illuminate\Database\Eloquent\Model;

class Iva extends Model
{
    protected $table = 'iva';
    protected $keyType = 'string';

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new AziendaIdOrNullScope());
    }
}
