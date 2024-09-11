<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAutorizzazione extends Model
{
    protected $table = 'tasks_autorizzazioni';

    protected $fillable = ['tasks_root_id', 'autorizzazione'];

    protected static function boot()
    {
        parent::boot();
    }

    public function task() {
        return $this->belongsTo('App\Models\Task', 'root_id');
    }
}
