<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;

class AttachmentMessaggio extends Model
{
    protected $table = 'attachments_messaggio';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function messaggio() {
        return $this->belongsTo('App\Models\Messaggio', 'messaggio_id');
    }

}
