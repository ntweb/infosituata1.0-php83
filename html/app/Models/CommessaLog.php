<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommessaLog extends Model
{
    protected $table = 'commesse_log';
    protected $keyType = 'string';

    public function item() {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }

    public function commessa() {
        return $this->belongsTo('App\Models\Commessa', 'commesse_id');
    }
}
