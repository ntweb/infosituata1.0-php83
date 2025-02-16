<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsMessaggioScope;
use Illuminate\Database\Eloquent\Model;

class Messaggio extends Model
{
    protected $table = 'messaggi';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
        static::addGlobalScope(new IsMessaggioScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function utenti() {
        return $this->belongsToMany('App\Models\Utente', 'messaggio_utente', 'messaggio_id', 'utente_id');
    }

    public function attachments() {
        return $this->hasMany('App\Models\AttachmentMessaggio', 'messaggio_id');
    }


}
