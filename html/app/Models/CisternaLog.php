<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class CisternaLog extends Model
{
    protected $table = 'cisterne_log';

    public function cisterna()
    {
        return $this->belongsTo('App\Models\Cisterna', 'cisterne_id');
    }
}
