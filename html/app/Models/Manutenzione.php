<?php
namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsAttrezzaturaScope;
use App\Scopes\ManutenzioneScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manutenzione extends Model
{
    protected $table = 'manutenzioni';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
        static::addGlobalScope(new ManutenzioneScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function item() {
        return $this->belongsTo('App\Models\Item', 'items_id');
    }

    public function dettagli() {
        return $this->hasMany('App\Models\ManutenzioneDetail', 'manutenzioni_id');
    }

    public function attachments() {
        return $this->hasMany('App\Models\AttachmentManutenzione', 'manutenzione_id');
    }

}

