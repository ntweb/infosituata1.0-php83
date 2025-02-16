<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'messaggio_chat';

    protected static function boot()
    {
        parent::boot();
    }

    public function messaggio() {
        return $this->belongsTo('App\Models\Topic', 'messaggio_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'users_id');
    }

}
