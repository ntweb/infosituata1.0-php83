<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChecklistData extends Model
{

    protected $table = 'checklists_data';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['checklists_id', 'key'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }
}
