<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $table = 'checklists';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function tpl() {
        return $this->belongsTo('App\Models\ChecklistTemplate', 'checklists_templates_id');
    }

    public function data() {
        return $this->hasMany('App\Models\ChecklistData', 'checklists_id');
    }

    public function node() {
        return $this->belongsTo('App\Models\Commessa', 'reference_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'users_id');
    }

    public function item() {
        return $this->belongsTo('App\Models\Item', 'reference_id');
    }

}
