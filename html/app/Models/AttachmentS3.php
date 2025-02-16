<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use Illuminate\Database\Eloquent\Model;

class AttachmentS3 extends Model
{
    protected $table = 'attachmentss3';
    protected $keyType = 'string';

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function node() {
        return $this->belongsTo('App\Models\Commessa', 'reference_id');
    }

    public function task() {
        return $this->belongsTo('App\Models\Task', 'reference_id');
    }

    public function rapportino() {
        return $this->belongsTo('App\Models\CommessaRapportino', 'reference_id');
    }

}
