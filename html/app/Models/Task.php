<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Task extends Model
{
    use NodeTrait;

    protected $table = 'tasks';

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function cliente() {
        return $this->belongsTo('App\Models\Cliente', 'clienti_id');
    }

    public function root() {
        return $this->belongsTo('App\Models\Task', 'root_id');
    }

    public function autorizzazioni() {
        return $this->hasMany('App\Models\TaskAutorizzazione', 'tasks_root_id');
    }

}
