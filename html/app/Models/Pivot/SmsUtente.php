<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SmsUtente extends Pivot
{
    public $incrementing = true;
    protected $table = 'messaggio_utente';
    protected $fillable = ['messaggio_id', 'utente_id'];

    public function messaggio() {
        return $this->belongsTo('App\Models\Sms', 'messaggio_id');
    }

    public function utente() {
        return $this->hasMany('App\Models\Utente', 'utente_id');
    }
}
