<?php
namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsAttrezzaturaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ricambio extends Model
{

    protected $table = 'ricambi';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

}

