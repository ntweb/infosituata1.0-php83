<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use App\Scopes\UserIdScope;
use Illuminate\Database\Eloquent\Model;

class InailModOt23 extends Model
{
    protected $table = 'inail_modot23';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
        static::addGlobalScope(new UserIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function updatedBy() {
        return $this->belongsTo('App\Models\User', 'updated_users_id');
    }

    public function attachments() {
        return $this->hasMany('App\Models\AttachmentModOt23', 'item_id');
    }
}
