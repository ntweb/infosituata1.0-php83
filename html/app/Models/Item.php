<?php
namespace App\Models;

use App\Scopes\AziendaIdScope;

use App\Scopes\IsUtenteScope;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Item extends Model
{
    // use SoftDeletes;
    protected $table = 'items';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AziendaIdScope());
    }

    public function getLabelAttribute()
    {
        if ($this->controller == 'utente')
            return Str::title("{$this->extras1} {$this->extras2}");

        if ($this->controller == 'mezzo')
            return Str::title("{$this->extras1} [{$this->extras3}]");

        if ($this->controller == 'attrezzatura')
            return Str::title("{$this->extras1} [{$this->extras3}]");

        return "{$this->extras1}";
    }

    public function azienda() {
        return $this->belongsTo('App\Models\Azienda', 'azienda_id');
    }

    public function cliente() {
        return $this->belongsTo('App\Models\Cliente', 'clienti_id');
    }

    public function attachments() {
        return $this->hasMany('App\Models\Attachment', 'item_id');
    }

    public function sedi() {
        return $this->belongsToMany('App\Models\Sede', 'sede_item', 'item_id', 'sede_id');
    }

    public function manutenzioni() {
        return $this->hasMany('App\Models\Manutenzione', 'items_id')->orderBy('data', 'desc');
    }

    public function controlli() {
        return $this->hasMany('App\Models\Controllo', 'items_id')->orderBy('data', 'desc');
    }

    public function carburante() {
        return $this->hasMany('App\Models\Carburante', 'items_id')->orderBy('km', 'desc');
    }

}
