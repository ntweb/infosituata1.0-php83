<?php

namespace App\Models;

use App\Scopes\AziendaIdScope;
use App\Scopes\IsUtenteScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Utente extends Model
{

    use SoftDeletes;
    protected $table = 'items';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
        static::addGlobalScope(new IsUtenteScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'utente_id');
    }

    public function gruppi() {
        return $this->belongsToMany('App\Models\Gruppo', 'gruppo_utente', 'utente_id', 'gruppo_id');
    }

    public function sedi() {
        return $this->belongsToMany('App\Models\Sede', 'sede_item', 'item_id', 'sede_id');
    }

    public function getLabelAttribute() {
            return Str::title("{$this->extras1} {$this->extras2}");
    }

    public function attachments() {
        return $this->hasMany('App\Models\Attachment', 'item_id');
    }

    public function scadenzeNonGestite($inLastMonths = 6) {
        return $this->hasMany('App\Models\Scadenza', 'item_id')
            ->whereNull('checked_at')
            ->whereBetween('end_at', [\Carbon\Carbon::now()->subMonths($inLastMonths)->endOfDay(), \Carbon\Carbon::now()->endOfDay()]);
    }
}
