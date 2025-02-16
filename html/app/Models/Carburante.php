<?php
namespace App\Models;

use App\Scopes\AziendaIdScope;

use App\Scopes\CarburanteScope;

use Illuminate\Database\Eloquent\Model;

class Carburante extends Model
{
    protected $table = 'manutenzioni';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
        static::addGlobalScope(new CarburanteScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function createdBy() {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function item() {
        return $this->belongsTo('App\Models\Item', 'items_id');
    }

    public function attachments() {
        return $this->hasMany('App\Models\AttachmentManutenzione', 'manutenzione_id');
    }

}
