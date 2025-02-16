<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MessaggioTopic extends Pivot
{
    public $incrementing = true;
    protected $table = 'messaggio_topic';
    protected $fillable = ['messaggio_id', 'users_id'];

    public function topic() {
        return $this->belongsTo('App\Models\Topic', 'messaggio_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'users_id');
    }
}
