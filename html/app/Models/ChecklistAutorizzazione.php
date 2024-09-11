<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class ChecklistAutorizzazione extends Model
{
    protected $table = 'checklists_autorizzazioni';

    protected $fillable = ['azienda_id', 'reference_controller'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

}
