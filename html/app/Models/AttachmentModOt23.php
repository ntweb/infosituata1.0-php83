<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;

class AttachmentModOt23 extends Model
{
    protected $table = 'attachments_modot23';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function mod() {
        return $this->belongsTo('App\Models\InailModOt23', 'item_id');
    }

}
