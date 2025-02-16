<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MessaggioWhatsapp extends Pivot
{
    public $incrementing = true;
    protected $table = 'messaggio_whatsapp';
    protected $fillable = ['messaggio_id', 'utente_id'];

    public function messaggio() {
        return $this->belongsTo('App\Models\Messaggio', 'messaggio_id');
    }

    public function utente() {
        return $this->belongsTo('App\Models\Utente', 'utente_id');
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }
}
