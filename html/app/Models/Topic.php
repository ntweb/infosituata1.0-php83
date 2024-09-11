<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsTopicScope;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'messaggi';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
        static::addGlobalScope(new IsTopicScope());
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
