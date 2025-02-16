<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Commessa extends Model
{
    use NodeTrait;

    protected $table = 'commesse';

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
        return $this->belongsTo('App\Models\Commessa', 'root_id');
    }

    public function executeAfter() {
        return $this->belongsTo('App\Models\Commessa', 'execute_after_id');
    }

    public function autorizzazioni() {
        return $this->hasMany('App\Models\CommessaAutorizzazione', 'commesse_root_id');
    }

    public function logs() {
        return $this->hasMany('App\Models\CommessaLog', 'commesse_id')->orderBy('commesse_id')->orderBy('inizio');
    }

    public function attachments() {
        return $this->hasMany('App\Models\AttachmentCommessa', 'commesse_id');
    }
}
