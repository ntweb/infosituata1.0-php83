<?php
namespace App\Models;

use App\Scopes\AziendaIdScope;

use App\Scopes\IsUtenteScope;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class TimbraturaPermesso extends Model
{
    // use SoftDeletes;
    protected $table = 'timbrature_permessi';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'users_id');
    }

}
