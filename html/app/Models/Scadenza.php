<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;

class Scadenza extends Model
{
    protected $table = 'scadenze';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function commessa() {
        return $this->belongsTo('App\Models\Commessa', 'commesse_id');
    }

    public function item() {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }

    public function module() {
        return $this->belongsTo('App\Models\InfosituataModuleDetail', 'infosituata_moduli_details_id');
    }

    public function detail() {
        return $this->belongsTo('App\Models\InfosituataModuleDetailScadenza', 'infosituata_moduli_details_scadenze_id');
    }

    public function gruppi() {
        return $this->belongsToMany('App\Models\Gruppo', 'gruppo_scadenza', 'scadenza_id', 'gruppo_id');
    }

    public function attachments() {
        return $this->hasMany('App\Models\AttachmentScadenza', 'scadenza_id');
    }

    public function createdBy() {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updatedBy() {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function checkedBy() {
        return $this->belongsTo('App\Models\User', 'checked_by');
    }
}
