<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class TaskTemplate extends Model
{
    use NodeTrait;

    protected $table = 'tasks_templates';

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

}
