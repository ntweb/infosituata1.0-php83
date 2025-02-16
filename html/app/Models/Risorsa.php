<?php
namespace App\Models;

use App\Scopes\AziendaIdScope;

use App\Scopes\IsRisorsaScope;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Risorsa extends Model
{
    use SoftDeletes;
    protected $table = 'items';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
        static::addGlobalScope(new IsRisorsaScope());
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function attachments() {
        return $this->hasMany('App\Models\Attachment', 'item_id');
    }

    public function scadenze() {
        return $this->hasMany('App\Models\Scadenza', 'item_id');
    }

    public function sedi() {
        return $this->belongsToMany('App\Models\Sede', 'sede_item', 'item_id', 'sede_id');
    }

    public function scadenzeNonGestite($inLastMonths = 6) {
        return $this->hasMany('App\Models\Scadenza', 'item_id')
            ->whereNull('checked_at')
            ->whereBetween('end_at', [\Carbon\Carbon::now()->subMonths($inLastMonths)->endOfDay(), \Carbon\Carbon::now()->endOfDay()]);
    }
}

