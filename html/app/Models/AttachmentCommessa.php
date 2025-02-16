<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;

class AttachmentCommessa extends Model
{
    protected $table = 'attachments_commessa';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function node() {
        return $this->belongsTo('App\Models\Commessa', 'commesse_id');
    }

    public function rapportino() {
        return $this->belongsTo('App\Models\CommessaRapportino', 'commesse_rapportini_id');
    }

}
