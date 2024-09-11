<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class CommessaTemplate extends Model
{
    use NodeTrait;

    protected $table = 'commesse_templates';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function executeAfter() {
        return $this->belongsTo('App\Models\CommessaTemplate', 'execute_after_id');
    }

}
