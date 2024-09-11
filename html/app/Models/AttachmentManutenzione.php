<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;

class AttachmentManutenzione extends Model
{
    protected $table = 'attachments_manutenzione';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function manutenzione() {
        return $this->belongsTo('App\Models\Manutenzione', 'manutenzione_id');
    }

}
