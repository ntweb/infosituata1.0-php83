<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class SquadraItem extends Model
{

    protected $table = 'squadre_items';

    protected static function boot()
    {
        parent::boot();
    }

    public function item() {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }

}
