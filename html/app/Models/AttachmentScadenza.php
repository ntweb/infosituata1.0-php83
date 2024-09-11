<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;

class AttachmentScadenza extends Model
{
    protected $table = 'attachments_scadenza';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function scadenza() {
        return $this->belongsTo('App\Models\Scadenza', 'scadenza_id');
    }

}
