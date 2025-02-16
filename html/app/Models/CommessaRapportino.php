<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class CommessaRapportino extends Model
{
    protected $table = 'commesse_rapportini';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function fase() {
        return $this->belongsTo('App\Models\Commessa', 'commesse_id');
    }

    public function commessa() {
        return $this->belongsTo('App\Models\Commessa', 'commesse_root_id');
    }

    public function attachments() {
        return $this->hasMany('App\Models\AttachmentCommessa', 'commesse_rapportini_id');
    }
}
