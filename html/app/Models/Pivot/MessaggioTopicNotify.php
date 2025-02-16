<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MessaggioTopicNotify extends Pivot
{
    public $incrementing = true;
    protected $table = 'messaggio_topic_notify';
    protected $fillable = ['messaggio_id', 'utente_id'];

    public function topic() {
        return $this->belongsTo('App\Models\Topic', 'messaggio_id');
    }

    public function utente() {
        return $this->belongsTo('App\Models\Utente', 'utente_id');
    }
}
