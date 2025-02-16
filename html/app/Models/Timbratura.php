<?php
namespace App\Models;

use App\Scopes\AziendaIdScope;

use App\Scopes\IsUtenteScope;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Timbratura extends Model
{
    // use SoftDeletes;
    protected $table = 'timbrature';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'users_id');
    }

}
