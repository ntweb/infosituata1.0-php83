<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MessaggioUtente extends Pivot
{
    public $incrementing = true;
    protected $table = 'messaggio_utente';
    protected $fillable = ['messaggio_id', 'utente_id'];

    public function messaggio() {
        return $this->belongsTo('App\Models\Messaggio', 'messaggio_id');
    }

    public function topic() {
        return $this->belongsTo('App\Models\Topic', 'messaggio_id');
    }

    public function utente() {
        return $this->belongsTo('App\Models\Utente', 'utente_id');
    }
}
