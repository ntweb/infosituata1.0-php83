<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessaggioWhatsapp extends Model
{
    protected $table = 'messaggio_whatsapp';

    protected static function boot()
    {
        parent::boot();
    }

    public function messaggio() {
        return $this->belongsTo('App\Models\Whatsapp', 'messaggio_id');
    }

    public function utente() {
        return $this->belongsTo('App\Models\Item', 'utente_id');
    }

}
